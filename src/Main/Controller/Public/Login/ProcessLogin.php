<?php

namespace Microfw\Src\Main\Controller\Public\Login;

use Microfw\Src\Main\Controller\Public\Login\Login;
use Microfw\Src\Main\Controller\Public\Login\RedirectUrl;
use Microfw\Src\Main\Controller\Public\Login\SecSessionStart;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;
use function GuzzleHttp\json_decode;

/**
 * Description of ProcessLogin
 *
 * @author Ricardo Gomes
 */
class ProcessLogin {

    public static function processLogin(String $email, String $p, String $gRecaptcha, Int $language) {

        function pv($pv_var) {
            $pv_tipo = '=';
            for ($f = 1; $f <= strlen(strval($pv_var)) - 10; $f++) {
                if ($pv_var[$f] == '=') {
                    $pv_tipo = '=';
                }
            }
            if ($pv_tipo == '=') {
                return str_replace('=', '', $pv_var);
            }
        }

        $translate = new Translate();
        $lang = new Language;
        $lang->setActive("1");
        $lang = $lang->getQuery();
        $lang_count = count($lang);
        $lg = "";
        $lg_code = "";
        $cr = new Language;
        $cr = $lang[0];
        $lg = $cr->getId();
        $lg_code = $cr->getCode();
//defini variáveis de configurações e recaptcha      
        $config = new McClientConfig;
        $sitekey = $config->getReChaveSiteKey();
        $secretkey = $config->getReChaveSecretKey();
//define pagina home do sistema
        $pag = $config->getDomain() . "/" . $config->getUrlPublic() . "/" . $config->getPageHomeClient();
        $pag_subscribe = $config->getDomain() . "/" . $config->getUrlPublic() . env('PAG_INDEX_SUBSCRIBE');
//inicia session
        $secSessionStart = new SecSessionStart;
//verifica se foi recebido as informações do formulário de login

        if (isset($email, $p, $gRecaptcha)) {
            if (trim($email) !== "" && trim($p) !== "") {
                $grecaptcha = $gRecaptcha;
                $language = isset($language) ? $language : $lg;
                if (!$grecaptcha) {

                    $_SESSION['erro_app_log'] = $translate->translate('Por Favor Responda a reCAPTCHA!', $lg_code);
                    RedirectUrl::redirectUrl();
                    exit();
                } else {
                    //verificação do recaptcha
                    $ip = $_SERVER['REMOTE_ADDR'];
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = array('secret' => $secretkey, 'response' => $grecaptcha);
                    $options = array(
                        'http' => array(
                            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                            'method' => 'POST',
                            'content' => http_build_query($data)
                        )
                    );
                    $context = stream_context_create($options);
                    $response = file_get_contents($url, false, $context);
                    $responseKeys = json_decode($response, true);
                    header('Content-type: application/json');
                    //caso o resultado do recaptcha seja positivo    
                    if ($responseKeys["success"] == 1 && $responseKeys["score"] >= 0.6) {
                        //verifica o login do usuário    
                        $returnLogin = (Login::login($email, $p, $language));
                        print_r($returnLogin);

                        if ($returnLogin == 1) {
                            // Login efetuado com sucesso

                            $planService = new CheckPlan;
                            $check = $planService->checkPlan();
                            if (!$check['allowed'] && !$check['plan_active']) {
                                header('Location: ' . $pag_subscribe);
                            } else {
                                header('Location:' . $pag);
                            }
                            exit();
                        } else if ($returnLogin == 2) {
                            $_SESSION['erro_app_log'] = $translate->translate('Login e/ou Senha incorreto(s)!', $lg_code);
                            RedirectUrl::redirectUrl();
                        } else if ($returnLogin == 3) {
                            $_SESSION['erro_app_log'] = $translate->translate('Sua conta foi bloqueada! '
                                    . 'Foi enviado para o seu e-mail instruções para o desbloqueio!', $lg_code);
                            RedirectUrl::redirectUrl();
                        } else if ($returnLogin == 4) {
                            $_SESSION['erro_app_log'] = $translate->translate('Sua conta foi bloqueada! '
                                    . 'Erro ao enviar e-mail de desboqueio, Entre em contato conosco!', $lg_code);
                            RedirectUrl::redirectUrl();
                        } else if ($returnLogin == 5) {
                            $_SESSION['erro_app_log'] = $translate->translate('Sua conta ainda está desativada!'
                                    . ' Acesse o e-mail enviado no momento do cadastro para ativá-la.', $lg_code);
                            RedirectUrl::redirectUrl();
                        } else {
                            $_SESSION['erro_app_log'] = $translate->translate('Não foi possível iniciar uma sessão segura!', $lg_code);
                            RedirectUrl::redirectUrl();
                        }
                    } else {
                        $_SESSION['erro_app_log'] = $translate->translate('Ocorreu um erro na resposta do reCAPTCHA, tente novamente!', $lg_code);
                        $responseKeys["success"];
                        RedirectUrl::redirectUrl();
                        exit();
                    }
                }
            } else {
                $_SESSION['erro_app_log'] = $translate->translate('Existem campos em branco!', $lg_code);
                RedirectUrl::redirectUrl();
                exit();
            }
        } else {
            $_SESSION['erro_app_log'] = $translate->translate('Existem campos em branco!', $lg_code);
            RedirectUrl::redirectUrl();
            exit();
        }
    }
}
