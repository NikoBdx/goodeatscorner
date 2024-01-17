<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($from, $to, $subject, $template, $text):void
    {
        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($template)
            ->text($text);

        $this->mailer->send($email);
    }
}
