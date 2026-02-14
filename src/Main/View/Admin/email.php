<?php

use Microfw\Src\Main\Common\Helpers\Admin\PhpMailer\Send;

$email = new Send();

$email->email = ["thetecinfor@gmail.com"];
$email->nameMailer = ["Ricardo Seixas"];
$email->subject = "testando 1232";
$email->body = "envio funcionando2";
$email->send();


