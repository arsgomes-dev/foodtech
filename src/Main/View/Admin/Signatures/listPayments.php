<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Admin\PaymentStatus;
use Microfw\Src\Main\Common\Entity\Admin\Currency;

$translate = new Translate();

$config = new McConfig();
$signaturePaymentSearch = new SignaturePayment;
$signaturesPayment = new SignaturePayment;
$privilege_types = $_SESSION['user_type'];
if (in_array("customer_signatures", $privilege_types)) {
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
    
    $signaturePaymentSearch->setSignature_id($signatureSearchGcid->getId());
    $order_by = "created_at ASC";
    $signaturesPayment = $signaturePaymentSearch->getQuery(limit: $limit, offset: $offset, order: $order_by);
    $signaturesPaymentCount = count($signaturesPayment);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <font style="vertical-align: inherit;">#</font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Desconto', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data do Faturamento', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Vencimento', $_SESSION['user_lang']); ?></font>
                </th>
                <th>
                    <font style="vertical-align: inherit;"><?php echo $translate->translate('Data de Pagamento', $_SESSION['user_lang']); ?></font>
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
            if ($signaturesPaymentCount > 0) {
                $signaturePayment = new SignaturePayment();
                for ($i = 0; $i < $signaturesPaymentCount; $i++) {
                    $signaturePayment = $signaturesPayment[$i];
                    $paymentStatus = new PaymentStatus;
                    $paymentStatus = $paymentStatus->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getPayment_status_id()]]);
                    $signatureSearch = new Signature;
                    $signature = new Signature;
                    $signature = $signatureSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturePayment->getSignature_id()]]);
                    $currency = new Currency;
                    $currencySearch = new Currency;
                    $currency = $currencySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

                    $price = $translate->translateMonetary($signature->getPrice(), $currency->getCurrency(), $currency->getLocale());
                    $date_billing = "";
                    if ($signaturePayment->getDate_billing() !== null && $signaturePayment->getDate_billing() !== "") {
                        $date_billing = (new DateTime($signaturePayment->getDate_billing()))->format("d/m/Y");
                    }
                    $date_due = "";
                    if ($signaturePayment->getDate_due() !== null && $signaturePayment->getDate_due() !== "") {
                        $date_due = (new DateTime($signaturePayment->getDate_due()))->format("d/m/Y");
                    }
                    $date_payment = "";
                    if ($signaturePayment->getDate_payment() !== null && $signaturePayment->getDate_payment() !== "") {
                        $date_payment = (new DateTime($signaturePayment->getDate_payment()))->format("d/m/Y");
                    }
                    
                    $discount = "";
                    if ($signature->getDiscount() !== null && $signature->getDiscount() !== "") {
                    $discount = number_format($signature->getDiscount(), 2, ',', '.') . '%';
                    }
                    $status_title = ($paymentStatus) ? $paymentStatus->getTitle() : "";
                    echo "<tr>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $signaturePayment->getId() . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $price . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $discount. "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_billing . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_due . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>" . $date_payment . "</font></td>";
                    echo "<td style='vertical-align:middle !important'><font style='vertical-align: inherit;'>";
                    echo $status_title ;
                    echo "</font></td>";
                    echo "<td style='vertical-align:middle !important'><a href='#' onclick='loadPayment(" . '"' . $signaturePayment->getGcid() . '"' . ")' ><i class='nav-icon fas fa-eye' title='" . $translate->translate('Visualizar', $_SESSION['user_lang']) . "'></i></a></td>";
                }
            } else {
                echo "<tr>";
                echo "<td colspan='8'>";
                echo "<center><b>" . $translate->translate('Nenhum pagamento encontrado!', $_SESSION['user_lang']) . "</b></center>";
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
