<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\VideoScript;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$language = new Language;
$translate = new Translate();
$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$planService = new CheckPlan;
$check = $planService->checkPlan();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-theme="dark">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel='stylesheet' href='/assets/css/home.css'>

        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("home");
            ?>
            <div class="content-wrapper" style="min-height: auto !important; margin-bottom: 20px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $workspaceDash = htmlspecialchars($_SESSION['active_workspace_title'] ?? $translate->translate('Dashboard', $_SESSION['client_lang']));
                    echo $baseHtml->baseBreadcrumb($workspaceDash, $directory, "Dashboard");
                    ?>  
                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                    <!-- end base html breadcrumb -->
                    <?php
                    if (!$check['allowed']) {
                        ?>
                        <div class="alert alert-info text-start" style="padding: 0.50rem 1.25rem; margin-bottom: 10px;">
                            <strong><?php echo $check['message']; ?></strong><br>
                        </div> 
                        <br>
                        <?php
                    } else {
                        ?>  
                        <!-- cards -->
                        <!-- Cards Premium -->
                        <div class="row g-3">

                            <div class="col-xl-3 col-md-6">
                                <div class="dashboard-card gradient-blue">
                                    <?php
                                    $scripts = new VideoScript;
                                    $count = $scripts->getCountSumQuery(
                                            customWhere: [['column' => 'customer_id', 'value' => $_SESSION['client_gcid']], ['column' => 'channel_gcid', 'value' => $_SESSION['active_workspace_gcid']]]
                                    );
                                    ?>
                                    <h4 class="value"><?php echo $count['total_count']; ?></h4>
                                    <p class="label"><?php echo $translate->translate('Roteiros Gerados', $_SESSION['client_lang']); ?></p>
                                    <div class="icon"><i class="fas fa-pen-nib"></i></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="dashboard-card gradient-purple">
                                    <?php
                                    $scriptsRecording = new VideoScript;
                                    $countRecording = $scriptsRecording->getCountSumQuery(
                                            customWhere: [['column' => 'customer_id', 'value' => $_SESSION['client_gcid']], ['column' => 'channel_gcid', 'value' => $_SESSION['active_workspace_gcid']]],
                                            customWhereOr: [['column' => 'status_id', 'values' => [4]]]
                                    );
                                    ?>
                                    <h4 class="value"><?php echo $countRecording['total_count']; ?></h4>
                                    <p class="label"><?php echo $translate->translate('Pronto para Gravação', $_SESSION['client_lang']); ?></p>
                                    <div class="icon"><i class="fas fa-video-camera"></i></div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="dashboard-card gradient-orange">
                                    <?php
                                    $scriptsAwaiting = new VideoScript;
                                    $countAwaiting = $scriptsAwaiting->getCountSumQuery(
                                            customWhere: [['column' => 'customer_id', 'value' => $_SESSION['client_gcid']], ['column' => 'channel_gcid', 'value' => $_SESSION['active_workspace_gcid']]],
                                            customWhereOr: [['column' => 'status_id', 'values' => [7]]]
                                    );
                                    ?>
                                    <h4 class="value"><?php echo $countAwaiting['total_count']; ?></h4>
                                    <p class="label"><?php echo $translate->translate('Aguardando Publicação', $_SESSION['client_lang']); ?></p>
                                    <div class="icon"><i class="fas fa-hourglass-1"></i></div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="dashboard-card gradient-green">
                                    <?php
                                    $scriptsPublished = new VideoScript;
                                    $countPublished = $scriptsPublished->getCountSumQuery(
                                            customWhere: [['column' => 'customer_id', 'value' => $_SESSION['client_gcid']], ['column' => 'channel_gcid', 'value' => $_SESSION['active_workspace_gcid']]],
                                            customWhereOr: [['column' => 'status_id', 'values' => [8]]]
                                    );
                                    ?>
                                    <h4 class="value"><?php echo $countPublished['total_count']; ?></h4>
                                    <p class="label"><?php echo $translate->translate('Projetos Publicados', $_SESSION['client_lang']); ?></p>
                                    <div class="icon"><i class="fas fa-globe"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- cards -->
                    <?php } ?>
                </section>
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php");
                ?>
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/assets/js/home/home.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>