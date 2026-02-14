<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\StConfig;
use Microfw\Src\Main\Common\Entity\Public\Company;
use Microfw\Src\Main\Common\Entity\Public\YoutubeChannels;
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
        <!-- ===================== -->
        <!--   ÁREA DE TRABALHO    -->
        <!-- ===================== -->

        <div class="user-panel pb-3 mb-3 d-flex flex-column" 
             style="border-bottom: 1px solid #4b545c;" data-toggle="tooltip"
             data-placement="right"
             title="<?php echo $translate->translate('Área de Trabalho', $_SESSION['client_lang']) . ": " . htmlspecialchars($_SESSION['active_workspace_title'] ?? $translate->translate('Nenhuma selecionada', $_SESSION['client_lang'])); ?>"
             style="border-bottom: 1px solid #4b545c;">

            <?php
            $active_workspace_gcid = $_SESSION['active_workspace_gcid'] ?? null;
            $channel_model = $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client" . "/model/channel_model.jpg";
            $active_workspace_thumb = "";
            if ($_SESSION['active_workspace_thumb'] === null || $_SESSION['active_workspace_thumb'] === "") {
                $active_workspace_thumb = $channel_model;
            } else {
                $active_workspace_thumb = $_SESSION['active_workspace_thumb'];
            }

            $active_workspace_title = $_SESSION['active_workspace_title'] ?? $translate->translate('Nenhuma selecionada', $_SESSION['client_lang']);
            ?>

            <div class="d-flex align-items-center">
                <div class="image mr-2">
                    <img id="ytChannelThumbnail"
                         src="<?php echo $active_workspace_thumb; ?>"
                         alt="Thumbnail do Canal"
                         class="img-circle elevation-2">
                </div>

                <div class="info" style="line-height: 1.2;">
                    <span class="text-muted text-xs d-block"><?php echo $translate->translate('Área de Trabalho', $_SESSION['client_lang']); ?></span>
                    <strong class="text-white text-sm d-block">
                        <?php echo htmlspecialchars($active_workspace_title); ?>
                    </strong>
                </div>
            </div>

            <a href="#"
               data-bs-toggle="modal" data-bs-target="#modalChangeWorkspace"
               class="btn btn-sm btn-outline-info mt-2"
               style="border-radius: 6px; width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                <i class="fas fa-exchange-alt"></i> <?php echo $translate->translate('Mudar Canal', $_SESSION['client_lang']); ?>
            </a>

        </div>
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
<div class="modal fade" id="modalChangeWorkspace" tabindex="-1" role="dialog" aria-labelledby="modalWorkspaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $translate->translate('Selecionar Área de Trabalho (Canal)', $_SESSION['client_lang']); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <p class="text-muted mb-3">
                    <?php echo $translate->translate('Escolha um canal para ativar como área de trabalho.', $_SESSION['client_lang']); ?>
                </p>

                <div class="list-group">

                    <?php
                    $channels = new YoutubeChannels();
                    $channels->setTable_db_primaryKey("customer_id");
                    $channels->setCustomer_id($_SESSION['client_gcid']);
                    $channels = $channels->getQuery();
                    if (!empty($channels)) {
                        foreach ($channels as $ch) {
                            ?>
                            <button 
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center select-workspace"
                                data-gcid="<?php echo $ch->getGcid(); ?>"
                                data-title="<?php echo htmlspecialchars($ch->getTitle()); ?>">

                                <div>
                                    <i class="fas fa-hashtag text-info mr-2"></i>
                                    <?php echo htmlspecialchars($ch->getTitle()); ?>
                                </div>

                                <i class="fas fa-chevron-right text-secondary"></i>
                            </button>
                            <?php
                        }
                    }
                    ?>

                </div>

            </div>

        </div>
    </div>
</div>