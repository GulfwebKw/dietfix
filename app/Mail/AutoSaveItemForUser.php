<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AutoSaveItemForUser extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $date;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $date
     */
    public function __construct($user,$date)
    {
        $this->user=$user;
        $this->date=$date;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.auto_save_item')->subject('Auto Save Item ')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }




}
