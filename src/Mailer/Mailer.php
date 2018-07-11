<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:31
 */

namespace Src\Mailer;


class Mailer
{

    public $transport;

    public function __construct(MailTransportInterface $mailTransport)
    {
        $this->transport = $mailTransport;
    }

    public function send(MailInterface $mail)
    {
        $this->transport->send($mail);
    }
}