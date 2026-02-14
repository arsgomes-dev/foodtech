<?php
use Microfw\Src\Main\Controller\Admin\Login\ProcessLogin;

ProcessLogin::processLogin($_POST['email'], $_POST['p'], $_POST['g-recaptcha'], $_POST['language']);
