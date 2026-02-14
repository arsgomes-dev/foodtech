<?php
session_start();

use Microfw\Src\Main\Controller\Landing\Login\ProtectedPage;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;

ProtectedPage::protectedPage();
$translate = new Translate();

// 2. RECUPERA DADOS
$plano_id = $_SESSION['cart']['plano_id'] ?? null;
$ciclo = $_SESSION['cart']['ciclo'] ?? 'mensal';
// 3. BUSCA PLANO
$plan_db = new AccessPlan;
$plan_db_search = new AccessPlan;
if ($plano_id !== null) {
    $plan_db = $plan_db->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $plano_id]]);
}
$plan_db_search = $plan_db_search->getQuery(customWhere: [['column' => 'status', 'value' => 1]], order: "price ASC");

$valor_base = 0.00;
$valor_total = 0.00;
$is_anual = null;
// 4. CÁLCULO
if ($plan_db !== null && $plano_id !== null) {
    $valor_base = $plan_db->getPrice();
    $valor_total = $valor_base;
    $is_anual = ($ciclo === 'anual');
    if ($is_anual) {
        $valor_total = $valor_base * (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Finalizar Pagamento - YoutubeOS</title>

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel='stylesheet' href='/assets/vendor/sweetalert2B/bootstrap-4.min.css'>

        <link rel="stylesheet" href="assets/public/css/payment.css">
    </head>
    <body>
        <input type="hidden" id="x_y" value="<?php echo (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12); ?>">
        <input type="hidden" id="efiCode" value="<?php echo (env('EFI_PAYEE_CODE')); ?>">
        <input type="hidden" id="efiEnvironment" value="<?php echo (env('EFI_ENVIRONMENT')); ?>">
        <?php
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/Landing/header_login.php");
        ?>


        <div class="container payment-container">
            <div class="row g-5">

                <div class="col-lg-7">
                    <h3 class="mb-4">Dados do Pagamento</h3>

                    <div class="card-visual">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="chip"></div>
                            <i class="fab fa-cc-visa fa-2x opacity-75"></i>
                        </div>
                        <div class="card-number-display" id="displayNum">•••• •••• •••• ••••</div>
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <small class="d-block opacity-75 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Nome do Titular</small>
                                <span id="displayName" class="fw-bold">SEU NOME</span>
                            </div>
                            <div>
                                <small class="d-block opacity-75 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Validade</small>
                                <span id="displayExp" class="fw-bold">MM/AA</span>
                            </div>
                        </div>
                    </div>

                    <form id="paymentForm">

                        <div class="mb-4">
                            <label class="form-label">Número do Cartão</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Nome Impresso</label>
                            <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Igual aparece no cartão" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">CPF do Titular</label>
                            <input type="text" class="form-control" id="card_cpf" name="card_cpf" placeholder="000.000.000-00" maxlength="14" required>
                            <div class="invalid-feedback">CPF inválido. Verifique os números.</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Validade (MM/AA)</label>
                                <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/AA" maxlength="5" required>
                                <div class="invalid-feedback">Data inválida ou expirada.</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">CVV</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4" required>
                                    <span class="input-group-text" style="border-left:none; border-right:1px solid rgba(255,255,255,0.1);" title="3 dígitos no verso"><i class="fas fa-question-circle"></i></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Parcelamento</label>
                            <select class="form-select" id="installments" name="installments">
                                <option value="1">1x de R$ <?= number_format($valor_total, 2, ',', '.') ?> (À vista)</option>
                            </select>
                        </div>
                        <div class="renewal-wrapper mb-4">
                            <div class="d-flex align-items-center justify-content-between p-3 rounded-3" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color);">

                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch custom-switch-lg me-3">
                                        <input class="form-check-input" type="checkbox" id="autoRenewSwitch" name="auto_renew" style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                    </div>
                                    <div>
                                        <label class="form-check-label fw-bold text-white mb-0" for="autoRenewSwitch" style="cursor: pointer;">
                                            Renovação Automática
                                        </label>
                                        <div class="small text-muted mt-1" style="font-size: 0.8rem; color: #fff !important;">
                                            Ao ativar, você concorda com os 
                                            <a href="#" class="text-primary-light text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">
                                                Termos de Renovação
                                            </a>.
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <?php
                        // Simulação ou chamada real da sua classe SignatureTerms
                        // Certifique-se de que a classe SignatureTerms está carregada
                        // use Microfw\Src\Main\Common\Entity\Public\SignatureTerms; 

                        $termTitle = "Termos de Renovação Automática";
                        $termContent = "Carregando termos...";
                        $termId = "";

                        if (class_exists('Microfw\Src\Main\Common\Entity\Public\SignatureTerms')) {
                            $signaturesTerms = new \Microfw\Src\Main\Common\Entity\Public\SignatureTerms();
                            $termObj = $signaturesTerms->getQuery(single: true, customWhere: [['column' => 'type', 'value' => "terms_automatic_renewal"], ['column' => 'status', 'value' => 1]], order: "created_at DESC");

                            if ($termObj) {
                                // $translate é global ou precisa ser instanciado? Assumindo que $translate existe.
                                // Se não, use texto direto.
                                $termTitle = $termObj->getTitle();
                                $termContent = $termObj->getTerm();
                                $termId = $termObj->getId();
                            }
                        }
                        ?>

                        <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content bg-card border-secondary">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title text-white fw-bold"><?= htmlspecialchars($termTitle) ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-secondary" style="font-size: 0.9rem; line-height: 1.6;">
                                        <input type="hidden" name="terms_id" id="terms_id" value="<?= $termId ?>">
                                        <?= $termContent ?> </div>
                                    <div class="modal-footer border-secondary">
                                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Fechar</button>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="acceptTerms()">Aceitar e Ativar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-pay">
                            <i class="fas fa-lock me-2"></i> Pagar R$ <span id="btnTotal"><?= number_format($valor_total, 2, ',', '.') ?></span>
                        </button>

                        <a href="/app/panel" class="btn btn-cancel mt-3 text-center d-block">
                            Cancelar e Voltar
                        </a>

                        <div class="text-center mt-3">
                            <small class="text-secondary"><i class="fas fa-shield-alt text-success me-1"></i> Ambiente 100% seguro com criptografia SSL.</small>
                        </div>
                    </form>
                </div>

                <div class="col-lg-5">
                    <div class="summary-card">
                        <h4 class="mb-4">Resumo do Pedido</h4>

                        <div class="mb-3">
                            <label class="text-secondary small fw-bold mb-2">PLANO ESCOLHIDO</label>
                            <select class="form-select bg-dark text-white border-secondary" id="planSelector">
                                <?php foreach ($plan_db_search as $p): if ($p->getPrice() <= 0) continue; ?>
                                    <option value="<?= $p->getGcid() ?>" 
                                            data-price="<?= $p->getPrice() ?>" 
                                            <?= ($plan_db !== null && $plano_id !== null && $plan_db->getGcid() == $p->getGcid()) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p->getTitle()) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 p-3 rounded-3" style="background: rgba(255,255,255,0.05);">
                            <div>
                                <span class="d-block text-white fw-bold">Ciclo de Pagamento</span>
                                <small class="text-success" id="economyLabel" style="<?= !$is_anual ? 'display:none' : '' ?>">
                                    Economia de 2 meses
                                </small>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cycleSelector" style="width: 3rem; height: 1.5rem; cursor: pointer;" <?= $is_anual ? 'checked' : '' ?>>
                                <label class="form-check-label ms-2 text-secondary small" for="cycleSelector" id="cycleLabel">
                                    <?= $is_anual ? 'Anual' : 'Mensal' ?>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-secondary small fw-bold">CUPOM DE DESCONTO</label>
                            <div class="input-group">
                                <input type="text" style="border: 1px solid;" class="form-control bg-dark border-secondary text-white text-uppercase" 
                                       id="couponCode" placeholder="Ex: PRO10">

                                <button class="btn btn-outline-custom type="button" id="btnApplyCoupon">
                                    Aplicar
                                </button>

                                <button class="btn btn-outline-danger" type="button" id="btnRemoveCoupon" style="display: none;">
                                    Remover
                                </button>
                            </div>
                            <div id="couponFeedback" class="small mt-1 fw-bold"></div>
                        </div>

                        <hr class="border-secondary opacity-25 my-4">

                        <div class="d-flex justify-content-between align-items-end mb-4">
                            <div class="text-secondary">
                                Total a pagar:
                                <small id="originalPriceDisplay" class="text-decoration-line-through text-muted d-block" style="display:none!important; font-size: 0.85rem;"></small>
                            </div>
                            <div class="price-tag" id="displayTotal">
                                R$ <?= number_format($valor_total, 2, ',', '.') ?>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 mb-3" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                            <ul class="list-unstyled text-secondary small mb-0">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Acesso liberado imediatamente</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>7 dias de garantia</li>
                                <li class="mb-0"><i class="fas fa-check-circle text-success me-2"></i>Nota fiscal automática</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <?php
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/Landing/footer_login.php");
        ?>

        <script>
            window.paymentConfig = {
                totalValue: <?= $valor_total ?>,
                isAnual: <?= $is_anual ? 'true' : 'false' ?>
            };
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            msgRenewCancel = "<?= $translate->translate('Selecione a assinatura que deseja cancelar!', $_SESSION['client_lang']); ?>";
            msgCardInvalid = "<?= $translate->translate('Cartão inválido!', $_SESSION['client_lang']); ?>";
            msgCardErrorPayment = "<?= $translate->translate('O processamento do pagamento falhou. Por favor, tente novamente.', $_SESSION['client_lang']); ?>";
            // =============================================================================
// CONFIGURAÇÕES GLOBAIS
// =============================================================================
            const EFI_CONFIG = {
                account: '<?= env('EFI_PAYEE_CODE') ?>',
                environment: '<?= env('EFI_SANDBOX') ? 'sandbox' : 'production' ?>' // 'sandbox' ou 'production'
            };
        </script>
        <script src="/assets/vendor/format/cpfFormat.min.js"></script>
        <script src="/assets/vendor/efi/payment-token-efi-umd.min.js"></script> 
        <script src="/assets/vendor/sweetalert2/sweetalert2.min.js"></script>
        <script src="/assets/public/js/payment.js"></script>
        <?php
        if ($plano_id === null) {
            ?>
            <script>
            </script>
            <?php
        }
        ?>
    </body>
</html>