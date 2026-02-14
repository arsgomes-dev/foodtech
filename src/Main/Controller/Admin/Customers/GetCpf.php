<?php

namespace Microfw\Src\Main\Controller\Admin\Customers;

session_start();

//Função para verificar se o CPF/CNPJ já esta cadastrado para um CLIENTE
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\Customers;

class GetCpf {

    //função que consulta se o CPF/CNPJ já esta cadastrado no DB
    function getCpfRegistered($cpf, $code) {
        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customer = new Customers;
        $cpfOne = "";
        //verifica se a variável GCID esta preenchida caso não passa a função seguinte
        if ($code !== null && $code !== "") {
            //informa que a busca no banco será atráves do GCID e não do ID
            $customer->setTable_db_primaryKey("gcid");
            //recebe o GCID e realiza a consulta
            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'cgid', 'value' => $code]]);
            //retorna o e-mail cadastrado para o cliente consultado
            $cpfOne = $customer->getCpf();
        }
        //declara a variavel de acordo com a classe CUSTOMERS (CLIENTES)
        $customerSearch = new Customers;
        //seta o CPF/CNPJ que deseja consultar se já esta cadastrado
        $count = $customerSearch->getCountSumQuery(
                customWhere: [['column' => 'cpf', 'value' => $cpf]]);
        $retorno = false;
        if ($count['total_count'] > 0) {
            if (str_replace(array('.', '-', '/'), "", $cpf) === $cpfOne) {
                //Verifica se o novo CPF/CNPJ informado é igual ao cadastrado para o mesmo cliente e retorna FALSE 
                $retorno = false;
            } else {
                //caso o CPF/CNPJ esteja cadastrado para outro cliente ele returna TRUE
                $retorno = true;
            }
        } else {
            $retorno = false;
        }
        return $retorno;
    }
}
