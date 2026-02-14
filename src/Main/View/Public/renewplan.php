<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;
use Microfw\Src\Main\Common\Entity\Public\Signature;
use Microfw\Src\Main\Common\Entity\Public\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Public\AccessPlansCoupon;
use Microfw\Src\Main\Common\Entity\Public\Currency;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;
use Microfw\Src\Main\Common\Entity\Public\SignatureTerms;

$language = new Language;
$translate = new Translate();
$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$planService = new CheckPlan;
$check = $planService->checkPlan();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>    
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <link rel='stylesheet' href='/assets/css/home.css'>

        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">
        <input type="hidden" id="x_y" value="<?php echo (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12); ?>">
        <input type="hidden" id="efiCode" value="<?php echo (env('EFI_PAYEE_CODE')); ?>">
        <input type="hidden" id="efiEnvironment" value="<?php echo (env('EFI_ENVIRONMENT')); ?>">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("home");
            ?>
            <div class="content-wrapper" style="min-height: auto !important; margin-bottom: 20px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    $workspaceDash = htmlspecialchars($translate->translate('Renovar Plano', $_SESSION['client_lang']));
                    echo $baseHtml->baseBreadcrumb($workspaceDash, $directory, "Renovar Plano");
                    ?>  
                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                    <!-- end base html breadcrumb -->
                    <?php if (!$check['allowed'] && $check['plan_active'] && !$check['plan_payment'] && !$check['plan_expired']) { ?>
                        <script>
                            window.location.href = "<?php echo $config->getDomain() . '/' . $config->getUrlPublic(); ?>/paymentplan";
                        </script>
                    <?php } else if (!$check['allowed'] && !$check['plan_active'] && !$check['plan_expired'] && !$check['plan_expired']) { ?>
                        <script>
                            window.location.href = "<?php echo $config->getDomain() . '/' . $config->getUrlPublic(); ?>/subscribe";
                        </script>
                        <?php
                    } else if ($check['allowed'] && $check['plan_active'] && $check['plan_payment'] && !$check['plan_expired']) {
                        ?>
                        <script>
                            window.location.href = "<?php echo $config->getDomain() . '/' . $config->getUrlPublic(); ?>";
                        </script>
                        <?php
                    }
                    $signature = new Signature();
                    $signature = $signature->getQuery(single: true, customWhere:
                            [['column' => 'customer_id', 'value' => $_SESSION['client_id']],
                                ['column' => 'status', 'value' => 1]], whereNull: ['date_end'],
                            order: "created_at DESC");

                    $signaturePayments = new SignaturePayment;
                    $signaturePayments = $signaturePayments->getQuery(limit: 1, customWhere: [
                        ['column' => 'signature_id', 'value' => $signature->getId()]], whereNot: ['date_payment' => null], order: "date_due DESC");

                    $countSignaturePayments = count($signaturePayments);

                    $signaturePayment = new SignaturePayment;

                    if ($countSignaturePayments > 0 && !$check['plan_expired']) {
                        ?>
                        <script>
                            window.location.href = "<?php echo $config->getDomain() . '/' . $config->getUrlPublic(); ?>";
                        </script>
                        <?php
                    }

                    $signaturePayments = new SignaturePayment;
                    $signaturePayments = $signaturePayments->getQuery(limit: 1, customWhere: [
                        ['column' => 'signature_id', 'value' => $signature->getId()]], whereNull: ['date_payment'], order: "date_due DESC");
                    $signaturePayment = $signaturePayments[0];

                    $date_due = "";
                    if ($signature->getDate_renovation() !== null && $signature->getDate_renovation() !== "") {
                        $date_due = (new DateTime($signature->getDate_renovation()))->format("d/m/Y");
                    }

                    $discount_code = "";

                    if ($signature->getDiscount() !== null && $signature->getDiscount() !== "" && $signature->getAccess_plan_coupon_id() !== null && $signature->getAccess_plan_coupon_id() !== "" && $signature->getAccess_plan_coupon_id() > 0) {
                        $coupon = new AccessPlansCoupon;
                        $coupon = $coupon->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_coupon_id()]]);
                        if ($coupon !== null) {
                            if ($coupon->getCoupon() !== null && $coupon->getCoupon() !== "") {
                                $discount_code = $coupon->getCoupon();
                            }
                        }
                    }

                    $discount = "";
                    if ($signature->getDiscount() !== null && $signature->getDiscount() !== "") {
                        $discount = number_format($signature->getDiscount(), 2, ',', '.') . '%';
                    }
                    $currency = new Currency;
                    $currencySearch = new Currency;
                    $currency = $currencySearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

                    $price = $translate->translateMonetary($signature->getPrice(), $currency->getCurrency(), $currency->getLocale());

                    
                    $discount_parcelas = 0;
                    
                                       
                    
                    
                    $price_mensal_real = "";
                    $price_anual_real = "";

                    $price_mensal = "";
                    $price_anual = "";
                    if ($signature->getRenewal_cycle() === "anual") {
                        
                        $parcelasDescontos = 0;
                        $price_total = ($signature->getPrice() / (int) (env('PAG_CYCLE_ANUAL_X_PRICE')) ?? 12);
                        if ((int) env('PAG_CYCLE_ANUAL_X_PRICE') > 0) {
                            $parcelasDescontos = (int) env('PAG_CYCLE_ANUAL_X_PRICE');
                        }

                        $price_mensal_real = $translate->translateMonetary(
                                $price_total,
                                $currency->getCurrency(),
                                $currency->getLocale()
                        );
                        if ($parcelasDescontos > 0) {
                            $price_mensal = $signature->getPrice() / $parcelasDescontos;
                        } else {
                            $price_mensal = $signature->getPrice() / 12;
                        }
                        $price_anual_real = $price;
                        $price_anual = $signature->getPrice();
                    } else {
                        $price_mensal = $signature->getPrice();
                        $price_mensal_real = $price;
                    }

