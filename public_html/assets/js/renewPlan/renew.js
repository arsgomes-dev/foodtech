$(document).ready(function () {
    // Configurações de localização
    const locale = $('#site_locale').val() || 'pt-br';
    const currency = 'BRL';

    // --- 1. INICIALIZAÇÃO (Ciclo e Cupom Automático) ---

    // A) Define o Ciclo correto (Mensal ou Anual) vindo do PHP
    const savedCycle = $('#rawCycle').val();
    if (savedCycle) {
        $('#inputCycle').val(savedCycle);
    }

    // B) Dispara a atualização inicial de valores
    $('#inputCycle').trigger('change');

    // C) AUTO-APLICAR CUPOM (A lógica que faltava)
    const preCode = $('#rawDicountCode').val(); // Pega valor do input hidden

    if (preCode && preCode.trim() !== '') {
        // 1. Preenche o campo visual
        $('#inputCouponCode').val(preCode);

        // 2. Simula o clique no botão "Aplicar" com pequeno delay
        setTimeout(function () {
            $('#btnApplyCoupon').trigger('click');
        }, 500);
    }

    // --- 2. ATUALIZAÇÃO VISUAL DO CARTÃO ---
    $('#inputCardNumber').on('input', function () {
        var value = $(this).val().replace(/\D/g, '').substring(0, 16);
        $(this).val(value);

        if (value === '') {
            $('#cardVisualNumber').text('•••• •••• •••• ••••');
        } else {
            var formatted = value.match(/.{1,4}/g).join(' ');
            $('#cardVisualNumber').text(formatted);
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

    // --- 3. EVENTOS DE AÇÃO ---
    $('#inputCycle').on('change', function () {
        updatePaymentValues();
    });

    $('#btnApplyCoupon').on('click', function () {
        const code = $('#inputCouponCode').val().trim();
        if (!code)
            return;

        const btn = $(this);
        btn.prop('disabled', true).text('...');

        const formData = new FormData();
        formData.append('code', code);

        // Ajuste a rota se necessário
        fetch('/validatecoupon', {method: 'POST', body: formData})
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' || data.status === true) {
                        // Salva desconto e código nos inputs hidden
                        $('#discountPercent').val(data.discount_percent);
                        $('#appliedCouponCode').val(code);

                        // Feedback Visual
                        $('#couponFeedback').text('Cupom aplicado!').attr('class', 'small text-right font-weight-bold text-success');
                        $('#inputCouponCode').prop('disabled', true).addClass('is-valid');

                        // Troca botões
                        $('#btnApplyCoupon').hide();
                        $('#btnRemoveCoupon').show();

                        // Recalcula totais
                        updatePaymentValues();
                    } else {
                        resetCoupon(false);
                        $('#couponFeedback').text(data.message || 'Inválido.').attr('class', 'small text-right font-weight-bold text-danger');
                        $('#inputCouponCode').addClass('is-invalid');
                    }
                })
                .catch(err => {
                    console.error(err);
                    $('#couponFeedback').text('Erro ao validar.').addClass('text-danger');
                })
                .finally(() => btn.prop('disabled', false).text('Aplicar'));
    });

    $('#btnRemoveCoupon').on('click', function () {
        resetCoupon(false);
    });

    function resetCoupon(silent) {
        $('#discountPercent').val(0);
        $('#appliedCouponCode').val('');

        const input = $('#inputCouponCode');
        input.val('').prop('disabled', false).removeClass('is-valid is-invalid');

        $('#btnApplyCoupon').show();
        $('#btnRemoveCoupon').hide();

        if (!silent)
            $('#couponFeedback').text('');

        // Esconde linha de desconto
        $('#rowDiscountAmount').hide();

        updatePaymentValues();
    }

    // --- 4. FUNÇÃO DE CÁLCULO ---
    function updatePaymentValues() {
        const cycle = $('#inputCycle').val();
        const $installments = $('#inputInstallments');
        const $displayTotal = $('#displayTotalPrice');
        const $rowDiscount = $('#rowDiscountAmount');
        const $displayDiscount = $('#displayDiscountAmount');

        const monthlyPrice = parseFloat($('#rawPriceMonthly').val() || 0);
        const annualPrice = parseFloat($('#rawPriceAnnual').val() || 0);
        const discountPercent = parseFloat($('#discountPercent').val() || 0);

        let basePrice = (cycle === 'mensal') ? monthlyPrice : annualPrice;
        let finalPrice = basePrice;

        // Atualiza Valor Base
        $('#displayBasePrice').text(basePrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // Aplica Desconto
        if (discountPercent > 0) {
            let discountAmount = basePrice * (discountPercent / 100);
            finalPrice = basePrice - discountAmount;

            $displayDiscount.text("- " + discountAmount.toLocaleString(locale, {style: 'currency', currency: currency}));
            $rowDiscount.css('display', 'flex');
        } else {
            $rowDiscount.hide();
        }

        // Atualiza Total Final
        $displayTotal.text(finalPrice.toLocaleString(locale, {style: 'currency', currency: currency}));

        // Lógica de Parcelas
        if (cycle === 'mensal') {
            $installments.html('<option value="1">1x (À vista)</option>');
            $installments.prop('disabled', true);
        } else {
            $installments.prop('disabled', false);
            if ($('#inputCardNumber').val().replace(/\D/g, '').length === 16) {
                identifyBrand();
            }
        }
    }

    // --- 5. RESET DO MODAL ---
    function resetarModalPagamento() {
        $('#renewForm')[0].reset();
        resetCoupon(true);
        $('#cardVisualNumber').text('•••• •••• •••• ••••');
        $('#cardVisualName').text('NOME DO CLIENTE');
        $('#cardVisualExp').text('MM/AA');
        $('#cardVisualBrand').attr('class', 'fas fa-credit-card fa-2x text-secondary');

        // Restaura ciclo original
        const saved = $('#rawCycle').val();
        if (saved)
            $('#inputCycle').val(saved);
        $('#inputCycle').trigger('change');

        var $btnPay = $('.btn-pay');
        $btnPay.prop('disabled', false).html('Pagar Agora');
        $btnPay.removeClass('btn-success btn-danger').addClass('btn-primary');
    }

    $('.btn-pay-cancel').on('click', resetarModalPagamento);
    $('#renewModal').on('hidden.bs.modal', resetarModalPagamento);
});

/**
 * Identifica a Bandeira e BUSCA PARCELAS via API da Efí
 */
async function identifyBrand() {
    try {
        const cardNumber = document.getElementById("inputCardNumber").value;
        const cycle = document.getElementById("inputCycle").value;
        const $installments = $('#inputInstallments');

        if (cardNumber.replace(/\D/g, '').length === 16) {
            const brand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

            // Ícones
            var icon = $('#cardVisualBrand');
            icon.removeClass().addClass('fab fa-2x');
            const brandIcons = {'visa': 'fa-cc-visa text-primary', 'mastercard': 'fa-cc-mastercard text-danger', 'amex': 'fa-cc-amex text-info', 'elo': 'fa-credit-card text-warning'};
            icon.addClass(brandIcons[brand] || 'fas fa-credit-card text-secondary');

            $('#inputCardBrand').val(brand);

            // BUSCA PARCELAS (Apenas se for Anual)
            if (cycle === 'anual') {
                const annualPrice = parseFloat($('#rawPriceAnnual').val() || 0);
                const discountPercent = parseFloat($('#discountPercent').val() || 0);
                let currentTotal = annualPrice;

                // Calcula total com desconto para a API
                if (discountPercent > 0) {
                    currentTotal = annualPrice - (annualPrice * (discountPercent / 100));
                }

                const totalCents = Math.floor(currentTotal * 100);

                const efiCode = document.getElementById('efiCode').value;
                const efiEnvironment = document.getElementById('efiEnvironment').value;
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
                        options += `<option value="${inst.installment}">${inst.installment}x de ${inst.currency}${labelJuros}</option>`;
                    });
                    $installments.html(options);
                }
            } else {
                $installments.html('<option value="1">1x (À vista)</option>');
            }
        }
    } catch (error) {
        console.error("Erro Efi:", error);
        $('#inputInstallments').html('<option value="1">1x (À vista)</option>');
    }
}

