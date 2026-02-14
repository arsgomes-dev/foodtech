<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentPriority;

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
        <!-- end top base html css -->
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/css/validation.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <link rel="stylesheet" href="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("tickets", "tickets");
            ?>
            <!--  style="min-height: 1004.44px;" -->
            <div class="content-wrapper">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Tickets", $_SESSION['user_lang']), $directory, $translate->translate("Tickets", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("ticket_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <button aria-label="Close" type="button" class="btn btn-default btn-i-color btn-filter" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".search-modal">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <?php
                                $selected_ord_date = "";
                                $response_ord = 0;
                                if (!empty($_POST['response'])) {
                                    $response_ord = ($_POST['response'] === "1") ? 1 : (($_POST['response'] === "2") ? 2 : (($_POST['response'] === "3") ? 3 : 0));
                                    if ($_POST['response'] === "2" || $_POST['response'] === "3") {
                                        $selected_ord_date = "selected";
                                    }
                                    $_POST = [];
                                } else if (!empty($_POST['messageReading'])) {
                                    $response_ord = ($_POST['messageReading'] === "1") ? 4 : 0;
                                    $_POST = [];
                                } else {
                                    $response_ord = 0;
                                }
                                $filter_ord_response = ($response_ord !== 0) ? "display: inline-block;" : "display: none;";
                                ?>                                
                                <button id="btn-clean-filter" style="<?php echo $filter_ord_response; ?>" onclick="cleanSearch();" type="button" class="btn btn-default btn-i-color-danger btn-filter" title="<?php echo $translate->translate('Limpar Filtro', $_SESSION['user_lang']); ?>">
                                    <i class="fas fa-filter-circle-xmark"></i>
                                </button>
                            </div>
                        </div>
                        <br>
                        <div class="card card-border-radius" style="margin-bottom: 40px !important;">
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
                                                    <optgroup label="<?php echo $translate->translate('Visualização', $_SESSION['user_lang']); ?>">
                                                        <option value='7'><?php echo $translate->translate('Lidos', $_SESSION['user_lang']); ?></option>
                                                        <?php
                                                        $selected_ord_response = ($response_ord === 1) ? "selected" : "";
                                                        $selected_ord_responseActive_ord = ($response_ord === 2) ? "checked" : "";
                                                        $selected_ord_responseAll_ord = ($response_ord !== 2) ? "checked" : "";
                                                        $selected_ord_responseEmergency_ord = "";
                                                        if ($response_ord === 1) {
                                                            $selected_ord_responseActive_ord = "checked";
                                                            $selected_ord_responseAll_ord = "";
                                                        } else if ($response_ord === 3) {
                                                            $selected_ord_responseEmergency_ord = 5;
                                                            $selected_ord_responseActive_ord = "checked";
                                                            $selected_ord_responseAll_ord = "";
                                                        }
                                                        $selected_ord_messageReading_ord = ($response_ord === 4) ? "selected" : "";
                                                        $selected_ord_date = ($response_ord === 0) ? "selected" : $selected_ord_date;
                                                        ?>
                                                        <option value='8' <?php echo $selected_ord_response; ?>><?php echo $translate->translate('Não Lidos', $_SESSION['user_lang']); ?></option>    
                                                        <option value='9' <?php echo $selected_ord_messageReading_ord; ?>><?php echo $translate->translate('Novas Mensagens', $_SESSION['user_lang']); ?></option>          
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Prioridade', $_SESSION['user_lang']); ?>">
                                                        <option value='1'><?php echo $translate->translate('Maior Prioridade', $_SESSION['user_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Menor Prioridade', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                        <option value='3' <?php echo $selected_ord_date; ?>><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                        <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Nome do Cliente', $_SESSION['user_lang']); ?>">
                                                        <option value='5'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='6'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                </select>
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
                                                    $departmentSearch = new TicketDepartment;
                                                    $departments = new TicketDepartment;
                                                    $departments = $departmentSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
                                                    $departmentsCount = count($departments);
                                                    if ($departmentsCount > 0) {
                                                        $department = new TicketDepartment;
                                                        for ($i = 0; $i < $departmentsCount; $i++) {
                                                            $department = $departments[$i];
                                                            $subDepartmentSearch = new TicketDepartmentSubdepartment;
                                                            $subDepartmentSearch->setTicket_department_id($department->getId());
                                                            $subDepartments = new TicketDepartmentSubdepartment;
                                                            $subDepartments = $subDepartmentSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
                                                            $subDepartmentsCount = count($subDepartments);
                                                            echo "<optgroup label='" . $department->getTitle() . "'>";
                                                            $subDepartment = new TicketDepartmentSubdepartment;
                                                            for ($j = 0; $j < $subDepartmentsCount; $j++) {
                                                                $subDepartment = $subDepartments[$j];
                                                                echo "<option value='" . $subDepartment->getId() . "'>" . $subDepartment->getTitle() . "</option>";
                                                            }
                                                            echo "</optgroup>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Prioridade', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <select class="form-control form-control-md" style="width: 100%;" name="priority_search" id="priority_search">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $departmentPrioritySearch = new TicketDepartmentSubdepartmentPriority();
                                                    $departmentsPriority = new TicketDepartmentSubdepartmentPriority;
                                                    $departmentsPriority = $departmentPrioritySearch->getQuery(limit: 0, offset: 0, order: "level ASC");
                                                    $departmentsPriorityCount = count($departmentsPriority);
                                                    if ($departmentsPriorityCount > 0) {
                                                        $departmentPriority = new TicketDepartmentSubdepartmentPriority;
                                                        for ($i = 0; $i < $departmentsPriorityCount; $i++) {
                                                            $departmentPriority = $departmentsPriority[$i];
                                                            $selected = ($selected_ord_responseEmergency_ord !== "" && $selected_ord_responseEmergency_ord === $departmentPriority->getLevel()) ? "selected" : "";
                                                            echo "<option value='" . $departmentPriority->getLevel() . "' " . $selected . ">" . $departmentPriority->getTitle() . " - " . $departmentPriority->getDeadline() . "h</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Cliente', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="description_search" name="description_search" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?>">
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
                                                        <input type="radio" id="status1" name="status" value="1" <?php echo $selected_ord_responseActive_ord; ?>/>
                                                        <label for="status1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></label>
                                                    </div>
                                                    <div class="icheck-danger" style="margin-top: 15px !important;">
                                                        <input type="radio" id="status2" name="status" value="2"/>
                                                        <label for="status2"><?php echo $translate->translate('Encerrado', $_SESSION['user_lang']); ?></label>
                                                    </div>
                                                    <div class="icheck-default" style="margin-top: 15px !important;">
                                                        <input type="radio" id="status3" name="status" value=""  <?php echo $selected_ord_responseAll_ord; ?>/>
                                                        <label for="status3"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?></label>
                                                    </div>
                                                </div> 
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnTickets();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
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
        <?php
        if (in_array("ticket_view", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/lists/tickets.min.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>