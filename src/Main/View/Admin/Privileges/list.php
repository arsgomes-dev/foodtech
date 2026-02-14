<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Privilege;

$translate = new Translate();

$config = new McConfig();
$privilegeSearch = new Privilege;
$privileges = new Privilege;
$privilege_types = $_SESSION['user_type'];
if (in_array("privileges_configuration", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = $_POST['ord'];
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $privilegeSearch->setDescription($_POST['description']);
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
        $order_by = "created_at DESC";
    } else if ($order == 4) {
        $order_by = "created_at ASC";
    }
    $privileges = $privilegeSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $privilegesCount = count($privileges);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Privilégio', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"></font>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($privilegesCount > 0) {
                $privilege = new Privilege;
                for ($i = 0; $i < $privilegesCount; $i++) {
                    $privilege = $privileges[$i];
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $privilege->getDescription() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/privilege/" . $privilege->getId() . "'>"
                    . "<i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum privilégio encontrado', $_SESSION['user_lang']) . "!</b></center>";
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
