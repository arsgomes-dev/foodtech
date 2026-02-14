// =============================================================================
// ESTADO GLOBAL DO PAGAMENTO
// =============================================================================
let paymentState = {
    discountPercent: 0,
    couponCode: '',
    currentTotal: 0
};

const locale = 'pt-br';
const currency = 'BRL';
const efiCode = document.getElementById('efiCode').value;
const efiEnvironment = document.getElementById('efiEnvironment').value;

$(document).ready(function () {

    // -------------------------------------------------------------------------
    // 1. LÓGICA DA TELA PRINCIPAL (Toggle Mensal/Anual)
    // -------------------------------------------------------------------------
    $('#btn-monthly, #btn-yearly').on('click', function () {
        // Alterna classe active visualmente
        $('.plan-toggle button').removeClass('active');
        $(this).addClass('active');

        const isYearly = $(this).attr('id') === 'btn-yearly';

        // Atualiza os preços nos cards da tela principal
        $('.plan-card').each(function () {
            const monthly = parseFloat($(this).data('monthly'));
            const yearly = parseFloat($(this).data('yearly'));
            const price = isYearly ? yearly : monthly;
            const period = isYearly ? '/ano' : '/mês';

            // Atualiza texto
            $(this).find('.price-value').text('R$ ' + price.toLocaleString(locale, {minimumFractionDigits: 2}));
            $(this).find('.period-label').text(period);
        });
    });

    // -------------------------------------------------------------------------
    // 2. ABRIR MODAL (Prepara os dados)
    // -------------------------------------------------------------------------
    $('.btn-subscribe').on('click', function () {
        const btn = $(this);
        const modal = $('#renewModal');

        // Detecta qual ciclo está selecionado na tela principal
        const isYearly = $('#btn-yearly').hasClass('active');
        const period = isYearly ? 'yearly' : 'monthly';

        // Pega dados do botão clicado
        const planId = btn.data('plan-id');
        const planTitle = btn.data('title');

        // Define preço base dependendo do ciclo
        // (Certifique-se que o botão PHP tem data-price-monthly e data-price-yearly)
        const rawPrice = isYearly ? parseFloat(btn.data('price-yearly')) : parseFloat(btn.data('price-monthly'));

        // Preenche Inputs Ocultos (Estado do Formulário)
        $('#selectedPlanId').val(planId);
        $('#selectedPlanTitle').val(planTitle);
        $('#selectedRawPrice').val(rawPrice);
        $('#selectedPeriod').val(period);

        // Preenche Textos Visuais
        $('#displayPlanName').text(planTitle);

        // Configurações Iniciais
        resetCoupon(true); // Limpa cupom antigo

        // Configura Parcelamento (Só habilita se for Anual)
        const $installments = $('#inputInstallments');
        if (isYearly) {
            $installments.prop('disabled', false).html('<option value="1">1x (À vista)</option>');
        } else {
            $installments.prop('disabled', true).html('<option value="1">1x (À vista)</option>');
        }

        // Calcula totais iniciais
        updateTotals();

        // Abre o modal
        modal.modal('show');
    });

    // -------------------------------------------------------------------------
    // 3. CÁLCULO CENTRAL DE VALORES (Função Principal)
    // -------------------------------------------------------------------------
    window.updateTotals = function () {
        // Pega preço bruto do input hidden
        const rawPrice = parseFloat($('#selectedRawPrice').val() || 0);
        let finalPrice = rawPrice;

        // Elementos da Interface
        const $displayBase = $('#displayBasePrice');
        const $displayTotal = $('#displayTotalPrice');
        const $rowDiscount = $('#rowDiscountAmount');
        const $displayDiscount = $('#displayDiscountAmount');

        // 1. Exibe Valor Base
        $displayBase.text(rawPrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // 2. Aplica Desconto (se houver)
        if (paymentState.discountPercent > 0) {
            let discountAmount = rawPrice * (paymentState.discountPercent / 100);
            finalPrice = rawPrice - discountAmount;

            $displayDiscount.text("- " + discountAmount.toLocaleString(locale, {style: 'currency', currency: currency}));

            // Remove d-none e força display flex para layout correto
            $rowDiscount.removeClass('d-none').css('display', 'flex');
        } else {
            // Esconde linha de desconto
            $rowDiscount.hide().addClass('d-none');
        }

        // Atualiza estado global
        paymentState.currentTotal = finalPrice;

        // 3. Exibe Total Final
        $displayTotal.text(finalPrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // 4. Se for anual e já tiver cartão, busca parcelas na API com o NOVO valor
        const period = $('#selectedPeriod').val();
        const cardNum = $('#inputCardNumber').val().replace(/\D/g, '');
        if (period === 'yearly' && cardNum.length === 16) {
            identifyBrand();
        }
    };

    // -------------------------------------------------------------------------
    // 4. LÓGICA DE CUPOM
    // -------------------------------------------------------------------------
    $('#btnApplyCoupon').on('click', function () {
        const code = $('#inputCouponCode').val().trim();
        if (!code)
            return;

        const btn = $(this);
        btn.prop('disabled', true).text('...');

        const formData = new FormData();
        formData.append('code', code);

        // Ajuste a rota '/validatecoupon' conforme seu sistema
        fetch('/validatecoupon', {method: 'POST', body: formData})
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' || data.status === true) {
                        paymentState.discountPercent = parseFloat(data.discount_percent);
                        paymentState.couponCode = code;

                        $('#couponFeedback').text('Cupom aplicado!').attr('class', 'small text-right font-weight-bold text-success');
                        $('#inputCouponCode').prop('disabled', true).addClass('is-valid');
                        $('#btnApplyCoupon').hide();
                        $('#btnRemoveCoupon').show(); // Mostra o botão X

                        updateTotals(); // Recalcula tudo
                    } else {
                        resetCoupon(false);
                        $('#couponFeedback').text(data.message || 'Cupom inválido.').attr('class', 'small text-right font-weight-bold text-danger');
                        $('#inputCouponCode').addClass('is-invalid');
                    }
                })
                .catch(err => {
                    console.error(err);
                    $('#couponFeedback').text('Erro ao validar.').attr('class', 'small text-right font-weight-bold text-danger');
                })
                .finally(() => {
                    btn.prop('disabled', false).text('Aplicar');
                });
    });

    // Botão de Remover Cupom
    $('#btnRemoveCoupon').on('click', function () {
        resetCoupon(false);
    });

    function resetCoupon(silent) {
        paymentState.discountPercent = 0;
        paymentState.couponCode = '';

        const input = $('#inputCouponCode');
        input.val('').prop('disabled', false).removeClass('is-valid is-invalid');

        $('#btnApplyCoupon').show();
        $('#btnRemoveCoupon').hide();

        if (!silent)
            $('#couponFeedback').text('');

        // Garante que a linha de desconto suma
        $('#rowDiscountAmount').hide().addClass('d-none');

        updateTotals();
    }

    // -------------------------------------------------------------------------
    // 5. MÁSCARAS E VISUAL DO CARTÃO
    // -------------------------------------------------------------------------
    $('#inputCardNumber').on('input', function () {
        var value = $(this).val().replace(/\D/g, '').substring(0, 16);
        $(this).val(value);

        if (value === '') {
            $('#cardVisualNumber').text('•••• •••• •••• ••••');
        } else {
            $('#cardVisualNumber').text(value.match(/.{1,4}/g).join(' '));
        }

        if (value.length === 16)
            identifyBrand();
    });

    $('#inputCardName').on('input', function () {
        var val = $(this).val().substring(0, 25);
        if (val === '')
            val = 'NOME DO CLIENTE';
        $('#cardVisualName').text(val.toUpperCase());
    });

    function updateExp() {
        var m = $('#inputExpMonth').val();
        var y = $('#inputExpYear').val();
        if (y)
            y = y.substring(2, 4);
        var txt = (m ? m : 'MM') + '/' + (y ? y : 'AA');
        $('#cardVisualExp').text(txt);
    }
    $('#inputExpMonth, #inputExpYear').on('change', updateExp);

    // -------------------------------------------------------------------------
    // 6. RESET AO FECHAR O MODAL
    // -------------------------------------------------------------------------
    $('.btn-pay-cancel, .close').on('click', function () {
        $('#paymentForm')[0].reset();
        resetCoupon(true);

        // Reset Visual Cartão
        $('#cardVisualNumber').text('•••• •••• •••• ••••');
        $('#cardVisualName').text('NOME DO CLIENTE');
        $('#cardVisualExp').text('MM/AA');
        $('#cardVisualBrand').attr('class', 'fas fa-credit-card fa-2x text-secondary');

        // Reset Botão Pagar
        $('.btn-pay').prop('disabled', false).html('Pagar Agora').removeClass('btn-success');

        $('#renewModal').modal('hide');
    });
});


