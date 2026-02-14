<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentPriority;

$translate = new Translate();

$config = new McConfig();
$ticketSearch = new Ticket;
$customersSearch = new Customers;
$tickets = new Ticket;
$privilege_types = $_SESSION['user_type'];
if (in_array("ticket_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $customersSearch->setName($_POST['description']);
        }
    }
    if (!empty($_POST['department'])) {
        if ($_POST['department'] !== "" && $_POST['department'] !== null && $_POST['department'] !== "") {
            $ticketSearch->setTicket_department_subdepartment_id($_POST['department']);
        }
    }
    if (!empty($_POST['priority'])) {
        if ($_POST['priority'] !== "" && $_POST['priority'] !== null && $_POST['priority'] !== "") {
            $ticketSearch->setLevel($_POST['priority']);
        }
    }
    if (isset($_POST['sts'])) {
        if ($_POST['sts'] !== "" && $_POST['sts'] !== null && $_POST['sts'] !== "") {
            $sts = "";
            if ($_POST['sts'] === "2") {
                $sts = 2;
            } else {
                $sts = 1;
            }
            $ticketSearch->setStatus($sts);
        }
    }
    $offset = 0;
    if (isset($page, $limit)) {
        $page = $page;
        $limitConfig = $limit;
    } else {
        $page = 1;
        $limitConfig = 20;
    }
    if ($page == 1) {
        $offset = 0;
    } else {
        $offset = ($limitConfig * $page) - $limitConfig;
    }
    $order_by = "B.name ASC";
    if ($order === 1) {
        $order_by = "A.level DESC";
    } else if ($order === 2) {
        $order_by = "A.level ASC";
    } else if ($order == 3) {
        $order_by = "A.created_at ASC";
    } else if ($order == 4) {
        $order_by = "A.created_at DESC";
    } else if ($order == 5) {
        $order_by = "B.name ASC";
    } else if ($order == 6) {
        $order_by = "B.name DESC";
    } else if ($order == 7) {
        $order_by = "A.response ASC";
    } else if ($order == 8) {
        $order_by = "A.response DESC";
    } else if ($order == 9) {
        $order_by = "A.message_reading_status DESC";
    }
    $tickets = $ticketSearch->getQuery(
            classB: $customersSearch,
            limit: $limit,
            offset: $offset,
            order: $order_by
    );
    $ticketsCount = count($tickets);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Título', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Departamento', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Prioridade', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($ticketsCount > 0) {
                $ticket = new Ticket;
                for ($i = 0; $i < $ticketsCount; $i++) {
                    $ticket = $tickets[$i];
                    $customer = new Customers();
                    $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getCustomer_id()]]);
                    $ico = "";
                    if ($ticket->getResponse() === 1) {
                        $ico = "<i class='nav-icon fa-regular fa-envelope' title='" . $translate->translate('Não Lido', $_SESSION['user_lang']) . "'></i>";
                    } else {
                        $ico = "<i class='nav-icon fa-regular fa-envelope-open' title='" . $translate->translate('Lido', $_SESSION['user_lang']) . "'></i>";
                    }
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $ticket->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $ico . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $customer->getName() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $ticket->getTitle() . "</font></td>";
                    $subDepartment = new TicketDepartmentSubdepartment;
                    $subDepartment = $subDepartment->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getTicket_department_subdepartment_id()]]);
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $subDepartment->getTitle() . "</font></td>";
                    $subDepartmentPriority = new TicketDepartmentSubdepartmentPriority;
                    $subDepartmentPriority = $subDepartmentPriority->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $ticket->getPriority_id()]]);
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $subDepartmentPriority->getTitle() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";
                    $status_ticket = "";
                    if ($ticket->getStatus() == 1) {
                        $status_ticket = $translate->translate("Ativo", $_SESSION['user_lang']);
                    } else if ($ticket->getStatus() == 2) {
                        $status_ticket = $translate->translate("Encerrado", $_SESSION['user_lang']);
                    } else if ($ticket->getStatus() == 3) {
                        $status_ticket = $translate->translate("Não Resolvido", $_SESSION['user_lang']);
                    }

                    echo $status_ticket;
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/ticket/" . $ticket->getGcid() . "'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='8'>";
                echo "<center><b>" . $translate->translate('Nenhum ticket encontrado!', $_SESSION['user_lang']) . "</b></center>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>


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
