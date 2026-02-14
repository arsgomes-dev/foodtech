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
<nav class="main-header navbar navbar-expand navbar-dark">
    <ul class="navbar-nav navbar-close">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <?php
    $planService = new CheckPlan;
    $check = $planService->checkPlan();
    if ($check['allowed']) {
        ?>
        <div class="description-plan">
            <span class="float-left badge bg-success"><?php echo (ucfirst($translate->translate('Usuário', $_SESSION['client_lang'])) . ": " . $_SESSION['client_plan_title']); ?></span>
            <span class="float-left badge bg-blue"><?php echo (ucfirst($translate->translate('Tokens Utilizados', $_SESSION['client_lang'])) . ": " . $_SESSION['client_plan_tokens_usage']); ?></span>
            <span class="float-left badge bg-info"><?php echo (ucfirst($translate->translate('Tokens Restantes', $_SESSION['client_lang'])) . ": <b>" . ((int) $_SESSION['client_plan_tokens'] - (int) $_SESSION['client_plan_tokens_usage'])); ?></b></span>
        </div>
            <?php
    }
    ?>         
    <div class="btn-group ml-auto navbar-user">
        <button type="button" class="btn-profile-photo btn dropdown-toggle dropdown-icon" data-toggle="dropdown">
            <?php
            $img = "";
            $profile_img = "/" . $_SESSION['client_gcid'] . "/photo/" . $_SESSION['client_photo'];
            $profile_model = "/model/client_model.png";
            $img = ($_SESSION['client_photo'] !== null && $_SESSION['client_photo'] !== "") ? $profile_img : $profile_model;
            ?>
            <img src="<?php echo $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client" . $img; ?>" alt="mdo" width="32" height="32" class="rounded-circle" style="margin-right: 2px;">
        </button>
        <div class="dropdown-menu" role="menu">
            <a class="dropdown-item" href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/profile"><i class="nav-icon fas fa-user-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Perfil', $_SESSION['client_lang'])); ?></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?php echo "/" . $config->getUrlPublic(); ?>/login/logout"><i class="nav-icon fas fa-sign-out-alt"></i>&nbsp;&nbsp;<?php echo ucfirst($translate->translate('Sair', $_SESSION['client_lang'])); ?></a>
        </div>
    </div>
</nav>