// =============================================================================
// 7. API EFIPAY - IDENTIFICA BANDEIRA E PARCELAS
// =============================================================================
async function identifyBrand() {
    try {
        const cardNumber = document.getElementById("inputCardNumber").value;
        const period = document.getElementById("selectedPeriod").value; // hidden input
        const $installments = $('#inputInstallments');

        if (cardNumber.length >= 16) {
            // 1. Bandeira
            const brand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

            // Atualiza ícone
            var icon = $('#cardVisualBrand');
            icon.removeClass().addClass('fab fa-2x');
            const icons = {
                'visa': 'fa-cc-visa text-primary',
                'mastercard': 'fa-cc-mastercard text-danger',
                'amex': 'fa-cc-amex text-info',
                'elo': 'fa-credit-card text-warning'
            };
            icon.addClass(icons[brand] || 'fas fa-credit-card text-secondary');
            $('#inputCardBrand').val(brand); // Select oculto ou visual na esquerda

            // 2. Parcelas (Só busca se for Anual)
            if (period === 'yearly') {
                const totalCents = Math.floor(paymentState.currentTotal * 100);

                const response = await EfiPay.CreditCard
                        .setAccount(efiCode) // <--- SEU ACCOUNT CODE
                        .setEnvironment(efiEnvironment)
                        .setBrand(brand)
                        .setTotal(totalCents)
                        .getInstallments();

                if (response.installments) {
                    let options = '';
                    response.installments.forEach(inst => {
                        let label = inst.has_interest ? "(c/ juros)" : "(s/ juros)";
                        options += `<option value="${inst.installment}">${inst.installment}x de ${inst.currency} ${label}</option>`;
                    });
                    $installments.html(options);
                }
            }
        }
    } catch (e) {
        console.error("Erro Efi:", e);
        // Fallback: Deixa 1x se der erro
        if ($('#selectedPeriod').val() === 'yearly') {
            $('#inputInstallments').html('<option value="1">1x (À vista)</option>');
        }
    }
}


