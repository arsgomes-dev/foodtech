<?php
namespace Microfw\Src\Main\Controller\Public;
use Microfw\Src\Main\Controller\Public\View\View;

class Oauth2logincallback {

    public function oauth2logincallback() {
        View::render('oauth2Logincallback');
    }
}

// executa automaticamente
(new Oauth2logincallback)->oauth2logincallback();

