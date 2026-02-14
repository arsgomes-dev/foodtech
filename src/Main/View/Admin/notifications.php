<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!doctype html>
<html lang="pt-br" style="height: auto;" data-bs-theme="light">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <!-- end top base html css -->
        <?php echo $baseHtml->baseCSSICheck(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <?php echo $baseHtml->baseCSSAlert(); ?>  
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("configuration", "notifications");
            ?>
            <div class="content-wrapper">

                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">

                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb("Notificações", $directory, "Notificações");
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("notification_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <br>
                        <section class="content" style="margin-bottom: 40px !important;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo $translate->translate('Menu', $_SESSION['user_lang']); ?></h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body p-0">
                                            <ul class="nav nav-pills flex-column">
                                                <input type="hidden" name="inbox_messages" id="inbox_messages" value="">
                                                <input type="hidden" name="star_messages" id="star_messages" value="">
                                                <li class="nav-item active">
                                                    <button class="btn btn-block btn-default border-0 btn-lg" style="background-color: transparent !important; text-align: left;" onclick="loadNotification(1, '<?php echo $translate->translate('Configurações', $_SESSION['user_lang']); ?>');">
                                                        <i class="fa fa-cogs"></i> <?php echo $translate->translate('Configurações', $_SESSION['user_lang']); ?>
                                                    </button>
                                                </li>
                                                <li class="nav-item">
                                                    <button class="btn btn-block btn-default border-0 btn-lg" style="background-color: transparent !important; text-align: left;" onclick="loadNotification(2, '<?php echo $translate->translate('Usuários', $_SESSION['user_lang']); ?>');">
                                                        <i class="fa fa-users"></i>  <?php echo $translate->translate('Usuários', $_SESSION['user_lang']); ?>
                                                    </button>
                                                </li>
                                                <li class="nav-item">
                                                    <button class="btn btn-block btn-default border-0 btn-lg" style="background-color: transparent !important; text-align: left;" onclick="loadNotification(5, '<?php echo $translate->translate('Mensagens', $_SESSION['user_lang']); ?>');">
                                                        <i class="fa fa-envelope"></i>  <?php echo $translate->translate('Mensagens', $_SESSION['user_lang']); ?>
                                                    </button>
                                                </li>
                                                <li class="nav-item">
                                                    <button class="btn btn-block btn-default border-0 btn-lg" style="background-color: transparent !important; text-align: left;" onclick="loadNotification(6, '<?php echo $translate->translate('Clientes', $_SESSION['user_lang']); ?>');">
                                                        <i class="fa fa-user-friends"></i>  <?php echo $translate->translate('Clientes', $_SESSION['user_lang']); ?>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9" id="notification"> 
                                    <div class="card card-primary card-outline">        
                                        <div class="card-header">
                                            <h3 class="card-title"><?php echo $translate->translate('Notificações', $_SESSION['user_lang']); ?></h3>
                                        </div>
                                        <div class="card-body p-0">
                                        </div>    
                                    </div>
                                </div>   
                            </div>
                        </section>



                        <?php
                    } else {
                        ?>
                        <div class="content-header">
                            <div class="container-fluid">
                                <div class="alert alert-warning alert-dismissible">
                                    <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                                    <?php
                                    echo $translate->translate('Você não tem permissão para visualizar esta página!', $_SESSION['user_lang']);
                                    ?>
                                    </font>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </section>
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
                ?>
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  

        <?php if (in_array("notification_view", $privilege_types)) { ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
            <script src="/libs/v1/admin/js/general/notifications/lists/notifications.js"></script>
            <script>
                                                        $(document).ready(function () {
                                                            loadNotification(1, '<?php echo $translate->translate('Configurações', $_SESSION['user_lang']); ?>');
                                                        });
            </script>
        <?php } ?>
        <!-- end bottom base html js -->
    </body>

</html>