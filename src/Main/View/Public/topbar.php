<?php

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig();
$translate = new Translate();
$language = new Language;
$website_logo = (isset($stConfig) ? $stConfig->getLogo() : "");
?>
<nav class="main-header navbar navbar-expand">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <?php
    $planService = new CheckPlan;
    $check = $planService->checkPlan();
    if ($check['allowed']) {
        ?>
        <div class="description-plan d-none d-md-flex align-items-center ml-3">
            <span class="badge bg-success"><?php echo (ucfirst($translate->translate('Usuário', $_SESSION['client_lang'])) . ": " . $_SESSION['client_plan_title']); ?></span>
        </div>
        <?php
    }
    ?>

    <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item">
            <a class="nav-link" href="#" id="theme-toggle" title="<?php echo $translate->translate('Alternar Tema', $_SESSION['client_lang']); ?>">
                <i class="fas fa-moon"></i>
            </a>
        </li>
        <li class="nav-item">
            <div class="btn-group">
                <button type="button" class="btn-profile-photo btn dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <?php
                    $img = "";
                    $profile_img = "/" . $_SESSION['client_gcid'] . "/photo/" . $_SESSION['client_photo'];
                    $profile_model = "/model/client_model.png";
                    $img = ($_SESSION['client_photo'] !== null && $_SESSION['client_photo'] !== "") ? $profile_img : $profile_model;
                    ?>
                    <img src="<?php echo $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client" . $img; ?>" alt="mdo" width="34" height="34" class="rounded-circle">
                </button>
                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a class="dropdown-item" href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/profile">
                        <i class="nav-icon fas fa-user-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Perfil', $_SESSION['client_lang'])); ?>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo "/" . $config->getUrlPublic(); ?>/login/logout">
                        <i class="nav-icon fas fa-sign-out-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Sair', $_SESSION['client_lang'])); ?>
                    </a>
                </div>
            </div>
        </li>
    </ul>
</nav>
