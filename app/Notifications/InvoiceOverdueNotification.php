<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceOverdueNotification extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice, public int $daysPastDue) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $customer = $this->invoice->customer?->name ?? 'Customer';
        $due = number_format($this->invoice->due, 2);

        return (new MailMessage)
            ->subject('Overdue Invoice Reminder — ' . $this->invoice->invoice_no)
            ->greeting('Hello ' . $customer . ',')
            ->line("This is a reminder that invoice **{$this->invoice->invoice_no}** is **{$this->daysPastDue} days past due**.")
            ->line("Outstanding amount: **৳ {$due}**")
            ->line('Please make the payment at your earliest convenience.')
            ->line('Thank you for your business.');
    }
}
