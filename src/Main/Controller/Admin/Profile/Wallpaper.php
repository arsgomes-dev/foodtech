<?php

session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Returning;
use Microfw\Src\Main\Common\Helpers\Admin\UploadFile\UploadImg;

$language = new Language;
$translate = new Translate();
$config = new McConfig();
if (!empty(array_filter($_FILES))) {
    if ($_FILES) {
        $returning = new Returning;
        $perfil = new User();
        if (!empty($_SESSION['user_id'])) {
            if ($_SESSION['user_id']) {
                $perfil->setId($_SESSION['user_id']);
                $id = $_SESSION['user_id'];
            }
        }
        $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . "/user/" . $_SESSION['user_gcid'] . "/wallpaper/";

        $input_name = "profile_wallpaper";
        if ($_FILES [$input_name]['name']) {
            $user = new User;
            $user = $user->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $id]]);
            $arquivo = $user->getWallpaper();
            $upload = new UploadImg;
            if ($arquivo) {
                if (file_exists($dir_base . $arquivo)) {
                    $upload->delete($dir_base, $arquivo);
                }
            }
            $returning = $upload->upload($dir_base, $input_name, $_FILES [$input_name]);
            if ($returning->getValue() === 1) {
                $perfil->setWallpaper($returning->getDescription());
                $return = $perfil->setSaveQuery();
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