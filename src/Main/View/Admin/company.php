<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Company;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_departments_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!doctype html>
<html lang="pt-br" style="height: auto;">
    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <?php echo $baseHtml->baseCSSDate(); ?>          
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>
    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">
        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("configuration", "company");
            ?>
            <div class="content-wrapper" style="min-height: auto !important;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Dados da Empresa', $_SESSION['user_lang']), $directory, $translate->translate('Dados da Empresa', $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->
                    <?php
                    if (in_array("configuration_company", $privilege_types)) {
                        $companySearch = new Company;
                        $company = new Company;
                        $company = $companySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);

                        $img_company = "";
                        $imgCompany = "/" . $company->getLogo();
                        $imgCompany_model = "/model/user_model.png";
                        $img_company = ($company->getLogo() !== null) ? $imgCompany : $imgCompany_model
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <br>

                        <form style="margin: 10px;" role="form" name="update_company" id="update_company">    
                            <input type="hidden" name="code" id="code" value="<?php echo $company->getId(); ?>">
                            <!-- start card -->
                            <div class="card card-border-radius">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card"></i> &nbsp; <b><?php echo $translate->translate('Dados da Empresa', $_SESSION['user_lang']); ?></b></h3>
                                </div>
                                <div class="card-body row">  
                                    <div class="col-lg-2 col-md-2 col-sm thetec">
                                        <div class="img-logo">
                                            <img class="img-circle elevation-2" src="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . "/logo" . $img_company; ?>" alt="User Avatar">
                                            <div class="img-update img-update-color" data-toggle="modal" data-target="#modal-photo">
                                                <i class="nav-icon fas fa-pen-to-square"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-12 row">

                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="cnpj"><?php echo $translate->translate('CNPJ', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" name="cnpj" id="cnpj" oninput="cnpjFormat(this);" inputmode="numeric" placeholder="<?php echo $translate->translate('CNPJ', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getCnpj() !== "" && $company->getCnpj() !== null) ? $company->getCnpj() : ""; ?>">
                                                <div id="to_validation_blank_cnpj" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="company_name"><?php echo $translate->translate('Razão Social', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" name="company_name" id="company_name" placeholder="<?php echo $translate->translate('Razão Social', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getName_company() !== "" && $company->getName_company() !== null) ? $company->getName_company() : ""; ?>">
                                                <div id="to_validation_blank_company_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="fantasy_name"><?php echo $translate->translate('Nome Fantasia', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" name="fantasy_name" id="fantasy_name" placeholder="<?php echo $translate->translate('Nome Fantasia', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getName_fantasy() !== "" && $company->getName_fantasy() !== null) ? $company->getName_fantasy() : ""; ?>">
                                                <div id="to_validation_blank_fantasy_name" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="municipal"><?php echo $translate->translate('Inscrição Municipal', $_SESSION['user_lang']); ?> *</label>
                                                <input inputmode="numeric" data-number type="text" class="form-control to_validations" name="municipal" id="municipal" placeholder="<?php echo $translate->translate('Inscrição Municipal', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getMunicipal_registration() !== "" && $company->getMunicipal_registration() !== null) ? $company->getMunicipal_registration() : ""; ?>">
                                                <div id="to_validation_blank_municipal" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="start">
                                                    <?php echo $translate->translate('Data de Abertura', $_SESSION['user_lang']); ?> *
                                                </label>
                                                <?php
                                                $start_date = ($company->getOpening() !== "" && $company->getOpening() !== null) ? $translate->translateDate($company->getOpening(), $_SESSION['user_lang']) : "";
                                                ?>
                                                <input type="text" data-role="date" class="data form-control to_validations" id="start" name="start" placeholder="<?php echo $translate->translate('Data de Abertura', $_SESSION['user_lang']); ?>"  value="<?php echo $start_date; ?>">
                                                <div id="to_validation_blank_start" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>


                                        <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                            <div class="form-group">
                                                <label for="contact"><?php echo $translate->translate('Contato', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="contact" name="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-####" value="<?php echo $company->getContact(); ?>">
                                                <div id="to_validation_blank_contact" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                            <div class="form-group">
                                                <label for="email"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?> *</label>
                                                <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" value="<?php echo $company->getEmail(); ?>">
                                                <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>   
                                    </div>
                                    <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                </div>
                            </div>
                            <!-- end card  -->

                            <!-- start card -->
                            <div class="card card-border-radius">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-address-card"></i> &nbsp; <b><?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?></b></h3>
                                </div>
                                <div class="card-body row">  
                                    <div class="col-lg-4 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="cep"><?php echo $translate->translate('CEP', $_SESSION['user_lang']); ?> *</label>
                                            <input inputmode="numeric" onkeyup="loadCep(this);" type="text" class="form-control to_validations" name="cep" id="cep" placeholder="<?php echo $translate->translate('CEP', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_cep() !== "" && $company->getAndress_cep() !== null) ? $company->getAndress_cep() : ""; ?>">
                                            <div id="to_validation_blank_cep" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            <div id="to_validation_invalid_cep" style="display: none;" class="to_blank"><span><?php echo $translate->translate('CEP inválido', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 col-sm-12"></div>
                                    <div class="col-lg-9 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="street"><?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="avenue" id="avenue" placeholder="<?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_street() !== "" && $company->getAndress_street() !== null) ? $company->getAndress_street() : ""; ?>">
                                            <div id="to_validation_blank_avenue" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="number"><?php echo $translate->translate('Número', $_SESSION['user_lang']); ?> *</label>
                                            <input inputmode="numeric" data-number type="text" class="form-control to_validations" name="number" id="number" placeholder="<?php echo $translate->translate('Número', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_number() !== "" && $company->getAndress_number() !== null) ? $company->getAndress_number() : ""; ?>">
                                            <div id="to_validation_blank_number" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="complement"><?php echo $translate->translate('Complemento', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control" name="complement" id="complement" placeholder="<?php echo $translate->translate('Complemento', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_complement() !== "" && $company->getAndress_complement() !== null) ? $company->getAndress_complement() : ""; ?>">
                                            <div id="to_validation_blank_complement" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="neighborhood"><?php echo $translate->translate('Bairro', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="neighborhood" id="neighborhood" placeholder="<?php echo $translate->translate('Bairro', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_neighbhood() !== "" && $company->getAndress_neighbhood() !== null) ? $company->getAndress_neighbhood() : ""; ?>">
                                            <div id="to_validation_blank_neighborhood" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="city"><?php echo $translate->translate('Cidade', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="city" id="city" placeholder="<?php echo $translate->translate('Cidade', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_city() !== "" && $company->getAndress_city() !== null) ? $company->getAndress_city() : ""; ?>">
                                            <div id="to_validation_blank_city" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="state"><?php echo $translate->translate('Estado', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="state" id="state" placeholder="<?php echo $translate->translate('Estado', $_SESSION['user_lang']); ?>" value="<?php echo ($company->getAndress_state() !== "" && $company->getAndress_state() !== null) ? $company->getAndress_state() : ""; ?>">
                                            <div id="to_validation_blank_state" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                </div>
                            </div>
                            <!-- end card  -->
                        </form>

                        <div class = "card card-border-radius">
                            <div class = "card-body">
                                <button type="button" class="btn btn-register float-left" id="btn-update-company"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                <button type="button" class="btn btn-cancel float-right" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>'"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                            </div>
                        </div>
                        <!-- modal photo -->
                        <div class="modal fade thetec" id="modal-photo" data-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title modal_title_img"><?php echo $translate->translate('Alterar Logo', $_SESSION['user_lang']); ?></h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-clean-form-photo">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="div_modal_img" for="logo_photo" id="div-upload-logo-photo">
                                            <img id="div_modal_logo_img" class="div_modal_logo_img">
                                            <div id="div_modal_logo_i_div">
                                                <i class="nav-icon fas fa-upload div_modal_img_i" for="logo_photo"></i>
                                            </div>
                                            <div class="div_modal_img_text" for="logo_photo">
                                                <?php echo $translate->translate('Clique aqui', $_SESSION['user_lang']); ?>!
                                            </div>
                                        </div>
                                        <form role="form" name="form_photo_logo" id="form_photo_logo" enctype="multipart/form-data">
                                            <input type="hidden" name="code" id="code" value="<?php echo $company->getId(); ?>">
                                            <input type="file" id="logo_photo" name="logo_photo" style="display: none;" accept="image/*" >
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
        if (in_array("configuration_company", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/format/cnpjFormat.min.js"></script>
            <script src="/libs/v1/admin/js/plugins/cep/loadCep.js"></script>
            <script src="/libs/v1/admin/js/plugins/cep/cepFormat.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="/libs/v1/admin/plugins/format/onlyNumbers.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
            <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="/libs/v1/admin/js/general/configuration/company.min.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>