<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
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
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <link rel='stylesheet' href='/assets/css/channels.css'>
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("channels");
            ?>
            <div class="content-wrapper" style="min-height: auto !important; margin-bottom: 20px;">
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                <section class="content col-lg-12 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['client_lang'])] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Meus Canais", $_SESSION['client_lang']), $directory, $translate->translate("Meus Canais", $_SESSION['client_lang']));
                    ?>  
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
                        <!-- div conteudo -->
                        <!-- start card -->
                        <div class="card card-border-radius">
                            <div class="card-body row" id="list_channels">  

                            </div>
                        </div>
                        <!-- end div conteudo -->
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
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/vendor/validation/js/formValidation.min.js"></script>
        <script src="/assets/js/channels/lists/channels.js"></script>
        <?php
// Se recebeu POST do PostRedirect
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!empty($_POST['message']) && !empty($_POST['code'])) {
                $_SESSION['toast_message'] = $_POST['message'];
                $_SESSION['toast_code'] = $_POST['code'];
            }

            // Redireciona para limpar o POST
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }
        ?>
        <?php
        $message = $_SESSION['toast_message'] ?? null;
        $code = $_SESSION['toast_code'] ?? null;

// Apaga após exibir
        unset($_SESSION['toast_message'], $_SESSION['toast_code']);
        ?>
        <?php if ($message && $code): ?>
            <script>
                $(document).ready(function () {

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    Toast.fire({
                        icon: (<?= json_encode($code) ?> == 1 ? 'success' : 'warning'),
                        title: " <?= htmlspecialchars($message) ?>"
                    });

                });
            </script>
        <?php endif; ?>
    </body>
</html>