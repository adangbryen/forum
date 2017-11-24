<?php

namespace App\Listeners;

use App\Mail\PleaseConfirmYourEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailConfirmationRequest
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user)->send(new PleaseConfirmYourEmail($event->user));
    }
}
