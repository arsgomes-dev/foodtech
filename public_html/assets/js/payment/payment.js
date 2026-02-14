// =============================================================================
// ESTADO GLOBAL DO PAGAMENTO
// =============================================================================
let configState = {
    discountPercent: 0,
    couponCode: '',
    currentTotal: 0
};

$(document).ready(function () {
    // Configurações Regionais
    const locale = $('#site_locale').val() || 'pt-br';
    const currency = 'BRL';

    // Elementos do Cupom
    const elCouponInput = document.getElementById('inputCouponCode');
    const elBtnApplyCoupon = document.getElementById('btnApplyCoupon');
    const elBtnRemoveCoupon = document.getElementById('btnRemoveCoupon');
    const elCouponFeedback = document.getElementById('couponFeedback');
    const efiCode = document.getElementById('efiCode').value;
    const efiEnvironment = document.getElementById('efiEnvironment').value;



    // =========================================================================
    // 2. CÁLCULO DE VALORES (CORE)
    // =========================================================================
    window.updatePaymentValues = function () {
        const cycle = $('#inputCycle').val();
        const $installments = $('#inputInstallments');

        // Elementos de Exibição
        const $displayBase = $('#displayBasePrice');      // R$ Valor Base
        const $displayTotal = $('#displayTotalPrice');    // R$ Valor Total
        const $rowDiscount = $('#rowDiscountAmount');     // Container da linha de desconto
        const $displayDiscount = $('#displayDiscountAmount'); // Texto "- R$ XX,XX"

        // 1. Define o preço base (Mensal ou Anual) lendo dos inputs hidden
        let rawMonthly = parseFloat($('#rawPriceMonthly').val() || 0);
        let rawAnnual = parseFloat($('#rawPriceAnnual').val() || 0);
        let basePrice = (cycle === 'mensal') ? rawMonthly : rawAnnual;

        // Atualiza o display do "Valor" (Preço Base)
        $displayBase.text(basePrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // 2. Calcula Desconto
        let finalPrice = basePrice;

        if (configState.discountPercent > 0) {
            let discountAmount = basePrice * (configState.discountPercent / 100);
            finalPrice = basePrice - discountAmount;

            // Mostra a linha de desconto
            $displayDiscount.text("- " + discountAmount.toLocaleString(locale, {style: 'currency', currency: currency}));
            $rowDiscount.show().css('display', 'flex');
        } else {
            $rowDiscount.hide();
        }

        // Atualiza estado global
        configState.currentTotal = finalPrice;

        // 3. Atualiza "Valor Total"
        $displayTotal.text(finalPrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // 4. Lógica de Parcelas
        if (cycle === 'mensal') {
            // Mensal é sempre à vista
            $installments.html('<option value="1">1x (À vista)</option>');
            $installments.prop('disabled', true);
        } else {
            // Anual permite parcelar
            $installments.prop('disabled', false);

            // Se já tem cartão digitado, recalcula as parcelas na API com o NOVO valor total
            if ($('#inputCardNumber').val().length === 16) {
                identifyBrand();
            } else {
                // Se não tem cartão, reseta parcelas provisoriamente
                $installments.html('<option value="1">1x (À vista)</option>');
            }
        }
    };

    // Trigger na mudança de ciclo
    $('#inputCycle').on('change', function () {
        updatePaymentValues();
    });


    // Inicializa valores ao carregar a página
    updatePaymentValues();

    // =========================================================================
    // 1. ATUALIZAÇÃO VISUAL DO CARTÃO (UX)
    // =========================================================================
    $('#inputCardNumber').on('input', function () {
        var value = $(this).val().replace(/\D/g, '').substring(0, 16);
        $(this).val(value);

        // Atualiza visual
        if (value === '') {
            $('#cardVisualNumber').text('•••• •••• •••• ••••');
        } else {
            var formatted = value.match(/.{1,4}/g).join(' ');
            $('#cardVisualNumber').text(formatted);
        }

        // Se completou 16 dígitos, busca bandeira e parcelas
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
            y = y.substring(2, 4); // Pega apenas os 2 últimos dígitos do ano
        var txt = (m ? m : 'MM') + '/' + (y ? y : 'AA');
        $('#cardVisualExp').text(txt);
    }
    $('#inputExpMonth, #inputExpYear').on('change', updateExp);


    // =========================================================================
    // 3. LÓGICA DE CUPOM
    // =========================================================================
    if (elBtnApplyCoupon)
        elBtnApplyCoupon.addEventListener('click', applyCoupon);
    if (elBtnRemoveCoupon)
        elBtnRemoveCoupon.addEventListener('click', () => removeCoupon(true));

    async function applyCoupon() {
        const code = elCouponInput.value.trim();
        if (!code) {
            showCouponFeedback('Digite um código.', 'text-danger');
            return;
        }

        // Loading UI
        elBtnApplyCoupon.disabled = true;
        elBtnApplyCoupon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const formData = new FormData();
            formData.append('code', code);

            // IMPORTANTE: Ajuste a rota para o seu backend PHP
            const response = await fetch('/validatecoupon', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();

            if (data.status === 'success' || data.status === true) {
                // SUCESSO
                configState.discountPercent = parseFloat(data.discount_percent);
                configState.couponCode = code;

                showCouponFeedback(data.message || 'Cupom aplicado!', 'text-success');
                elCouponInput.classList.add('is-valid');
                elCouponInput.classList.remove('is-invalid');

                elCouponInput.disabled = true;
                elBtnApplyCoupon.style.display = 'none';
                elBtnRemoveCoupon.style.display = 'block';

                updatePaymentValues(); // Recalcula totais
            } else {
                // ERRO
                removeCoupon(false);
                showCouponFeedback(data.message || 'Cupom inválido.', 'text-danger');
                elCouponInput.classList.add('is-invalid');
            }
        } catch (error) {
            console.error(error);
            showCouponFeedback('Erro ao validar cupom.', 'text-danger');
        } finally {
            elBtnApplyCoupon.disabled = false;
            elBtnApplyCoupon.innerHTML = 'Aplicar';
        }
    }

// Adicione este parâmetro 'silent' para não mostrar mensagens quando for um reset automático
    function removeCoupon(silent = false) {
        // 1. Zera o estado global
        configState.discountPercent = 0;
        configState.couponCode = '';

        // 2. Resete Visual dos Inputs
        const elInput = $('#inputCouponCode');
        elInput.val('');                 // Limpa texto
        elInput.prop('disabled', false); // Destrava
        elInput.removeClass('is-valid is-invalid'); // Remove cores verde/vermelho

        // 3. Resete dos Botões
        $('#btnApplyCoupon').show();
        $('#btnRemoveCoupon').hide();

        // 4. Limpa mensagens de feedback
        $('#couponFeedback').text('').removeClass('text-success text-danger');
        $('#rowDiscountAmount').hide(); // Esconde a linha "- R$ 10,00"

        // 5. Se não for silencioso, avisa que removeu (opcional)
        if (!silent) {
            $('#couponFeedback').text('').removeClass('text-success text-danger');
        }

        // 6. RECALCULA O TOTAL (Volta ao preço original)
        updatePaymentValues();
    }

    function showCouponFeedback(msg, className) {
        if (elCouponFeedback) {
            elCouponFeedback.innerText = msg;
            elCouponFeedback.className = 'small mt-1 fw-bold ' + className;
        }
    }

    // =========================================================================
    // 4. RESET DO MODAL
    // =========================================================================
    function resetarModalPagamento() {
        // 1. Reseta o formulário HTML padrão (limpa cartão, nome, etc)
        $('#renewForm')[0].reset();

        // 2. Zera especificamente a lógica do Cupom (Modo Silencioso)
        removeCoupon(true);

        // 3. Reseta Visual do Cartão de Crédito
        $('#cardVisualNumber').text('•••• •••• •••• ••••');
        $('#cardVisualName').text('NOME DO CLIENTE');
        $('#cardVisualExp').text('MM/AA');
        $('#cardVisualBrand').attr('class', 'fas fa-credit-card fa-2x text-secondary');

        // 4. Reseta Botão de Pagar
        var $btnPay = $('.btn-pay');
        $btnPay.prop('disabled', false).html('Pagar Agora');
        $btnPay.removeClass('btn-success btn-danger').addClass('btn-primary');

        // 5. Garante que os selects voltem ao padrão
        $('#inputCycle').val('mensal'); // Volta para mensal
        $('#inputInstallments').prop('disabled', true).html('<option value="1">1x (À vista)</option>');

        // 6. Força o recálculo final para garantir que a tela mostre o preço cheio
        updatePaymentValues();
    }

    $('.btn-pay-cancel').on('click', resetarModalPagamento);
    $('#renewModal').on('hidden.bs.modal', resetarModalPagamento);
});


// =============================================================================
// 5. INTEGRAÇÃO EFIPAY (Bandeira e Parcelas)
// =============================================================================
async function identifyBrand() {
    try {
        const cardNumber = document.getElementById("inputCardNumber").value;
        const cycle = document.getElementById("inputCycle").value;
        const $installments = $('#inputInstallments');

        if (cardNumber.length >= 16) {
            // 1. Identifica Bandeira
            const brand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

            // 2. Atualiza ícone visual
            var icon = $('#cardVisualBrand');
            icon.removeClass().addClass('fab fa-2x');
            const brandIcons = {
                'visa': 'fa-cc-visa text-primary',
                'mastercard': 'fa-cc-mastercard text-danger',
                'amex': 'fa-cc-amex text-info',
                'elo': 'fa-credit-card text-warning'
            };
            icon.addClass(brandIcons[brand] || 'fas fa-credit-card text-secondary');
            $('#inputCardBrand').val(brand); // Input hidden ou select oculto se existir

            // 3. Busca Parcelas (Apenas se for ciclo Anual)
            if (cycle === 'anual') {
                // Usa o total já calculado com desconto
                const totalCents = Math.floor(configState.currentTotal * 100);

                const response = await EfiPay.CreditCard
                        .setAccount(efiCode)
                        .setEnvironment(efiEnvironment)
                        .setBrand(brand)
                        .setTotal(totalCents)
                        .getInstallments();

                if (response.installments) {
                    let options = '';
                    response.installments.forEach(inst => {
                        let labelJuros = inst.has_interest ? " (com juros)" : " (sem juros)";
                        options += `<option value="${inst.installment}">
                            ${inst.installment}x de ${inst.currency}${labelJuros}
                        </option>`;
                    });
                    $installments.html(options);
                }
            }
        }
    } catch (error) {
        console.error("Erro Efi:", error);
        // Fallback
        if ($('#inputCycle').val() === 'anual') {
            // Se der erro na API, deixa pelo menos 1x
            $('#inputInstallments').html('<option value="1">1x (À vista)</option>');
        }
    }
}

// =============================================================================
// 6. GERAÇÃO E ENVIO DO PAGAMENTO
// =============================================================================
async function generatePayment() {
    const $btnPay = $('.btn-pay');
    const originalBtnText = $btnPay.html();

    // UI Loading
    $btnPay.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');
    const Toast = swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000});

    try {
        // Coleta de Dados
        const signature = document.getElementById("signature").value;
        const cardNumber = document.getElementById("inputCardNumber").value;
        const cardName = document.getElementById("inputCardName").value;
        const cardDocument = document.getElementById("inputCardCPF").value;
        const cardCVV = document.getElementById("inputCardCVV").value;
        const cardMonth = document.getElementById("inputExpMonth").value;
        const cardYear = document.getElementById("inputExpYear").value;
        const terms = document.getElementById("terms").value;
        const cycle = document.getElementById("inputCycle").value;
        const installments = document.getElementById("inputInstallments").value;
        const isAutoRenew = document.getElementById('autoRenewSwitch').checked;

        // Validação Simples
        if (!cardNumber || !cardName || !cardDocument || !cardCVV) {
            throw new Error("Preencha todos os dados do cartão.");
        }

        // 1. Gera Token EfiPay
        const cardBrand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

        if (cardBrand && cardBrand !== "undefined") {
            const result = await EfiPay.CreditCard
                    .setAccount('55b11259c72eea012f3ea9192ea89fb1') // <--- SEU ACCOUNT CODE AQUI
                    .setEnvironment('sandbox')
                    .setCreditCardData({
                        brand: cardBrand,
                        number: cardNumber,
                        cvv: cardCVV,
                        expirationMonth: cardMonth,
                        expirationYear: cardYear,
                        holderName: cardName,
                        holderDocument: cardDocument.replace(/\D/g, ''),
                        reuse: isAutoRenew
                    })
                    .getPaymentToken();

            const dir = $("#dir_site").val() ? "/" + $("#dir_site").val() : "";

            // 2. Monta Payload
            const payload = {
                brand: cardBrand,
                signature: signature,
                payment_token: result.payment_token,
                card_mask: result.card_mask,
                cycle: cycle,
                installments: installments,
                auto_renew: isAutoRenew ? 1 : 0,
                terms: terms,
                coupon_code: configState.couponCode, // Envia o cupom validado
                method: 'credit_card',
                type: 'efipay'
            };

            // 3. Envia ao Backend
            $.post(dir + "/signature/paymentsignature", payload, function (response) {
                // Espera retorno "1->Mensagem" ou "2->Erro"
                var msg = response.trim().split("->");

                if (msg[0] === "1") {
                    Toast.fire({icon: 'success', title: " " + msg[1]});
                    $btnPay.prop('disabled', true).html('<i class="fas fa-check"></i> Aprovado!').addClass('btn-success');

                    setTimeout(() => {
                        $('#renewModal').modal('hide');
                        window.location.href = dir; // Reload ou redirecionamento
                    }, 1500);
                } else {
                    Toast.fire({icon: "error", title: " " + (msg[1] || "Erro desconhecido")});
                    resetButton();
                }
            }).fail(function () {
                Toast.fire({icon: "error", title: "Erro de comunicação com o servidor."});
                resetButton();
            });

        } else {
            Toast.fire({icon: "error", title: "Bandeira não suportada."});
            resetButton();
        }
    } catch (error) {
        console.error(error);
        Toast.fire({icon: "error", title: error.message || "Erro ao processar pagamento."});
        resetButton();
    }

    function resetButton() {
        setTimeout(function () {
            $btnPay.prop('disabled', false).html(originalBtnText);
        }, 500);
    }
}