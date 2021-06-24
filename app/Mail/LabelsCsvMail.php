<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LabelsCsvMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $csvPath;

    public function __construct(string $csvPath)
    {
        $this->csvPath = $csvPath;
    }

    public function build()
    {
        return $this
            ->markdown('emails.products.labels-csv')
            ->attachFromStorage($this->csvPath);
    }
}
