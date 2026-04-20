<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public string $customMessage;

    public function __construct(Invoice $invoice, string $customMessage = '')
    {
        $this->invoice = $invoice;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        $label = $this->invoice->type === 'sales' ? 'Invoice' : 'Bill';
        return new Envelope(
            subject: "{$label} #{$this->invoice->invoice_no} from " . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'customMessage' => $this->customMessage,
            ],
        );
    }
}
