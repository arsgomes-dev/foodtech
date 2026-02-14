<?php

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\Company;

$config = new McConfig();
$stConfig = new StConfig;
$language = new Language;
$translate = new Translate();
$stConfig = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
$website_logo = (isset($stConfig) ? $stConfig->getLogo() : "");
$website_ico = (isset($stConfig) ? "/ico/" . $stConfig->getIco() : "");
$website_title = (isset($stConfig) ? $stConfig->getTitle() : "");

$stCompany = new Company();
$stCompany = $stCompany->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);

$website_title = (isset($stCompany) ? $stCompany->getName_fantasy() : $website_title);
$website_ico = (isset($stCompany) ? "/logo/" . $stCompany->getLogo() : $website_favicon);

$privilege_types = $_SESSION['user_type'];

?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/" class="brand-link" style="border-right: 3px solid #5faee3;">
        <img src="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . $website_ico; ?>" alt="<?php echo $website_title; ?>" class="brand-image image img-circle elevation-3" style="opacity: .8; margin-left: 20px;">
        <span class="brand-text font-weight-light"><?php echo $website_title; ?></span>
    </a>

    <div class="sidebar" style="border-right: 3px solid #5faee3;">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <?php
                $img = "";
                $profile_img = "/" . $_SESSION['user_gcid'] . "/photo/" . $_SESSION['user_photo'];
                $profile_model = "/model/user_model.png";
                $img = ($_SESSION['user_photo'] !== null) ? $profile_img : $profile_model;
                ?>
                <img src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $img; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/profile" class="d-block"><?php echo $_SESSION['user_username']; ?></a>
            </div>
        </div>



        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/" class="nav-link <?php echo ($menu_active === "home") ? "active" : ""; ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p><?php echo ucfirst($translate->translate('Dashboard', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/profile" class="nav-link <?php echo ($menu_active === "profile") ? "active" : ""; ?>">
                        <i class="nav-icon far fa-user"></i>
                        <p><?php echo ucfirst($translate->translate('Perfil', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>

                <?php
                $path = $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/Menus";
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
                    require $_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/Menus/" . $dir_array[$i];
                    $html = ob_get_contents();
                    ob_end_clean();
                    print_r($html);
                }
                ?>
                <?php if (in_array("configuration", $privilege_types)) { ?>
                    <li class="nav-item <?php echo ($menu_active === "configuration") ? "menu-is-opening menu-open" : ""; ?>">
                        <a href="#" class="nav-link <?php echo ($menu_active === "configuration") ? "active" : ""; ?>">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                <?php echo ucfirst($translate->translate('Configurações', $_SESSION['user_lang'])); ?>
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: <?php echo ($menu_active === "configuration") ? "block" : "none"; ?>;">
                            <li class="nav-item">
                                <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/privileges" class="nav-link  <?php echo ($submenu_active === "privileges") ? "active" : ""; ?>">
                                    <i class="nav-icon fas fa-key"></i>
                                    <p><?php echo ucfirst($translate->translate('Privilégios', $_SESSION['user_lang'])); ?></p>
                                </a>
                            </li>
                            <?php if (in_array("notification_view", $privilege_types)) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/notifications" class="nav-link  <?php echo ($submenu_active === "notifications") ? "active" : ""; ?>">
                                        <i class="nav-icon fas fa-envelope"></i>
                                        <p><?php echo ucfirst($translate->translate('Notificações', $_SESSION['user_lang'])); ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                            <?php if (in_array("configuration_company", $privilege_types)) { ?>
                                <li class="nav-item">
                                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/company" class="nav-link  <?php echo ($submenu_active === "company") ? "active" : ""; ?>">
                                        <i class="nav-icon fas fa-computer"></i>
                                        <p><?php echo ucfirst($translate->translate('Dados da Empresa', $_SESSION['user_lang'])); ?></p>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <li class="nav-item mt-3">
                    <a href="<?php echo "/" . $config->getUrlAdmin(); ?>/login/logout" 
                       class="nav-link bg-danger text-white rounded-pill px-3 py-2 d-flex align-items-center justify-content-center shadow-sm"
                       >
                        <i class="fas fa-sign-out-alt me-2"></i>
                        <p class="m-0"><?php echo ucfirst($translate->translate('Sair', $_SESSION['user_lang'])); ?></p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>

</aside>