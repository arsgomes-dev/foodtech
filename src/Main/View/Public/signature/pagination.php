<?php

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

session_start();

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\Signature;

$config = new McClientConfig();

$translate = new Translate();
$signatureSearch = new SignaturePayment;
$signatures = new SignaturePayment;
?>
<div class="clearfix">
    <ul class="pagination pagination-sm float-right">
        <?php
        $page = $_POST['pag'];
        $limit = $_POST['limit'];
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
        $signatureSearchGcid = new Signature();
        $signatureSearchGcid = $signatureSearchGcid->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);

        $signatureSearch->setSignature_id($signatureSearchGcid->getId());
        $order_by = "created_at ASC";
        $signatures = $signatureSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
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