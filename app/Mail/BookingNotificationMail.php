<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $oldData;
    public $newData;
    public $bookingNumber; // Add this
    public $bookingService; // Add this

    /**
     * Create a new message instance.
     */
    public function __construct($oldData, $newData, $bookingNumber, $bookingService)
    {
        $this->oldData = $oldData;
        $this->newData = $newData;
        $this->bookingNumber = $bookingNumber;
        $this->bookingService = $bookingService;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // $bookingNumber = $this->newData->booking_number ?? $this->newData->id;
        $bookingNumber = $this->bookingNumber;

        /* return new Envelope(
            subject: 'Booking #' . $bookingNumber . ' Updated',
        ); */
        return new Envelope(
            subject: 'Booking #' . $bookingNumber .' '.$this->bookingService.' Updated',
            from: new Address('mis@bestumrahpackagesuk.com', 'MIS'),
            cc: [
                //new Address($this->ccFromEmail, $this->ccFromName), // Replace with the actual CC email address and name
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking_notification',
            with: [
                'bookingNumber' => $this->bookingNumber,
                'oldBooking' => $this->oldData,
                'newBooking' => $this->newData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
