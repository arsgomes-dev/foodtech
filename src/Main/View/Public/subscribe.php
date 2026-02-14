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

$plans = new AccessPlan;
$plans = $plans->getQuery(customWhere: [['column' => 'status', 'value' => 1]], order: 'price ASC');
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-theme="dark">

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
            <div class="content-wrapper" style="height: 100% !important; margin-bottom: 20px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    $workspaceDash = htmlspecialchars($translate->translate('Assinar um Plano', $_SESSION['client_lang']));
                    echo $baseHtml->baseBreadcrumb($workspaceDash, $directory, "Planos de Assinatura");
                    ?>  
                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                    <!-- end base html breadcrumb -->


                    <section class="content">
                        <div class="container-fluid">

                            <?php if ($check['plan_active']): ?>
                                <div class="alert alert-warning">
                                    <strong>Atenção:</strong> Você já possui um plano ativo. 
                                    Cancele o plano atual para contratar outro.
                                </div>
                            <?php else: ?>

                                <div class="row g-4 justify-content-center">
                                    <div class="d-flex justify-content-center mb-4 plan-toggle">
                                        <button id="btn-monthly" class="btn btn-outline-primary active">Mensal</button>
                                        <button id="btn-yearly" class="btn btn-outline-primary">
                                            <span class="btn-text">Anual</span>
                                            <?php
                                            if (env('PAG_CYCLE_ANUAL_X_PRICE') > 0) {
                                                echo '<span class="yearly-badge">';
                                                if (env('PAG_CYCLE_ANUAL_X_PRICE') < 12) {
                                                    if (env('PAG_CYCLE_ANUAL_X_PRICE') === 1) {
                                                        echo "(1 mês grátis)";
                                                    } else {
                                                        echo "(" . (12 - env('PAG_CYCLE_ANUAL_X_PRICE')) . " meses grátis)";
                                                    }
                                                }
                                                echo '</span>';
                                            }
                                            ?>                                     
                                        </button>
                                    </div>

                                </div>
                                <br><br>
                                <div class="row g-4 justify-content-center">

                                    <?php foreach ($plans as $plan): ?>
                                        <?php if ($plan->getPrice() <= 0) continue; ?>

                                        <?php $recommended = $plan->getRibbon_tag() === 'recomendado'; ?>

                                        <div class="col-lg-4 col-md-6">
                                            <div class="plan-card <?= $recommended ? 'recommended' : '' ?>"
                                                 data-monthly="<?= $plan->getPrice() ?>"
                                                 data-yearly="<?= $plan->getPrice() * (int) (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12) ?>"
                                                 data-discount="10">

                                                <?php if ($recommended): ?>
                                                    <div class="plan-recommended-label">⭐ Recomendado</div>
                                                <?php endif; ?>

                                                <div class="text-center mb-3">
                                                    <div class="plan-title"><?= htmlspecialchars($plan->getTitle()) ?></div>
                                                    <small class="text-muted"><?= htmlspecialchars($plan->getObservation()) ?></small>
                                                </div>

                                                <div class="text-center mb-1">
                                                    <span class="plan-price price-value"></span>
                                                    <div class="plan-period period-label"></div>
                                                </div>

                                                <div class="text-center mb-3 d-none economy-label text-success fw-semibold">
                                                    Economize <span class="economy-value"></span>
                                                </div>

                                                <button
                                                    class="btn btn-default btn-register w-100 btn-lg btn-subscribe"
                                                    data-plan="<?= $plan->getGcid(); ?>"
                                                    data-title="<?= $plan->getTitle(); ?>" 
                                                    data-price-monthly="<?= $plan->getPrice(); ?>" 
                                                    data-price-yearly="<?= $plan->getPrice() * (int) (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12); ?>" 
                                                    data-features="<?= $plan->getDescription(); ?>" 
                                                    data-plan-id="<?= $plan->getGcid(); ?>"
                                                    >
                                                    Assinar plano
                                                </button>
                                                <br><br>
                                                <?php
                                                $descriptions = array_filter(
                                                        array_map('trim', explode(';', $plan->getDescription()))
                                                );
                                                ?>

                                                <ul class="plan-features list-unstyled text-start mb-4">
                                                    <?php foreach ($descriptions as $index => $desc): ?>
                                                        <li class="<?= $index === 0 ? 'feature-main' : '' ?>"
                                                            data-bs-toggle="tooltip"
                                                            title="<?= htmlspecialchars($desc) ?>">
                                                            <i class="fas fa-check-circle feature-icon"></i>
                                                            <?= htmlspecialchars($desc) ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                                <button class="btn btn-link btn-show-more">Mostrar mais</button>
                                            </div>

                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>
                    </section>



                    <!-- Modal Assinatura -->
                    <div class="modal fade" id="renewModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <form id="paymentForm">
                                <input type="hidden" id="selectedPlanId">
                                <input type="hidden" id="selectedPlanTitle">
                                <input type="hidden" id="selectedRawPrice"> <input type="hidden" id="selectedPeriod">  
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <button type="button" class="close position-absolute" data-bs-dismiss="modal" aria-label="Close" title="<?= $translate->translate('Cancelar', $_SESSION['client_lang']); ?>" style="right: 15px; top: 15px; z-index: 10;">
                                            <span>&times;</span>
                                        </button>

                                        <div class="row no-gutters" style="padding: 20px;">

                                            <div class="col-12 col-lg-6 payment-form-area">
                                                <div class="d-flex justify-content-between align-items-center mb-4">
                                                    <h5 style="font-weight: bold;">
                                                        <i class="fas fa-wallet text-primary"></i> <?= $translate->translate('Pagamento', $_SESSION['client_lang']); ?>
                                                    </h5> 
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
                                                        <select name="installments" id="inputInstallments" class="form-control custom-input" disabled>
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

                                            <div class="col-12 col-lg-6 payment-summary-area d-flex flex-column align-items-center">

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
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small"><?= $translate->translate('Plano', $_SESSION['client_lang']); ?></span>
                                                        <span class="font-weight-bold small" id="displayPlanName"></span>
                                                    </div>

                                                    <hr class="my-2">

                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <span><?= $translate->translate('Valor', $_SESSION['client_lang']); ?></span>
                                                        <span id="displayBasePrice" class="font-weight-bold">R$ 0,00</span>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <div class="d-flex align-items-center justify-content-between mb-3 mt-3">
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

                                                        <div id="couponFeedback" class="small text-right font-weight-bold" style="margin-top: -10px; margin-bottom: 10px;"></div>

                                                        <div id="rowDiscountAmount" class="justify-content-between mt-2 text-success" style="display: none;">
                                                            <small>Desconto aplicado</small>
                                                            <small id="displayDiscountAmount">- R$ 0,00</small>
                                                        </div>
                                                    </div>

                                                    <hr>

                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span style="font-size: 1.1rem;"><?= $translate->translate('Valor Total', $_SESSION['client_lang']); ?></span>
                                                        <span id="displayTotalPrice" class="font-weight-bold text-success" style="font-size: 1.5rem;">
                                                            R$ 0,00
                                                        </span>
                                                    </div>

                                                    <small id="oldPriceDisplay" class="text-muted text-decoration-line-through d-none"></small>

                                                    <div class="text-right mt-1">
                                                        <small class="text-muted"><i class="fas fa-lock text-success mr-1"></i> Pagamento Seguro</small>
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
        <script src="/assets/js/subscribe/subscribe.js"></script>
        <script src="/assets/js/subscribe/payment.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>

