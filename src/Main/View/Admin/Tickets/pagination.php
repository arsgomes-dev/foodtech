<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;

$translate = new Translate();
$ticketSearch = new Ticket;
$customersSearch = new Customers;
$tickets = new Ticket;
$privilege_types = $_SESSION['user_type'];
if (in_array("ticket_view", $privilege_types)) {
    ?>
    <div class="clearfix">
        <ul class="pagination pagination-sm float-right">
            <?php
            $page = $_POST['pag'];
            $limit = $_POST['limit'];
            $order = $_POST['ord'];
            if (!empty($_POST['description'])) {
                if ($_POST['description'] !== "" && $_POST['description'] !== null && $_POST['description'] !== "") {
                    $customersSearch->setName($_POST['description']);
                }
            }
            if (!empty($_POST['department'])) {
                if ($_POST['department'] !== "" && $_POST['department'] !== null && $_POST['department'] !== "") {
                    $ticketSearch->setTicket_department_subdepartment_id($_POST['department']);
                }
            }
            if (!empty($_POST['priority'])) {
                if ($_POST['priority'] !== "" && $_POST['priority'] !== null && $_POST['priority'] !== "") {
                    $ticketSearch->setLevel($_POST['priority']);
                }
            }
            if (!empty($_POST['sts'])) {
                if ($_POST['sts'] !== "" && $_POST['sts'] !== null && $_POST['sts'] !== "") {
                    $sts = "";
                    if ($_POST['sts'] === "2") {
                        $sts = 2;
                    } else {
                        $sts = 1;
                    }
                    $ticketSearch->setStatus($sts);
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
            $order_by = "B.name ASC";
            if ($order === 1) {
                $order_by = "A.level DESC";
            } else if ($order === 2) {
                $order_by = "A.level ASC";
            } else if ($order == 3) {
                $order_by = "A.created_at ASC";
            } else if ($order == 4) {
                $order_by = "A.created_at DESC";
            } else if ($order == 5) {
                $order_by = "B.name ASC";
            } else if ($order == 6) {
                $order_by = "B.name DESC";
            } else if ($order == 7) {
                $order_by = "A.response ASC";
            } else if ($order == 8) {
                $order_by = "A.response DESC";
            } else if ($order == 9) {
                $order_by = "A.message_reading_status DESC";
            }
            $tickets = $ticketSearch->getQuery(
                    classB: $customersSearch,
                    limit: $limit,
                    offset: $offset,
                    order: $order_by
            );
            $total_registros = count($tickets);
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
    
