<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
?>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu();
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                
                <!-- start base html breadcrumb -->
                <?php 
                $directory = [];
                $directory["Home"] = ""; 
                echo $baseHtml->baseBreadcrumb("Dashboard", $directory, "Dashboard"); ?>  
                <!-- end base html breadcrumb -->

                <section class="content">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Gráficos</h3>
                        </div>
                        <div class="card-body">
                            Start creating your amazing application!

                        </div>

                        <div class="card-footer">
                            Footer
                        </div>

                    </div>

                </section>

            </div>

            <!-- footer start -->
            <?php
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
            ?>
            <!-- footer end -->
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <!-- end bottom base html js -->
    </body>

</html>