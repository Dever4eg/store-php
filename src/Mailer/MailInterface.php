<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 11.07.18
 * Time: 12:22
 */

namespace Src\Mailer;


interface MailInterface
{
    public function __construct($subject, $message, $recipient, $from, array $headers = []);

    public function getSubject();
    public function getMessage();
    public function getRecipient();
    public function getHeaders();
    public function getFrom();
}