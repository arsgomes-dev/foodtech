<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\Client;

$language = new Language;
$translate = new Translate();
$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-theme="dark">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel='stylesheet' href='/assets/css/layouts/layout-profile.min.css'>
        <link rel='stylesheet' href='/assets/css/colors/layout-profile-colors.min.css'>   
        <?php echo $baseHtml->baseCSSDate(); ?>  
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("profile", "profile");
            ?>
            <div class="content-wrapper">
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">   
                <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                <?php
                $client = new Client;
                $client = $client->getQuery(single: true,
                        customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);
                $imgProfile = "";
                $profile_img = "/" . $client->getGcid() . "/photo/" . $client->getPhoto();
                $profile_model = "/model/client_model.png";
                $imgProfile = ($client->getPhoto() !== null) ? $profile_img : $profile_model;
                ?>


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">

                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Perfil", $_SESSION['client_lang']), $directory, $translate->translate("Perfil", $_SESSION['client_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->


                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <!-- start card -->
                    <div class="card card-border-radius card-custom">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card"></i> &nbsp; <b><?php echo $translate->translate('Informações pessoais', $_SESSION['client_lang']); ?></b></h3>
                        </div>
                        <div class="card-body row">  
                            <div class="col-lg-2 col-md-2 col-sm profile">
                                <div class="img-user-new-profile">
                                    <img class="img-circle elevation-2" src="<?php echo $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client" . $imgProfile; ?>" alt="User Avatar">
                                    <div class="img-update img-update-color" data-toggle="modal" data-target="#modal-photo">
                                        <i class="nav-icon fas fa-pen-to-square"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-12">
                                <form name="edit_profile" id="edit_profile" autocomplete="off">
                                    <div class="form-group to_validation to_validation_name">
                                        <label for="name"><?php echo $translate->translate('Nome', $_SESSION['client_lang']); ?> *</label>
                                        <input type="text" class="form-control to_validations" id="name" name="name" placeholder="<?php echo $translate->translate('Nome', $_SESSION['client_lang']); ?>" value="<?php echo $client->getName(); ?>">
                                        <div id="to_validation_blank_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="cpf"><?php echo $translate->translate('CPF', $_SESSION['client_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="cpf" name="cpf" oninput="cpfFormat(this)" onblur="cpfSearchProfile(this);" placeholder="<?php echo $translate->translate('CPF', $_SESSION['client_lang']); ?>" value="<?php echo $client->getCpf(); ?>">
                                                <div id="to_validation_invalid_cpf" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('CPF inválido', $_SESSION['client_lang']); ?>!</span></div>
                                                <div id="to_validation_blank_cpf" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                                <div id="to_validation_already_registered_cpf" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('Cpf já cadastrado', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                            <div class="form-group">
                                                <label for="birth"><?php echo $translate->translate('Nascimento', $_SESSION['client_lang']); ?> *</label>
                                                <input placeholder="dd/mm/yyyy" data-role="date" type="text" class="data form-control to_validations" id="birth" name="birth" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" value="<?php echo $client->getFormatDateToBrazil($client->getBirth()); ?>">
                                                <div id="to_validation_blank_birth" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                            <div class="form-group">
                                                <label for="contact"><?php echo $translate->translate('Contato', $_SESSION['client_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="contact" name="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-####" value="<?php echo $client->getContact(); ?>">
                                                <div id="to_validation_blank_contact" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                            <div class="form-group">
                                                <label for="email"><?php echo $translate->translate('E-mail', $_SESSION['client_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" onblur="emailSearchProfile(this);" value="<?php echo $client->getEmail(); ?>">
                                                <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['client_lang']); ?>!</span></div>
                                                <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                                <div id="to_validation_already_registered_email" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>
                                        </div>                                                                   
                                    </div>
                                </form>
                                <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['client_lang']); ?> *</b></span>
                            </div>
                        </div>
                        <div class="card-footer card-footer-transparent justify-content-between border-top">
                            <button type="button" class="btn btn-default btn-register float-left" onclick="update(edit_profile);"><?php echo $translate->translate('Salvar', $_SESSION['client_lang']); ?></button>
                        </div>
                    </div>
                    <!-- end card  -->

                    <div class="card card-border-radius card-custom">
                        <div class="card-header">
                            <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-gear"></i> &nbsp; <b><?php echo $translate->translate('Dados da conta', $_SESSION['client_lang']); ?></b></h3>
                        </div>
                        <div class="card-body">           
                            <table class="table table-hover text-nowrap table-borderless">
                                <tbody>
                                    <tr class="table-profile" data-toggle="modal" data-target="#upd-passwd">
                                        <td><?php echo $translate->translate('Alterar senha', $_SESSION['client_lang']); ?></td>
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
                                <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Alterar Foto do Perfil', $_SESSION['client_lang']); ?></h4>
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
                                        <?php echo $translate->translate('Clique aqui', $_SESSION['client_lang']); ?>!
                                    </div>
                                </div>
                                <form role="form" name="form_photo_profile" id="form_photo_profile" enctype="multipart/form-data">
                                    <input type="file" id="profile_photo" name="profile_photo" style="display: none;" accept="image/*" >
                                </form>
                            </div>
                            <div class="modal-footer card-footer-transparent justify-content-between">
                                <button type="button" class="btn btn-default btn-register" onclick="profilePhotoSave();"><?php echo $translate->translate('Salvar', $_SESSION['client_lang']); ?></button>
                                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanFormPhoto();"><?php echo $translate->translate('Cancelar', $_SESSION['client_lang']); ?></button>
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
                                <h4 class="modal-title"><?php echo $translate->translate('Alterar Senha', $_SESSION['client_lang']); ?></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="clear_form(pass_profile);">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <form name="pass_profile" id="pass_profile" autocomplete="off">
                                    <div class="form-group to_validation to_validation_pass_current">
                                        <label for="passCurrent"><?php echo $translate->translate('Senha Atual', $_SESSION['client_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passCurrent" name="passCurrent" placeholder="<?php echo $translate->translate('Senha Atual', $_SESSION['client_lang']); ?>">
                                        <div id="to_validation_blank_passCurrent" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                    </div>
                                    <div class="form-group to_validation to_validation_passNew">
                                        <label for="passNew"><?php echo $translate->translate('Nova Senha', $_SESSION['client_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passNew" name="passNew" onblur="passVerify();" placeholder="<?php echo $translate->translate('Nova Senha', $_SESSION['client_lang']); ?>">
                                        <div id="to_validation_blank_passNew" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                        <div id="to_validation_passNewQuantity" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A senha deve ter pelo menos 8 caracteres. Por favor, digite novamente', $_SESSION['client_lang']); ?>!</span></div>
                                        <div id="to_validation_passNewComplexify" style="display: none;" class="to_blank"></div>
                                        <div id="to_validation_passNewComplexifyAlphaNumber" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A senha deve conter números e letras (maiúscula e minúscula)', $_SESSION['client_lang']); ?>!</span></div>
                                    </div>
                                    <div class="form-group to_validation to_validation_passConfirm">
                                        <label for="passConfirm"><?php echo $translate->translate('Confirmar Senha', $_SESSION['client_lang']); ?></label>
                                        <input type="password" class="form-control to_validations" id="passConfirm" name="passConfirm" onblur="passConfirm();" placeholder="<?php echo $translate->translate('Confirmar Senha', $_SESSION['client_lang']); ?>">
                                        <div id="to_validation_blank_passConfirm" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                        <div id="to_validation_passConfirmNew" style="display: none;" class="to_blank"><span><?php echo $translate->translate('A nova senha e a confirmação da senha não coincidem', $_SESSION['client_lang']); ?>!</span></div>
                                    </div>
                                </form>

                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default btn-register" onclick="passwSave(pass_profile);"><?php echo $translate->translate('Salvar', $_SESSION['client_lang']); ?></button>
                                <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="clear_form(pass_profile);"><?php echo $translate->translate('Cancelar', $_SESSION['client_lang']); ?></button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- end modal pass-->
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php");
                ?>
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/vendor/validation/js/formValidation.min.js"></script>
        <script src="/assets/vendor/validation/js/emailValidation.min.js"></script>
        <script src="/assets/vendor/validation/js/cpfValidation.min.js"></script>
        <script src="/assets/vendor/format/cpfFormat.min.js"></script>
        <script src="/assets/vendor/complexify/jquery.complexify.min.js"></script>
        <script src="/assets/vendor/inputmask/jquery.inputmask.min.js"></script>
        <script src="/assets/vendor/data/js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="/assets/vendor/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script src="/assets/vendor/cripto/formhash.min.js"></script>
        <script src="/assets/vendor/cripto/md5.min.js"></script>
        <?php echo $translate->translateDatePicker($_SESSION['client_lang']); ?>
        <script src="/assets/vendor/inputmask/inputmask.min.js"></script>
        <script src="/assets/vendor/inputmask/locale.min.js"></script>
        <script src="/assets/js/profile/profile.js"></script>
        <!-- end bottom base html js -->
    </body>

</html>