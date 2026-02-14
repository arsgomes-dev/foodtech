<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlansCoupon;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_access_plans_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel="stylesheet" href="/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/css/accessPlan.css'>
        <!-- end top base html css -->
    </head>
    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">
        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("access_plans", "coupons");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("access_plans_coupons_edit", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Planos de Acesso', $_SESSION['user_lang'])] = "accessPlans";
                    $directory[$translate->translate('Cupons de desconto', $_SESSION['user_lang'])] = "coupons";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Cupom de desconto", $_SESSION['user_lang']), $directory, $translate->translate("Cupom de desconto", $_SESSION['user_lang']));
                    ?>  
                    <?php
                    if (in_array("access_plans_view", $privilege_types)) {
                        $couponSearch = new AccessPlansCoupon;
                        $coupon = new AccessPlansCoupon;
                        $coupon = $couponSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $gets['code']]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <br>
                        <div class="row" style="margin-bottom: 40px !important;">
                            <div class="col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <form style="margin: 10px;" role="form" name="update_coupon" id="update_coupon">
                                            <input type="hidden" name="code" id="code" value="<?php echo $coupon->getId(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="title"><?php echo $translate->translate('Cupom', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" name="title" id="title" placeholder="<?php echo $translate->translate('Cupom', $_SESSION['user_lang']); ?>" value="<?php echo $coupon->getCoupon(); ?>">
                                                        <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="percentage"><?php echo $translate->translate('Desconto (%)', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" data-number class="form-control to_validations" id="percentage" name="percentage" placeholder="<?php echo $translate->translate('Desconto (%)', $_SESSION['user_lang']); ?>" value="<?php echo $coupon->getDiscount(); ?>">
                                                        <div id="to_validation_blank_percentage" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="quantity"><?php echo $translate->translate('Quantidade de Uso', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" data-number class="form-control to_validations" id="quantity" name="quantity" placeholder="<?php echo $translate->translate('Quantidade de Uso', $_SESSION['user_lang']); ?>" value="<?php echo $coupon->getAmount_use(); ?>">
                                                        <div id="to_validation_blank_quantity" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="start">
                                                            <?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <?php
                                                        $start_date = $translate->translateDate($coupon->getDate_start(), $_SESSION['user_lang']);
                                                        ?>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="start" name="start" placeholder="<?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?>" value="<?php echo $start_date; ?>">
                                                        <div id="to_validation_blank_start" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="end">
                                                            <?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <?php
                                                        $end_date = $translate->translateDate($coupon->getDate_end(), $_SESSION['user_lang']);
                                                        ?>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="end" name="end" placeholder="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>" value="<?php echo $end_date; ?>">
                                                        <div id="to_validation_blank_end" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="status"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                        <?php
                                                        $statusInactive = ($coupon->getStatus() === 0) ? "selected" : "";
                                                        $statusActive = ($coupon->getStatus() === 1) ? "selected" : "";
                                                        ?>
                                                        <select <?php echo $edit; ?> class="custom-select to_validations" id="sts" name="sts">
                                                            <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                            <option value="1" <?php echo $statusActive; ?>><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                            <option value="0" <?php echo $statusInactive; ?>><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                        </select>
                                                        <div id="to_validation_blank_sts" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>

                                    <div class="card-footer card-footer-transparent justify-content-between border-top">
                                        <?php
                                        if (in_array("access_plans_coupons_edit", $privilege_types)) {
                                            ?>
                                        <button type="button" class="btn btn-default btn-register" name="save" id="div-update-coupon"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/coupons" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <?php
            if (in_array("access_plans_coupons_edit", $privilege_types)) {
                ?>
                <script>
                                            var language_subscription_validation_input_insert_description = '<?php echo $translate->translate('Essa descrição já consta na lista!', $_SESSION['user_lang']); ?>';
                                            var language_delete_option = "<?php echo $translate->translate('Remover', $_SESSION['user_lang']); ?>";
                </script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/jquery_maskmoney/jquery.maskMoney.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
                <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/currency.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/onlyNumbers.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/accessPlans/update/coupon.js"></script>
                <?php
            }
        ?>
        <!-- end bottom base html js -->
    </body>
</html>