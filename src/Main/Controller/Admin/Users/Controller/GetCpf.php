<?php

namespace Microfw\Src\Main\Controller\Admin\Users\Controller;

session_start();

//Função para consultasr se CPF esta cadastrado para outro usuário. A intenção é bloquear cadastros com CPFs duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão.
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\User;

class GetCpf {

    function getCpfRegistered($cpf, $code): bool {
        //Remove todos os caracteres especiais e mantém apenas os números.
        $cpfInformed = str_replace(array('.', '-', '/'), "", $cpf);
        $cpfUser = null;
        //Consulta o usuário
        if ($code !== null && $code !== "") {
            $user = new User;
            $user->setTable_db_primaryKey("gcid");
            $user = $user->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
            //Remove todos os caracteres especiais e mantém apenas os números.
            $cpfUser = str_replace(array('.', '-', '/'), "", $user->getCpf());
        }
        //verifica se o novo CPF informado é igual ao já registrado para o mesmo usuário e retorna FALSE
        if ($cpfInformed === $cpfUser) {
            return false;
        }
        //verifica se o novo CPF informado pertence a outro usuário caso sim retorna TRUE caso não retorna FALSE
        $userSearch = new User;
        $count = $userSearch->getCountSumQuery(
                customWhere: [['column' => 'cpf', 'value' => $cpfInformed]]
        );
        return $count['total_count'] > 0;
    }
}
