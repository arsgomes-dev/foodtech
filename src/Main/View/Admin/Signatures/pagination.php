<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Signature;

$translate = new Translate();
$signatureSearch = new Signature;
$customersSearch = new Customers;
$signatures = new Signature;
$privilege_types = $_SESSION['user_type'];
$start = [];
$end = [];
if (in_array("ticket_view", $privilege_types)) {
    ?>
    <div class="clearfix">
        <ul class="pagination pagination-sm float-right">
            <?php
            $page = $_POST['pag'];
            $limit = $_POST['limit'];
            if (isset($_POST['ord'])) {
                $order = $_POST['ord'];
            } else {
                $order_by = "A.created_at ASC";
                $order = 0;
            }
            if (!empty($_POST['start'])) {
                if ($_POST['start'] !== "" && $_POST['start'] !== null && $_POST['start'] !== "") {
                    $start['A.date_start'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['start'])));
                }
            }
            if (!empty($_POST['end'])) {
                if ($_POST['end'] !== "" && $_POST['end'] !== null && $_POST['end'] !== "") {
                    $end['A.date_start'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['end'])));
                }
            }
            if (!empty($_POST['closureStart'])) {
                if ($_POST['closureStart'] !== "" && $_POST['closureStart'] !== null && $_POST['closureStart'] !== "") {
                    $start['A.date_end'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['closureStart'])));
                }
            }
            if (!empty($_POST['closureEnd'])) {
                if ($_POST['closureEnd'] !== "" && $_POST['closureEnd'] !== null && $_POST['closureEnd'] !== "") {
                    $end['A.date_end'] = date('Y-m-d', strtotime(str_replace("/", "-", $_POST['closureEnd'])));
                }
            }
            if (!empty($_POST['description'])) {
                if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
                    $customersSearch->setName($_POST['description']);
                }
            }

            if (!empty($_POST['sts'])) {
                if ($_POST['sts'] !== "" && $_POST['sts'] !== null && $_POST['sts'] !== "") {
                    if ($_POST['sts'] === "4") {
                        $signatureSearch->setStatus(0);
                    } else {
                        $signatureSearch->setStatus($_POST['sts']);
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
            $order_by = "A.created_at ASC";
            if ($order === 1) {
                $order_by = "A.created_at ASC";
            } else if ($order == 2) {
                $order_by = "A.created_at DESC";
            } else if ($order == 3) {
                $order_by = "B.name ASC";
            } else if ($order == 4) {
                $order_by = "B.name DESC";
            } else if ($order == 5) {
                $order_by = "A.date_start ASC";
            } else if ($order == 6) {
                $order_by = "A.date_start DESC";
            } else if ($order == 7) {
                $order_by = "A.date_end ASC";
            } else if ($order == 8) {
                $order_by = "A.date_end DESC";
            }
            $signatures = $signatureSearch->getQuery(
                    classB: $customersSearch,
                    limit: $limit,
                    offset: $offset,
                    order: $order_by,
                    less_equal: $end,
                    greater_equal: $start
            );
            $total_registros = count($signatures);
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
            for ($i = $inicio;
                    $i <= $limite;
                    $i++) {
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
    
