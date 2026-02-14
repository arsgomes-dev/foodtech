<?php

session_start();

//Função para o usuário alterar sua senha pelo PERFIL
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig;
$planService = new CheckPlan;
$check = $planService->checkPlan();
if (!$check['allowed']) {
    header('Location: ' . $config->getDomain() . "/" . $config->getUrlPublic());
    exit;
}
//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();

//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
if (!empty(array_filter($_POST)) &&
        !empty($_SESSION['client_id']) && isset($_SESSION['client_id']) &&
        !empty($_POST['passCurrentProfile']) && isset($_POST['passCurrentProfile']) &&
        !empty($_POST['passNewProfile']) && isset($_POST['passNewProfile']) &&
        !empty($_POST['passConfirmProfile']) && isset($_POST['passConfirmProfile'])) {
    //recebe a senha atual
    $perfil_password_current = $_POST['passCurrentProfile'];
    //recebe a nova senha
    $perfil_password_new = $_POST['passNewProfile'];
    //recebe a confirmação da nova senha
    $perfil_password_confirm = $_POST['passConfirmProfile'];
    //declara a classe USUÁRIO
    $client = new Client();
    //consulta no banco os dados atuais do usuário logado a quem pertece o perfil
    $client = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);
    //trás do DB a variável SALT já atribuida ao usuário que é usada para compor a criptografia da senha
    $salt = $client->getSalt();
    //cria um resultado criptográfico sha512 da junção da senha atual digitada ($perfil_password_current) e do $salt
    $password_current = hash('sha512', $perfil_password_current . $salt);
    //verifica se a senha atual digitada é a mesma salva no DB
    if ($password_current === $client->getPasswd()) {
        //declara a Classe usuário em uma variável perfil
        $perfil = new Client;
        //seta o ID para informar que será uma atualização
        $perfil->setId($_SESSION['client_id']);
        //confere se a nova senha digitada e a confirmação são as mesmas
        if ($perfil_password_new && $perfil_password_confirm) {
            //cria um resultado criptográfico sha512 da junção da nova senha ($perfil_password_new) e do $salt
            $password = hash('sha512', $perfil_password_new . $salt);
            //seta a nova senha na variável Passwd da classe Usuário
            $perfil->setPasswd($password);
            //executa a atualização no DB e após retorna uma das mensagens ao usuário, a depender do resultado 
            $return = $perfil->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['client_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['client_lang']);
            }
        } else {
            echo "2->" . $translate->traduzir('A nova senha e a confirmação da senha não coincidem!');
        }
    } else {
        echo "2->" . $translate->translate('A senha atual informada está incorreta!', $_SESSION['client_lang']);
    }
} else {
    echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['client_lang']);
}