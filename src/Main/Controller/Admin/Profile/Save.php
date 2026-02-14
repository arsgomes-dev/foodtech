
<?php

session_start();

//Função para atualização dos dados do PERFIL do usuário
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Helpers\Admin\Validate\IsValidCpf;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Controller\Admin\Profile\Controller\GetEmail;
use Microfw\Src\Main\Controller\Admin\Profile\Controller\GetCpf;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa as configurações do site
$config = new McConfig();

//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
if (!empty(array_filter($_POST)) && $_POST &&
        !empty($_SESSION['user_id']) && isset($_SESSION['user_id']) &&
        !empty($_POST['name']) && isset($_POST['name']) &&
        !empty($_POST['cpf']) && isset($_POST['cpf']) &&
        !empty($_POST['birth']) && isset($_POST['birth']) &&
        !empty($_POST['contact']) && isset($_POST['contact']) &&
        !empty($_POST['email']) && isset($_POST['email'])) {
    //verifica se o cpf informado é válido
    $isValidCpf = new IsValidCpf;
    if ($isValidCpf->getIsValidCPF($_POST['cpf'])) {
        $searchEmail = new GetEmail;
        //verifica se o e-mail informado já esta cadastrado
        if ($searchEmail->getEmailRegistered($_POST['email']) === false) {
            $searchCpf = new GetCpf;
            //verifica se o CPF informado já esta cadastrado
            if ($searchCpf->getCpfRegistered($_POST['cpf']) === false) {
                //declara a classe USER
                $user = new User();
                //seta os valores que será salvos no DB
                $user->setId($_SESSION['user_id']);
                $user->setName($_POST['name']);
                $user->setCpf($_POST['cpf']);
                $user->setBirth($_POST['birth']);
                $user->setContact($_POST['contact']);
                $user->setEmail($_POST['email']);
                //salva no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                $return = $user->setSaveQuery();
                if ($return == 1) {
                    echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
                } else if ($return == 3) {
                    echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
                }
            } else {
                echo "2->" . $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']) . "!";
            }
        } else {
            echo "2->" . $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']) . "!";
        }
    } else {
        echo "2->" . $translate->translate('CPF inválido', $_SESSION['user_lang']) . "!";
    }
} else {
    echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
}