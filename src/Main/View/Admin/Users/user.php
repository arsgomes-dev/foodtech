<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\Department;
use Microfw\Src\Main\Common\Entity\Admin\Privilege;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentAgent;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;


$language = new Language;
$translate = new Translate();
$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$privilege_types = $_SESSION['user_type'];
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-bs-theme="light">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel='stylesheet' href='/libs/v1/admin/plugins/validation/css/validation.min.css'>
        <link rel="stylesheet" href="/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
        <link rel='stylesheet' href='/libs/v1/admin/css/user.min.css'>
        <link rel='stylesheet' href='/libs/v1/admin/css/userColor.min.css'>
        <link rel='stylesheet' href='/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("users", "users");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">   
                <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                <?php
                $user = new User;
                $user->setTable_db_primaryKey('gcid');
                $user = $user->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gets['code']]]);

                $imgUser = "";
                $user_img = "/" . $user->getGcid() . "/photo/" . $user->getPhoto();
                $user_model = "/model/user_model.png";
                $imgUser = ($user->getPhoto() !== null) ? $user_img : $user_model;
                ?>


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">

                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("user_view", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Usuários', $_SESSION['user_lang'])] = "users";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Usuário', $_SESSION['user_lang']), $directory, $translate->translate('Usuário', $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->


                    <?php
                    if (in_array("user_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <!-- start card -->
                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card"></i> &nbsp; <b><?php echo $translate->translate('Informações pessoais', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body row">  
                                <div class="col-lg-2 col-md-2 col-sm user">
                                    <div class="img-user">
                                        <img class="img-circle elevation-2" src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $imgUser; ?>" alt="User Avatar">
                                        <div class="img-update img-update-color" data-toggle="modal" data-target="#modal-photo">
                                            <i class="nav-icon fas fa-pen-to-square"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12">
                                    <form name="edit_user" id="edit_user" autocomplete="off">
                                        <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                        <div class="form-group to_validation to_validation_name">
                                            <label for="name"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" id="name" name="name" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>" value="<?php echo $user->getName(); ?>">
                                            <div id="to_validation_blank_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12 to_validation">
                                                <div class="form-group">
                                                    <label for="cpf"><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?> *</label>
                                                    <input type="text" class="form-control to_validations" id="cpf" name="cpf" oninput="cpfFormat(this)" onblur="cpfSearch(this, '<?php echo $user->getGcid(); ?>');" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>" value="<?php echo $user->getCpf(); ?>">
                                                    <div id="to_validation_invalid_cpf" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('CPF inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_blank_cpf" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_already_registered_cpf" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <?php
                                                    $now = new DateTime($user->getBirth());
                                                    $dateBirth = $now->format('d/m/Y');
                                                    ?>
                                                    <label for="birth"><?php echo $translate->translate('Nascimento', $_SESSION['user_lang']); ?> *</label>
                                                    <input type="text" class="data form-control to_validations" id="birth" name="birth" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" data-role="date" value="<?php echo $dateBirth; ?>">
                                                    <div id="to_validation_blank_birth" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <label for="contact"><?php echo $translate->translate('Contato', $_SESSION['user_lang']); ?> *</label>
                                                    <input type="text" class="form-control to_validations" id="contact" name="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-####" value="<?php echo $user->getContact(); ?>">
                                                    <div id="to_validation_blank_contact" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <label for="email"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?> *</label>
                                                    <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" onblur="emailSearch(this, '<?php echo $user->getGcid(); ?>');" value="<?php echo $user->getEmail(); ?>">
                                                    <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_already_registered_email" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>                                                                   
                                        </div>
                                    </form>
                                </div>
                                <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                            </div>
                            <div class="card-footer card-footer-transparent justify-content-between border-top">
                                <button type="button" class="btn btn-default btn-register float-left" onclick="update(edit_user);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>
                        <!-- end card  -->


                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card-clip"></i> &nbsp; <b><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body">                                  
                                <form name="edit_department" id="edit_department" autocomplete="off">
                                    <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                            <div class="form-group ">
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
                                                            $selectedDepartment = ($user->getDepartment_id() === $department->getId()) ? "selected" : "";
                                                            echo '<option value="' . $department->getId() . '" ' . $selectedDepartment . ' >' . $department->getTitle() . '</option>';
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
                            <div class="card-footer card-footer-transparent justify-content-between border-top">
                                <button type="button" class="btn btn-default btn-register float-left" onclick="updateDepartment(edit_department);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>


                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-gear"></i> &nbsp; <b><?php echo $translate->translate('Dados da conta', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body">           
                                <table class="table table-hover text-nowrap table-borderless">
                                    <tbody>
                                        <tr class="table-user" data-toggle="modal" data-target="#modal-privileges">
                                            <td><?php echo $translate->translate('Privilégios de Acesso', $_SESSION['user_lang']); ?></td>
                                            <td id="tr-privileges">
                                                <?php
                                                $privilegeSearch = new Privilege;
                                                $privileges = new Privilege;
                                                $privileges = $privilegeSearch->getQuery();
                                                $privilegeCount = count($privileges);
                                                if ($privilegeCount > 0) {
                                                    $privilege = new Privilege;
                                                    for ($i = 0; $i < $privilegeCount; $i++) {
                                                        $privilege = $privileges[$i];
                                                        if ($user->getPrivilege_id() == $privilege->getId()) {
                                                            echo $privilege->getDescription();
                                                        }
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <i class="nav-icon-color nav-icon fas fa-edit"></i>    
                                            </td>
                                        </tr>
                                        <tr class="table-user" data-toggle="modal" data-target="#modal-status">
                                            <td><?php echo $translate->translate('Status de Acesso', $_SESSION['user_lang']); ?></td>
                                            <td id="tr-status">
                                                <?php
                                                if ($user->getStatus() === 0) {
                                                    echo $translate->translate('Inativo', $_SESSION['user_lang']);
                                                } else if ($user->getStatus() === 1) {
                                                    echo $translate->translate('Ativo', $_SESSION['user_lang']);
                                                } else if ($user->getStatus() === 2) {
                                                    echo $translate->translate('Bloqueado', $_SESSION['user_lang']);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <i class="nav-icon-color nav-icon fas fa-edit"></i>    
                                            </td>
                                        </tr>
                                        <tr class="table-user" data-toggle="modal" data-target="#modal-agent">
                                            <td><?php echo $translate->translate('Status do Atendimento', $_SESSION['user_lang']); ?></td>
                                            <td>                                             
                                                <?php
                                                if ($user->getStatus_agent() === 1) {
                                                    echo $translate->translate('Ativo', $_SESSION['user_lang']);
                                                } else {
                                                    echo $translate->translate('Inativo', $_SESSION['user_lang']);
                                                }
                                                ?>
                                            </td>
                                            <td><i class="nav-icon-color nav-icon fas fa-edit"></i>  </td>
                                        </tr>
                                        <tr class="table-user" onclick="recoveryPasswd('<?php echo $user->getGcid(); ?>')">
                                            <td><?php echo $translate->translate('Recuperar Senha', $_SESSION['user_lang']); ?></td>
                                            <td>**********</td>
                                            <td><i class="nav-icon-color nav-icon fas fa-key"></i></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- modal photo -->
                        <div class="modal fade" id="modal-photo" data-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Alterar Foto', $_SESSION['user_lang']); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-clean-form-photo">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="div_modal_img" for="user_photo" id="div-upload-user-photo">
                                            <img id="div_modal_user_img">
                                            <div id="div_modal_user_i_div">
                                                <i class="nav-icon fas fa-upload div_modal_img_i" for="user_photo"></i>
                                            </div>
                                            <div class="div_modal_img_text" for="user_photo">
                                                <?php echo $translate->translate('Clique aqui', $_SESSION['user_lang']); ?>!
                                            </div>
                                        </div>
                                        <form role="form" name="form_photo_user" id="form_photo_user" enctype="multipart/form-data">
                                            <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                            <input type="file" id="user_photo" name="user_photo" style="display: none;" accept="image/*" >
                                        </form>
                                    </div>
                                    <div class="modal-footer card-footer-transparent justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" id="btn-save-photo"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" id="btn-clean-form-photo"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- modal photo -->

                        <!-- modal status -->
                        <div class="modal fade" id="modal-status" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Status de Acesso', $_SESSION['user_lang']); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form role="form" name="edit_status" id="edit_status">
                                            <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                            <div class="col-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <select class="custom-select to_validations" id="status" name="status">
                                                        <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                        <?php
                                                        $statusInactive = ($user->getStatus() === 0) ? "selected" : "";
                                                        $statusActive = ($user->getStatus() === 1) ? "selected" : "";
                                                        $statusBlocked = ($user->getStatus() === 2) ? "selected" : "";
                                                        echo '<option value="1" ' . $statusActive . ' >' . $translate->translate('Ativo', $_SESSION['user_lang']) . '</option>';
                                                        echo '<option value="2" ' . $statusBlocked . ' >' . $translate->translate('Bloqueado', $_SESSION['user_lang']) . '</option>';
                                                        echo '<option value="0" ' . $statusInactive . ' >' . $translate->translate('Inativo', $_SESSION['user_lang']) . '</option>';
                                                        ?>
                                                    </select>

                                                    <div id="to_validation_blank_status" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer card-footer-transparent justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" id="btn-update-status"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- modal status -->


                        <!-- modal privileges -->
                        <div class="modal fade" id="modal-privileges" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Privilégios de Acesso', $_SESSION['user_lang']); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form role="form" name="edit_privileges" id="edit_privileges">
                                            <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                            <div class="col-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <select class="custom-select to_validations" id="privileges" name="privileges">
                                                        <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                        <?php
                                                        if ($privilegeCount > 0) {
                                                            $privilege = new Privilege;
                                                            for ($i = 0; $i < $privilegeCount; $i++) {
                                                                $privilege = $privileges[$i];

                                                                $selected = "";
                                                                if ($user->getPrivilege_id() == $privilege->getId()) {
                                                                    $selected = "selected";
                                                                }
                                                                ?>
                                                                <option value="<?php echo $privilege->getId(); ?>" <?php echo $selected; ?>><?php echo $privilege->getDescription(); ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>

                                                    <div id="to_validation_blank_privileges" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer card-footer-transparent justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" id="btn-update-privileges"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- modal privileges -->

                        <!-- modal agent -->
                        <div class="modal fade" id="modal-agent" data-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Configurações do Atendimento', $_SESSION['user_lang']); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form role="form" name="edit_agent" id="edit_agent">
                                            <input type="hidden" name="code" id="code" value="<?php echo $user->getGcid(); ?>">
                                            <?php
                                            //tickets
                                            $status_agent = false;
                                            $agentStatusActive = "";
                                            $agentStatusInactive = "";

                                            $tickets_agent = new TicketDepartmentSubdepartmentAgent;
                                            $tickets_agent->setTicket_agent_id($user->getId());
                                            $tickets_agent = $tickets_agent->getQuery();
                                            $tickets_types = [];
                                            $tickets_agent_count = count($tickets_agent);
                                            for ($b = 0; $b < $tickets_agent_count; $b++) {
                                                $ticket = new TicketDepartmentSubdepartmentAgent;
                                                $ticket = $tickets_agent[$b];
                                                array_push($tickets_types, $ticket->getTicket_department_subdepartment_id());
                                            }
                                            ?> 

                                            <div class="form-group col-lg-12 col-sm-12 col-md-12">
                                                <label for="agent_status"><font style="vertical-align: inherit;"><?php echo $translate->translate('Status do Atendente', $_SESSION['user_lang']); ?> *</font></label>
                                                <br>
                                                <select class="custom-select to_validations" id="agent_status" name="agent_status" onchange="displayDepartments();">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $checked = "";
                                                    if ($user->getStatus_agent() == 1) {
                                                        $agentStatusActive = "selected";
                                                        $display = "";
                                                        $status_agent = true;
                                                    } else {
                                                        $agentStatusInactive = "selected";
                                                    }
                                                    echo '<option value="1" ' . $agentStatusActive . ' >' . $translate->translate('Ativo', $_SESSION['user_lang']) . '</option>';
                                                    echo '<option value="0" ' . $agentStatusInactive . ' >' . $translate->translate('Inativo', $_SESSION['user_lang']) . '</option>';
                                                    ?>
                                                </select>
                                                <div id="to_validation_blank_agent_status" name="to_validation_blank_agent_status"></div>
                                            </div>


                                            <small><?php echo $translate->translate('* Aqui você poderá definir se o atendente estará ou não ativo para os atendimentos dos tickets', $_SESSION['user_lang']); ?></small>
                                            <hr>

                                            <div style="display: none;" id="show_departments">              


                                                <ul class="nav nav-pills custom-nav-pills mb-3" id="departments-tab" role="tablist">
                                                    <?php
                                                    $ticketDepartaments = new TicketDepartment;
                                                    $departmentAll = $ticketDepartaments->getQuery();
                                                    if (count($departmentAll) > 0) {
                                                        for ($i = 0; $i < count($departmentAll); $i++) {
                                                            $department = new TicketDepartment;
                                                            $department = $departmentAll[$i];
                                                            $active = ($i == 0) ? "active" : "";
                                                            ?> 
                                                            <li class="nav-item">
                                                                <a class="nav-link <?php echo $active; ?>" id="pills-<?php echo $department->getId(); ?>-tab" data-toggle="pill" href="#pills-<?php echo $department->getId(); ?>" role="tab" aria-controls="pills-<?php echo $department->getId(); ?>" aria-selected="true"><?php echo $department->getTitle(); ?></a>
                                                            </li>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                                <div class="tab-content" id="pills-tabContent">
                                                    <?php
                                                    if (count($departmentAll) > 0) {
                                                        for ($i = 0; $i < count($departmentAll); $i++) {
                                                            $department = new TicketDepartment;
                                                            $department = $departmentAll[$i];
                                                            $active = ($i == 0) ? "show active" : "";
                                                            ?> 
                                                            <div class="tab-pane fade <?php echo $active; ?>" id="pills-<?php echo $department->getId(); ?>" role="tabpanel" aria-labelledby="pills-<?php echo $department->getId(); ?>-tab">

                                                                <ul class="list-group">
                                                                    <?php
                                                                    $subdepartments = new TicketDepartmentSubdepartment;
                                                                    $subdepartments->setTicket_department_id($department->getId());
                                                                    $subdepartmentAll = $subdepartments->getQuery();
                                                                    if (count($subdepartmentAll) > 0) {
                                                                        for ($a = 0; $a < count($subdepartmentAll); $a++) {
                                                                            $subdepartment = new TicketDepartmentSubdepartment;
                                                                            $subdepartment = $subdepartmentAll[$a];
                                                                            $iChecked = "";
                                                                            if (in_array($subdepartment->getId(), $tickets_types)) {
                                                                                $iChecked = "checked";
                                                                            }
                                                                            ?>

                                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                                <?php echo $subdepartment->getTitle(); ?>
                                                                                <div class="form-check form-switch m-0 icheck-success">
                                                                                    <!--  data-on-text="" data-off-text="" data-handle-width="40" -->
                                                                                    <input data-toggle="switch" data-on-color="success" type="checkbox" id="agent_subdepartment_<?php echo $subdepartment->getId(); ?>" name="agent_subdepartment[]" value="<?php echo $subdepartment->getId(); ?>" <?php echo $iChecked; ?>/>
                                                                                    <label for="agent_subdepartment_<?php echo $subdepartment->getId(); ?>"></label>
                                                                                </div>
                                                                            </li>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>  
                                                                </ul>
                                                            </div>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer card-footer-transparent justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" id="btn-update-agent"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- modal agent -->
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
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
                ?>
            </div>
            <br>
            <!-- footer start -->
            <!-- footer end -->
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <?php
        if (in_array("user_view", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/emailValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/cpfValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/format/cpfFormat.min.js"></script>
            <script src="/libs/v1/admin/plugins/complexify/jquery.complexify.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
            <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="/libs/v1/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
            <script src="/libs/v1/admin/js/plugins/cripto/formhash.min.js"></script>
            <script src="/libs/v1/admin/js/plugins/cripto/md5.min.js"></script>
            <script>
                                                    var recoveryTitle = "<?php echo $translate->translate('Desejar recuperar a senha do usuário?', $_SESSION['user_lang']); ?>";
                                                    var recoveryText = "<?php echo $translate->translate('Atenção: a senha atualmente utilizada deixará de funcionar e não poderá mais ser usada.', $_SESSION['user_lang']); ?>";
                                                    var recoveryButton = "<?php echo $translate->translate('Confirmar', $_SESSION['user_lang']); ?>!";
            </script>
            <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="/libs/v1/admin/js/general/users/update/user.min.js"></script>
            <script>
                                                    $('[data-toggle="switch"]').bootstrapSwitch();
                                                    $(document).ready(function () {
                                                        loadOccupations(<?php echo $user->getDepartment_id() ?>, <?php echo $user->getDepartment_occupation_id() ?>);
                                                    });
            </script>
            <?php
            if ($status_agent === true) {
                ?>
                <script>
                    $(document).ready(function () {
                        displayDepartments();
                    });
                </script>
            <?php }
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>