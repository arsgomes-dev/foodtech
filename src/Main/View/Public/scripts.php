<?php
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\VideoScriptStatus;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$translate = new Translate();

$planService = new CheckPlan;
$check = $planService->checkPlan();
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
    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed thetec" style="height: auto;">
        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("script", "script");
            ?>
            <div class="content-wrapper">
                <section class="content col-lg-12 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Meus Roteiros", $_SESSION['client_lang']), $directory, $translate->translate("Roteiros", $_SESSION['client_lang']));
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
                        $checkScripts = $planService->checkScriptsLimits();
                        if (!$checkScripts['allowed']) {
                            ?>

                            <div class="alert alert-info text-start" style="padding: 0.50rem 1.25rem; margin-bottom: 10px;">
                                <strong><?php echo $check['message']; ?></strong><br>
                            </div> 
                            <br>
                            <?php
                        } else {
                            ?>  
                    <div class="row">
                        <div class="col-lg-8 col-sm-12">
                            <button aria-label="Close" type="button" class="btn btn-default btn-register" title="<?php echo $translate->translate('Filtro', $_SESSION['client_lang']); ?>" data-bs-toggle="modal" data-bs-target="#search-modal">
                                <i class="fas fa-filter"></i>
                            </button>                        
                            <button id="btn-clean-filter" style="display: none;" onclick="cleanSearch();" type="button" class="btn btn-default btn-cancel" title="<?php echo $translate->translate('Limpar Filtro', $_SESSION['client_lang']); ?>">
                                <i class="fas fa-filter-circle-xmark"></i>
                            </button>
                        </div>
                        <?php
                        $class_btn_script = "";
                        $text_btn_script = "";
                        $link_btn_script = "";
                        if (!empty($_SESSION['active_workspace_gcid']) && isset($_SESSION['active_workspace_gcid'])) {
                            if (!$check['allowed']) {
                                $class_btn_script = "btn-secondary";
                                $text_btn_script = $translate->translate('Cota esgotada', $_SESSION['client_lang']);
                            } else {
                                $class_btn_script = "btn-default btn-register";
                                $text_btn_script = $translate->translate('Novo Roteiro', $_SESSION['client_lang']);
                                $link_btn_script = "window.location.href = '/" . $config->getUrlPublic() . "/scripts/create'";
                            }
                        } else {
                            $class_btn_script = "btn-secondary";
                            $text_btn_script = $translate->translate('Selecione um canal!', $_SESSION['client_lang']);
                        }
                        ?>
                        <div class="col-lg-4 col-sm-12 d-flex flex-column justify-content-center">
                            <button aria-label="Close" type="button" class="btn btn-block <?php echo $class_btn_script; ?>" title="<?php echo $translate->translate('Novo Roteiro', $_SESSION['client_lang']); ?>" onclick="<?php echo $link_btn_script ?>">
                                <i class="fa fa-plus"></i> <?php echo $text_btn_script; ?>
                            </button>
                        </div>
                    </div>
                    <br>
                    <div class="card shadow-sm rounded-3 card-custom">
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
                                    <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Filtrar', $_SESSION['client_lang']); ?></h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="overflow-y: auto;">
                                    <form id="searchFilter">          
                                        <div class="card card-outline card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Ordenar por', $_SESSION['client_lang']); ?></b></h3>
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
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['client_lang']); ?>...</option>
                                                    <optgroup label="<?php echo $translate->translate('Data', $_SESSION['client_lang']); ?>">
                                                        <option value='1'><?php echo $translate->translate('Mais Recente', $_SESSION['client_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Mais Antigo', $_SESSION['client_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data da Postagem', $_SESSION['client_lang']); ?>">
                                                        <option value='5'><?php echo $translate->translate('Crescente', $_SESSION['client_lang']); ?></option>
                                                        <option value='6'><?php echo $translate->translate('Decrescente', $_SESSION['client_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Status', $_SESSION['client_lang']); ?>">
                                                        <option value='7'><?php echo $translate->translate('Crescente', $_SESSION['client_lang']); ?></option>
                                                        <option value='8'><?php echo $translate->translate('Decrescente', $_SESSION['client_lang']); ?></option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>    
                                        <div class="card card-outline card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Data da Postagem', $_SESSION['client_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <div class="row">  
                                                    <div class="col-lg-6 col-sm-12">
                                                        <input type="text" class="data form-control" id="date_start_search" name="date_start_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('De', $_SESSION['client_lang']); ?>" data-role="date">
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12">
                                                        <input type="text" class="data form-control" id="date_end_search" name="date_end_search" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="<?php echo $translate->translate('Até', $_SESSION['client_lang']); ?>" data-role="date">
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div> 
                                        <div class="card card-outline card-custom">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Status', $_SESSION['client_lang']); ?></b></h3>
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
                                                    <?php
                                                    $status = new VideoScriptStatus;
                                                    $status = $status->getQuery();
                                                    foreach ($status as $sts) {
                                                        echo '
                                                        <div class="icheck-default color-' . $sts->getColor() . '" style="margin-top: 5px !important;">
                                                        <input type="radio" id="status' . $sts->getId() . '" name="status" value="' . $sts->getId() . '"/>
                                                        <label for="status' . $sts->getId() . '">' . $sts->getTitle() . '</label>
                                                        </div>';
                                                    }
                                                    ?>
                                                    <div class="icheck-default color-bg-magenta" style="margin-top: 5px !important;">
                                                        <input type="radio" id="status999" name="status" value="" checked/>
                                                        <label for="status999"><?php echo $translate->translate('Todos', $_SESSION['client_lang']); ?></label>
                                                    </div>
                                                </div> 
                                            </div>
                                            <!-- /.card-body -->
                                        </div>         
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-register" onclick="loadBtnScripts();"><?php echo $translate->translate('Filtrar', $_SESSION['client_lang']); ?></button>
                                    <button type="button" class="btn btn-default btn-print" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['client_lang']); ?></button>
                                    <button type="button" class="btn btn-default btn-cancel" data-bs-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['client_lang']); ?></button>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <!-- fim modal -->
                    <?php }} ?>
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
        <script>
            var current_page = <?php echo (isset($_SESSION['current_page']) ) ? $_SESSION['current_page'] : 1; ?>;
        </script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/vendor/data/js/jquery-ui-1.10.4.custom.min.js"></script> 
        <?php echo $translate->translateDatePicker($_SESSION['client_lang']); ?>
        <script src="/assets/vendor/inputmask/inputmask.min.js"></script>
        <script src="/assets/vendor/inputmask/locale.min.js"></script>
        <script src="/assets/js/scripts/lists/scripts.js"></script>
        <?php
        session_start();

        $hasSession = false;

        $keys = [
            'current_date_start_search',
            'current_date_end_search',
            'current_status'
        ];

        foreach ($keys as $key) {
            if (!empty($_SESSION[$key])) {
                $hasSession = true;
                break;
            }
        }

        if ($hasSession):
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    loadBtnScripts();
                });
            </script>
        <?php endif; ?>
        <?php
        session_start();
        $currentOrd = (int) ($_SESSION['current_order'] ?? 0);
        $statusOrd = (int) ($_SESSION['current_status'] ?? 0);
        $startOrd = ($_SESSION['current_date_start_search'] ?? '');
        $endOrd = ($_SESSION['current_date_end_search'] ?? '');
        ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const currentOrd = <?= $currentOrd ?>;

                const select = document.getElementById('ord_search');
                if (currentOrd > 0) {
                    if (select) {
                        select.value = currentOrd.toString();
                    }
                } else {
                    select.value = 1;
                }

                const startOrd = "<?= $startOrd ?>";
                if (startOrd !== "" && startOrd !== null) {
                    document.getElementById("date_start_search").value = startOrd;
                } else {
                    document.getElementById("date_start_search").value = "";
                }
                const endOrd = "<?= $endOrd ?>";
                if (endOrd !== "" && endOrd !== null) {
                    document.getElementById("date_end_search").value = endOrd;
                } else {
                    document.getElementById("date_end_search").value = "";
                }
                const statusOrd = <?= $statusOrd ?>;
                if (statusOrd > 0) {
                    document.querySelector('input[id=status' + statusOrd + ']').checked = true;
                } else {
                    document.querySelector('input[id=status999]').checked = true;
                }
            });

        </script>
    </body>

</html>