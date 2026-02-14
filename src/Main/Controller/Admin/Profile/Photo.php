<?php

session_start();

//Função para atualização da FOTO do PERFIL do Usuário
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importa as configurações do site
$config = new McConfig();
//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
if (!empty(array_filter($_FILES)) && $_FILES && $_FILES["profile_photo"]['name']) {
    if (!empty($_SESSION['user_id']) && $_SESSION['user_id']) {
        //delara a classe Usuário
        $perfil = new User();
        //seta o ID para informar que será uma atualização
        $id = $_SESSION['user_id'];
        $perfil->setId($id);
        //delcara variável com o diretório onde ficará a FOTO nesse local é usado o GCID do usuário e não o ID
        $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/user/" . $_SESSION['user_gcid'] . "/photo/";
        //declara o nome da variável file do FRONTEND
        $input_name = "profile_photo";
        $user = (new User)->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $id]]);
        //consulta a FOTO que o usuário já possui
        $arquivo = $user->getPhoto();
        $upload = new UploadImg;
        if ($arquivo) {
            // Se houver uma foto anterior, exclui do servidor
            if (file_exists($dir_base . $arquivo)) {
                $upload->delete($dir_base, $arquivo);
            }
        }
        //declara a classe que receberá o retorno do UPLOAD da FOTO do PERFIL
        $returning = new Returning;
        // Faz o upload do novo arquivo
        $returning = $upload->upload($dir_base, $input_name, $_FILES [$input_name]);
        // Verifica se o upload foi bem-sucedido
        if ($returning->getValue() === 1) {
            $perfil->setPhoto($returning->getDescription());
            //executa a atualização no DB e após retorna uma das mensagens ao usuário, a depender do resultado 
            $return = $perfil->setSaveQuery();
            //atualiza a foto da sessão do usuário
            $_SESSION['user_photo'] = $returning->getDescription();
            echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
        } else {
            //caso ocorra um erro no upload da foto ele apresenta
            echo $returning->getValue() . "->" . $returning->getDescription();
        }
    } else {
        echo "2->" . $translate->translate('Usuário não encontrado!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
}