// 1. Define o valor inicial como o preço cheio



                    $final_amount = $signature->getPrice();

// 2. Só aplica a matemática se o desconto não for NULL e for maior que zero
                    if ($signature->getDiscount() !== null && $signature->getDiscount() > 0) {
                        $discount_val = $signature->getDiscount();
                        $final_amount = $signature->getPrice() - ($signature->getPrice() * ($discount_val / 100));
                    }

// 3. Gera a string formatada (R$ XX,XX) usando o valor calculado acima
                    $total_price = $translate->translateMonetary(
                            $final_amount,
                            $currency->getCurrency(),
                            $currency->getLocale()
                    );

                    $accessPlan = new AccessPlan;
                    $accessPlan = $accessPlan->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_id()]]);
                    $items = array_filter(array_map('trim', explode(';', $accessPlan->getDescription())));

                    $coupon = new AccessPlansCoupon;
                    $coupon = $coupon->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getAccess_plan_coupon_id()]]);
                    ?>


                    <div class="container">
                        <div class="card card-orange card-custom">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <?= $translate->translate('Assinatura vencida', $_SESSION['client_lang']); ?>
                                </h3>
                            </div>
                            <div class="card-body">
                                <h4><?= $translate->translate('Plano', $_SESSION['client_lang']) . ": " . htmlspecialchars($accessPlan->getTitle()) ?></h4>
                                <ul class="list-unstyled mt-3">
                                    <?php foreach ($items as $item): ?>
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success mr-2"></i>
                                            <?= htmlspecialchars($item) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <hr>


                                <table class="table table-sm table-borderless mt-3">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted"><strong><?= $translate->translate('Data de Vencimento', $_SESSION['client_lang']); ?>:</strong></td>
                                            <td class="text-right"><?= htmlspecialchars($date_due) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted"><strong><?= $translate->translate('Ciclo de pagamento atual', $_SESSION['client_lang']); ?>:</strong></td>
                                            <td class="text-right"><?php
                                    echo htmlspecialchars(mb_ucfirst($signature->getRenewal_cycle()));
                                    if ((int) env('PAG_CYCLE_ANUAL_X_PRICE') > 0) {
                                        echo " - " .
                                        $translate->translate('Com desconto de', $_SESSION['client_lang']) . " " .
                                        (12 - (int) env('PAG_CYCLE_ANUAL_X_PRICE')) . " " .
                                        $translate->translate('parcela(s)', $_SESSION['client_lang']) .
                                        "";
                                    }
                                    ?>
                                            </td>
                                        </tr>
                                        <?php if ($signature->getRenewal_cycle() === "anual") { ?>
                                            <tr>
                                                <td class="text-muted"><strong><?= $translate->translate('Valor Mensal', $_SESSION['client_lang']); ?>:</strong></td>
                                                <td class="text-right"><?= htmlspecialchars($price_mensal_real); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($discount_code !== "") { ?>
                                            <tr>
                                                <td class="text-muted"><strong><?= $translate->translate('Valor sem Desconto', $_SESSION['client_lang']); ?>:</strong></td>
                                                <td class="text-right"><?= htmlspecialchars($price); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted"><strong><?= $translate->translate('Cupom de Desconto', $_SESSION['client_lang']); ?>:</strong></td>
                                                <td class="text-right"><?= htmlspecialchars($discount_code) . " (" . $discount . ")"; ?></td>
                                            </tr>
                                        <?php } ?>
                                        <tr>
                                            <td class="text-muted"><strong><?= $translate->translate('Valor Total', $_SESSION['client_lang']); ?>:</strong></td>
                                            <td class="text-right"><?= htmlspecialchars($total_price) ?></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-4 d-flex gap-2">
                                    <button class="btn btn-success" id="btnRenew" data-bs-toggle="modal" data-bs-target="#renewModal">
                                        <i class="fas fa-sync"></i> <?= $translate->translate('Renovar Assinatura', $_SESSION['client_lang']); ?>
                                    </button>

                                    <button class="btn btn-danger" id="btnCancel">
                                        <i class="fas fa-times"></i> <?= $translate->translate('Cancelar Assinatura', $_SESSION['client_lang']); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="renewModal" tabindex="-1" role="dialog" aria-labelledby="renewModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <form id="renewForm">
                                <input type="hidden" id="signature" value="<?= $signature->getGcid(); ?>">
                                <input type="hidden" id="signaturePayment" value="<?= $signaturePayment->getGcid(); ?>">
                                <input type="hidden" id="rawPriceMonthly" value="<?= $price_mensal; ?>">
                                <input type="hidden" id="rawPriceAnnual" value="<?= ($price_anual); ?>">
                                <input type="hidden" id="rawCycle" value="<?= $signature->getRenewal_cycle(); ?>">
                                <input type="hidden" id="rawDicountCode" value="<?= $discount_code; ?>">

                                <input type="hidden" id="discountPercent" value="0">
                                <input type="hidden" id="appliedCouponCode" value="">

                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="close position-absolute" data-bs-dismiss="modal" aria-label="Close" title="<?= $translate->translate('Cancelar', $_SESSION['client_lang']); ?>" style="right: 15px; top: 15px; z-index: 10;">
                                            <span>&times;</span>
                                        </button>

                                        <div class="row no-gutters" style="padding: 20px;">

                                            <div class="col-12 col-lg-6 payment-form-area pr-lg-4">
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <h5 style="font-weight: bold;">
                                                        <i class="fas fa-wallet text-primary"></i> <?= $translate->translate('Pagamento', $_SESSION['client_lang']); ?>
                                                    </h5> 
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label class="form-label"><?= $translate->translate('Ciclo de Pagamento', $_SESSION['client_lang']); ?></label>
                                                            <select name="cycle" id="inputCycle" class="form-control custom-input" required>
                                                                <option value="mensal" selected><?= $translate->translate('Mensal', $_SESSION['client_lang']); ?></option>
                                                                <option value="anual"><?= $translate->translate('Anual', $_SESSION['client_lang']); ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label"><?= $translate->translate('Número do Cartão', $_SESSION['client_lang']); ?></label>
                                                    <div class="input-group">
                                                        <input type="text" name="card_number" id="inputCardNumber" class="form-control custom-input" maxlength="16" placeholder="0000 0000 0000 0000" onkeydown="identifyBrand();" onblur="identifyBrand();" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text border-0 form-span"><i class="fas fa-check-circle"></i></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="form-label"><?= $translate->translate('Nome do Titular', $_SESSION['client_lang']); ?></label>
                                                    <input type="text" name="card_holder" id="inputCardName" class="form-control custom-input" maxlength="25" placeholder="<?= $translate->translate('Como no cartão', $_SESSION['client_lang']); ?>" required>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <label class="form-label"><?= $translate->translate('Validade', $_SESSION['client_lang']); ?></label>
                                                        <div class="d-flex">
                                                            <select name="exp_month" id="inputExpMonth" class="form-control custom-input mr-2" required>
                                                                <option value=""><?= $translate->translate('Mês', $_SESSION['client_lang']); ?></option>
                                                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                                                    <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>"><?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                            <select name="exp_year" id="inputExpYear" class="form-control custom-input" required>
                                                                <option value=""><?= $translate->translate('Ano', $_SESSION['client_lang']); ?></option>
                                                                <?php
                                                                $year = (int) date('Y');
                                                                for ($y = $year; $y <= 2050; $y++):
                                                                    ?>
                                                                    <option value="<?= $y ?>"><?= $y ?></option>
                                                                <?php endfor; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">CVV</label>
                                                        <input type="text" name="cvv" id="inputCardCVV" class="form-control custom-input" maxlength="4" placeholder="123" required>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label"><?= $translate->translate('CPF do Titular', $_SESSION['client_lang']); ?></label>
                                                        <input type="text" id="inputCardCPF" name="inputCardCPF" oninput="cpfFormat(this)" class="form-control custom-input" placeholder="<?= $translate->translate('Apenas números', $_SESSION['client_lang']); ?>" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label"><?= $translate->translate('Bandeira', $_SESSION['client_lang']); ?></label>
                                                        <select name="card_brand" id="inputCardBrand" class="form-control custom-input" required>
                                                            <option value=""><?= $translate->translate('Selecione', $_SESSION['client_lang']); ?></option>
                                                            <option value="visa">Visa</option>
                                                            <option value="mastercard">Mastercard</option>
                                                            <option value="elo">Elo</option>
                                                            <option value="amex">Amex</option>
                                                            <option value="hipercard">Hipercard</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label"><?= $translate->translate('Parcelamento', $_SESSION['client_lang']); ?></label>
                                                        <select name="installments" id="inputInstallments" class="form-control custom-input" <?php ($signature->getRenewal_cycle() === "mensal") ? "disabled" : ""; ?>>
                                                            <option value="1">1x (À vista)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="renewal-container">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <div class="custom-control custom-switch custom-switch-lg">
                                                            <input type="checkbox" class="custom-control-input" id="autoRenewSwitch" name="auto_renew">
                                                            <label class="custom-control-label font-weight-bold" for="autoRenewSwitch">
                                                                <?= $translate->translate('Renovação Automática', $_SESSION['client_lang']); ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="terms-link-area">
                                                        <small><?= $translate->translate('Ao ativar, você concorda com os', $_SESSION['client_lang']); ?> 
                                                            <a href="#" data-toggle="modal" data-target="#termsModal"><?= $translate->translate('Termos de Renovação', $_SESSION['client_lang']); ?></a>.
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <button type="button" class="btn-pay btn-default btn-register" onclick="generatePayment();">
                                                        <?= $translate->translate('Pagar Agora', $_SESSION['client_lang']); ?>
                                                    </button>
                                                    <button type="button" class="btn-pay-cancel btn-default btn-cancel" data-bs-dismiss="modal" aria-label="Close" >
                                                        <?= $translate->translate('Cancelar', $_SESSION['client_lang']); ?>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-12 col-lg-6 payment-summary-area d-flex flex-column align-items-center pl-lg-4">
                                                <div class="mt-5">
                                                    <div class="card-visual">
                                                        <div class="card-header-row">
                                                            <div class="card-chip"></div>
                                                            <div class="wifi-symbol"><i class="fas fa-wifi"></i></div>
                                                        </div>
                                                        <div class="card-number-display" id="cardVisualNumber">•••• •••• •••• ••••</div>
                                                        <div class="card-details-display">
                                                            <div>
                                                                <small><?= $translate->translate('Titular', $_SESSION['client_lang']); ?></small>
                                                                <span id="cardVisualName"><?= $translate->translate('NOME DO CLIENTE', $_SESSION['client_lang']); ?></span>
                                                            </div>
                                                            <div class="card-footer-row">
                                                                <div><span id="cardVisualExp">MM/AA</span></div>
                                                                <div><i class="fas fa-credit-card fa-2x" id="cardVisualBrand"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4" style="width: 100%; padding: 0 15px;">
                                                    <div class="order-row">
                                                        <span><?= $translate->translate('Cód Pedido', $_SESSION['client_lang']); ?></span><br>
                                                        <span class="font-weight-bold text-dark"><?= htmlspecialchars($signaturePayment->getGcid()) ?></span>
                                                    </div>
                                                    <div class="order-row d-flex justify-content-between">
                                                        <span><?= $translate->translate('Plano', $_SESSION['client_lang']); ?></span>
                                                        <span class="font-weight-bold text-dark ml-1"><?= htmlspecialchars($accessPlan->getTitle()); ?></span>
                                                    </div>

                                                    <hr>

                                                    <div class="form-group mb-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-2 mt-3">
                                                            <label class="mb-0 small text-muted font-weight-bold text-nowrap mr-2" style="font-size: 0.7rem;">
                                                                CUPOM DE DESCONTO
                                                            </label>
                                                            <div class="input-group" style="max-width: 180px;"> 
                                                                <input type="text" class="form-control custom-input text-uppercase" id="inputCouponCode" placeholder="CÓDIGO" style="height: 30px; font-size: 0.8rem;">
                                                                <div class="input-group-append">
                                                                    <button class="btn btn-default btn-register" type="button" id="btnApplyCoupon" style="height: 30px; font-size: 0.75rem; padding: 0 10px;">
                                                                        Aplicar
                                                                    </button>
                                                                    <button class="btn btn-default btn-cancel" type="button" id="btnRemoveCoupon" style="height: 30px; font-size: 0.75rem; padding: 0 10px; display: none;">
                                                                        Remover
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="couponFeedback" class="small text-right font-weight-bold" style="margin-top: -5px; margin-bottom: 5px;"></div>

                                                        <div id="rowDiscountAmount" class="justify-content-between mt-2 text-success" style="display: none;">
                                                            <small>Desconto aplicado</small>
                                                            <small id="displayDiscountAmount">- R$ 0,00</small>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span style="font-size: 1.1rem;"><?= $translate->translate('Valor Total', $_SESSION['client_lang']); ?></span>
                                                        <span id="displayTotalPrice" class="font-weight-bold text-success" style="font-size: 1.5rem;">
                                                            <?= htmlspecialchars($total_price) ?>
                                                        </span>
                                                    </div>
                                                    <div class="text-right mt-2">
                                                        <i class="fas fa-receipt text-muted"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                            </form>
                        </div>
                    </div>
                    <?php
                    $signaturesTerms = new SignatureTerms();
                    $signaturesTerms = $signaturesTerms->getQuery(single: true, customWhere: [['column' => 'type', 'value' => "terms_automatic_renewal"], ['column' => 'status', 'value' => 1]], order: "created_at DESC");
                    ?>
                    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title font-weight-bold"><?= $translate->translate($signaturesTerms->getTitle(), $_SESSION['client_lang']); ?></h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <input type="hidden" name="terms" id="terms" value="<?= $signaturesTerms->getId(); ?>">
                                <div class="modal-body text-justify" style="font-size: 14px; line-height: 1.6;">
                                    <?= $signaturesTerms->getTerm(); ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= $translate->translate('Fechar', $_SESSION['client_lang']); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php");
                ?>
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>        
        <script>
                                                        msgRenewCancel = "<?= $translate->translate('Selecione a assinatura que deseja cancelar!', $_SESSION['client_lang']); ?>";
                                                        msgCardInvalid = "<?= $translate->translate('Cartão inválido!', $_SESSION['client_lang']); ?>";
                                                        msgCardErrorPayment = "<?= $translate->translate('O processamento do pagamento falhou. Por favor, tente novamente.', $_SESSION['client_lang']); ?>";
        </script>
        <script src="/assets/vendor/format/cpfFormat.min.js"></script>
        <script src="/assets/vendor/efi/payment-token-efi-umd.min.js"></script> 
        <script src="/assets/js/renewPlan/renew.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>