<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailabl extends Mailable
{
    use Queueable, SerializesModels;

    public  $params;
    public $view;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view=null,$token)
    {
        $this->params=$token;
        $this->view=$view;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       if($this->view==null){
           $view='default';
       }else{
           $view=$this->view;
       }
        return $this->view('emails.'.$view);
    }
}
