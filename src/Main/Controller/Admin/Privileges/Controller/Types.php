<?php

session_start();

//Função para cadastro e atualização dos TIPOS DE PRIVILÉGIOS
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\PrivilegeTypePrivilege;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("privileges_configuration", $privilege_types)) {
    //verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST && !empty($_POST['code']) && isset($_POST['code']) && !empty($_POST['privileges_type']) && isset($_POST['privileges_type'])) {

        $returns = 0;
        //recebe o ID do privilégio
        $code = $_POST['code'];
        //declara a classe em uma variável e preenche com as informações a serem salvas no DB
        $privilege_types = new PrivilegeTypePrivilege;
        //Informa que a primary key para essa função será usada privilege_id no lugar o ID
        $privilege_types->setTable_db_primaryKey("privilege_id");
        $privilege_types->setPrivilege_id($code);
        //deleta todos as associações entre o privilégio e os tipos de privilégios que possui 
        $privilege_types->setDeleteQuery();
        //recebe a variável array com os novos privilégios
        $types = $_POST['privileges_type'];
        $typesCount = count($types);
        //a estrutura de repetição irá peccorar o array de privilégios a associá-los ao privilégio escolhido no DB
        if ($typesCount > 0) {
            for ($i = 0; $i < $typesCount; $i++) {
                //Declara classe de associação 
                $privilege_type = new PrivilegeTypePrivilege;
                //informa o ID a qual será associado
                $privilege_type->setPrivilege_id($code);
                //informa os privilegios
                $privilege_type->setPrivilege_type_id($types[$i]);
                //salva no DB e retorna o resultado
                $return = $privilege_type->setSaveQuery();
                if ($return == 2) {
                    //atribui o valor 2 a váriavél $returns informando que foi salvo do DB
                    $returns = 2;
                } else if ($return == 3) {
                    //atribui o valor 2 a váriavél $returns informando que houve um erro ao salvar do DB
                    $returns = 3;
                    //como exista um erro ele finaliza o loop do for e não processe para as próximas execuções 
                    break;
                }
            }
        }
        //retorna a mensagem de acordo com o resultado acima 
        if ($returns == 2) {
            echo "1->" . $translate->translate('Privilégios de acesso atualizados com sucesso!', $_SESSION['user_lang']);
        } else if ($returns == 3) {
            echo "2->" . $translate->translate('Erro ao atualizar os privilégios de acesso!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}