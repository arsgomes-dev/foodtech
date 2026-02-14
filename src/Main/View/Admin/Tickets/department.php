<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentPriority;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
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

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto; margin-bottom: 20px;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("tickets", "ticket_departaments");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    $directory[$translate->translate('Tickets', $_SESSION['user_lang'])] = "tickets";
                    $directory[$translate->translate('Departamentos', $_SESSION['user_lang'])] = "tickets/departments";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Ticket - Departamento", $_SESSION['user_lang']), $directory, $translate->translate("Departamento", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("ticket_department_view", $privilege_types)) {
                        $department = new TicketDepartment;
                        $department = $department->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $gets["code"]]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <br>
                        <div class="row" style="margin-bottom: 40px !important;">
                            <div class="col-lg-7 col-sm-12">
                                <div class="card card-border-radius">
                                    <div class="card-body">     
                                        <form role="form" name="edit_department" id="edit_department">
                                            <input type="hidden" name="code" id="code" value="<?php echo $department->getId(); ?>">
                                            <div class="form-group to_validation">
                                                <label><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="title"  name="title" placeholder="<?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?>" value="<?php echo $department->getTitle(); ?>">
                                                <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                            <div class="form-group to_validation">
                                                <label for="status"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                <?php
                                                $statusInactive = ($department->getStatus() === 0) ? "selected" : "";
                                                $statusActive = ($department->getStatus() === 1) ? "selected" : "";
                                                ?>
                                                <select class="custom-select to_validations" id="status" name="status">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <option value="1" <?php echo $statusActive; ?>><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                    <option value="0" <?php echo $statusInactive; ?>><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                </select>
                                                <div id="to_validation_blank_status" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top">
                                    <?php
                                                    if (in_array("ticket_department_create", $privilege_types)) {
                                                        ?>
                                        <button type="button" class="btn btn-default btn-register" onclick="updateDepartment(edit_department);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                    <?php
                                                    }
                                                        ?>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/tickets/departments" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title col-lg-9 col-sm-12"> <?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?></h3>
                                        <div class="card-tools col-lg-3 col-sm-12">                
                                            <div class="input-group input-group-sm float-left">
                                                <div class="input-group-append btn-filter-new">
                                                    <button type="button" class="btn btn-default float-left btn-filter-new-btn" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>"  data-toggle="modal" data-target=".search-modal">
                                                        <i class="fas fa-filter"></i>
                                                    </button>
                                                    <?php
                                                    if (in_array("ticket_department_create", $privilege_types)) {
                                                        ?>
                                                        <button type="button" class="btn btn-default btn-flat float-left btn-filter-new-btn" title="<?php echo $translate->translate('Novo', $_SESSION['user_lang']); ?>"  data-toggle="modal" data-target=".modal-new-subdepartment">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">                                           
                                        <div id="list" style="overflow: auto;"></div>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top" id=pagination></div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <style>
                            .search-modal{
                                padding-right: 0 !important;
                            }
                            .search-modal .modal-dialog{
                                height: 100% !important;
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
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label><?php echo $translate->translate('Ordenar por', $_SESSION['user_lang']); ?></label>
                                            <select class="form-control form-control-md" style="width: 100%;" name="ord_search" id="ord_search">
                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                <optgroup label="<?php echo $translate->translate(' Departamento', $_SESSION['user_lang']); ?>">
                                                    <option value='1'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                    <option value='2'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                </optgroup>
                                                <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                    <option value='3' selected><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                    <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                </optgroup>
                                            </select>
                                        </div>                    
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                              <input type="text" id="description_search" name="description_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?>">
                                         </div>
                                            <!-- /.card-body -->
                                        </div>                          
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadSubdepartment();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- Modal -->
                        <?php
                        if (in_array("ticket_department_create", $privilege_types)) {
                            ?>
                            <div class="modal fade modal-new-subdepartment" id="modal-new-subdepartment" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">
                                                <div id="h5-save-title"><?php echo $translate->translate('Cadastar Subdepartamento', $_SESSION['user_lang']); ?></div>
                                                <div id="h5-update-title" style="display: none;"><?php echo $translate->translate('Atualizar Subdepartamento', $_SESSION['user_lang']); ?></div>
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form style="margin: 10px;" role="form" name="form_subdepartment" id="form_subdepartment">
                                                <input type="hidden" name="department" id="department" value="<?php echo $department->getId(); ?>">
                                                <input type="hidden" name="code" id="sub_code">
                                                <div class="row">
                                                    <div class="col-lg-12 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label><?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" id="title_subdepartment"  name="title" placeholder="<?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_title_subdepartment" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="status_priority"><?php echo $translate->translate('Prioridade', $_SESSION['user_lang']); ?> *</label>
                                                            <select class="custom-select to_validations" id="status_priority" name="status_priority">
                                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                                <?php
                                                                $ticketPrioritys = new TicketDepartmentSubdepartmentPriority;
                                                                $ticketPrioritys = $ticketPrioritys->getQuery();
                                                                $ticketPrioritysCount = count($ticketPrioritys);
                                                                if ($ticketPrioritysCount > 0) {
                                                                    $ticketPriority = new TicketDepartmentSubdepartmentPriority;
                                                                    for ($i = 0; $i < $ticketPrioritysCount; $i++) {
                                                                        $ticketPriority = $ticketPrioritys[$i];
                                                                        echo ' <option value="' . $ticketPriority->getId() . '">' . $ticketPriority->getTitle() . ' - ' . $ticketPriority->getDeadline() . 'h</option>';
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <div id="to_validation_blank_status_priority" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-sm-12 to_validation">
                                                        <div class="form-group">
                                                            <label for="status_subdepartment"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                            <select class="custom-select to_validations" id="status_subdepartment" name="status">
                                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                                <option value="1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                                <option value="0"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                            </select>
                                                            <div id="to_validation_blank_status_subdepartment" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>
                                            <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" id="btn-save-title" onclick="createSubdepartment(form_subdepartment);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-register" id="btn-update-title" style="display: none;" onclick="createSubdepartment(form_subdepartment);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanSubdepartment(form_subdepartment);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
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
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/update/ticket_department.js"></script>
          <?php
                                                    if (in_array("ticket_department_view", $privilege_types)) {
                                                        ?>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/lists/ticket_subdepartment.js"></script>
        
                                                    <?php  }
                                                    if (in_array("ticket_department_create", $privilege_types)) {
                                                        ?>
        
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/create/ticket_subdepartment.js"></script>
          <?php
                                                    }
                                                        ?>
        <!-- end bottom base html js -->
    </body>

</html>