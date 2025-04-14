<?php

namespace App\Listeners;

use App\Events\SendInvoiceEmailAdminEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SendInvoiceEmailAdmin;
use Illuminate\Support\Facades\Mail;

class SendInvoiceEmailAdminListener
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
    public function handle(SendInvoiceEmailAdminEvent $event): void
    {
        Mail::to($event->email)->send(new SendInvoiceEmailAdmin($event->subject, $event->invoiceLink, $event->user, $event->order));
    }
}
