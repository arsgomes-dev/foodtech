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
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/css/accessPlan.css'>
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("access_plans", "access_plans");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Planos de Acesso", $_SESSION['user_lang']), $directory, $translate->translate("Planos de Acesso", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->
                    <?php
                    if (in_array("access_plans_view", $privilege_types)) {
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
                                if (in_array("access_plans_create", $privilege_types)) {
                                    ?>
                                    <button aria-label="Close" type="button" class="btn btn-block btn-success btn-color" title="<?php echo $translate->translate('Adicionar Plano', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".new-modal">
                                        <i class="fa fa-plus"></i> <?php echo $translate->translate('Adicionar Plano', $_SESSION['user_lang']); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="card card-border-radius" style="margin-bottom: 40px !important;">
                            <div class="card-body">           
                                <div id="list" style="overflow-x: auto;">
                                </div>
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
                                    <div class="modal-body">                                                  
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
                                                    <optgroup label="<?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?>">
                                                        <option value='1'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                        <option value='3' selected><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                        <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>">
                                                        <option value='5'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='6'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>                                 
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="description_search" name="description_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?>">
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
                                        <button type="button" class="btn btn-primary" onclick="loadBtnPlans();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- END Modal -->
                        <?php
                        if (in_array("access_plans_create", $privilege_types)) {
                            ?>
                            <!-- modal new -->
                            <div class="modal fade new-modal" id="new-modal" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?php echo $translate->translate('Novo Plano de Acesso', $_SESSION['user_lang']); ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-clean-form-plan">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form name="new_plan" id="new_plan" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="title"><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" name="title" id="title" placeholder="<?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?>" >
                                                            <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="description">
                                                                <?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?> *
                                                            </label>
                                                            <div id="div_description_elements" class="col-lg-12 col-sm-12 description_elements" onclick="mouseClickDescription();">
                                                                <input type="hidden" class = "to_validations" id="description_element_count" name="description_element_count">
                                                                <div id="description_elements" class="row col-lg-12">
                                                                </div>          
                                                                <input type="text" class="form-control description_element_input" id="description_element" name="description_element">
                                                            </div>
                                                            <span style="font-size: 11px;"><?php echo $translate->translate('Tecle ENTER após cada tag', $_SESSION['user_lang']); ?></span>
                                                            <div id="to_validation_blank_description_element_count" style="display: none;" class="to_blank error invalid-feedback"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>   
                                                    </div>
                                                    <div class="col-lg-12 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="observ">
                                                            <?php echo $translate->translate('Observações', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <textarea class="form-control to_validations" name="observ" id="observ" placeholder="<?php echo $translate->translate('observações', $_SESSION['user_lang']); ?>"></textarea>
                                                        <div id="to_validation_blank_observ" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="ribbon"><?php echo $translate->translate('Fita de Promoção', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" class="form-control" id="ribbon" name="ribbon" placeholder="<?php echo $translate->translate('Ex.: Promoção', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_ribbon" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nFoods">
                                                            <?php echo $translate->translate('Número de Alimentos/Refeição', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nFoods" name="nFoods" placeholder="<?php echo $translate->translate('Número de Alimentos/Refeição', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off">
                                                        <div id="to_validation_blank_nFoods" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nMeals">
                                                            <?php echo $translate->translate('Número de Refeições/Dia', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nMeals" name="nMeals" placeholder="<?php echo $translate->translate('Número de Refeições/Dia', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off">
                                                        <div id="to_validation_blank_nMeals" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nReports">
                                                            <?php echo $translate->translate('Exportar Cardápio', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nReports" name="nReports" placeholder="<?php echo $translate->translate('Exportar Cardápio', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off">
                                                        <div id="to_validation_blank_nReports" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nExport">
                                                            <?php echo $translate->translate('Exportar Dados', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nExport" name="nExport" placeholder="<?php echo $translate->translate('Exportar Dados', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off">
                                                        <div id="to_validation_blank_nExport" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="price">
                                                                <?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?> *
                                                            </label>
                                                            <input type="text" class="form-control to_validations" id="price" name="price" data-currency="<?php echo $_SESSION['user_currency']; ?>" data-locale="<?php echo str_replace("_", "-", $_SESSION['user_currency_locale']); ?>" placeholder="<?php echo $_SESSION['user_currency_placeholder']; ?>" inputmode="numeric" autocomplete="off">
                                                            <div id="to_validation_blank_price" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="durationn"><?php echo $translate->translate('Duração (dias)', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" data-number class="form-control to_validations" id="validat" name="validat" placeholder="<?php echo $translate->translate('Duração (dias)', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_validat" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="start">
                                                                <?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?> *
                                                            </label>
                                                            <input type="text" data-role="date" class="data form-control to_validations" id="start" name="start" placeholder="<?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_start" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="end">
                                                                <?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?> *
                                                            </label>
                                                            <input type="text" data-role="date" class="data form-control to_validations" id="end" name="end" placeholder="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_end" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="status"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                            <select class="custom-select to_validations" id="sts" name="sts">
                                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                                <option value="1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                                <option value="0"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                            </select>
                                                            <div id="to_validation_blank_sts" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>
                                            <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" id="div-create-plan"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" id="div-clean-plan"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- end modal -->
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
        if (in_array("access_plans_view", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/accessPlans/lists/accessPlans.js"></script>
            <?php
        }
        if (in_array("access_plans_create", $privilege_types)) {
            ?>
            <script>
                                                                var language_subscription_validation_input_insert_description = '<?php echo $translate->translate('Essa descrição já consta na lista!', $_SESSION['user_lang']); ?>';
                                                                var language_delete_option = "<?php echo $translate->translate('Remover', $_SESSION['user_lang']); ?>";
            </script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/currency.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/onlyNumbers.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/accessPlans/create/accessPlan.js"></script>
        <?php } ?>
        <!-- end bottom base html js -->
    </body>

</html>