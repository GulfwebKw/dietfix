<?php


namespace App\Events;


class Sms
{

    public $mobile;
    public $message;
    public function __construct($mobile,$message)
    {
        $this->mobile=$mobile;
        $this->message=$message;

    }

    public function broadcastOn()
    {
      //  return [];
    }





}
