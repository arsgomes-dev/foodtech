<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartment;
use Microfw\Src\Main\Common\Entity\Admin\TicketDepartmentSubdepartmentPriority;

$translate = new Translate();

$config = new McConfig();
$subdepartmentSearch = new TicketDepartmentSubdepartment;
$subdepartments = new TicketDepartmentSubdepartment;
$privilege_types = $_SESSION['user_type'];
if (in_array("ticket_department_view", $privilege_types)) {
    $code = $_POST['code'];
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];
    $subdepartmentSearch->setTicket_department_id($code);
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $subdepartmentSearch->setTitle($_POST['description']);
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
    $order_by = "title ASC";
    if ($order == 1) {
        $order_by = "title ASC";
    } else if ($order == 2) {
        $order_by = "title DESC";
    } else if ($order == 3) {
        $order_by = "created_at DESC";
    } else if ($order == 4) {
        $order_by = "created_at ASC";
    }
    $subdepartments = $subdepartmentSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $subdepartmentsCount = count($subdepartments);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Subdepartamento', $_SESSION['user_lang']); ?></font>
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
            if ($subdepartmentsCount > 0) {
                $subdepartment = new TicketDepartmentSubdepartment;
                for ($i = 0; $i < $subdepartmentsCount; $i++) {
                    $subdepartment = $subdepartments[$i];
                    $priority = new TicketDepartmentSubdepartmentPriority;
                    $priority = $priority->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $subdepartment->getTicket_department_subdepartment_priority_id()]]);
                    $status = ($subdepartment->getStatus() === 1) ? "Ativo" : "Inativo";
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $subdepartment->getTitle() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $priority->getTitle() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $status . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='#' onclick='editSubdepartment(" . $subdepartment->getId() . ',"' . $subdepartment->getTitle() . '",' . $subdepartment->getStatus() . "," . $subdepartment->getTicket_department_subdepartment_priority_id() . ")'>"
                    . "<i class='nav-icon fas fa-edit' title='" . $translate->translate('Editar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum subdepartamento encontrado!', $_SESSION['user_lang']) . "</b></center>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/tickets/update/ticket_subdepartment.js"></script>
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
