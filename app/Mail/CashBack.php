<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CashBack extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $cashBack;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $cashBack
     */
    public function __construct($user,$cashBack)
    {
        $this->user=$user;
        $this->cashBack=$cashBack;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cash_back')->subject('Cash Back Request ')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }


}
