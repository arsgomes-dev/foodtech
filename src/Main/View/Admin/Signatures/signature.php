<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Company;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlansCoupon;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Currency;

$config = new McConfig();
$baseHtml = new BaseHtml();
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
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("signatures");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("privileges_configuration", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Assinaturas', $_SESSION['user_lang'])] = "signatures";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Assinatura', $_SESSION['user_lang']), $directory, $translate->translate('Assinatura', $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->
                    <?php
                    if (in_array("customer_signatures", $privilege_types)) {
                        $companySearch = new Company;
                        $company = new Company;
                        $company = $companySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);

                        $signatureSearch = new Signature;
                        $signature = new Signature;
                        $signatureSearch->setTable_db_primaryKey("gcid");
                        $signature = $signatureSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gets['code']]]);

                        $customer = new Customers();
                        $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);

                        $plan = new AccessPlan;
                        $plan = $plan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);

                        $currency = new Currency;
                        $currencySearch = new Currency;
                        $currency = $currencySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

                        $price = $translate->translateMonetary($signature->getPrice(), $currency->getCurrency(), $currency->getLocale());

                        $coupon = null;
                        if ($signature->getAccess_plan_coupon_id() !== null && $signature->getAccess_plan_coupon_id() !== "" && $signature->getAccess_plan_coupon_id() > 0) {
                            $coupon = new AccessPlansCoupon();
                            $coupon = $coupon->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_coupon_id()]]);
                        }
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <input type="hidden" name="code" id="code" value="<?php echo $signature->getGcid(); ?>">
                        <br>
                        <div class="row" style="margin-bottom: 40px !important;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    <i class="fas fa-globe"></i> <?php echo $company->getName_fantasy(); ?>
                                                    <?php
                                                    $date_create = "";
                                                    if ($signature->getCreated_at() !== null && $signature->getCreated_at() !== "") {
                                                        $date_create = (new DateTime($signature->getCreated_at()))->format("d/m/Y");
                                                        ?>
                                                        <small class="float-right"><?php echo $translate->translate('Data', $_SESSION['user_lang']) . ": " . $date_create; ?></small>
                                                        <?php
                                                    }
                                                    ?>
                                                </h4>
                                                <div class="row invoice-info">
                                                    <!-- /.col -->
                                                    <div class="col-lg-5 col-sm-12 invoice-col">
                                                        <?php echo $translate->translate('Para', $_SESSION['user_lang']); ?>
                                                        <address>
                                                            <strong><?php echo $customer->getName(); ?></strong><br>
                                                            <?php echo $customer->getAndress_avenue() . ", " . $customer->getAndress_number(); ?><br>
                                                            <?php echo $customer->getAndress_neighborhood() . ", " . $customer->getAndress_city() . ", " . $customer->getAndress_state() . ", " . $customer->getAndress_cep(); ?><br>
                                                            <?php echo $translate->translate('Contato', $_SESSION['user_lang']) . ": " . $customer->getContact(); ?><br>
                                                            <?php echo $translate->translate('E-mail', $_SESSION['user_lang']) . ": " . $customer->getEmail(); ?>
                                                        </address>
                                                    </div>
                                                    <div class="col-lg-2 col-sm-12 invoice-col"></div>
                                                    <!-- /.col -->
                                                    <div class="col-lg-5 col-sm-12 invoice-col">
                                                        <?php echo $translate->translate('Fatura', $_SESSION['user_lang']); ?>
                                                        <br>
                                                        <b><?php echo $translate->translate('Ordem de Serviço', $_SESSION['user_lang']); ?>: </b><?php echo $signature->getGcid(); ?><br>
                                                        <b><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?>: </b><?php echo $plan->getTitle(); ?><br>
                                                        <b><?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?>: </b><?php echo $price; ?>
                                                        <?php
                                                        if ($signature->getDiscount() !== null && $signature->getDiscount() !== "") {
                                                            ?>
                                                            <br>
                                                            <b><?php echo $translate->translate('Cupom de desconto', $_SESSION['user_lang']); ?>:</b> 
                                                            <?php echo number_format($signature->getDiscount(), 2, ',', '.') . '% ' . "(" . $coupon->getCoupon() . ")"; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                        <br>
                                                        <b><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?>:</b> 
                                                        <?php
                                                        $status_signature = "";
                                                        if ($signature->getStatus() == 0) {
                                                            $status_signature = $translate->translate("Inativo", $_SESSION['user_lang']);
                                                        } else if ($signature->getStatus() == 1) {
                                                            $status_signature = $translate->translate("Ativo", $_SESSION['user_lang']);
                                                        } else if ($signature->getStatus() == 2) {
                                                            $status_signature = $translate->translate("Cancelado", $_SESSION['user_lang']);
                                                        } else if ($signature->getStatus() == 3) {
                                                            $status_signature = $translate->translate("Bloqueado", $_SESSION['user_lang']);
                                                        }
                                                        echo $status_signature;
                                                        ?>
                                                        <br>
                                                        <b><?php echo $translate->translate('Renovação', $_SESSION['user_lang']); ?>:</b> 
                                                        <?php
                                                        $renovation_signature = "";
                                                        if ($signature->getAuto_renew() == 0) {
                                                            $renovation_signature = $translate->translate("Inativo", $_SESSION['user_lang']);
                                                        } else {
                                                            $renovation_signature = $translate->translate("Ativo", $_SESSION['user_lang']);
                                                        }
                                                        echo $renovation_signature;
                                                        ?>
                                                        <?php
                                                        $date_renovation = "";
                                                        if ($signature->getDate_renovation() !== null && $signature->getDate_renovation() !== "") {
                                                            $date_renovation = (new DateTime($signature->getDate_renovation()))->format("d/m/Y");
                                                            ?>
                                                            <br>
                                                            <b><?php echo $translate->translate('Próxima Renovação', $_SESSION['user_lang']); ?>:</b> 
                                                            <?php echo $date_renovation; ?>
                                                            <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <!-- /.col -->
                                                </div>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">           
                                        <div id="list" style="overflow-x: auto;"></div>
                                    </div>
                                    <div class="card-footer card-footer-transparent" id="pagination"></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade payment-modal" id="payment-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">
                                            <div><?php echo $translate->translate('Dados do Pagamento', $_SESSION['user_lang']); ?></div>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanPayment();">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="payment-modal-div" style="padding: 0 !important;"></div>                                    
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
        <?php
        if (in_array("privileges_configuration", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/signatures/lists/payments.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/signatures/update/payments.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>