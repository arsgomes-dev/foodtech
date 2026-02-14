<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;

$translate = new Translate();

$config = new McConfig();
$occupationSearch = new DepartmentOccupation();
$occupations = new DepartmentOccupation();
$privilege_types = $_SESSION['user_type'];
if (in_array("department_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];
    $department = isset($_POST['code']) ? $_POST['code'] : "";
    $occupationSearch->setDepartment_id($department);
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $occupationSearch->setTitle($_POST['description']);
        }
    }
    $offset = 0;
    if (isset($page, $limit)) {
        $page = $page;
        $limitConfig = $limit;
    } else {
        $page = 1;
        $limitConfig = 10;
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
    $occupations = $occupationSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $occupationsCount = count($occupations);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Função', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($occupationsCount > 0) {
                $occupation = new DepartmentOccupation();
                for ($i = 0; $i < $occupationsCount; $i++) {
                    $occupation = $occupations[$i];
                    ?>
                    <tr>
                    <td style='vertical-align:middle !important'><font style='vertical-align: inherit;'><?php echo $occupation->getTitle(); ?></font></td>
                    <td style='vertical-align:middle !important'><a href='javascript:editOccupation(<?php echo $occupation->getId(); ?>, "<?php echo $occupation->getTitle(); ?>");'>
                    <i class='nav-icon fas fa-edit' title='<?php $translate->translate('Visualizar', $_SESSION['user_lang']) ?>'></i></a></td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhuma função encontrada!', $_SESSION['user_lang']) . "</b></center>";
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
