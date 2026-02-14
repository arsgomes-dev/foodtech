<?php
session_start();

use Microfw\Src\Main\Business\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\McConfig;
use Microfw\Src\Main\Common\Entity\FoodGroup;
use Microfw\Src\Main\Functions\Translate;

$translate = new Translate();

$config = new McConfig();
$foodSearch = new FoodGroup;
$foods = new FoodGroup;
$privilege_types = $_SESSION['user_type'];
if (in_array("food_group_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];

    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $foodSearch->setDescription($_POST['description']);
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
    $foods = $foodSearch->getAll($limit, $offset, $order_by);
    $foodsCount = count($foods);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Grupo', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($foodsCount > 0) {
                $food = new FoodGroup();
                for ($i = 0; $i < $foodsCount; $i++) {
                    $food = $foods[$i];
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $food->getDescription() . "</font></td>";
                    ?>
                          <td style='vertical-align:middle !important'><a href='javascript:group_edit(<?php echo $food->getId(); ?>, "<?php echo $food->getDescription(); ?>");'>
                          <i class='nav-icon fas fa-pencil' title='<?php $translate->translate('Visualizar', $_SESSION['user_lang']) ?>'></i></a></td>
                <?php
                    echo "</tr>";
            }
        } else {
                    echo "<tr>";
                    echo "<td colspan='6'>";
                    echo "<center><b>" . $translate->translate(''
                            . '', $_SESSION['user_lang']) . "</b></center>";
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
