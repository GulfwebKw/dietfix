<?php


namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserReport extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $phone;
    public $weight;
    public $height;
    public $age;
    public $option;
    public $details;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $date
     */
    public function __construct($name,$phone,$weight,$height,$age,$option,$details)
    {
        $this->name=$name;
        $this->phone=$phone;
        $this->weight=$weight;
        $this->height=$height;
        $this->age=$age;
        $this->option=$option;
        $this->details=$details;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reportUser')->subject('New Diet Registration Details ')->from(env("MAIL_FROM_ADDRESS",env("APP_NAME")));
    }




}
