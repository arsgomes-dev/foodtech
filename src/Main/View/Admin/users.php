<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Department;
use Microfw\Src\Main\Common\Entity\Admin\Privilege;

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
            $baseHtml->baseMenu("users", "users");
            ?>
            <div class="content-wrapper" style="min-height: auto !important;">


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Usuários", $_SESSION['user_lang']), $directory, $translate->translate("Usuários", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("user_view", $privilege_types)) {
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
                                if (in_array("user_create", $privilege_types)) {
                                    ?>
                                    <button aria-label="Close" type="button" class="btn btn-block btn-success btn-color" title="<?php echo $translate->translate('Adicionar usuário', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".new-modal">
                                        <i class="fa fa-plus"></i> <?php echo $translate->translate('Adicionar usuário', $_SESSION['user_lang']); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="card card-border-radius" style="margin-bottom: 10px;">
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
                                                <select class="form-control form-control-md" style="width: 100%;" name="ord_search" id="ord_search">
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
                                                <input type="text" id="name_search" name="name_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>">
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
                                                <input type="text" id="cpf_search" name="cpf_search" oninput="cpfFormat(this);" class="form-control form-control-md" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>">
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
                                                <input type="text" id="email_search" name="email_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <select class="form-control form-control-md" style="width: 100%;" name="department_search" id="department_search">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $departmentSearch = new Department;
                                                    $departments = new Department;
                                                    $departments = $departmentSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
                                                    $departmentsCount = count($departments);
                                                    if ($departmentsCount > 0) {
                                                        $department = new Department;
                                                        for ($i = 0; $i < $departmentsCount; $i++) {
                                                            $department = $departments[$i];
                                                            ?>
                                                            <option value="<?php echo $department->getId(); ?>"><?php echo $department->getTitle(); ?></option>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </select>
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
                                            </div>
                                            <!-- /.card-body -->
                                        </div> 
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnUsers();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- END Modal -->
                        <?php
                        if (in_array("user_create", $privilege_types)) {
                            ?>
                            <!-- modal new -->
                            <div class="modal fade new-modal" id="new-modal" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?php echo $translate->translate('Novo Usuário', $_SESSION['user_lang']); ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(new_user);">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <form name="new_user" id="new_user" autocomplete="off">

                                                <div class="form-group to_validation to_validation_name">
                                                    <label for="name"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?> *</label>
                                                    <input type="text" class="form-control to_validations" id="name" name="name" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>">
                                                    <div id="to_validation_blank_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="cpf"><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" id="cpf" name="cpf" oninput="cpfFormat(this);" onblur="cpfSearch(this, null);" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_invalid_cpf" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('CPF inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                            <div id="to_validation_blank_cpf" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                            <div id="to_validation_already_registered_cpf" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="birth"><?php echo $translate->translate('Nascimento', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control data to_validations" id="birth" name="birth" data-role="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric"  placeholder="dd/mm/yyyy ">
                                                            <div id="to_validation_blank_birth" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="contact"><?php echo $translate->translate('Contato', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" id="contact" name="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-#### ">
                                                            <div id="to_validation_blank_contact" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="privileges"><?php echo $translate->translate('Privilégios', $_SESSION['user_lang']); ?> *</label>
                                                            <select class="custom-select to_validations" id="privileges" name="privileges">
                                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                                <?php
                                                                $privilegeSearch = new Privilege;
                                                                $privileges = new Privilege;
                                                                $privileges = $privilegeSearch->getQuery();
                                                                $privilegeCount = count($privileges);
                                                                if ($privilegeCount > 0) {
                                                                    $privilege = new Privilege;
                                                                    for ($i = 0; $i < $privilegeCount; $i++) {
                                                                        $privilege = $privileges[$i];
                                                                        ?>
                                                                        <option value="<?php echo $privilege->getId(); ?>"><?php echo $privilege->getDescription(); ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <div id="to_validation_blank_privileges" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="email"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" onblur="emailSearch(this, null);">
                                                            <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                            <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                            <div id="to_validation_already_registered_email" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="department"><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?> *</label>
                                                            <select class="custom-select to_validations" id="department" name="department" onchange="loadOccupation();">
                                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                                <?php
                                                                $departmentSearch = new Department;
                                                                $departments = new Department;
                                                                $departments = $departmentSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
                                                                $departmentsCount = count($departments);
                                                                if ($departmentsCount > 0) {
                                                                    $department = new Department;
                                                                    for ($i = 0; $i < $departmentsCount; $i++) {
                                                                        $department = $departments[$i];
                                                                        ?>
                                                                        <option value="<?php echo $department->getId(); ?>"><?php echo $department->getTitle(); ?></option>
                                                                        <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>

                                                            <div id="to_validation_blank_department" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="occupation"><?php echo $translate->translate('Função', $_SESSION['user_lang']); ?> *</label> 
                                                            <div id="ocuppation_div">                                                           
                                                                <select class="custom-select to_validations" id="occupation" name="occupation">
                                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>                                                               
                                                                </select>
                                                            </div>    
                                                            <div id="to_validation_blank_occupation" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>          
                                                </div>
                                            </form>
                                            <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" onclick="createUser(new_user);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(new_user);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
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
        <script src="/libs/v1/admin/js/general/users/lists/users.min.js"></script>
        <?php
        if (in_array("user_create", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/emailValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/cpfValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/format/cpfFormat.min.js"></script>
            <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
            <script>
               var recoveryTitle = "<?php echo $translate->translate('Desejar recuperar a senha do usuário?', $_SESSION['user_lang']); ?>";
               var recoveryText = "<?php echo $translate->translate('Atenção, a senha utilizada anteriormente não funcionará mais.', $_SESSION['user_lang']); ?>";
               var recoveryButton = "<?php echo $translate->translate('Confirmar', $_SESSION['user_lang']); ?>!";
            </script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <script src="/libs/v1/admin/js/general/users/create/user.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>