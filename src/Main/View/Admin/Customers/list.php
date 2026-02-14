<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;

$translate = new Translate();

$config = new McConfig();
$customerSearch = new Customers;
$customers = new Customers;
$privilege_types = $_SESSION['user_type'];
if (in_array("customer_view", $privilege_types)) {
    $page = $_POST['pag'];
    $limit = $_POST['limit'];
    $order = (isset($_POST['ord']) ? $_POST['ord'] : 3);
    
    if (!empty($_POST['description'])) {
        if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
            $customerSearch->setName($_POST['description']);
        }
    }
    if (!empty($_POST['cpf'])) {
        if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
            $customerSearch->setCpf($_POST['cpf']);
        }
    }
    if (!empty($_POST['email'])) {
        if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
            $customerSearch->setEmail($_POST['email']);
        }
    }
    if (isset($_POST['status'])) {
        if ($_POST['status'] !== "" && $_POST['status'] !== null && $_POST['status'] !== "") {
            if ($_POST['status'] === "1") {
                $customerSearch->setStatus(1);
            } else {
                $customerSearch->setStatus(0);
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
    $order_by = "name ASC";
    if ($order == 1) {
        $order_by = "name ASC";
    } else if ($order == 2) {
        $order_by = "name DESC";
    } else if ($order == 3) {
        $order_by = "created_at ASC";
    } else if ($order == 4) {
        $order_by = "created_at DESC";
    }
    $customers = $customerSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $customersCount = count($customers);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Nome', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('E-mail', $_SESSION['user_lang']); ?></font>
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
            if ($customersCount > 0) {
                $customer = new Customers();
                for ($i = 0; $i < $customersCount; $i++) {
                    $customer = $customers[$i];
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $customer->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $customer->getName() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $customer->getEmail() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";
                    echo ($customer->getStatus() === 1) ? "Ativo" : (($customer->getStatus() === 2) ? "Bloqueado" : "Inativo");
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/customer/" . $customer->getGcid() . "'><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='6'>";
                echo "<center><b>" . $translate->translate('Nenhum cliente encontrado!', $_SESSION['user_lang']) . "</b></center>";
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
