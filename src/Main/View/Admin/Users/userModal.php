<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\Privilege;
use Microfw\Src\Main\Common\Entity\Admin\Department;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentAgent;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;

$config = new McConfig();
$baseHtml = new BaseHtml();
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel='stylesheet' href='/libs/v1/admin/css/user.css'>
        <!-- end top base html css -->
    </head>

    <body>

        <!-- start base html breadcrumb -->

        <?php
        $user = new User;
        $user = $user->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_POST['code']]]);

        $imgProfile = "";
        $profile_img = "/" . $user->getGcid() . "/photo/" . $user->getPhoto();
        $profile_model = "/model/user_model.png";
        $imgProfile = ($user->getPhoto() !== null) ? $profile_img : $profile_model;

        $imgWallpaper = "";
        $wallpaper_img = "/" . $user->getGcid() . "/wallpaper/" . $user->getWallpaper();
        $wallpaper_model = "/model/wallpaper.png";
        $imgWallpaper = ($user->getWallpaper() !== null) ? $wallpaper_img : $wallpaper_model;
        ?> 
        <!-- end base html breadcrumb -->

        <section class="content">

            <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">   
            <div class="row">
                <div class="col-lg-12 border-1 user-profile">
                    <img src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $imgWallpaper; ?>" class="img-fluid img-user-wallpaper" alt="Responsive image">
                    <div class="img-user-profile">
                        <img class="img-circle elevation-2" src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $imgProfile; ?>" alt="User Avatar">
                    </div>
                </div>
                <div class="col-lg-12 user-profile" style="margin-top: 75px;">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-home" aria-selected="true"><?php echo $translate->translate('Perfil', $_SESSION['user_lang']); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-tickets-tab" data-toggle="pill" href="#pills-tickets" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo $translate->translate('Tickets', $_SESSION['user_lang']); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-passwd-tab" data-toggle="pill" href="#pills-passwd" role="tab" aria-controls="pills-profile" aria-selected="false"><?php echo $translate->translate('Senha', $_SESSION['user_lang']); ?></a>
                        </li>
                    </ul>
                    <form name="edit_user" id="edit_user" autocomplete="off" style="padding: 30px;">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                <input type="hidden" name="gcid" id="gcid" value="<?php echo $user->getGcid(); ?>">
                                <div class="form-group to_validation to_validation_name">
                                    <label for="nameEdit"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></label>
                                    <input type="text" class="form-control form-control-border to_validations" id="nameEdit" name="name" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>" value="<?php echo $user->getName(); ?>">
                                    <div id="to_validation_blank_nameEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="cpfEdit"><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?></label>
                                            <input type="text" class="form-control form-control-border to_validations" id="cpfEdit" name="cpf" oninput="cpfFormat(this)" onblur="cpfSearchEdit(this, '<?php echo $user->getGcid(); ?>');" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>" value="<?php echo $user->getCpf(); ?>">
                                            <div id="to_validation_invalid_cpfEdit" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('CPF inválido', $_SESSION['user_lang']); ?>!</span></div>
                                            <div id="to_validation_blank_cpfEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            <div id="to_validation_already_registered_cpfEdit" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('Cpf já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <?php
                                            $now = new DateTime($user->getBirth());
                                            $dateBirth = $now->format('d/m/Y');
                                            ?>
                                            <label for="birthEdit"><?php echo $translate->translate('Nascimento', $_SESSION['user_lang']); ?></label>
                                            <input type="text" class="form-control form-control-border to_validations" id="birthEdit" name="birth" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" value="<?php echo $dateBirth; ?>">
                                            <div id="to_validation_blank_birthEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="contactEdit"><?php echo $translate->translate('Contato', $_SESSION['user_lang']); ?></label>
                                            <input type="text" class="form-control form-control-border to_validations" id="contactEdit" name="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-####" value="<?php echo $user->getContact(); ?>">
                                            <div id="to_validation_blank_contactEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="privilegesEdit"><?php echo $translate->translate('Privilégios', $_SESSION['user_lang']); ?></label>
                                            <select class="custom-select form-control-border to_validations" id="privilegesEdit" name="privileges">
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
                                                        $selectedPrivilege = ($user->getPrivilege_id() === $privilege->getId()) ? "selected" : "";
                                                        echo '<option value="' . $privilege->getId() . '" ' . $selectedPrivilege . ' >' . $privilege->getDescription() . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>

                                            <div id="to_validation_blank_privilegesEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="emailEdit"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?></label>
                                            <input type="text" class="form-control form-control-border to_validations" id="emailEdit" name="email"  data-mask="" inputmode="text" onblur="emailSearchEdit(this,'<?php echo $user->getGcid(); ?>');" value="<?php echo $user->getEmail(); ?>">
                                            <div id="to_validation_invalid_emailEdit" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                            <div id="to_validation_blank_emailEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            <div id="to_validation_already_registered_emailEdit" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="statusEdit"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></label>
                                            <select class="custom-select form-control-border to_validations" id="statusEdit" name="status">
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

                                            <div id="to_validation_blank_statusEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="departmentEdit"><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></label>
                                            <select class="custom-select form-control-border to_validations" id="departmentEdit" name="department" onchange="loadOccupation(true);">
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

                                            <div id="to_validation_blank_departmentEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                        <div class="form-group">
                                            <label for="occupationEdit"><?php echo $translate->translate('Ocupação', $_SESSION['user_lang']); ?></label> 
                                            <div id="ocuppationEdit_div">                                                           
                                                <select class="custom-select form-control-border to_validations" id="occupationEdit" name="occupation">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>                                                               
                                                </select>
                                            </div>    
                                            <div id="to_validation_blank_occupationEdit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>          
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-tickets" role="tabpanel" aria-labelledby="pills-tickets-tab">
                                <?php
                                //tickets
                                $status_agent = false;
                                $checked = "";
                                if ($user->getStatus_agent() == 1) {
                                    $checked = "checked";
                                    $display = "";
                                    $status_agent = true;
                                }
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
                                    <input data-toggle="switch" data-on-color="success" type="checkbox" class="form-control" onchange="displayDepartments();"
                                           name="agent_status" id="agent_status" value="1" <?php echo $checked; ?>>          
                                    <div id="validation_agent_status" name="validation_agent_status"></div>
                                </div>
                                <small><?php echo $translate->translate('* Aqui você poderá definir se o atendente estará ou não ativo para os atendimentos dos tickets', $_SESSION['user_lang']); ?></small>
                                <hr>

                                <div style="display: none;" id="show_departments">              


                                    <ul class="nav nav-pills mb-3" id="departments-tab" role="tablist">
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

                                                    <div class="card">
                                                        <div class="card-body">
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
                                                    </div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-passwd" role="tabpanel" aria-labelledby="pills-passwd-tab">
                                <button type="button" class="btn btn-outline-secondary btn-block btn-flat" onclick="recoveryPasswd(<?php echo $user->getId(); ?>)" ><i class="fas fa-key"></i> <?php echo $translate->translate('Recuperar Senha', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </section>

        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
        <script src="/libs/v1/admin/plugins/validation/js/emailValidation.js"></script>
        <script src="/libs/v1/admin/plugins/validation/js/cpfValidation.js"></script>
        <script src="/libs/v1/admin/plugins/format/cpfFormat.js"></script>
        <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
        <script src="/libs/v1/admin/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script>
                                    $('[data-toggle="switch"]').bootstrapSwitch();
                                    $(document).ready(function () {
                                        loadOccupationsEdit(<?php echo $user->getDepartment_id() ?>, <?php echo $user->getDepartment_occupation_id() ?>);
                                        //Datemask dd/mm/yyyy
                                        $('#birthEdit').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
                                        //Contact (##) #####-####
                                        $('#contactEdit').inputmask('(99) 99999-9999', {'placeholder': '(##) #####-####'});
                                        //cpf
                                        cpfFormat(document.getElementById("cpfEdit"));
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
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>