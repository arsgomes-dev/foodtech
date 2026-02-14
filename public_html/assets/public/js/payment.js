document.addEventListener('DOMContentLoaded', function () {

    // Recupera configurações iniciais
    const config = window.paymentConfig || {
        totalValue: 0,
        isAnual: false,
        discountPercent: 0,
        couponCode: ''
    };

    // =============================================================================
    // 1. SELEÇÃO DE ELEMENTOS DO DOM
    // =============================================================================

    // Elementos de Pagamento
    const elCardNumber = document.getElementById('card_number');
    const elCardName = document.getElementById('card_holder');
    const elCardCpf = document.getElementById('card_cpf');
    const elCardExp = document.getElementById('card_expiry');
    const elCardCvv = document.getElementById('card_cvv');
    const elInstallments = document.getElementById('installments');
    const elForm = document.getElementById('paymentForm');
    const elAutoRenew = document.getElementById('autoRenewSwitch');
    const elTerms = document.getElementById('terms_id');

    // Elementos de Plano e Ciclo
    const elPlanSelector = document.getElementById('planSelector');
    const elCycleSelector = document.getElementById('cycleSelector');
    const elCycleLabel = document.getElementById('cycleLabel');
    const elEconomyLabel = document.getElementById('economyLabel');
    const elDisplayTotal = document.getElementById('displayTotal');
    const elBtnTotal = document.getElementById('btnTotal');

    // Elementos de Cupom
    const elCouponInput = document.getElementById('couponCode');
    const elBtnApplyCoupon = document.getElementById('btnApplyCoupon');
    const elBtnRemoveCoupon = document.getElementById('btnRemoveCoupon'); // NOVO
    const elCouponFeedback = document.getElementById('couponFeedback');
    const elOriginalPriceDisplay = document.getElementById('originalPriceDisplay');

    // Elementos Visuais
    const visNum = document.getElementById('displayNum');
    const visName = document.getElementById('displayName');
    const visExp = document.getElementById('displayExp');
    const visBrandIcon = document.querySelector('.card-visual i');

    // =============================================================================
    // 2. FUNÇÕES DE VALIDAÇÃO E FORMATAÇÃO
    // =============================================================================

    function formatBRL(val) {
        return val.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    }

    function validateExpiryDate(input) {
        const val = input.value;
        input.classList.remove('is-invalid', 'is-valid');

        if (val.length !== 5) {
            if (val.length > 0)
                input.classList.add('is-invalid');
            return false;
        }

        const [mm, aa] = val.split('/').map(num => parseInt(num, 10));
        const now = new Date();
        const currentYear = parseInt(now.getFullYear().toString().substr(-2));
        const currentMonth = now.getMonth() + 1;

        let isValid = true;
        if (!mm || mm < 1 || mm > 12)
            isValid = false;
        if (!aa || aa < currentYear)
            isValid = false;
        if (aa === currentYear && mm < currentMonth)
            isValid = false;

        if (!isValid) {
            input.classList.add('is-invalid');
            return false;
        } else {
            input.classList.add('is-valid');
            return true;
        }
    }

    function validateCPF(input) {
        let cpf = input.value.replace(/\D/g, '');
        input.classList.remove('is-invalid', 'is-valid');

        if (cpf === '')
            return false;

        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            input.classList.add('is-invalid');
            return false;
        }

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++)
            soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11))
            resto = 0;
        if (resto !== parseInt(cpf.substring(9, 10))) {
            input.classList.add('is-invalid');
            return false;
        }

        soma = 0;
        for (let i = 1; i <= 10; i++)
            soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11))
            resto = 0;
        if (resto !== parseInt(cpf.substring(10, 11))) {
            input.classList.add('is-invalid');
            return false;
        }

        input.classList.add('is-valid');
        return true;
    }

    // =============================================================================
    // 3. LÓGICA DE CUPOM DE DESCONTO (APLICAR E REMOVER)
    // =============================================================================

    if (elBtnApplyCoupon) {
        elBtnApplyCoupon.addEventListener('click', applyCoupon);
    }

    if (elBtnRemoveCoupon) {
        elBtnRemoveCoupon.addEventListener('click', removeCoupon);
    }

    async function applyCoupon() {
        const code = elCouponInput.value.trim();

        if (!code) {
            showCouponFeedback('Digite um código.', 'text-danger');
            return;
        }

        elBtnApplyCoupon.disabled = true;
        elBtnApplyCoupon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const formData = new FormData();
            formData.append('code', code);

            const response = await fetch('/validatecoupon', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                // SUCESSO
                config.discountPercent = parseFloat(data.discount_percent);
                config.couponCode = code;

                // Feedback visual
                showCouponFeedback(data.message, 'text-success');
                elCouponInput.classList.add('is-valid');
                elCouponInput.classList.remove('is-invalid');

                // Trava o input e troca os botões
                elCouponInput.disabled = true;
                elBtnApplyCoupon.style.display = 'none';
                elBtnRemoveCoupon.style.display = 'block';

                // Recalcula
                updatePaymentValues();

            } else {
                // ERRO
                removeCoupon(false); // Reseta estados sem limpar mensagem imediatamente
                showCouponFeedback(data.message, 'text-danger');
                elCouponInput.classList.add('is-invalid');
            }

        } catch (error) {
            console.error(error);
            showCouponFeedback('Erro ao validar cupom.', 'text-danger');
        } finally {
            elBtnApplyCoupon.disabled = false;
            elBtnApplyCoupon.innerText = 'Aplicar';
        }
    }

    function removeCoupon(clearMsg = true) {
        // Zera configurações
        config.discountPercent = 0;
        config.couponCode = '';

        // Destrava input e reseta botões
        elCouponInput.value = '';
        elCouponInput.disabled = false;
        elCouponInput.classList.remove('is-valid', 'is-invalid');

        elBtnApplyCoupon.style.display = 'block';
        elBtnRemoveCoupon.style.display = 'none';

        if (clearMsg) {
            showCouponFeedback('', '');
        }

        // Recalcula (volta ao preço original)
        updatePaymentValues();
    }

    function showCouponFeedback(msg, className) {
        if (elCouponFeedback) {
            elCouponFeedback.innerText = msg;
            elCouponFeedback.className = 'small mt-1 fw-bold ' + className;
        }
    }

    // =============================================================================
    // 4. LÓGICA DE ATUALIZAÇÃO DE VALORES
    // =============================================================================

    function updatePaymentValues() {
        if (!elPlanSelector || !elCycleSelector)
            return;

        const selectedOption = elPlanSelector.options[elPlanSelector.selectedIndex];
        let rawPrice = selectedOption.getAttribute('data-price');
        let basePrice = parseFloat(rawPrice);

        const isAnual = elCycleSelector.checked;

        let totalOriginal = basePrice;
        if (isAnual) {
            totalOriginal = basePrice * 10;
        }

        // Aplica Desconto
        let finalValue = totalOriginal;
        if (config.discountPercent > 0) {
            const discountValue = totalOriginal * (config.discountPercent / 100);
            finalValue = totalOriginal - discountValue;

            if (elOriginalPriceDisplay) {
                elOriginalPriceDisplay.innerText = formatBRL(totalOriginal);
                elOriginalPriceDisplay.style.setProperty('display', 'block', 'important');
            }
        } else {
            if (elOriginalPriceDisplay)
                elOriginalPriceDisplay.style.setProperty('display', 'none', 'important');
        }

        // Atualiza Config Global
        config.totalValue = finalValue;
        config.isAnual = isAnual;

        // UI
        if (elCycleLabel)
            elCycleLabel.innerText = isAnual ? 'Anual' : 'Mensal';
        if (elEconomyLabel)
            elEconomyLabel.style.display = isAnual ? 'block' : 'none';

        const formattedTotal = formatBRL(finalValue);

        if (elDisplayTotal)
            elDisplayTotal.innerText = formattedTotal;

        const elBtnPaySpan = document.querySelector('.btn-pay span') || elBtnTotal;
        if (elBtnPaySpan)
            elBtnPaySpan.innerText = formattedTotal.replace('R$', '').trim();

        // Parcelas (Importante chamar a API de novo com o novo valor se tiver cartão)
        const currentCard = elCardNumber.value.replace(/\D/g, '');
        if (currentCard.length >= 6) {
            identifyBrandAndInstallments(currentCard);
        } else {
            elInstallments.innerHTML = `<option value="1">1x de ${formattedTotal} (À vista)</option>`;
        }
    }

    // =============================================================================
    // 5. MÁSCARAS E EVENTOS DE INPUT
    // =============================================================================

    if (elPlanSelector)
        elPlanSelector.addEventListener('change', updatePaymentValues);
    if (elCycleSelector)
        elCycleSelector.addEventListener('change', updatePaymentValues);

    if (elCardNumber) {
        elCardNumber.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '');
            if (val.length > 16)
                val = val.substring(0, 16);
            this.value = val;
            let formatted = val.match(/.{1,4}/g)?.join(' ') || '•••• •••• •••• ••••';
            visNum.innerText = formatted;
            if (val.length >= 6)
                identifyBrandAndInstallments(val);
        });
    }

    if (elCardName) {
        elCardName.addEventListener('input', function () {
            let val = this.value.toUpperCase();
            if (val.length > 22)
                val = val.substring(0, 22) + '...';
            visName.innerText = val || 'SEU NOME';
        });
    }

    if (elCardCpf) {
        elCardCpf.addEventListener('input', function () {
            let v = this.value.replace(/\D/g, "");
            if (v.length > 11)
                v = v.substring(0, 11);
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d)/, "$1.$2");
            v = v.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            this.value = v;
            if (this.classList.contains('is-invalid'))
                this.classList.remove('is-invalid');
        });
        elCardCpf.addEventListener('blur', function () {
            validateCPF(this);
        });
    }

    if (elCardExp) {
        elCardExp.addEventListener('input', function () {
            let val = this.value.replace(/\D/g, '');
            if (val.length >= 2)
                val = val.substring(0, 2) + '/' + val.substring(2, 4);
            this.value = val;
            visExp.innerText = val || 'MM/AA';
            if (this.classList.contains('is-invalid'))
                this.classList.remove('is-invalid');
        });
        elCardExp.addEventListener('blur', function () {
            validateExpiryDate(this);
        });
    }

    if (elCardCvv) {
        elCardCvv.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // =============================================================================
    // 6. INTEGRAÇÃO EFI
    // =============================================================================

    async function identifyBrandAndInstallments(cardNumber) {
        try {
            const brand = await EfiPay.CreditCard
                    .setCardNumber(cardNumber)
                    .verifyCardBrand();

            if (brand && brand !== 'undefined') {
                updateBrandIcon(brand);
                fetchInstallments(brand);
            }
        } catch (error) {
        }
    }

    function updateBrandIcon(brand) {
        if (!visBrandIcon)
            return;
        visBrandIcon.className = '';
        if (brand === 'visa')
            visBrandIcon.className = 'fab fa-cc-visa fa-2x opacity-75';
        else if (brand === 'mastercard')
            visBrandIcon.className = 'fab fa-cc-mastercard fa-2x opacity-75';
        else if (brand === 'amex')
            visBrandIcon.className = 'fab fa-cc-amex fa-2x opacity-75';
        else if (brand === 'elo')
            visBrandIcon.className = 'fas fa-credit-card fa-2x opacity-75';
        else
            visBrandIcon.className = 'fas fa-credit-card fa-2x opacity-75';
    }

    async function fetchInstallments(brand) {
        if (!config.isAnual) {
            elInstallments.innerHTML = `<option value="1">1x de ${formatBRL(config.totalValue)} (À vista)</option>`;
            return;
        }

        try {
            // Usa o config.totalValue (que já tem o desconto aplicado se houver)
            const totalInCents = Math.floor(config.totalValue * 100);

            const response = await EfiPay.CreditCard
                    .setAccount(EFI_CONFIG.account)
                    .setEnvironment(EFI_CONFIG.environment)
                    .setBrand(brand)
                    .setTotal(totalInCents)
                    .getInstallments();

            if (response.installments && response.installments.length > 0) {
                let optionsHtml = '';
                response.installments.forEach(inst => {
                    let text = `${inst.installment}x de ${inst.currency}`;
                    text += inst.has_interest ? " (com juros)" : " (sem juros)";
                    optionsHtml += `<option value="${inst.installment}">${text}</option>`;
                });
                elInstallments.innerHTML = optionsHtml;
            }
        } catch (error) {
            elInstallments.innerHTML = `<option value="1">1x de ${formatBRL(config.totalValue)} (À vista)</option>`;
        }
    }

    // =============================================================================
    // 7. SUBMIT DO FORMULÁRIO
    // =============================================================================

    if (elForm) {
        elForm.addEventListener('submit', function (e) {
            e.preventDefault();
            generatePayment();
        });
    }

    async function generatePayment() {
        if (elCardExp && !validateExpiryDate(elCardExp)) {
            elCardExp.focus();
            Swal.fire({icon: 'warning', title: 'Data Inválida', text: 'Verifique a validade do cartão.', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false});
            return;
        }

        if (elCardCpf && !validateCPF(elCardCpf)) {
            elCardCpf.focus();
            Swal.fire({icon: 'warning', title: 'CPF Inválido', text: 'Verifique o número do documento.', toast: true, position: 'top-end', timer: 3000, showConfirmButton: false});
            return;
        }

        const $btnPay = document.querySelector('.btn-pay');
        const originalText = $btnPay.innerHTML;
        $btnPay.disabled = true;
        $btnPay.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processando...';

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
        });

        try {
            const isBlocked = await EfiPay.CreditCard.isScriptBlocked();
            if (isBlocked)
                throw new Error("Script de segurança bloqueado.");

            const cardNumber = elCardNumber.value;
            const cardName = elCardName.value;
            const cardCvv = elCardCvv.value;
            const cardCpfClean = elCardCpf.value.replace(/\D/g, '');
            const [expMonth, expYear] = elCardExp.value.split('/');
            const fullYear = '20' + expYear;
            const selectedInstallment = elInstallments.value;
            const isAutoRenew = elAutoRenew ? elAutoRenew.checked : false;
            const isTerms = elTerms.value;
            const currentPlanId = elPlanSelector.value;
            const currentCycle = elCycleSelector.checked ? 'anual' : 'mensal';

            const brand = await EfiPay.CreditCard
                    .setCardNumber(cardNumber)
                    .verifyCardBrand();

            if (brand === 'undefined' || brand === 'unsupported')
                throw new Error("Bandeira não suportada.");

            const result = await EfiPay.CreditCard
                    .setAccount(EFI_CONFIG.account)
                    .setEnvironment(EFI_CONFIG.environment)
                    .setCreditCardData({
                        brand: brand,
                        number: cardNumber,
                        cvv: cardCvv,
                        expirationMonth: expMonth,
                        expirationYear: fullYear,
                        holderName: cardName,
                        holderDocument: cardCpfClean,
                        reuse: isAutoRenew
                    })
                    .getPaymentToken();

            const formData = new FormData();
            formData.append('payment_token', result.payment_token);
            formData.append('card_mask', result.card_mask);
            formData.append('installments', selectedInstallment);
            formData.append('brand', brand);
            formData.append('cpf', cardCpfClean);
            formData.append('auto_renew', isAutoRenew ? 1 : 0);
            formData.append('terms', isTerms);
            formData.append('plan_id', currentPlanId);
            formData.append('cycle', currentCycle);
            formData.append('coupon_code', config.couponCode || '');
            formData.append('method', 'credit_card');
            formData.append('type', 'efipay');
            // ... (código anterior de coleta de dados e FormData) ...

            // 1. ENVIA PARA O BACKEND
            const response = await fetch('/eficredit', {
                method: 'POST',
                body: formData
            });

            // 2. RECEBE O TEXTO PURO
            const responseText = await response.text();

            // 3. SEPARA PELO DELIMITADOR "->"
            // Ex: "1->Pagamento aprovado" vira ["1", "Pagamento aprovado"]
            const parts = responseText.trim().split("->");

            // Verifica se o formato é válido (tem pelo menos código e mensagem)
            if (parts.length >= 2) {
                const code = parts[0];     // "1", "2" ou "3"
                const message = parts[1];  // A mensagem de texto

                if (code === '1') {
                    // --- TIPO 1: SUCESSO ---
                    Toast.fire({icon: 'success', title: message});

                    // Atualiza visual do botão
                    $btnPay.innerHTML = '<i class="fas fa-check"></i> Sucesso!';
                    $btnPay.classList.replace('btn-pay', 'btn-success');

                    // Redireciona
                    setTimeout(() => {
                        window.location.href = '/app/home';
                    }, 2000);

                } else if (code === '2') {
                    // --- TIPO 2: ERRO / AVISO ---
                    Toast.fire({icon: 'error', title: message});
                    resetButton($btnPay, originalText);

                } else if (code === '3') {
                    // --- TIPO 3: INFORMATIVO / ESCOLHA ---
                    // Ex: "Selecione o parcelamento" ou avisos de fluxo
                    Toast.fire({icon: 'warning', title: message});
                    resetButton($btnPay, originalText);

                } else {
                    // Código desconhecido (fallback)
                    Toast.fire({icon: 'error', title: message});
                    resetButton($btnPay, originalText);
                }

            } else {
                // --- FORMATO INVÁLIDO ---
                // Caso o servidor retorne um Fatal Error do PHP ou JSON não tratado
                console.error("Resposta do servidor:", responseText);

                // Tenta ver se é JSON por segurança, senão erro genérico
                try {
                    const json = JSON.parse(responseText);
                    Toast.fire({icon: 'error', title: json.message || 'Erro inesperado.'});
                } catch (e) {
                    Toast.fire({icon: 'error', title: 'Erro de comunicação com o servidor.'});
                }
                resetButton($btnPay, originalText);
            }



        } catch (error) {
            console.error(error);
            Toast.fire({icon: 'error', title: error.message || 'Erro no pagamento.'});
            resetButton($btnPay, originalText);
        }
    }

    function resetButton(btn, text) {
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = text;
        }, 1000);
    }

    setTimeout(updatePaymentValues, 100);
});

function acceptTerms() {
    document.getElementById('autoRenewSwitch').checked = true;
    var modalEl = document.getElementById('termsModal');
    var modal = bootstrap.Modal.getInstance(modalEl);
    modal.hide();
}