<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 13:28
 */

namespace Src\Mailer;


class VarDumpMailTransport implements MailTransportInterface
{
    public function send(MailInterface $mail)
    {
        var_dump($mail);
    }
}