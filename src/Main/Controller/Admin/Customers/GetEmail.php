<?php

namespace Microfw\Src\Main\Controller\Admin\Customers;

session_start();

//Função para verificar se o e-mail já esta cadastrado para um CLIENTE
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\Customers;

class GetEmail {

    //função que consulta se o e-mail já esta cadastrado no DB
    function getEmailRegistered($email, $code) {
        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customer = new Customers;
        $emailOne = "";
        //verifica se a variável GCID esta preenchida caso não passa a função seguinte
        if ($code !== null && $code !== "") {
            //informa que a busca no banco será atráves do GCID e não do ID
            $customer->setTable_db_primaryKey("gcid");
            //recebe o GCID e realiza a consulta
            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'cgid', 'value' => $code]]);
            //retorna o e-mail cadastrado para o cliente consultado
            $emailOne = $customer->getEmail();
        }
        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customers = new Customers;
        //seta o e-mail que deseja consultar se já esta cadastrado
        $count = $customers->getCountSumQuery(
                customWhere: [['column' => 'email', 'value' => $email]]
        );
        //declara a variável retorno
        $retorno = false;
        //verifica se existe registro, caso não returna FALSE
        if ($count['total_count'] > 0) {
            if ($email === $emailOne) {
                //Verifica se o novo e-mail informado é igual ao cadastrado para o mesmo cliente e retorna FALSE 
                $retorno = false;
            } else {
                //caso o e-mail esteja cadastrado para outro cliente ele returna TRUE
                $retorno = true;
            }
        } else {
            $retorno = false;
        }
        return $retorno;
    }
}
