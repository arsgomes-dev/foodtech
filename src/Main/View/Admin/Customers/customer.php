<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;

$privilege_types = $_SESSION['user_type'];
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
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/css/validation.css'>
        <link rel="stylesheet" href="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/css/user.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/css/userColor.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("customers", "customers");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">   


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">

                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Clientes', $_SESSION['user_lang'])] = "customers";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Cliente', $_SESSION['user_lang']), $directory, $translate->translate('Cliente', $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("customer_view", $privilege_types)) {
                        $edit = (in_array("customer_edit", $privilege_types)) ? "" : "disabled";
                        $customer = new Customers();
                        $customer->setTable_db_primaryKey("Gcid");
                        $customer = $customer->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gets['code']]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <!-- start card -->
                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card"></i> &nbsp; <b><?php echo $translate->translate('Informações pessoais', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body row">  
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <form name="edit_profile" id="edit_profile" autocomplete="off">
                                        <input type="hidden" name="code" id="code" value="<?php echo $customer->getGcid(); ?>">
                                        <div class="form-group">
                                            <label for="code_customer"><?php echo $translate->translate('Código do cliente', $_SESSION['user_lang']); ?>*</label>
                                            <input type="text" disabled class="form-control" value="<?php echo $customer->getGcid(); ?>">
                                        </div>
                                        <div class="form-group to_validation>
                                             <label for="name"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></label>
                                            <input type="text" disabled class="form-control" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getName(); ?>">
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12>
                                                 <div class="form-group">  
                                                <label for="cpf"><?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?></label>
                                                <input type="text" disabled class="form-control" id="cpf" placeholder="<?php echo $translate->translate('CPF', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getCpf(); ?>">
                                            </div>
                                            <div class="col-lg-6 col-sm-12">                                                        
                                                <div class="form-group">
                                                    <?php
                                                    $dateBirth = "";
                                                    if ($customer->getBirth() !== null && $customer->getBirth() !== "") {
                                                        $now = new DateTime($customer->getBirth());
                                                        $dateBirth = $now->format('d/m/Y');
                                                    }
                                                    ?>
                                                    <label for="birth"><?php echo $translate->translate('Nascimento', $_SESSION['user_lang']); ?></label>
                                                    <input type="text" disabled class="data form-control" id="birth" data-role="date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" value="<?php echo $dateBirth; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12">                                                        
                                                <div class="form-group">
                                                    <label for="contact"><?php echo $translate->translate('Contato', $_SESSION['user_lang']); ?></label>
                                                    <input type="text" disabled class="form-control" id="contact" data-inputmask=""mask":(99) 99999-9999"" data-mask="" inputmode="numeric" placeholder="(##) #####-####" value="<?php echo $customer->getContact(); ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <label for="email"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?></label>
                                                    <input type="text" class="form-control to_validations" id="email" name="email"  data-mask="" inputmode="text" onblur="emailSearch(this, '<?php echo $customer->getGcid(); ?>');" value="<?php echo $customer->getEmail(); ?>">
                                                    <div id="to_validation_invalid_email" style="display: none;" class="to_invalid"><span><?php echo $translate->translate('E-mail inválido', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_blank_email" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    <div id="to_validation_already_registered_email" style="display: none;" class="to_already_registered"><span><?php echo $translate->translate('E-mail já cadastrado', $_SESSION['user_lang']); ?>!</span></div>
                                                </div>
                                            </div>                                                                   
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php
                            if (in_array("customer_edit", $privilege_types)) {
                                ?>
                                <div class="card-footer card-footer-transparent justify-content-between border-top">
                                    <button type="button" class="btn btn-register float-left" onclick="updateCustomer(edit_profile);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        <!-- end card  -->


                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-id-card-clip"></i> &nbsp; <b><?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body">  
                                <!-- address -->
                                <div class="row">                                               
                                    <div class="col-12 form-group">
                                        <label for="cep"><?php echo $translate->translate('CEP', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('CEP', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_cep(); ?>">
                                    </div>                                          
                                    <div class="col-12 form-group">
                                        <label for="avenue"><?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('Endereço', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_avenue(); ?>">
                                    </div>                                      
                                    <div class="col-12 form-group">
                                        <label for="complement"><?php echo $translate->translate('Complemento', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('Complemento', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_complement(); ?>">
                                    </div>                                    
                                    <div class="col-lg-6 col-sm-12 form-group">
                                        <label for="number"><?php echo $translate->translate('Número', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('Número', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_number(); ?>">
                                    </div>                             
                                    <div class="col-lg-6 col-sm-12 form-group">
                                        <label for="neighborhood"><?php echo $translate->translate('Bairro', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('Bairro', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_neighborhood(); ?>">
                                    </div>                             
                                    <div class="col-lg-6 col-sm-12 form-group">
                                        <label for="city"><?php echo $translate->translate('Cidade', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control" placeholder="<?php echo $translate->translate('Cidade', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_city(); ?>">
                                    </div>                         
                                    <div class="col-lg-6 col-sm-12 form-group">
                                        <label for="state"><?php echo $translate->translate('Estado', $_SESSION['user_lang']); ?>*</label>
                                        <input disabled type="text" class="form-control to_validations" placeholder="<?php echo $translate->translate('Estado', $_SESSION['user_lang']); ?>" value="<?php echo $customer->getAndress_state(); ?>">
                                    </div> 
                                </div>
                                <!-- end address -->
                            </div>
                        </div>

                        <div class="card card-border-radius">
                            <div class="card-header">
                                <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-gear"></i> &nbsp; <b><?php echo $translate->translate('Dados da conta', $_SESSION['user_lang']); ?></b></h3>
                            </div>
                            <div class="card-body">           
                                <table class="table table-hover text-nowrap table-borderless">
                                    <tbody>
                                        <tr class="table-user" data-toggle="modal" data-target="#modal-status">
                                            <td><?php echo $translate->translate('Status de Acesso', $_SESSION['user_lang']); ?></td>
                                            <td id="tr-status">
                                                <?php
                                                if ($customer->getStatus() === 0) {
                                                    echo $translate->translate('Inativo', $_SESSION['user_lang']);
                                                } else if ($customer->getStatus() === 1) {
                                                    echo $translate->translate('Ativo', $_SESSION['user_lang']);
                                                } else if ($customer->getStatus() === 2) {
                                                    echo $translate->translate('Bloqueado', $_SESSION['user_lang']);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <i class="nav-icon-color nav-icon fas fa-edit"></i>    
                                            </td>
                                        </tr>
                                        <?php
                                        if (in_array("customer_edit", $privilege_types)) {
                                            ?>
                                            <tr class="table-user" onclick="recoveryPasswd('<?php echo $customer->getGcid(); ?>')">
                                                <td><?php echo $translate->translate('Recuperar Senha', $_SESSION['user_lang']); ?></td>
                                                <td>**********</td>
                                                <td><i class="nav-icon-color nav-icon fas fa-key"></i></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>


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
                                            <input type="hidden" name="code" id="code" value="<?php echo $customer->getGcid(); ?>">
                                            <div class="col-12 to_validation">                                                        
                                                <div class="form-group">
                                                    <select class="custom-select to_validations" id="status" name="status">
                                                        <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                        <?php
                                                        $statusInactive = ($customer->getStatus() === 0) ? "selected" : "";
                                                        $statusActive = ($customer->getStatus() === 1) ? "selected" : "";
                                                        $statusBlocked = ($customer->getStatus() === 2) ? "selected" : "";
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
                                        <?php
                                        if (in_array("customer_edit", $privilege_types)) {
                                            ?>
                                            <button type="button" class="btn btn-default btn-register" onclick="updateStatus(edit_status);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>  
                                            <?php
                                        }
                                        ?>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>     
                        <!-- modal status -->

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
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/emailValidation.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/cpfFormat.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>

        <?php
        if (in_array("customer_edit", $privilege_types)) {
            ?>
            <script>
                                                var recoveryTitle = "<?php echo $translate->translate('Desejar recuperar a senha do usuário?', $_SESSION['user_lang']); ?>";
                                                var recoveryText = "<?php echo $translate->translate('Atenção: a senha atualmente utilizada deixará de funcionar e não poderá mais ser usada.', $_SESSION['user_lang']); ?>";
                                                var recoveryButton = "<?php echo $translate->translate('Confirmar', $_SESSION['user_lang']); ?>!";
            </script>

            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/customer/update/customer.min.js"></script>
            <!-- end bottom base html js -->
            <?php
        }
        ?>
    </body>

</html>