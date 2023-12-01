<?php

namespace App\Service;

class Mailer
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMessage($from, $to, $subject, $body)
    {
        $message = (new \Swift_Message($subject))
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body)
            ->setContentType('text/html');

        return $this->mailer->send($message);
    }
}