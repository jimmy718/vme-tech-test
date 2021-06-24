<?php

namespace Tests\Feature;

use App\Mail\LabelsCsvMail;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MailedProductCsvExportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('s3');
        Carbon::setTestNow(now());
    }

    /** @test */
    public function guests_cannot_export_products()
    {
        $this->postJson(route('exports.products.mail'))->assertUnauthorized();
    }

    /** @test */
    public function filters_must_be_applied_to_export()
    {
        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('exports.products.mail', []))
            ->assertJsonValidationErrors([
                'filter'
            ]);
    }

    /** @test */
    public function generated_csv_contains_correct_products()
    {
        Product::factory()->create(['name' => 'test a']);
        Product::factory()->create(['name' => 'test b']);
        Product::factory()->create(['name' => 'exclude']);

        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('exports.products.mail', [
                'filter' => [
                    'search' => 'test'
                ]
            ]))
            ->assertOk();

        $csv = Storage::get('labels/' . now()->toIso8601String() . '.csv');

        $this->assertStringContainsString('test a', $csv);
        $this->assertStringContainsString('test b', $csv);
        $this->assertStringNotContainsString('exclude', $csv);
    }

    /** @test */
    public function generated_csv_is_mailed_to_all_staff_email_address()
    {
        Product::factory()->create(['name' => 'test a']);

        $this
            ->actingAs(User::factory()->create())
            ->postJson(route('exports.products.mail', [
                'filter' => [
                    'search' => 'test'
                ]
            ]))
            ->assertOk();

        Mail::assertSent(LabelsCsvMail::class);
    }
}
