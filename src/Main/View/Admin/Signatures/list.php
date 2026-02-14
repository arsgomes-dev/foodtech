<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;

$translate = new Translate();

$config = new McConfig();
$signatureSearch = new Signature;
$customersSearch = new Customers;
$signatures = new Signature;
$privilege_types = $_SESSION['user_type'];
$start = [];
$end = [];
if (in_array("customer_signatures", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    if (isset($_POST['ord'])) {
        $order = $_POST['ord'];
    } else {
        $order_by = "A.created_at ASC";
        $order = 0;
    }
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $customersSearch->setName($_POST['description']);
        }
    }
    if (!empty($_POST['start'])) {
        if ($_POST['start'] !== "" && $_POST['start'] !== null && $_POST['start'] !== "") {
            $start['A.date_start'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['start'])));
        }
    }
    if (!empty($_POST['end'])) {
        if ($_POST['end'] !== "" && $_POST['end'] !== null && $_POST['end'] !== "") {
            $end['A.date_start'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['end'])));
        }
    }
    if (!empty($_POST['closureStart'])) {
        if ($_POST['closureStart'] !== "" && $_POST['closureStart'] !== null && $_POST['closureStart'] !== "") {
            $start['A.date_end'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['closureStart'])));
        }
    }
    if (!empty($_POST['closureEnd'])) {
        if ($_POST['closureEnd'] !== "" && $_POST['closureEnd'] !== null && $_POST['closureEnd'] !== "") {
            $end['A.date_end'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['closureEnd'])));
        }
    }

    if (!empty($_POST['sts'])) {
        if ($_POST['sts'] !== "" && $_POST['sts'] !== null && $_POST['sts'] !== "") {
            if ($_POST['sts'] === "4") {
                $signatureSearch->setStatus(0);
            } else {
                $signatureSearch->setStatus($_POST['sts']);
            }
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
    $order_by = "A.created_at ASC";
    if ($order === 1) {
        $order_by = "A.created_at ASC";
    } else if ($order == 2) {
        $order_by = "A.created_at DESC";
    } else if ($order == 3) {
        $order_by = "B.name ASC";
    } else if ($order == 4) {
        $order_by = "B.name DESC";
    } else if ($order == 5) {
        $order_by = "A.date_start ASC";
    } else if ($order == 6) {
        $order_by = "A.date_start DESC";
    } else if ($order == 7) {
        $order_by = "A.date_end ASC";
    } else if ($order == 8) {
        $order_by = "A.date_end DESC";
    }
    $signatures = $signatureSearch->getQuery(
            classB: $customersSearch,
            limit: $limit,
            offset: $offset,
            order: $order_by,
            less_equal: $end,
            greater_equal: $start
    );
    $signaturesCount = count($signatures);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Cliente', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Renovação Automática', $_SESSION['user_lang']); ?></font>
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
            if ($signaturesCount > 0) {
                $signature = new Signature;
                for ($i = 0; $i < $signaturesCount; $i++) {
                    $signature = $signatures[$i];
                    $customer = new Customers();
                    $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);
                    $plan = new AccessPlan;
                    $plan = $plan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);
                    $date_start = "";
                    if ($signature->getDate_start() !== null && $signature->getDate_start() !== "") {
                        $date_start = (new DateTime($signature->getDate_start()))->format("d/m/Y");
                    }
                    $date_end = "";
                    if ($signature->getDate_end() !== null && $signature->getDate_end() !== "") {
                        $date_end = (new DateTime($signature->getDate_end()))->format("d/m/Y");
                    }
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $signature->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $customer->getName() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $plan->getTitle() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_start . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_end . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";
                    $renovation_signature = "";
                    if ($signature->getAuto_renew() == 0) {
                        $renovation_signature = $translate->translate("Inativo", $_SESSION['user_lang']);
                    } else {
                        $renovation_signature = $translate->translate("Ativo", $_SESSION['user_lang']);
                    }
                    echo $renovation_signature;
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";
                    $status_signature = "";
                    if ($signature->getStatus() == 0) {
                        $status_signature = $translate->translate("Inativo", $_SESSION['user_lang']);
                    } else if ($signature->getStatus() == 1) {
                        $status_signature = $translate->translate("Ativo", $_SESSION['user_lang']);
                    } else if ($signature->getStatus() == 2) {
                        $status_signature = $translate->translate("Cancelado", $_SESSION['user_lang']);
                    } else if ($signature->getStatus() == 3) {
                        $status_signature = $translate->translate("Bloqueado", $_SESSION['user_lang']);
                    }

                    echo $status_signature;
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/signatures/" . $signature->getGcid() . "'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='8'>";
                echo "<center><b>" . $translate->translate('Nenhuma assinatura encontrada!', $_SESSION['user_lang']) . "</b></center>";
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
