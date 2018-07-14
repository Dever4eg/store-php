<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:29
 */

namespace Src\Mailer;


interface MailTransportInterface
{
    public function send(MailInterface $mail);
}