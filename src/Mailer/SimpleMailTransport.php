<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:37
 */

namespace Src\Mailer;


class SimpleMailTransport implements MailTransportInterface
{
    public function send(MailInterface $mail)
    {
        return mail(
            $mail->getRecipient(),
            $mail->getSubject(),
            $mail->getMessage(),
            $mail->getHeaders()
        );
    }
}