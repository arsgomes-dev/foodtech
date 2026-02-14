<?php

namespace Microfw\Src\Main\Controller\Admin\Profile\Controller;

session_start();

//Função para consultasr se CPF esta cadastrado para outro usuário. A intenção é bloquear cadastros com CPFs duplicados.
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão.
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Entity\Admin\User;

class GetCpf {

    function getCpfRegistered($cpf): bool {
        //Remove todos os caracteres especiais e mantém apenas os números.
        $cpfInformed = str_replace(array('.', '-', '/'), "", $cpf);
        //Consulta o usuário da SESSION
        $user = new User;
        $user = $user->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['user_id']]]);
        //Remove todos os caracteres especiais e mantém apenas os números.
        $cpfUserLogged = str_replace(array('.', '-', '/'), "", $user->getCpf());
        //verifica se o novo CPF informado é igual ao já registrado para o mesmo usuário e retorna FALSE
        if ($cpfInformed === $cpfUserLogged) {
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
