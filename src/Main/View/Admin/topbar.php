<?php

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;

$config = new McConfig();
$translate = new Translate();
$language = new Language;
$website_logo = (isset($stConfig) ? $stConfig->getLogo() : "");
?>
<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav navbar-close">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <div class="btn-group ml-auto navbar-user">
        <button type="button" class="btn-profile-photo btn dropdown-toggle dropdown-icon" data-toggle="dropdown">
            <?php
            $img = "";
            $profile_img = "/" . $_SESSION['user_gcid'] . "/photo/" . $_SESSION['user_photo'];
            $profile_model = "/model/user_model.png";
            $img = ($_SESSION['user_photo'] !== null) ? $profile_img : $profile_model;
            ?>
            <img src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $img; ?>" alt="mdo" width="32" height="32" class="rounded-circle" style="margin-right: 2px;">
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/profile"><i class="nav-icon fas fa-user-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Perfil', $_SESSION['user_lang'])); ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo "/" . $config->getUrlAdmin(); ?>/login/logout"><i class="nav-icon fas fa-sign-out-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Sair', $_SESSION['user_lang'])); ?></a>
        </div>
    </div>
</nav>