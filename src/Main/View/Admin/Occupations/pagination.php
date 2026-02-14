<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;

$translate = new Translate();
$page = 1;
$limitConfig = 20;
$occupationSearch = new DepartmentOccupation;
$occupations = new DepartmentOccupation();
$privilege_types = $_SESSION['user_type'];
if (in_array("department_view", $privilege_types)) {
    ?>
    <div class="clearfix">
        <ul class="pagination pagination-sm float-right">
            <?php
            $page = (isset($_POST['pag'])?$_POST['pag'] : 1);
            $limit = (isset($_POST['limit'])?$_POST['limit'] : 1);
            $order = (isset($_POST['ord'])?$_POST['ord'] : 1);
            $department = isset($_POST['code'])?$_POST['code']:"";
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
                $limitConfig = 5;
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
            $occupations = $occupationSearch->getQuery();
            $total_registros = count($occupations);
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
    
