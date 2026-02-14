<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
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
        <link rel='stylesheet' href='/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/css/validation.min.css'>
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("customers", "customers");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb("Clientes", $directory, "Clientes");
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("customer_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <button aria-label="Close" type="button" class="btn btn-default btn-i-color btn-filter" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".search-modal">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <?php
                                $response_ord = (!empty($_POST['response']) ? (($_POST['response'] === "1") ? 1 : 0) : 0);
                                $filter_ord_response = ($response_ord === 1) ? "display: inline-block;" : "display: none;";
                                ?>                                
                                <button id="btn-clean-filter" style="<?php echo $filter_ord_response; ?>" onclick="cleanSearch();" type="button" class="btn btn-default btn-i-color-danger btn-filter" title="<?php echo $translate->translate('Limpar Filtro', $_SESSION['user_lang']); ?>">
                                    <i class="fas fa-filter-circle-xmark"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="card card-border-radius" style="margin-bottom: 40px !important;">
                            <div class="card-body">           
                                <div id="list" style="overflow-x: auto;"></div>
                            </div>
                            <div class="card-footer card-footer-transparent" id="pagination"  style="overflow-x: auto;"></div>
                        </div>
                        <!-- Modal -->

                        <style>
                            .search-modal{
                                padding-right: 0 !important;
                            }
                            .search-modal .modal-dialog{
                                margin-bottom: 0;
                                margin-top: 0;
                                margin-right: 0;
                            }
                            .search-modal .modal-dialog .modal-content{
                                height: 100% !important;
                                padding-right: 0 !important;
                            }
                        </style>
                        <div class="modal fade search-modal" id="search-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" style="overflow-y: auto;">                   
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Ordenar por', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">                                           
                                                <select class="form-control form-control-md" style="width: 100%;" name="ord" id="ord">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <optgroup label="<?php echo $translate->translate(' Nome', $_SESSION['user_lang']); ?>">
                                                        <option value='1'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                        <option value='3' selected><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                        <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>       
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="name" name="name" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="cpf" name="cpf" oninput="cpfFormat(this);" class="form-control form-control-md" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="email" name="email" class="form-control form-control-md" placeholder="<?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <div class="icheck-success">
                                                    <input type="radio" id="status1" name="status" value="1"/>
                                                    <label for="status1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></label>
                                                </div>
                                                <div class="icheck-danger" style="margin-top: 15px !important;">
                                                    <input type="radio" id="status2" name="status" value="2"/>
                                                    <label for="status2"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></label>
                                                </div>
                                                <div class="icheck-default" style="margin-top: 15px !important;">
                                                    <input type="radio" id="status3" name="status" value="" checked/>
                                                    <label for="status3"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?></label>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnCustomers();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- END Modal -->
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
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/cpfFormat.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/customer/lists/customer.min.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>