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
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;

$language = new Language;
$translate = new Translate();
$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-bs-theme="light">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel='stylesheet' href='/libs/v1/admin/css/profile.min.css'>
        <link rel='stylesheet' href='/libs/v1/admin/css/profileColor.min.css'>   
        <?php echo $baseHtml->baseCSSDate(); ?>  
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("profile", "profile");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">   
                <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                <?php
                $user = new User;
                $user = $user->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['user_id']]]);

                $imgProfile = "";
                $profile_img = "/" . $user->getGcid() . "/photo/" . $user->getPhoto();
                $profile_model = "/model/user_model.png";
                $imgProfile = ($user->getPhoto() !== null) ? $profile_img : $profile_model;

                $imgWallpaper = "";
                $wallpaper_img = "/" . $user->getGcid() . "/wallpaper/" . $user->getWallpaper();
                $wallpaper_model = "/model/wallpaper.png";
                $imgWallpaper = ($user->getWallpaper() !== null) ? $wallpaper_img : $wallpaper_model;
                ?>


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">

                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Perfil", $_SESSION['user_lang']), $directory, $translate->translate("Perfil", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->


                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                    <!-- start card -->
                    <div class="card card-border-radius">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card"></i> &nbsp; <b><?php echo $translate->translate('Informações pessoais', $_SESSION['user_lang']); ?></b></h3>
                        </div>
                        <div class="card-body row">  
                            <div class="col-lg-2 col-md-2 col-sm profile">
                                <div class="img-user-new-profile">
                                    <img class="img-circle elevation-2" src="<?php echo $config->getDomainAdmin() . $config->getBaseFileAdmin() . "/user" . $imgProfile; ?>" alt="User Avatar">
                                    <div class="img-update img-update-color" data-toggle="modal" data-target="#modal-photo">
                                        <i class="nav-icon fas fa-pen-to-square"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <form name="edit_profile" id="edit_profile" autocomplete="off">
                                    <div class="form-group to_validation to_validation_name">
                                        <label for="name"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?> *</label>
                                        <input type="text" class="form-control to_validations" id="name" name="name" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>" value="<?php echo $user->getName(); ?>">
                                        <div id="to_validation_blank_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="cpf"><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="cpf" name="cpf" oninput="cpfFormat(this)" onblur="cpfSearchProfile(this);" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>" value="<?php echo $user->getCpf(); ?>">
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
                                                <input placeholder="dd/mm/yyyy" data-role="date" type="text" class="data form-control to_validations" id="birth" name="birth" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" value="<?php echo $dateBirth; ?>">
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
                                                <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" onblur="emailSearchProfile(this);" value="<?php echo $user->getEmail(); ?>">
                                                <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                <div id="to_validation_already_registered_email" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>                                                                   
                                    </div>
                                </form>
                                <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                            </div>
                        </div>
                        <div class="card-footer card-footer-transparent justify-content-between border-top">
                            <button type="button" class="btn btn-default btn-register float-left" onclick="update(edit_profile);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                        </div>
                    </div>
                    <!-- end card  -->


                    <div class="card card-border-radius">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card-clip"></i> &nbsp; <b><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></b></h3>
                        </div>
                        <div class="card-body row">  
                            <div class="col-12">

                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">                                                        
                                        <div class="form-group">
                                            <label for="department"><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></label>
                                            <?php
                                            $departmentSearch = new Department;
                                            $department = new Department;
                                            $department = $departmentSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $user->getDepartment_id()]]);
                                            ?>  
                                            <input type="text" class="form-control disabled" disabled placeholder="<?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?>" value="<?php echo $department->getTitle(); ?>">

                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 ">                                                        
                                        <div class="form-group">
                                            <label for="occupation"><?php echo $translate->translate('Função', $_SESSION['user_lang']); ?></label> 
                                            <?php
                                            $occupationSearch = new DepartmentOccupation;
                                            $occupation = new DepartmentOccupation;
                                            $occupation = $occupationSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $user->getDepartment_occupation_id()]]);
                                            ?>  
                                            <input id="occupation" type="text" class="form-control disabled" disabled placeholder="<?php echo $translate->translate('Ocupação', $_SESSION['user_lang']); ?>" value="<?php echo $occupation->getTitle(); ?>">

                                        </div>
                                    </div>          
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="card card-border-radius">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-gear"></i> &nbsp; <b><?php echo $translate->translate('Dados da conta', $_SESSION['user_lang']); ?></b></h3>
                        </div>
                        <div class="card-body">           
                            <table class="table table-hover text-nowrap table-borderless">
                                <tbody>
                                    <tr class="table-profile" data-toggle="modal" data-target="#upd-passwd">
                                        <td><?php echo $translate->translate('Alterar senha', $_SESSION['user_lang']); ?></td>
                                        <td>**********</td>
                                        <td><i class="nav-icon-color nav-icon fas fa-arrow-right-arrow-left"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>

                <!-- modal photo -->
                <div class="modal fade" id="modal-photo" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Alterar Foto do Perfil', $_SESSION['user_lang']); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanFormPhoto();">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="div_modal_img" for="profile_photo" onclick="uploadPhoto();">
                                    <img id="div_modal_profile_img">
                                    <div id="div_modal_profile_i_div">
                                        <i class="nav-icon fas fa-upload div_modal_img_i" for="profile_photo"></i>
                                    </div>
                                    <div class="div_modal_img_text" for="profile_photo">
                                        <?php echo $translate->translate('Clique aqui', $_SESSION['user_lang']); ?>!
                                    </div>
                                </div>
                                <form role="form" name="form_photo_profile" id="form_photo_profile" enctype="multipart/form-data">
                                    <input type="file" id="profile_photo" name="profile_photo" style="display: none;" accept="image/*" >
                                </form>
                            </div>
                            <div class="modal-footer card-footer-transparent justify-content-between">
                                <button type="button" class="btn btn-default btn-register" onclick="profilePhotoSave();"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanFormPhoto();"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>
                    </div>
                </div>     
                <!-- modal photo -->
                <!-- modal pass -->
                <div class="modal fade upd-passwd" id="upd-passwd" style="display: none;" data-backdrop="static">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><?php echo $translate->translate('Alterar Senha', $_SESSION['user_lang']); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clear_form(pass_profile);">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form name="pass_profile" id="pass_profile" autocomplete="off">
                                    <div class="form-group to_validation to_validation_pass_current">
                                        <label for="passCurrent"><?php echo $translate->translate('Senha Atual', $_SESSION['user_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passCurrent" name="passCurrent" placeholder="<?php echo $translate->translate('Senha Atual', $_SESSION['user_lang']); ?>">
                                        <div id="to_validation_blank_passCurrent" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                    </div>
                                    <div class="form-group to_validation to_validation_passNew">
                                        <label for="passNew"><?php echo $translate->translate('Nova Senha', $_SESSION['user_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passNew" name="passNew" onblur="passVerify();" placeholder="<?php echo $translate->translate('Nova Senha', $_SESSION['user_lang']); ?>">
                                        <div id="to_validation_blank_passNew" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        <div id="to_validation_passNewQuantity" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A senha deve ter pelo menos 8 caracteres. Por favor, digite novamente', $_SESSION['user_lang']); ?>!</span></div>
                                        <div id="to_validation_passNewComplexify" style="display: none;" class="to_blank"></div>
                                        <div id="to_validation_passNewComplexifyAlphaNumber" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A senha deve conter números e letras (maiúscula e minúscula)', $_SESSION['user_lang']); ?>!</span></div>
                                    </div>
                                    <div class="form-group to_validation to_validation_passConfirm">
                                        <label for="passConfirm"><?php echo $translate->translate('Confirmar Senha', $_SESSION['user_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passConfirm" name="passConfirm" onblur="passConfirm();" placeholder="<?php echo $translate->translate('Confirmar Senha', $_SESSION['user_lang']); ?>">
                                        <div id="to_validation_blank_passConfirm" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        <div id="to_validation_passConfirmNew" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A nova senha e a confirmação da senha não coincidem', $_SESSION['user_lang']); ?>!</span></div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default btn-register" onclick="passwSave(pass_profile);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="clear_form(pass_profile);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- end modal pass-->
            <!-- footer start -->
            <?php
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
            ?>
            <!-- footer end -->
            </div>
            <br>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
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
        <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
        <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
        <script src="/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
        <script src="/libs/v1/admin/js/general/profile/profile.min.js"></script>
        <!-- end bottom base html js -->
    </body>

</html>