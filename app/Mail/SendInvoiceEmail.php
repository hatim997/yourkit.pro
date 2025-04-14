<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Utils\Helper;

class SendInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $subject;
    public $invoiceLink;
    public $user;
    public $order;
    public $siteLogo;
    public $siteTitle;
    public $operationalContacts;

    public function __construct($subject, $invoiceLink, $user, $order)
    {
        $this->subject = $subject;
        $this->invoiceLink = $invoiceLink;
        $this->user = $user;
        $this->order = $order;
        $this->siteLogo = !empty(Helper::setting("logo")) && file_exists(public_path("storage/", Helper::setting("logo"))) ? url(asset("storage/".Helper::setting("logo"))) : url(asset("assets/frontend/images/logo.png"));
        $this->siteTitle = empty(Helper::setting("title")) ? env('APP_NAME') : Helper::setting("title");
        $this->operationalContacts = Helper::getOperationalContacts();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.send-invoice',
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
