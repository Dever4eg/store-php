<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:42
 */

namespace Src\Mailer;


class Mail implements MailInterface
{
    protected $subject;
    protected $message;
    protected $recipient;
    protected $headers = [];

    public function __construct($subject, $message, $recipient, $headers = [])
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->recipient = $recipient;
        $this->headers = $headers;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
}