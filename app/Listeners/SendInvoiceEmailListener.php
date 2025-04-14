<?php

namespace App\Listeners;

use App\Events\SendInvoiceEmailEvent;
use App\Mail\SendInvoiceEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendInvoiceEmailEvent $event): void
    {
        Mail::to($event->email)->send(new SendInvoiceEmail($event->subject, $event->invoiceLink, $event->user, $event->order));
    }
}
