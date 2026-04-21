<?php

namespace App\Mail;

use App\Models\Quotation;
use App\Services\QuotationPdfService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Quotation $quotation;
    public string $customMessage;

    public function __construct(Quotation $quotation, string $customMessage = '')
    {
        $this->quotation = $quotation;
        $this->customMessage = $customMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Quotation #{$this->quotation->quotation_no} from " . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.quotation',
            with: [
                'quotation' => $this->quotation,
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $pdf = app(QuotationPdfService::class)->render($this->quotation);

        return [
            Attachment::fromData(fn() => $pdf, $this->quotation->quotation_no . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
