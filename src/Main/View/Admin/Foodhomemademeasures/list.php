<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\FoodHomemadeMeasure;

$translate = new Translate();

$config = new McConfig();
$foodSearch = new FoodHomemadeMeasure;
$foods = new FoodHomemadeMeasure;
$privilege_types = $_SESSION['user_type'];
if (in_array("food_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];
    if (!empty($_POST['code']) && $_POST['code'] !== "" && $_POST['code'] !== null && $_POST['code'] !== "") {

        $foodSearch->setFood_id($_POST['code']);
        if (!empty($_POST['description'])) {
            if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
                $foodSearch->setMeasure($_POST['description']);
            }
        }
        $offset = 0;
        if (isset($page, $limit)) {
            $page = $page;
            $limitConfig = $limit;
        } else {
            $page = 1;
            $limitConfig = 5;
        }
        if ($page == 1) {
            $offset = 0;
        } else {
            $offset = ($limitConfig * $page) - $limitConfig;
        }
        $order_by = "measure ASC";
        if ($order == 1) {
            $order_by = "measure ASC";
        } else if ($order == 2) {
            $order_by = "measure DESC";
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
                        <font style="vertical-align: inherit;"><?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?></font>
                    </th>
                    <th>
                        <font style="vertical-align: inherit;"><?php echo $translate->translate('Quantidade (g/ml)', $_SESSION['user_lang']); ?></font>
                    </th>
                    <th>
                        <font style="vertical-align: inherit;"></font>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($foodsCount > 0) {
                    $food = new FoodHomemadeMeasure();
                    for ($i = 0; $i < $foodsCount; $i++) {
                        $food = $foods[$i];
                        echo "<tr>";
                        echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getMeasure() . "</font></td>";
                        echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getGrammage() . "</font></td>";
                        ?>
                    <td style='vertical-align:middle !important'><a href='javascript:measure_edit(<?php echo $food->getId(); ?>, "<?php echo $food->getMeasure(); ?>", "<?php echo ($food->getGrammage() !== null) ? str_replace(".", ",", $food->getGrammage()) : ""; ?>");'>
                            <i class='nav-icon fas fa-pencil' title='<?php $translate->translate('Visualizar', $_SESSION['user_lang']) ?>'></i></a></td>
                    <?php
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum medida caseira encontrada!', $_SESSION['user_lang']) . "</b></center>";
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
                    echo $translate->translate('Selecione um alimento!', $_SESSION['user_lang']);
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
