<?php

session_start();

//Função para atualização do e-mail do CLIENTE
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Controller\Admin\Customers\GetEmail;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//Importa dos dados de confugurações do sistema
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("customer_edit", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty($_POST['email']) && isset($_POST['email'])) {

        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customerOne = new Customers();
        //recebe o GCID do FRONTEND
        $code = $_POST['code'];
        //informa que a busca no banco será atráves do GCID e não do ID
        $customerOne->setTable_db_primaryKey("gcid");
        //realiza a consulta no DB e retorna com linha única
        $customerOne = $customerOne->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        //verifica se o cadastro do cliente existe no banco de dados
        if ($customerOne !== null && $customerOne->getId() !== null && $customerOne->getId() > 0) {
            //chama a função que verifica se o novo e-mail informado já existe no DB, caso não exista segue a função, caso exista ele informa que o e-mail já esta cadastrado
            $emailSearch = new GetEmail;
            if ($emailSearch->getEmailRegistered($_POST['email'], $code) === false) {
                //o e-mail estando disponível ele segue a função                
                //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
                $customer = new Customers();
                //Setando o ID, informa que o cliente já existe e então irá atualizar as informações
                $customer->setId($customerOne->getId());
                //Seta o novo e-mail
                $customer->setEmail($_POST['email']);
                //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
                $return = $customer->setSaveQuery();
                if ($return == 1) {
                    echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']) . "!";
                } else if ($return == 3) {
                    echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
                }
            } else {
                echo "2->" . $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']) . "!";
            }
        } else {
            echo "2->" . $translate->translate('Cliente não encontrado', $_SESSION['user_lang']) . "!";
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   