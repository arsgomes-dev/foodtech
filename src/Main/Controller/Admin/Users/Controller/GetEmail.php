<?php

namespace Microfw\Src\Main\Controller\Admin\Users\Controller;

session_start();

//Função para consultar se E-MAIL esta cadastrado para outro usuário, A intenção é bloquear cadastros com e-mails duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\User;

class GetEmail {

    function getEmailRegistered($email, $code): bool {
        // Cria instância da classe e consulta pelo GCID
        $user = new User;
        $currentEmail = "";
        if ($code !== null && $code !== "") {
            $user->setTable_db_primaryKey("gcid");
            $user = $user->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $code]]);
            $currentEmail = $user->getEmail();
        }
        //verifica se o novo E-MAIL informado é igual ao já registrado para o mesmo usuário e retorna FALSE
        if ($email === $currentEmail) {
            return false;
        }
        //verifica se o novo E-MAIL informado pertence a outro usuário caso sim retorna TRUE caso não retorna FALSE
        $userSearch = new User;
        $count = $userSearch->getCountSumQuery(
                customWhere: [['column' => 'email', 'value' => $email]]
        );
        return $count['total_count'] > 0;
    }
}
