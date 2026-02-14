<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\StConfig;
use Microfw\Src\Main\Common\Entity\Public\Company;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$planService = new CheckPlan;
$check = $planService->checkPlan();

$config = new McClientConfig();
$stConfig = new StConfig;
$language = new Language;
$translate = new Translate();
//$stConfig = $stConfig->getOne(1);
$stConfig = $stConfig->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);
$website_logo = (isset($stConfig) ? $stConfig->getLogo() : "");
$website_ico = (isset($stConfig) ? "/ico/" . $stConfig->getIco() : "");
$website_title = (isset($stConfig) ? $stConfig->getTitle() : "");

$stCompany = new Company();
//$stCompany = $stCompany->getOne(1);
$stCompany = $stCompany->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);

$website_title = (isset($stCompany) ? $stCompany->getName_fantasy() : $website_title);
$website_ico = (isset($stCompany) ? "/logo/" . $stCompany->getLogo() : $website_favicon);
?>
<script src="/assets/js/home/slidebar.js"></script>
<aside class="main-sidebar elevation-4">

    <!-- Brand -->
    <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/" class="brand-link" style="border-right: 3px solid #5faee3;">
        <img src="<?php echo $config->getDomain() . $config->getBaseFile() . $website_ico; ?>" alt="<?php echo $website_title; ?>" class="brand-image image img-circle elevation-3" style="opacity: .8; margin-left: 20px;">
        <span class="brand-text font-weight-light"><?php echo $website_title; ?></span>
        <hr>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="border-right: 3px solid #5faee3; display:flex; flex-direction:column; height:100%;">
        <nav class="mt-2" style="flex:1; display:flex; flex-direction:column;">

            <!-- Menu principal -->
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false" style="flex:1; display:flex; flex-direction:column;">

                <?php
                if (!$check['allowed']) {
                    if ($check['plan_active']) {
                        if (!$check['plan_payment']) {
                            ?>
                            <li class="renew-plan nav-item">
                                <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/renewplan" class="nav-link <?php echo ($menu_active === "renewplan") ? "active" : ""; ?>">
                                    <i class="nav-icon fas fa-lock"></i>
                                    <p><?php echo ucfirst($translate->translate('Renovar Plano', $_SESSION['client_lang'])); ?></p>
                                </a>
                            </li>
                            <?php
                        }
                    } else {
                        ?>
                        <li class="renew-plan nav-item">
                            <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/subscribe" class="nav-link <?php echo ($menu_active === "subscribe") ? "active" : ""; ?>">
                                <i class="nav-icon fas fa-lock"></i>
                                <p><?php echo ucfirst($translate->translate('Assinar Plano', $_SESSION['client_lang'])); ?></p>
                            </a>
                        </li>
                        <?php
                    }
                }
                ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/" class="nav-link <?php echo ($menu_active === "home") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p><?php echo ucfirst($translate->translate('Dashboard', $_SESSION['client_lang'])); ?></p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/profile" class="nav-link <?php echo ($menu_active === "profile") ? "active" : ""; ?>">
                        <i class="nav-icon far fa-user"></i>
                        <p><?php echo ucfirst($translate->translate('Perfil', $_SESSION['client_lang'])); ?></p>
                    </a>
                </li>

                <?php
                $path = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/Menus";
                $directory_admin = dir($path);
                $dir_array = array();
                while ($file_admin = $directory_admin->read()) {
                    if (!strcasecmp($file_admin, ".") == 0 && !strcasecmp($file_admin, "..") == 0) {
                        array_push($dir_array, $file_admin);
                    }
                }
                sort($dir_array);
                for ($i = 0; $i < count($dir_array); $i++) {
                    ob_start();
                    require $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/Menus/" . $dir_array[$i];
                    $html = ob_get_contents();
                    ob_end_clean();
                    print_r($html);
                }
                ?>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/signature" class="nav-link <?php echo ($menu_active === "signature") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p><?php echo ucfirst($translate->translate('Assinatura', $_SESSION['client_lang'])); ?></p>
                    </a>
                </li>   
                <!-- Rodapé: user-panel + logout -->
                <div class="menu-footer">
                    <li class="nav-item user-panel user-panel-item">
                        <a href="<?php echo $config->getDomain() . "/" . $config->getUrlPublic(); ?>/profile" class="nav-link user-box">
                            <div class="image">

                                <?php
                                $img = "";
                                $profile_img = "/" . $_SESSION['client_gcid'] . "/photo/" . $_SESSION['client_photo'];
                                $profile_model = "/model/client_model.png";
                                $img = ($_SESSION['client_photo'] !== null && $_SESSION['client_photo'] !== "") ? $profile_img : $profile_model;
                                ?>
                                <img src="<?php echo $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client" . $img; ?>"
                                     class="img-circle elevation-2" alt="User Image">
                            </div>

                            <div class="info">
                                <?php echo $_SESSION['client_username']; ?>
                            </div>
                        </a>
                    </li>
                    <!-- Logout -->
                    <li class="nav-item logout-item">
                        <a href="<?php echo "/" . $config->getUrlPublic(); ?>/login/logout" class="nav-link">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <p><?php echo ucfirst($translate->translate('Sair', $_SESSION['client_lang'])); ?></p>
                        </a>
                    </li>

                </div>
            </ul>
        </nav>
    </div>
</aside>