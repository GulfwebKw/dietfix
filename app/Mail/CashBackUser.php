<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CashBackUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $cashBack;
    public $status;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $cashBack
     * @param $status
     */
    public function __construct($user,$cashBack,$status)
    {
        $this->user=$user;
        $this->cashBack=$cashBack;
        $this->status=$status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cash_back_user')->subject('Cash Back Request ')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }


}
