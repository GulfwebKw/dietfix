<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Payment extends Mailable
{
    use Queueable, SerializesModels;
    public $payment;
    public $status=0;

    /**
     * Create a new message instance.
     *
     * @param $payment
     * @param $status
     */
    public function __construct($payment,$status)
    {
        $this->payment=$payment;
        $this->status=$status;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
     //  if($this->status){
            return $this->view('emails.user_payment_success')->subject('New Payment Success')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
       // }
       // return $this->view('emails.user_payment_failed')->subject('New Payment Failed')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }



}
