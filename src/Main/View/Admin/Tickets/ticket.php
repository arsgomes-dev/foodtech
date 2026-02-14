<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\TicketSend;
use Microfw\Src\Main\Controller\Admin\Tickets\MarkTicketRead;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <!-- end top base html css -->
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/css/validation.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <link rel="stylesheet" href="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("tickets", "tickets");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    $directory[$translate->translate('Tickets', $_SESSION['user_lang'])] = "tickets";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Ticket", $_SESSION['user_lang']), $directory, $translate->translate("Ticket", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("ticket_view", $privilege_types)) {
                        $ticket = new Ticket;
                        $ticket->setTable_db_primaryKey("Gcid");
                        $ticket = $ticket->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $gets["code"]]]);
                        if (!empty($ticket)) {
                            $customer = new Customers();
                            $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getCustomer_id()]]);
                            ?>
                            <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                            <div class="container-fluid" style="margin-bottom: 40px !important;">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-ticket"></i> &nbsp; <b><?php echo $translate->translate("Ticket", $_SESSION['user_lang']); ?></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <h5><b><?php echo $translate->translate("Cliente", $_SESSION['user_lang']); ?>:</b>&nbsp;&nbsp;                                          
                                            <a target="_blank" href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/customer/" . $customer->getGcid(); ?>"><?php echo $customer->getName(); ?></a></h5>
                                        <hr>
                                        <div class="form-group">
                                            <label><?php echo $translate->translate('Título', $_SESSION['user_lang']); ?></label>
                                            <input type="text" disabled class="form-control" value="<?php echo $ticket->getTitle(); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label><?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?></label>
                                            <br>
                                            <div class="ticket-description">
                                                <?php echo $ticket->getDescription(); ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <?php
                                                    $date_send = new DateTime($ticket->getDate_send());
                                                    ?>
                                                    <label><?php echo $translate->translate('Data do Ticket', $_SESSION['user_lang']); ?></label>
                                                    <input type="text" disabled class="form-control" value="<?php echo $date_send->format("d/m/Y") . " " . $translate->translate("às", $_SESSION['user_lang']) . " " . $date_send->format("H:i") . "h"; ?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="form-group">
                                                    <?php
                                                    $status_ticket = "";
                                                    if ($ticket->getStatus() == 1) {
                                                        $status_ticket = $translate->translate("Ativo", $_SESSION['user_lang']);
                                                    } else if ($ticket->getStatus() == 2) {
                                                        $status_ticket = $translate->translate("Encerrado", $_SESSION['user_lang']);
                                                    } else if ($ticket->getStatus() == 3) {
                                                        $status_ticket = $translate->translate("Não Resolvido", $_SESSION['user_lang']);
                                                    }
                                                    ?> 
                                                    <label><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></label>
                                                    <input type="text" disabled class="form-control" value="<?php echo $status_ticket; ?>">
                                                </div>
                                            </div> <?php
                                            if ($ticket->getStatus() !== 1 && $ticket->getClosure_description() !== null && $ticket->getClosure_description() !== "") {
                                                ?>
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="form-group">
                                                        <label><?php echo $translate->translate('Comentários', $_SESSION['user_lang']); ?></label>                                                   
                                                        <textarea disabled class="form-control"><?php echo $ticket->getClosure_description(); ?></textarea>
                                                    </div>
                                                </div>
                                            <?php }
                                            ?> 
                                            <?php
                                            if ($ticket->getUpdated_at() !== null && $ticket->getUpdated_at() !== "" && $ticket->getUser_id_updated() !== null && $ticket->getUser_id_updated() > 0) {
                                                ?>
                                                <div
                                                    class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                        $dateUpdated = new DateTime($ticket->getUpdated_at());
                                                        ?>
                                                        <label><?php echo $translate->translate('Última atualização em', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" disabled class="form-control" value="<?php echo $dateUpdated->format("d/m/Y") . " " . $translate->translate("às", $_SESSION['user_lang']) . " " . $dateUpdated->format("H:i") . "h"; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                        $userUpdate = new User;
                                                        $userUpdate = $userUpdate->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getUser_id_updated()]]);
                                                        ?>
                                                        <label><?php echo $translate->translate('Última atualização feita por', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" disabled class="form-control" value="<?php echo $userUpdate->getName(); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                            <?php
                                            if ($ticket->getDate_reading() !== null && $ticket->getDate_reading() !== "" && $ticket->getUser_id_reading() !== null && $ticket->getUser_id_reading() > 0) {
                                                ?>
                                                <div
                                                    class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                        $dateReading = new DateTime($ticket->getDate_reading());
                                                        ?>
                                                        <label><?php echo $translate->translate('Ticket lido em', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" disabled class="form-control" value="<?php echo $dateReading->format("d/m/Y") . " " . $translate->translate("às", $_SESSION['user_lang']) . " " . $dateReading->format("H:i") . "h"; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="form-group">
                                                        <?php
                                                        $userReading = new User;
                                                        $userReading = $userReading->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getUser_id_reading()]]);
                                                        ?>
                                                        <label><?php echo $translate->translate('Ticket lido por', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" disabled class="form-control" value="<?php echo $userReading->getName(); ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div> 
                                    </div>        
                                    <?php if (in_array("ticket_close", $privilege_types) && $ticket->getStatus() === 1) { ?>
                                        <div class="card-footer card-footer-transparent justify-content-between border-top">    
                                            <button type="button" class="btn btn-default btn-flat btn-cancel" data-toggle="modal" data-target=".close-modal"><i class="fas fa-envelope-square"></i> <?php echo $translate->translate("Encerrar Ticket", $_SESSION['user_lang']); ?></button>
                                        </div>
                                    <?php } else if (in_array("ticket_reopen", $privilege_types) && ($ticket->getStatus() === 2 || $ticket->getStatus() === 3)) { ?>
                                        <div class="card-footer card-footer-transparent justify-content-between border-top">    
                                            <button type="button" class="btn btn-default btn-flat btn-register" onclick="reactivateTicket('<?php echo $ticket->getGcid(); ?>');"><i class="fas fa-envelope-square"></i> <?php echo $translate->translate("Reabrir Ticket", $_SESSION['user_lang']); ?></button>
                                        </div>
                                    <?php } ?>
                                </div> 

                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-ticket"></i> &nbsp; <b><?php echo $translate->translate("Mensagens", $_SESSION['user_lang']); ?></b></h3>
                                    </div>
                                    <div class="card-body">
                                        <!-- carrega as mensagens recebidas -->    
                                        <div class="mailbox-read-message">
                                            <div class="timeline">                           

                                                <?php
                                                //consulta as mensagens do ticket 
                                                $ticketSendAll = new TicketSend;
                                                $ticketSendAllSearch = new TicketSend;
                                                $ticketSendAllSearch->setTicket_id($ticket->getId());
                                                $ticketSendAll = $ticketSendAllSearch->getQuery(limit: 0, offset: 0, order: "date_send ASC");
                                                $date_ticket_send = "";
                                                $ticket_send = new TicketSend;
                                                if (count($ticketSendAll) > 0) {
                                                    if ($ticket->getDate_reading() === null || $ticket->getDate_reading() === "") {
                                                        $markTicketRead = new MarkTicketRead;
                                                        $markTicketRead->setMarkTicketRead($ticket->getId());
                                                    }
                                                    for ($i = 0; $i < count($ticketSendAll); $i++) {
                                                        $ticket_send = $ticketSendAll[$i];
                                                        $check = "";
                                                        if ($ticket_send->getCustomer_id() !== null && $ticket_send->getCustomer_id() !== "" && $ticket_send->getCustomer_id() > 0) {
                                                            if (empty($ticket_send->getDate_read())) {
                                                                $markMessageRead = new MarkTicketRead;
                                                                $markMessageRead->setMarkMessageRead($ticket_send->getId());
                                                                $check = '  <i style="color: #5ba456;" class="fas fa-check-double"></i>';
                                                            }
                                                        }
                                                        //defini variaveis que será utilizadas
                                                        $agent = null;
                                                        $client = null;
                                                        $user = null;
                                                        $name = "";
                                                        $span_style = "";
                                                        $h3_style = "";
                                                        $i_style = "";
                                                        //verifica se a mensagem foi enviada pelo agente
                                                        if ($ticket_send->getUser_id() !== null && $ticket_send->getUser_id() !== "" && $ticket_send->getUser_id() > 0) {
                                                            $agent_ticket = new User;
                                                            $agent_ticket = $agent_ticket->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket_send->getUser_id()]]);
                                                            $occupation = new DepartmentOccupation();
                                                            $occupation = $occupation->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $agent_ticket->getDepartment_occupation_id()]]);
                                                            if ($ticket_send->getUser_id() == $_SESSION['user_id']) {
                                                                $name = $translate->translate("Você", $_SESSION['user_lang']);
                                                                $span_style = "float: left;";
                                                                $h3_style = "text-align: right;";
                                                                $i_style = "fas fa-user bg-green";
                                                            } else {
                                                                $name = $agent_ticket->getName() . " - " . $occupation->getTitle();
                                                                $i_style = "fas fa-comments bg-yellow";
                                                                $span_style = "float: right;";
                                                                $h3_style = "text-align: left;";
                                                                $i_style = "fas fa-user bg-yellow";
                                                            }
                                                        } else if ($ticket_send->getCustomer_id() !== null && $ticket_send->getCustomer_id() !== "" && $ticket_send->getCustomer_id() > 0) {
                                                            $client = new Customers;
                                                            $client = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket_send->getCustomer_id()]]);
                                                            $name = $client->getName();
                                                            $i_style = "fas fa-comments bg-yellow";
                                                            $span_style = "float: right;";
                                                            $h3_style = "text-align: left;";
                                                            $i_style = "fas fa-user bg-blue";
                                                        }

                                                        $date_send_return2 = new DateTime($ticket_send->getDate_send());
                                                        $date_send_return = $date_send_return2->format("d/m/Y");
                                                        if ($date_send_return !== $date_ticket_send) {
                                                            $date_ticket_send = $date_send_return;
                                                            echo '<div class="time-label">
                                                                    <span class="bg-red">' . $date_send_return . '</span>
                                                                  </div>';
                                                        }
                                                        if (!empty($ticket_send->getDate_read())) {
                                                            $check = '  <i style="color: #5ba456;" class="fas fa-check-double"></i>';
                                                        } else {
                                                            $check = '  <i class="fas fa-check"></i>';
                                                        }
                                                        ?>
                                                        <div>
                                                            <i class="<?php echo $i_style; ?>"></i>
                                                            <div class="timeline-item">
                                                                <span style="<?php echo $span_style; ?>" class="time"><i class="fas fa-clock"></i> <?php echo $date_send_return2->format("H:i") . 'h ' . $check; ?></span>
                                                                <h3 style=""<?php echo $h3_style; ?>" class="timeline-header no-border"><?php echo $name; ?></h3>
                                                                <div class="timeline-body">
                                                                    <?php echo $ticket_send->getMessage(); ?>
                                                                    <?php
                                                                    if ($ticket_send->getFile() !== "" && $ticket_send->getFile() !== null) {
                                                                        ?>
                                                                        <br>
                                                                        <hr>
                                                                        <a href="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/tickets/" . $ticket->getGcid() . "/" . $ticket_send->getFile(); ?>?image=250" data-toggle="lightbox">
                                                                            <img src="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/tickets/" . $ticket->getGcid() . "/" . $ticket_send->getFile(); ?>?image=250" class="img-fluid" style="width: 40%;">
                                                                        </a>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                                <?php
                                                                if (in_array("ticket_send_delete", $privilege_types) && $client == null) {
                                                                    ?>
                                                                    <div class="timeline-footer">
                                                                        <form name="message_send_op" id="message_send_op">
                                                                            <?php
                                                                            if ($ticket_send->getStatus() == 1) {
                                                                                ?>
                                                                                <button type="button" class="btn btn-info btn-sm btn-flat" onclick="statusMessageSend(<?php echo $ticket_send->getId(); ?>, 2);"><i class="fas fa-fas fa-lock"></i> <?php echo $translate->translate('Desativar', $_SESSION['user_lang']); ?></button>
                                                                            <?php } else { ?>
                                                                                <button type="button" class="btn btn-info btn-sm btn-flat" onclick="statusMessageSend(<?php echo $ticket_send->getId(); ?>, 1);"><i class="fas fa-fas fa-lock-open"></i><?php echo $translate->translate('Ativar', $_SESSION['user_lang']); ?> </button>
                                                                            <?php } ?>
                                                                            <button type="button" class="btn btn-danger btn-sm btn-flat" onclick="trashMessageSend(<?php echo $ticket_send->getId(); ?>);"><i class="fas fa-trash-alt"></i><?php echo $translate->translate('Excluir', $_SESSION['user_lang']); ?></button>
                                                                        </form>
                                                                    </div> <?php } ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                if (in_array("ticket_send", $privilege_types) && $ticket->getStatus() === 1) {
                                                    ?>                
                                                    <div>
                                                        <i class="fas fa-paper-plane bg-green"></i>
                                                        <div class="timeline-item">
                                                            <h3 class="timeline-header"><?php echo $_SESSION['user_username']; ?></h3>
                                                            <div class="timeline-body">
                                                                <form role="form" id="messageSend" name="messageSend" enctype="multipart/form-data">
                                                                    <input type="hidden" id="code" name="code" value="<?php echo $ticket->getId(); ?>">
                                                                    <textarea class="form-control" id="ticket_message_send" name="message_send" placeholder="<?php echo $translate->translate('Digite aqui a resposta...', $_SESSION['user_lang']); ?>"></textarea>
                                                                    <br>
                                                                    <h6><?php echo $translate->translate('Anexar imagem', $_SESSION['user_lang']); ?>:</h6>
                                                                    <input type="file" id="ticket_message_img" name="ticket_message_img" class="form-control border-0" accept="image/*" >
                                                                </form>   
                                                            </div>
                                                            <div class="timeline-footer">
                                                                <a style="cursor:pointer;" class="btn btn-success btn-sm btn-flat" onclick="ticketSend();"><?php echo $translate->translate('Responder', $_SESSION['user_lang']); ?></a>
                                                                <a style="cursor:pointer;" class="btn btn-danger btn-sm btn-flat" onclick="cleanForm(messageSend);"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <div>
                                                    <i class="fas fa-clock bg-gray"></i>
                                                </div>
                                            </div>
                                        </div>      
                                    </div>        
                                </div> 
                            </div>     

                            <div class="modal fade close-modal" id="close-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <center><h3><?php echo $translate->translate('O ticket que deseja encerrar já foi resolvido?', $_SESSION['user_lang']); ?></h3></center>
                                            <form style="margin: 10px;" role="form" name="form_close_ticket" id="form_close_ticket">
                                                <input type="hidden" id="code" name="code" value="<?php echo $ticket->getGcid(); ?>">
                                                <div class="row">
                                                    <div class="col-12 to_validation">
                                                        <div class="form-group">
                                                            <div class="icheck-success">
                                                                <input type="radio" id="status1" name="stTicket" value="2"/>
                                                                <label for="status1"><?php echo $translate->translate('Foi resolvido', $_SESSION['user_lang']); ?></label>
                                                            </div>
                                                            <div class="icheck-danger" style="margin-top: 15px !important;">
                                                                <input type="radio" id="status2" name="stTicket" value="3"/>
                                                                <label for="status2"><?php echo $translate->translate('Não foi resolvido', $_SESSION['user_lang']); ?></label>
                                                            </div>
                                                            <div id="to_validation_blank_stTicket" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>  
                                                    </div>
                                                    <div class="col-12 to_validation">
                                                        <div class="form-group">
                                                            <label><?php echo $translate->translate('Deixe um comentário', $_SESSION['user_lang']); ?></label>
                                                            <textarea class="form-control to_validations" id="description_close" name="description_close" placeholder="<?php echo $translate->translate('Digite aqui...', $_SESSION['user_lang']); ?>"></textarea>
                                                            <div id="to_validation_blank_description_close" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>                                                    
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer justify-content-center border-0">
                                            <button type="button" class="btn btn-default btn-register" onclick="deactivateTicket(form_close_ticket);"><?php echo $translate->translate('Encerrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanFormClose(form_close_ticket);" ><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div> 



                            <?php
                        } else {
                            ?>
                            <div class="content-header">
                                <div class="container-fluid">
                                    <div class="alert alert-warning alert-dismissible">
                                        <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                                        <?php
                                        echo $translate->translate('Nenhum ticket encontrado!', $_SESSION['user_lang']);
                                        ?>
                                        </font>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
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
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.all.min.js"></script>
        <?php
        if (in_array("ticket_close", $privilege_types) && $client == null) {
            ?>
            <script>
                                                var titleMessage = "<?php echo $translate->translate('O ticket que deseja encerrar já foi resolvido?', $_SESSION['user_lang']); ?>";
                                                var okMessage = "<?php echo $translate->translate('Sim, foi resolvido', $_SESSION['user_lang']); ?>";
                                                var noMessage = "<?php echo $translate->translate('Não', $_SESSION['user_lang']); ?>";
                                                var notResolvedMessage = "<?php echo $translate->translate('Qual o motivo pelo qual a questão ainda não foi resolvida?', $_SESSION['user_lang']); ?>";
            </script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/update/ticket.js"></script>
            <?php
        }
        if (in_array("ticket_send", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/update/sendResponse.js"></script>
        <?php } ?>
        <style>
            .mailbox-read-message {
                padding: 5px;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/bs5-lightbox@1.8.5/dist/index.bundle.min.js"></script>
        <!-- end bottom base html js -->
    </body>

</html>