/**
 * GERAÇÃO DO PAGAMENTO
 */
async function generatePayment() {
    const $btnPay = $('.btn-pay');
    const originalBtnText = $btnPay.html();
    $btnPay.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processando...');

    const Toast = swal.mixin({toast: true, position: 'top-end', showConfirmButton: false, timer: 3000});

    try {
        const signature = document.getElementById("signature").value;
        const signaturePayment = document.getElementById("signaturePayment").value;
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
        const couponCode = document.getElementById('appliedCouponCode').value;

        const cardBrand = await EfiPay.CreditCard.setCardNumber(cardNumber).verifyCardBrand();

        const efiCode = document.getElementById('efiCode').value;
        const efiEnvironment = document.getElementById('efiEnvironment').value;
        if (cardBrand && cardBrand !== "undefined") {
            const result = await EfiPay.CreditCard
                    .setAccount(efiCode)
                    .setEnvironment(efiEnvironment)
                    .setCreditCardData({
                        brand: cardBrand, number: cardNumber, cvv: cardCVV,
                        expirationMonth: cardMonth, expirationYear: cardYear,
                        holderName: cardName, holderDocument: cardDocument.replace(/\D/g, ''),
                        reuse: isAutoRenew
                    }).getPaymentToken();

            const dir = $("#dir_site").val() ? "/" + $("#dir_site").val() : "";

            const payload = {
                signature: signature,
                signaturePayment: signaturePayment,
                payment_token: result.payment_token,
                card_mask: result.card_mask,
                cycle: cycle,
                installments: installments,
                coupon_code: couponCode,
                auto_renew: isAutoRenew ? 1 : 0,
                terms: terms,
                method: 'credit_card',
                type: 'efipay'
            };

            $.post(dir + "/signature/payment", payload, function (response) {
                var msg = response.trim().split("->");
                if (msg[0] === "1") {
                    Toast.fire({icon: 'success', title: " " + msg[1]});
                    $btnPay.prop('disabled', true).html('<i class="fas fa-check"></i> Aprovado!').addClass('btn-success');
                    setTimeout(() => {
                        $('#renewModal').modal('hide');
                        window.location.href = dir;
                    }, 1500);
                } else {
                    Toast.fire({icon: "error", title: " " + msg[1]});
                    resetButton();
                }
            });
        } else {
            Toast.fire({icon: "error", title: "Bandeira não suportada."});
            resetButton();
        }
    } catch (error) {
        console.error(error);
        Toast.fire({icon: "error", title: "Erro ao processar pagamento."});
        resetButton();
    }

    function resetButton() {
        setTimeout(function () {
            $btnPay.prop('disabled', false).html(originalBtnText);
        }, 500);
    }
}