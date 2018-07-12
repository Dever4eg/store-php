<?php
/**
 * Created by PhpStorm.
 * User: phpuser
 * Date: 12.07.18
 * Time: 12:19
 */

namespace Src\Session;


interface FlashMessageInterface
{

    public function __construct($type, $title, $body);

    public function getType();
    public function getTitle();
    public function getBody();
}