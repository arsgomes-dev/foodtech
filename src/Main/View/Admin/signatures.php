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
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <!-- end top base html css -->
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/css/validation.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <link rel="stylesheet" href="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
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
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Assinaturas", $_SESSION['user_lang']), $directory, $translate->translate("Assinaturas", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("customer_signatures", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <button aria-label="Close" type="button" class="btn btn-default btn-i-color btn-filter" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".search-modal">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <?php
                                $response_ord = 0;
                                if (!empty($_POST['response'])) {
                                    $response_ord = ($_POST['response'] === "1") ? 1 : 0;
                                    $_POST = [];
                                } else if (!empty($_POST['messageReading'])) {
                                    $response_ord = ($_POST['messageReading'] === "1") ? 2 : 0;
                                    $_POST = [];
                                } else {
                                    $response_ord = 0;
                                }
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
                            <div class="card-footer card-footer-transparent" id="pagination"></div>
                        </div>
                        <!-- Modal -->
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
                                        <form id="searchFilter">          
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
                                                    <select class="form-control form-control-md" style="width: 100%;" name="ord_search" id="ord_search">
                                                        <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                        <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                            <option value='1' selected><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                            <option value='2'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                        </optgroup>
                                                        <optgroup label="<?php echo $translate->translate('Nome do Cliente', $_SESSION['user_lang']); ?>">
                                                            <option value='3'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                            <option value='4'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                        </optgroup>
                                                        <optgroup label="<?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?>">
                                                            <option value='5'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                            <option value='6'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                        </optgroup>
                                                        <optgroup label="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>">
                                                            <option value='7'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                            <option value='8'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                        </optgroup>
                                                    </select>
                                                </div>
                                                <!-- /.card-body -->
                                            </div>    
                                            <div class="card card-outline">
                                                <div class="card-header">
                                                    <h3 class="card-title"><b><?php echo $translate->translate('Cliente', $_SESSION['user_lang']); ?></b></h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <!-- /.card-tools -->
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body" style="display: block;">
                                                    <input type="text" id="description_search" name="description_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Cliente', $_SESSION['user_lang']); ?>">
                                                </div>
                                                <!-- /.card-body -->
                                            </div> 
                                            <div class="card card-outline">
                                                <div class="card-header">
                                                    <h3 class="card-title"><b><?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?></b></h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <!-- /.card-tools -->
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body" style="display: block;">
                                                    <div class="row">  
                                                        <div class="col-lg-6 col-sm-12">
                                                            <input type="text" class="data form-control" id="date_start_search" name="date_start_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('De', $_SESSION['user_lang']); ?>" data-role="date">
                                                        </div>
                                                        <div class="col-lg-6 col-sm-12">
                                                            <input type="text" class="data form-control" id="date_end_search" name="date_end_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('Até', $_SESSION['user_lang']); ?>" data-role="date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /.card-body -->
                                            </div> 
                                            <div class="card card-outline">
                                                <div class="card-header">
                                                    <h3 class="card-title"><b><?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?></b></h3>
                                                    <div class="card-tools">
                                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <!-- /.card-tools -->
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body" style="display: block;">
                                                    <div class="row">  
                                                        <div class="col-lg-6 col-sm-12">
                                                            <input type="text" class="data form-control" id="date_closure_start_search" name="date_closure_start_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('De', $_SESSION['user_lang']); ?>" data-role="date">
                                                        </div>
                                                        <div class="col-lg-6 col-sm-12">
                                                            <input type="text" class="data form-control" id="date_closure_end_search" name="date_closure_end_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('Até', $_SESSION['user_lang']); ?>" data-role="date">
                                                        </div>
                                                    </div>
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
                                                    <div class="form-group">
                                                        <div class="icheck-info" style="margin-top: 5px !important;">
                                                            <input type="radio" id="status1" name="status" value="4"/>
                                                            <label for="status1"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></label>
                                                        </div>
                                                        <div class="icheck-success" style="margin-top: 15px !important;">
                                                            <input type="radio" id="status2" name="status" value="1"/>
                                                            <label for="status2"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></label>
                                                        </div>
                                                        <div class="icheck-danger" style="margin-top: 15px !important;">
                                                            <input type="radio" id="status3" name="status" value="2"/>
                                                            <label for="status3"><?php echo $translate->translate('Cancelado', $_SESSION['user_lang']); ?></label>
                                                        </div>
                                                        <div class="icheck-warning" style="margin-top: 15px !important;">
                                                            <input type="radio" id="status4" name="status" value="3"/>
                                                            <label for="status4"><?php echo $translate->translate('Bloqueado', $_SESSION['user_lang']); ?></label>
                                                        </div>
                                                        <div class="icheck-default" style="margin-top: 15px !important;">
                                                            <input type="radio" id="status5" name="status" value="" checked/>
                                                            <label for="status5"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?></label>
                                                        </div>
                                                    </div> 
                                                </div>
                                                <!-- /.card-body -->
                                            </div>         
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnSignatures();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
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
        <?php
        if (in_array("customer_signatures", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/currency.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/onlyNumbers.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/signatures/lists/signatures.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>