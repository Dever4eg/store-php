<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:37
 */

namespace Src\Mailer;


class SwiftSmtpMailTransport implements MailTransportInterface
{
    protected $transport;

    public function __construct($host, $port, $user, $password)
    {
        $this->transport = (new \Swift_SmtpTransport($host, $port))
            ->setUsername($user)
            ->setPassword($password)
        ;
    }

    public function send(MailInterface $mail)
    {
        // Create the Mailer using your created Transport
        $mailer = new \Swift_Mailer($this->transport);

        // Create a message
        $message = (new \Swift_Message( $mail->getSubject() ) )
            ->setTo([$mail->getRecipient()])
            ->setFrom([$mail->getFrom()])
            ->setBody($mail->getMessage())
        ;

        $headers = $message->getHeaders();
        foreach ($mail->getHeaders() as $key => $header) {
            $headers->addTextHeader($key, $header);
        }

        // Send the message
        return $mailer->send($message);
    }
}