<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Currency;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlanPrice;

$translate = new Translate();

$config = new McConfig();
$planSearch = new AccessPlanPrice;
$plans = new AccessPlanPrice;
$privilege_types = $_SESSION['user_type'];
if (in_array("access_plans_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $code = $_POST['code'];
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
    $order_by = "created_at DESC";
    $planSearch->setAccess_plan_id($code);
    $plans = $planSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $plansCount = count($plans);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Moeda', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?></font>
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
            if ($plansCount > 0) {
                $plan = new AccessPlanPrice;
                for ($i = 0; $i < $plansCount; $i++) {
                    $plan = $plans[$i];
                    $currency = new Currency;
                    $currency = $currency->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $plan->getCurrency_id()]]);
                    $price = $translate->translateMonetary($plan->getPrice(), $currency->getCurrency(), $currency->getLocale());
                    $date_start = $translate->translateDate($plan->getDate_start(), $_SESSION['user_lang']);
                    $date_end = $translate->translateDate($plan->getDate_end(), $_SESSION['user_lang']);
                    $status = ($plan->getStatus() === "1") ? "Ativo" : "Inativo";
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $currency->getCurrency() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $price . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $status . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='#' onclick='editPrice(" . $plan->getId() . ',' . $plan->getCurrency_id() . ',"' . $price . '","' . $date_start . '","' . $date_end . '",' . $plan->getStatus() . ")' >"
                    . "<i class='nav-icon fas fa-edit' title='" . $translate->translate('Editar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhuma moeda encontrada!', $_SESSION['user_lang']) . "</b></center>";
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
