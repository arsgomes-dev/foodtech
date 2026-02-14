<?php

session_start();

//Função para atualização dos pagamentos
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os as configurações do site
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("customer_signatures", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['date_payment']) && isset($_POST['date_payment']) &&
            !empty($_POST['method']) && isset($_POST['method']) &&
            !empty($_POST['sts']) && isset($_POST['sts'])) {
        //declara a classe
        $payment = new SignaturePayment;
        //informa que o GCID vai ser usado como primaryKey no lugar do ID
        $payment->setTable_db_primaryKey("gcid");
        $payment->setGcid($_POST['code']);
        //preenche com as informações que serão atualizadas
        $payment->setDate_payment($_POST['date_payment']);
        $payment->setPayment_method_id($_POST['method']);
        $payment->setPayment_status_id($_POST['sts']);
        $payment->setUser_id_updated($_SESSION['user_id']);
        //chama a função SAVE que executa o UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
        $return = $payment->setSaveQuery();
        if ($return == 1) {
            echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 3) {
            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   