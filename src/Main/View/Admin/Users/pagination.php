<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\User;

$translate = new Translate();
$page = 1;
$limitConfig = 20;
$userSearch = new User;
$users = new User;
$privilege_types = $_SESSION['user_type'];
if (in_array("user_view", $privilege_types)) {
    ?>
    <div class="clearfix">
        <ul class="pagination pagination-sm float-right">
            <?php
            $page = (isset($_POST['pag']) ? $_POST['pag'] : 1);
            $limit = (isset($_POST['limit']) ? $_POST['limit'] : 1);
            $order = (isset($_POST['ord']) ? $_POST['ord'] : 1);
            if (!empty($_POST['description'])) {
                if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
                    $userSearch->setName($_POST['description']);
                }
            }
            if (!empty($_POST['cpf'])) {
                if ($_POST['cpf'] !== "" && $_POST['cpf'] !== null && $_POST['cpf'] !== "") {
                    $userSearch->setCpf($_POST['cpf']);
                }
            }
            if (!empty($_POST['email'])) {
                if ($_POST['email'] !== "" && $_POST['email'] !== null && $_POST['email'] !== "") {
                    $userSearch->setEmail($_POST['email']);
                }
            }
            if (!empty($_POST['department'])) {
                if ($_POST['department'] !== "" && $_POST['department'] !== null && $_POST['department'] !== "") {
                    $userSearch->setDepartment_id($_POST['department']);
                }
            }
            if (isset($_POST['status'])) {
                if ($_POST['status'] !== "" && $_POST['status'] !== null && $_POST['status'] !== "") {
                    if ($_POST['status'] === "1") {
                        $userSearch->setStatus(1);
                    } else {
                        $userSearch->setStatus(0);
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
            $users = $userSearch->getQuery();
            $total_registros = count($users);
            $total_pages = Ceil($total_registros / $limitConfig);
            $total = $total_pages;
            $max_links = 6;
            $links_laterais = ceil($max_links / 2);
            $inicio = $page - $links_laterais;
            $limite = $page + $links_laterais;
            if ($page != 1 && $page > $max_links) {
                echo "<li class='page-item'><a class='page-link' href='javascript:pagination(1)'>«</a></li>";
            } else {
                echo "<li class='page-item disabled'><a class='page-link'a>«</a></li>";
            }
            for ($i = $inicio; $i <= $limite; $i++) {
                if ($i == $page) {
                    echo "<li class='page-item active'><a class='page-link' href='javascript:pagination(" . $i . ")'>" . $i . "</a></li>";
                } else {
                    if ($i >= 1 && $i <= $total) {
                        echo "<li class='page-item'><a class='page-link' href='javascript:pagination(" . $i . ")'>" . $i . "</a></li>";
                    }
                }
            }
            if ($page != $total && $total > $max_links) {
                echo "<li class='page-item'><a class='page-link' href='javascript:pagination(" . $total . ")'>»</a></li>";
            } else {
                echo "<li class='page-item disabled'><a class='page-link'>»</a></li>";
            }
            ?></ul>
    </div>
    <?php
}
?>
    
