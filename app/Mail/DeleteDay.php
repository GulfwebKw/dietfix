<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeleteDay extends Mailable
{
    use Queueable, SerializesModels;
    public $userId;
    public $days;
    public $newDays;


    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $date
     */
    public function __construct($userId,$days,$newDay)
    {
        $this->userId=$userId;
        $this->days=$days;
        $this->newDays=$newDay;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.deleteDay')->subject('Delete Days By User  ')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }




}
