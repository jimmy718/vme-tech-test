<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $oldValues;
    public array $newValues;

    public function __construct(array $oldValues, array $newValues)
    {
        $this->oldValues = $oldValues;
        $this->newValues = $newValues;
    }

    public function build()
    {
        return $this->markdown('emails.products.update');
    }
}
