<?php

session_start();

//Função para atualizar a foto do USUÁRIO
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Company;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

//importa o arquivo de tradução para os retornos da página
$language = new Language;
$translate = new Translate();
//importar configurações do site
$config = new McConfig();
//importa os privilégios de acesso do usuario
$privilege_types = $_SESSION['user_type'];
//verifica se o usuário tem privilégio de acesso a função
if (in_array("configuration", $privilege_types)) {
//verifica se as variáveis enviadas via POST estão preenchidas, caso não retorna um ERRO
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['code']) && isset($_POST['code']) &&
            !empty(array_filter($_FILES))) {
        if ($_FILES) {
            $returning = new Returning;
            $input_name = "logo_photo";
            if ($_FILES [$input_name]['name']) {
                // Cria instância da classe e consulta pelo GCID se já existe foto do usuário   
                $company = new Company();
                $company = $company->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_POST['code']]]);
                $arquivo = $company->getLogo();
                //Cria instância da classe com as funções da imagem
                $upload = new UploadImg;
                //Seta diretório da imagem
                $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFile() . "/logo/";
                //Se existir foto é excluída
                if ($arquivo) {
                    if (file_exists($dir_base . $arquivo)) {
                        $upload->delete($dir_base, $arquivo);
                    }
                }
                //Realiza upload da nova foto
                $returning = $upload->upload($dir_base, $input_name, $_FILES [$input_name]);
                if ($returning->getValue() === 1) {
                    // Cria instância da classe e seta informações da nova foto
                    $companyLogo = new Company();
                    $companyLogo->setId($company->getId());
                    $companyLogo->setLogo($returning->getDescription());
                    //salva no DB
                    $return = $companyLogo->setSaveQuery();
                    echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
                } else {
                    echo $returning->getValue() . "->" . $returning->getDescription();
                }
            } else {
                echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
            }
        } else {
            echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}   