<?php

namespace App\Jobs;

use App\Mail\LabelsCsvMail;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class MailCsvToStaffJob
{
    use Dispatchable;

    private string $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function handle()
    {
        Mail::to('shop-floor-staff@co-op-shopper.com')
            ->send(
                new LabelsCsvMail($this->filename)
            );
    }
}
