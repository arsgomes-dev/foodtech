<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlanPrice;

$translate = new Translate();

$config = new McConfig();
$planSearch = new AccessPlan;
$privilege_types = $_SESSION['user_type'];
if (in_array("access_plans_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];

    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $planSearch->setTitle($_POST['description']);
        }
    }
    if (isset($_POST['sts'])) {
        if ($_POST['sts'] !== "" && $_POST['sts'] !== null && $_POST['sts'] !== "") {
            if ($_POST['sts'] === "1") {
                $planSearch->setStatus(1);
            } else {
                $planSearch->setStatus(0);
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
    $order_by = "title ASC";
    if ($order == 1) {
        $order_by = "title ASC";
    } else if ($order == 2) {
        $order_by = "title DESC";
    } else if ($order == 3) {
        $order_by = "created_at DESC";
    } else if ($order == 4) {
        $order_by = "created_at ASC";
    } else if ($order == 5) {
        $order_by = "date_end ASC";
    } else if ($order == 6) {
        $order_by = "date_end DESC";
    }

    $plans = $planSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $planCount = count($plans);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?></font>
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
            if ($planCount > 0) {
                for ($i = 0; $i < $planCount; $i++) {
                    $plan = new AccessPlan;
                    $plan = $plans[$i];
                    $date_start = new DateTime($plan->getDate_start());
                    $date_end = new DateTime($plan->getDate_end());
                    $statusText = "";
                    if ($plan->getStatus() === 1) {
                        $statusText = "Ativo";
                    } else {
                        $statusText = "Inativo";
                    }
                    $price = "";
                    $priceSearch = new AccessPlanPrice;
                    $priceSearch->setAccess_plan_id($plan->getId());
                    $priceSearch->setStatus(1);
                    $priceSearch->setCurrency_id($_SESSION['user_currency_id']);
                    $priceSearch = $priceSearch->getQuery(limit: 1);
                    if (count($priceSearch) > 0) {
                        $price = $translate->translateMonetary($priceSearch[0]->getPrice(), $_SESSION['user_currency'], $_SESSION['user_currency_locale']);
                    } else {
                        $price = $translate->translateMonetary($plan->getPrice(), $_SESSION['user_currency'], $_SESSION['user_currency_locale']);
                    }
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $plan->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $plan->getTitle() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $price . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_start->format("d/m/Y") . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_end->format("d/m/Y") . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $statusText . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/accessPlans/" . $plan->getId() . "'>"
                    . "<i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='7'>";
                echo "<center><b>" . $translate->translate('Nenhum plano de acesso encontrado!', $_SESSION['user_lang']) . "</b></center>";
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
