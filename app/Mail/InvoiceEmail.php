<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $body;
    public $footer;
    public $attachmentPath;
    public $fromAddress;
    public $fromName;
    public $ccFromEmail;
    public $ccFromName;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $body, $footer, $attachmentPath, $fromAddress, $fromName, $ccFromEmail, $ccFromName)
    {
        $this->booking = $booking;
        $this->body = $body;
        $this->footer = $footer;
        $this->attachmentPath = $attachmentPath;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->ccFromEmail = $ccFromEmail;
        $this->ccFromName = $ccFromName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->booking->company->name.' Booking Invoice No: '.$this->booking->booking_prefix . $this->booking->booking_number,
            //from: new Address(config('mail.from.address'), config('mail.from.name'))
            from: new Address($this->fromAddress, $this->fromName),
            cc: [
                new Address($this->ccFromEmail, $this->ccFromName), // Replace with the actual CC email address and name
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'body' => $this->body,
                'footer' => $this->footer,

                'booking' => $this->booking,
                'passengers' => $this->booking->passengers,
                'flights' => $this->booking->flights,
                'hotels' => $this->booking->hotels,
                'transports' => $this->booking->transports,
                'prices' => $this->booking->prices,
                'otherCharges' => $this->booking->otherCharges,
                'installmentPlan' => $this->booking->installmentPlan,
                'payments' => $this->booking->payments,
                'refunds' => $this->booking->refunds,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            // $this->attachmentPath => [
            //'mime' => 'application/pdf',
            // ]
        ];
    }
}
