<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentAgent;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;

$translate = new Translate();
$privilege_types = $_SESSION['user_type'];
$config = new McConfig;
$baseHtml = new BaseHtml();

if (!empty($_POST['code']) && isset($_POST['code'])) {
    if (in_array("ticket_agents_create", $privilege_types)) { //consulta se usuário tem permissão de acesso 
        $status_agent = false;
        ?>

        <section class="content">
                   <div class="row">                               
                                    <div class="form-group col-lg-12 col-sm-12 col-md-12">
                                        <label><font style="vertical-align: inherit;"><?php echo $translate->translate('Status do Atendimento *', $_SESSION['user_lang']); ?></font></label>
                                        <br>
                                        <?php
                                        $user = new User;
                                        $agent = new User;
                                        $agent = $user->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_POST['code']]]);
                                        $checked = "";
                                        $display = "";
                                        if ($agent !== "") {
                                            if (isset($agent) && $agent !== null) {
                                                if ($agent->getStatus_agent() == 1) {
                                                    $checked = "checked";
                                                    $display = "";
                                                    $status_agent = true;
                                                }
                                            }
                                        }
                                        $tickets_agent = new TicketDepartmentSubdepartmentAgent;
                                        $tickets_agent->setTicket_agent_id($_POST['code']);
                                        $tickets_agent = $tickets_agent->getQuery();
                                        $tickets_types = [];
                                        $tickets_agent_count = count($tickets_agent);
                                        for ($b = 0; $b < $tickets_agent_count; $b++) {
                                            $ticket = new TicketDepartmentSubdepartmentAgent;
                                            $ticket = $tickets_agent[$b];
                                            array_push($tickets_types, $ticket->getTicket_department_subdepartment_id());
                                        }
                                        ?>
                                        <input data-toggle="switch" data-on-color="success" type="checkbox" class="form-control" onchange="displayDepartments();" name="agent_status" id="agent_status" value="1" <?php echo $checked; ?>>          
                                        <div id="validation_agent_status" name="validation_agent_status"></div>
                                    </div>
                                </div>
                                <small><?php echo $translate->translate('* Aqui você poderá definir se o atendente estará ou não ativo para os atendimentos dos tickets', $_SESSION['user_lang']); ?></small>
                                <hr>
                               
                                
                                <div style="display: none;" id="show_departments">              
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $translate->translate('Departamentos', $_SESSION['user_lang']); ?></h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <ul class="nav nav-pills flex-column">
                   <?php
                                        $ticketDepartaments = new TicketDepartment;
                                        $departmentAll = $ticketDepartaments->getQuery();
                                        if (count($departmentAll) > 0) {
                                            for ($i = 0; $i < count($departmentAll); $i++) {
                                                $department = new TicketDepartment;
                                                $department = $departmentAll[$i];
                                                ?> 
                                 <li class="nav-item active">
                                     <button class="btn btn-block btn-default border-0 btn-lg" id="vert-tabs-<?php echo $department->getId(); ?>-tab" 
                                             data-toggle="pill" href="#vert-tabs-<?php echo $department->getId(); ?>" role="tab" aria-controls="vert-tabs-<?php echo $department->getId(); ?>" aria-selected="true" 
                                             style="background-color: transparent !important; text-align: left;" >
                                        <i class="fa fa-cogs"></i> <?php echo $department->getTitle(); ?>
                                    </button>
                                </li>
                                
                                        <?php }} ?>
                              
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-9" id="notification" > 
                    <div class="card card-primary card-outline">        
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $translate->translate('Setores', $_SESSION['user_lang']); ?></h3>
                        </div>
                        <div class="card-body p-0 tab-content">
                        <?php
                         if (count($departmentAll) > 0) {
                                            for ($i = 0; $i < count($departmentAll); $i++) {
                                                $department = new TicketDepartment;
                                                $department = $departmentAll[$i];
                                                ?> 
                         <div class="tab-pane fade" id="vert-tabs-<?php echo $department->getId(); ?>" role="tabpanel" aria-labelledby="vert-tabs-<?php echo $department->getId(); ?>-tab">
                             <br>                      
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
                                                                    <div class="form-group col-lg-12 col-sm-12 col-md-12">
                                                                        <input data-toggle="switch" data-on-color="success" type="checkbox" class="form-control" name="agent_subdepartment[]" id="agent_subdepartment[]" value="<?php echo $subdepartment->getId(); ?>" <?php echo $iChecked; ?>>          
                                                                        <label><font style="vertical-align: inherit;"><?php echo $subdepartment->getTitle(); ?></font></label>
                                                                    </div>    
                                                                    <?php
                                                                }
                                                            }
                                                            ?>   
                            </div>
                              <?php }} ?>
                            </div>
                        </div>    
                    </div>
                </div>   
            </div>
        </div>
        </section>

        <?php echo $baseHtml->baseJS(); ?>  
        <script src="<?php echo $config->getDomainAdmin(); ?>/layout_pn/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
        <script>$('[data-toggle="switch"]').bootstrapSwitch();</script>
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
    }
} else {
    ?>
    <div class="alert alert-info alert-dismissible">
        <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
        <?php
        echo $translate->translate('Selecione um agente para configurar suas permissões!', $_SESSION['user_lang']);
        ?>
        </font></div>
<?php }
?>