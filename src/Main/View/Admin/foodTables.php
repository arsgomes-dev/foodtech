<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
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
        <?php echo $baseHtml->baseCSSICheck(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <?php echo $baseHtml->baseCSSDate(); ?>          
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("foods", "tables"); 
            ?>
            <div class="content-wrapper" style="min-height: auto !important;">


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Tabela de Alimentos", $_SESSION['user_lang']), $directory, $translate->translate("Tabela de Alimentos", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("food_table_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <button aria-label="Close" type="button" class="btn btn-default btn-i-color btn-filter" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".search-modal">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <button id="btn-clean-filter" style="display: none;" onclick="cleanSearch();" type="button" class="btn btn-default btn-i-color-danger btn-filter" title="<?php echo $translate->translate('Limpar Filtro', $_SESSION['user_lang']); ?>">
                                    <i class="fas fa-filter-circle-xmark"></i>
                                </button>
                            </div>
                            <div class="col-lg-4 col-sm-12 d-flex flex-column justify-content-center">
                                <?php
                                if (in_array("food_table_create", $privilege_types)) {
                                    ?>
                                    <button aria-label="Close" type="button" class="btn btn-block btn-success btn-color" title="<?php echo $translate->translate('Adicionar Tabela', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".table-create">
                                        <i class="fa fa-plus"></i> <?php echo $translate->translate('Adicionar Tabela', $_SESSION['user_lang']); ?>
                                    </button>
                                <?php } ?>
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
                                                    <opttable label="<?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?>">
                                                        <option value='1' selected><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </opttable>
                                                    <opttable label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                        <option value='3'><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                        <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                    </opttable>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="table_name_search" name="table_name_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>           
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnTables();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- END Modal -->
                        <?php
                        if (in_array("food_table_create", $privilege_types)) {
                            ?>
                            <div class="modal fade new-modal table-create" id="table-create" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(form_table);">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form style="margin: 10px;" role="form" name="form_create_table" id="form_create_table">
                                                <div class="row">
                                                    <div class="col-lg-12 col-sm-12">
                                                        <div class="form-table to_validation to_validation_description">
                                                            <label for="description"><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control to_validations" id="description" name="description" placeholder="<?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_description" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">       
                                            <button type="button" class="btn btn-default btn-register" onclick="createTable(form_create_table);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(form_create_table);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        if (in_array("food_table_edit", $privilege_types)) {
                            ?>
                            <div class="modal fade update-modal table-update" id="table-update" data-backdrop="static" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(form_update_table);">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form style="margin: 10px;" role="form" name="form_update_table" id="form_update_table">
                                                <input type="hidden" id="code" name="code">
                                                <div class="row">
                                                    <div class="col-lg-12 col-sm-12">
                                                        <div class="form-table to_validation to_validation_description">
                                                            <label for="description"><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control to_validations" id="description_edit" name="description" placeholder="<?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_description_edit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" onclick="createTable(form_update_table);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(form_update_table);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
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


            </div>

            <!-- footer start -->
            <?php
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
            ?>
            <!-- footer end -->
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  

        <?php
        if (in_array("food_table_create", $privilege_types) || in_array("food_table_edit", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <?php
        }
        if (in_array("food_table_view", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/js/general/foodTables/lists/foodTables.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>