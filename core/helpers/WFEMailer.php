<?php

namespace app\core\helpers;

class Email {

    private $from = 'youremail@email.fr';
    private $from_name = 'Your Name';
    private $reply_to = 'workframeworkever@gmail.com';
    private $to = 'workframeworkever@gmail.com';
    private $subject = 'subject';
    private $message = 'message';

    public function __construct($to, $subject, $message, $attachment = '', $attachment_filename = '') {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function mail() {



        if (mail($mailto, $subject, "", $header)) {
            $header = "From: " . ($this->from_name) . " <" . ($this->from) . ">\r\n";
            $header .= "Reply-To: " . ($this->reply_to) . "\r\n";
        } elseif (mail($this->to, $this->subject, $this->message, $header)) {
            return true;
        } else {
            return false;
        }
    }

}
