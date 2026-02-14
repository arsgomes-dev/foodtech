<?php

session_start();

//Função para atualização do status de acesso do CLIENTE
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
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
            !empty($_POST['code']) && isset($_POST['code'])) {
        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customersOne = new Customers();
        //recebe o GCID do FRONTEND
        $code = $_POST['code'];
        //informa que a busca no banco será atráves do GCID e não do ID
        $customersOne->setTable_db_primaryKey("gcid");
        //realiza a consulta no DB e retorna com linha única
        $customersOne = $customersOne->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
        //verifica se o cadastro do cliente existe no banco de dados
        if ($customersOne !== null && $customersOne->getId() !== null && $customersOne->getId() > 0) {
            //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
            $customers = new Customers();
            //Setando o ID, informa que o cliente já existe e então irá atualizar as informações
            $customers->setId($customersOne->getId());
            //consulta a variável POST Status, se existe insere o valor correspondente, caso não coloca como 0 (false)
            if ($_POST['status'] === "" || $_POST['status'] === null || empty($_POST['status']) || !isset($_POST['status'])) {
                $customers->setStatus(0);
            } else {
                $customers->setStatus($_POST['status']);
            }
            //chama a função SAVE que executa o INSERT/UPDATE no DB e após retorna uma das mensagens ao usuário, a depender do resultado
            $return = $customers->setSaveQuery();
            if ($return == 1) {
                echo "1->" . $translate->translate('Status atualizado com sucesso!', $_SESSION['user_lang']);
            } else if ($return == 3) {
                echo "2->" . $translate->translate('Erro ao atualizar status!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Cliente não encontrado!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   