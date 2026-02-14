<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Food;

$translate = new Translate();

$config = new McConfig();
$foodSearch = new Food;
$foods = new Food;
$privilege_types = $_SESSION['user_type'];
if (in_array("food_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];

    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $foodSearch->setDescription($_POST['description']);
        }
    }
    if (!empty($_POST['group'])) {
        if ($_POST['group'] !== "" && $_POST['group'] !== null && $_POST['group'] !== "") {
            $foodSearch->setFood_group_id($_POST['group']);
        }
    }
    if (!empty($_POST['brand'])) {
        if ($_POST['brand'] !== "" && $_POST['brand'] !== null && $_POST['brand'] !== "") {
            $foodSearch->setFood_brand_id($_POST['brand']);
        }
    }
    if (!empty($_POST['table'])) {
        if ($_POST['table'] !== "" && $_POST['table'] !== null && $_POST['table'] !== "") {
            $foodSearch->setFood_table_id($_POST['table']);
        }
    }
    if (!empty($_POST['status'])) {
        if ($_POST['status'] !== "" && $_POST['status'] !== null && $_POST['status'] !== "") {
            $foodSearch->setStatus($_POST['status']);
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
    $order_by = "description ASC";
    if ($order == 1) {
        $order_by = "description ASC";
    } else if ($order == 2) {
        $order_by = "description DESC";
    } else if ($order == 3) {
        $order_by = "created_at ASC";
    } else if ($order == 4) {
        $order_by = "created_at DESC";
    }
    $foods = $foodSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $foodsCount = count($foods);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Referência', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($foodsCount > 0) {
                $food = new Food();
                for ($i = 0; $i < $foodsCount; $i++) {
                    $food = $foods[$i];
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getDescription() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getKcal() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getGrammage_reference() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/foods/" . $food->getId() . "'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum alimento encontrado!', $_SESSION['user_lang']) . "</b></center>";
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