// =============================================================================
// 8. ENVIO DO PAGAMENTO
// =============================================================================
async function generatePayment() {
    const $btnPay = $('.btn-pay');
    const originalText = $btnPay.html();

    // UI Loading
    $btnPay.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');
    const Toast = swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000});

    try {
        // Coleta dados
        const planId = $('#selectedPlanId').val();
        const cycle = $('#selectedPeriod').val() === 'yearly' ? 'anual' : 'mensal';
        const installments = $('#inputInstallments').val();
        const terms = document.getElementById("terms").value;
        const coupon = paymentState.couponCode;

        // Dados do Cartão
        const cardNumber = $('#inputCardNumber').val();
        const cardName = $('#inputCardName').val();
        const cardDoc = $('#inputCardCPF').val().replace(/\D/g, '');
        const cardCvv = $('#inputCardCVV').val();
        const cardM = $('#inputExpMonth').val();
        const cardY = $('#inputExpYear').val();
        const isAutoRenew = document.getElementById('autoRenewSwitch').checked;

        if (!cardNumber || !cardName || !cardDoc || !cardCvv)
            throw new Error("Preencha todos os dados do cartão.");

        // Gera Token
        const brand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

        if (brand && brand !== 'undefined') {
            const result = await EfiPay.CreditCard
                    .setAccount('55b11259c72eea012f3ea9192ea89fb1') // <--- SEU ACCOUNT CODE
                    .setEnvironment('sandbox')
                    .setCreditCardData({
                        brand: brand,
                        number: cardNumber,
                        cvv: cardCvv,
                        expirationMonth: cardM,
                        expirationYear: cardY,
                        holderName: cardName,
                        holderDocument: cardDoc,
                        reuse: isAutoRenew
                    }).getPaymentToken();

            // Monta Payload
            const payload = {
                brand: brand,
                plan: planId,
                payment_token: result.payment_token,
                card_mask: result.card_mask,
                cycle: cycle,
                installments: installments,
                coupon_code: coupon,
                auto_renew: isAutoRenew ? 1 : 0,
                terms: terms,
                method: 'credit_card',
                type: 'efipay'
            };

            const dir = $("#dir_site").val() ? "/" + $("#dir_site").val() : "";

            // Envia
            $.post(dir + "/signature/signature", payload, function (res) {
                var msg = res.trim().split("->");

                if (msg[0] === "1") {
                    Toast.fire({icon: 'success', title: msg[1]});
                    $btnPay.html('<i class="fas fa-check"></i> Sucesso!').addClass('btn-success');
                    setTimeout(() => {
                        $('#renewModal').modal('hide');
                        window.location.href = dir; // Reload ou redirecionamento
                    }, 1500);
                } else {
                    Toast.fire({icon: "error", title: msg[1]});
                    resetBtn();
                }
            }).fail(function () {
                Toast.fire({icon: "error", title: "Erro ao comunicar com servidor."});
                resetBtn();
            });

        } else {
            throw new Error("Bandeira não suportada.");
        }
    } catch (e) {
        console.error(e);
        Toast.fire({icon: "error", title: e.message || "Erro no pagamento"});
        resetBtn();
    }

    function resetBtn() {
        setTimeout(() => $btnPay.prop('disabled', false).html(originalText), 500);
    }
}