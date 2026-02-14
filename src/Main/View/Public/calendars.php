<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$language = new Language;
$translate = new Translate();
$planService = new CheckPlan;
$check = $planService->checkPlan();
?>
<!doctype html>
<html lang="pt-br" style="height: auto;" data-theme="dark">
    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <?php echo $baseHtml->baseCSSICheck(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <?php echo $baseHtml->baseCSSDate(); ?>          
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>
    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed thetec" style="height: auto;">
        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("calendar", "calendar");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-12 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Calendário de Postagens", $_SESSION['client_lang']), $directory, $translate->translate("Calendário", $_SESSION['client_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->
                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                    <?php
                    if (!$check['allowed']) {
                        ?>

                        <div class="alert alert-info text-start" style="padding: 0.50rem 1.25rem; margin-bottom: 10px;">
                            <strong><?php echo $check['message']; ?></strong><br>
                        </div> 
                        <br>
                        <?php
                    } else {
                        ?>  
                        <div class="card shadow-sm rounded-3 card-custom">
                            <div class="card-body">
                                <div id="list" class="text-center py-5">
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade addScript" id="addScript" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Novo Roteiro', $_SESSION['client_lang']); ?></h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" onclick="cleanForm(form_script);">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form style="margin: 10px;" role="form" name="form_script" id="form_script">
                                            <input type="hidden" id="dataSelecionada" id="data" name="data">

                                            <!-- Título -->
                                            <div class="mb-3 to_validation">
                                                <label class="form-label">
                                                    <?php echo $translate->translate('Título do Roteiro', $_SESSION['client_lang']); ?>
                                                </label>
                                                <input type="text" class="form-control to_validations" id="title" name="title" required>      
                                                <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>

                                            <!-- Palavras-chave -->
                                            <div class="mb-3 to_validation">
                                                <label class="form-label">
                                                    <?php echo $translate->translate('Palavras-chave', $_SESSION['client_lang']); ?>
                                                </label>
                                                <input type="text" class="form-control to_validations" 
                                                       id="keys"
                                                       name="keys"
                                                       placeholder="<?php echo $translate->translate('Separadas por vírgula', $_SESSION['client_lang']); ?>"
                                                       required>
                                                <div id="to_validation_blank_keys" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>

                                            <!-- Conteúdo -->
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <?php echo $translate->translate('Conteúdo', $_SESSION['client_lang']); ?>
                                                </label>
                                                <textarea class="form-control" name="description_not" id="description_not"></textarea>
                                                <textarea class="to_validations" style="display: none;" name="description" id="description" rows="5"></textarea>
                                                <div id="to_validation_blank_description" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['client_lang']); ?>!</span></div>
                                            </div>

                                            <!-- Thumbnail -->
                                            <div class="mb-3">
                                                <label class="form-label">
                                                    <?php echo $translate->translate('Thumbnail', $_SESSION['client_lang']); ?>
                                                </label>

                                                <div class="thumbnail-wrapper mx-auto" id="thumbnailBox" data-text="<?php echo $translate->translate('Clique para enviar imagem', $_SESSION['client_lang']); ?>">
                                                    <div class="thumbnail-placeholder" id="thumbPreview">
                                                        <i class="fas fa-cloud-upload-alt fa-3x"></i>
                                                        <p class="mt-2">
                                                            <?php echo $translate->translate('Clique para enviar imagem', $_SESSION['client_lang']); ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" hidden>
                                            </div>
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['client_lang']); ?> *</b></span>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" onclick="createScript(form_script);"><?php echo $translate->translate('Cadastrar', $_SESSION['client_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-bs-dismiss="modal" onclick="cleanForm(form_script);"><?php echo $translate->translate('Cancelar', $_SESSION['client_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- fim modal -->
                    <?php } ?>
                </section>        
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php");
                ?>  
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/vendor/data/js/jquery-ui-1.10.4.custom.min.js"></script> 
        <script src="/assets/vendor/fullcalendar/index.global.min.js"></script>
        <script src='/assets/vendor/tinymce/tinymce.min.js'></script>
        <script src="/assets/vendor/validation/js/formValidation.min.js"></script>
        <script>
                                            var editTitle = "<?php echo $translate->translate('Deseja editar o roteiro', $_SESSION['client_lang']); ?>?";
                                            var editText = "<?php echo $translate->translate('Você será redirecionado para a página de edição', $_SESSION['client_lang']); ?>.";
                                            var editButton = "<?php echo $translate->translate('Confirmar', $_SESSION['client_lang']); ?>!";
                                            var createTitle = "<?php echo $translate->translate('Deseja criar um novo roteiro', $_SESSION['client_lang']); ?>?";
                                            var createText = "<?php echo $translate->translate('Você será redirecionado para a página de criação', $_SESSION['client_lang']); ?>.";
        </script>
        <script src="/assets/js/calendars/lists/calendars.js"></script>

    </body>

</html>