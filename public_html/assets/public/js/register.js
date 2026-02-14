document.addEventListener('DOMContentLoaded', function () {

    // --- VARIÁVEIS GLOBAIS DO WIZARD ---
    let currentStep = 0;
    const steps = document.querySelectorAll(".step-content");
    const indicators = document.querySelectorAll(".step-item");
    const cicloRadios = document.querySelectorAll('input[name="ciclo"]');
    const planRadios = document.querySelectorAll('input[name="plano"]');
    const x_y = document.getElementById('x_y').value;

    // INICIALIZAÇÃO VISUAL
    showStep(currentStep);
    updateCardPrices();

    // ============================================================
    // 1. LÓGICA DE VERIFICAÇÃO DE E-MAIL (AJAX)
    // ============================================================
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');

    if (emailInput) {
        emailInput.addEventListener('blur', function () {
            const email = this.value;
            if (!email || !email.includes('@'))
                return;

            emailInput.classList.remove('is-invalid', 'is-valid');

            fetch('/landing/checkemail', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({email: email})
            })
                    .then(response => response.json())
                    .then(data => {
                        emailInput.classList.remove('is-invalid', 'is-valid');
                        if (data.status === 'available') {
                            emailInput.classList.add('is-valid');
                        } else if (data.status === 'exists') {
                            emailInput.classList.add('is-invalid');
                            if (emailFeedback)
                                emailFeedback.innerHTML = 'Este e-mail já possui conta. <a href="/app/login" class="fw-bold text-danger text-decoration-none">Clique aqui para entrar</a>.';
                        } else if (data.status === 'invalid_dns') {
                            emailInput.classList.add('is-invalid');
                            if (emailFeedback)
                                emailFeedback.innerHTML = `<i class="fas fa-wifi me-1"></i> ${data.message}`;
                        } else {
                            emailInput.classList.add('is-invalid');
                            if (emailFeedback)
                                emailFeedback.innerText = 'Por favor, insira um e-mail válido.';
                        }
                    })
                    .catch(error => console.error('Erro ao verificar email:', error));
        });

        emailInput.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    }

    // ============================================================
    // 1.1. LÓGICA DE VERIFICAÇÃO DE CPF (VISUAL)
    // ============================================================
    const cpfInput = document.getElementById('cpf');

    if (cpfInput) {
        cpfInput.addEventListener('blur', function () {
            const cpfValue = this.value;
            this.classList.remove('is-invalid', 'is-valid');
            if (cpfValue.length === 0)
                return;

            if (isCPF(cpfValue)) {
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
                let feedback = this.nextElementSibling;
                if (feedback && feedback.classList.contains('invalid-feedback')) {
                    feedback.innerText = "CPF inválido.";
                }
            }
        });

        cpfInput.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });
    }

    // ============================================================
    // 1.2. VALIDAÇÃO DE SENHAS IGUAIS (ONBLUR)
    // ============================================================
    const senhaInput = document.getElementById('senha');
    const confSenhaInput = document.getElementById('conf_senha');

    if (senhaInput && confSenhaInput) {
        confSenhaInput.addEventListener('blur', function () {
            const senha = senhaInput.value;
            const confirmacao = this.value;

            this.classList.remove('is-invalid', 'is-valid');
            if (confirmacao === '')
                return;

            if (senha !== confirmacao) {
                this.classList.add('is-invalid');
                let feedback = this.nextElementSibling;
                // Pula botão de olho se existir
                if (feedback && (feedback.tagName === 'BUTTON' || feedback.classList.contains('input-group-text'))) {
                    feedback = feedback.nextElementSibling;
                }
                if (feedback)
                    feedback.innerText = "As senhas não coincidem.";
            } else {
                this.classList.add('is-valid');
            }
        });

        confSenhaInput.addEventListener('input', function () {
            this.classList.remove('is-invalid');
        });

        senhaInput.addEventListener('input', function () {
            confSenhaInput.classList.remove('is-valid');
            if (confSenhaInput.value !== '') {
                if (this.value !== confSenhaInput.value) {
                    confSenhaInput.classList.remove('is-valid');
                }
            }
        });
    }

    // ============================================================
    // 2. LÓGICA DE PLANOS E PREÇOS
    // ============================================================
    cicloRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            updateCardPrices();
            updateSummary();
        });
    });
    planRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            updateSummary();
        });
    });

    function updateCardPrices() {
        const anualInput = document.getElementById('ciclo_anual');
        const isAnual = anualInput ? anualInput.checked : false;
        const planInputs = document.querySelectorAll('input[name="plano"]');

        planInputs.forEach(input => {
            let basePrice = parseFloat(input.getAttribute('data-price'));
            let displayPrice = isAnual ? basePrice * x_y : basePrice;
            let label = input.nextElementSibling;
            let priceElement = label.querySelector('.price-display');

            if (priceElement) {
                const priceText = formatCurrency(displayPrice);
                const suffix = isAnual ? ' <small class="fs-6 text-muted fw-normal">/ano</small>' : ' <small class="fs-6 text-muted fw-normal">/mês</small>';
                priceElement.innerHTML = priceText + suffix;
            }
        });
    }

    // ============================================================
    // 3. NAVEGAÇÃO DO WIZARD (nextPrev)
    // ============================================================

    window.nextPrev = function (n) {

        // --- TRAVA DE CPF (Passo 1 -> 2) ---
        if (n === 1 && currentStep === 1) {
            const cpfInput = document.getElementById('cpf');
            if (cpfInput && !isCPF(cpfInput.value)) {
                showInputError('cpf', 'CPF inválido. Verifique os números.');
                return false;
            } else {
                showInputError('cpf', 'CPF inválido. Verifique os números.');
            }
        }

        // --- TRAVA DE E-MAIL E SENHA (Passo 3 -> 4) ---
        if (n === 1 && currentStep === 3) {
            // E-mail
            if (emailInput) {
                if (emailInput.classList.contains('is-invalid')) {
                    emailInput.focus();
                    emailInput.classList.add('shake');
                    setTimeout(() => emailInput.classList.remove('shake'), 500);
                    return false;
                }
                if (!emailInput.value) {
                    showInputError('email', 'O e-mail é obrigatório.');
                    return false;
                }
            }
            // Senha
            const senhaEl = document.getElementById('senha');
            const confSenhaEl = document.getElementById('conf_senha');
            if (senhaEl && confSenhaEl) {
                if (senhaEl.value !== confSenhaEl.value) {
                    showInputError('conf_senha', 'As senhas não coincidem.');
                    return false;
                }
            }
        }

        // Validação genérica
        if (n === 1 && !validateForm())
            return false;

        // Atualiza resumo
        if (n === 1 && currentStep === 2)
            updateSummary();

        // --------------------------------------------------------
        // LÓGICA DE DECISÃO: AVANÇAR OU ENVIAR?
        // --------------------------------------------------------
        let nextStepIndex = currentStep + n;

        // Se o próximo passo for maior que o total => ENVIO (Ajax + MD5)
        if (nextStepIndex >= steps.length) {
            submitFormAjax();
            return false; // Não incrementa currentStep
        }

        // Se não for envio, apenas avança
        currentStep = nextStepIndex;
        showStep(currentStep);
    }

    // ============================================================
    // FUNÇÃO DE ENVIO (AJAX + MD5 + TERMOS)
    // ============================================================
    function submitFormAjax() {

        // 1. TRAVA DE SEGURANÇA: E-MAIL
        if (emailInput && emailInput.classList.contains('is-invalid')) {
            emailInput.focus();
            return false;
        }

        // 2. TRAVA DE SEGURANÇA: TERMOS E CONDIÇÕES (NOVO)
        const termosCheckbox = document.getElementById('termos');
        if (termosCheckbox) {
            if (!termosCheckbox.checked) {
                // Adiciona classe de erro
                termosCheckbox.classList.add('is-invalid');

                // Feedback visual (Shake)
                termosCheckbox.focus();
                termosCheckbox.classList.add('shake'); // Certifique-se de ter o CSS .shake
                setTimeout(() => termosCheckbox.classList.remove('shake'), 500);

                // Adiciona evento para limpar o erro assim que clicar
                termosCheckbox.addEventListener('change', function () {
                    this.classList.remove('is-invalid');
                }, {once: true});

                return false; // IMPEDE O ENVIO
            }
        }

        // 3. UI Loading
        const nextBtn = document.getElementById("nextBtn");
        const originalBtnText = nextBtn.innerHTML;
        nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processando...';
        nextBtn.disabled = true;

        const form = document.getElementById("wizardForm");

        // 4. CRIPTOGRAFIA (MD5)
        const senhaInput = document.getElementById('senha');
        const confSenhaInput = document.getElementById('conf_senha');

        if (senhaInput && typeof md5c === 'function') {
            // Cria input hidden para a senha hash
            var p = document.createElement("input");
            p.name = "passw";
            p.type = "hidden";
            p.value = md5c(senhaInput.value);
            form.appendChild(p);

            // Confirmação (opcional)
            if (confSenhaInput) {
                var p_conf = document.createElement("input");
                p_conf.name = "passw_conf";
                p_conf.type = "hidden";
                p_conf.value = md5c(confSenhaInput.value);
                form.appendChild(p_conf);
            }

            // LIMPA OS CAMPOS ORIGINAIS
            senhaInput.value = "";
            if (confSenhaInput)
                confSenhaInput.value = "";

        } else if (senhaInput && typeof md5c === 'undefined') {
            console.error("ERRO: Biblioteca MD5 não encontrada.");
        }

        // 5. Cria FormData
        const formData = new FormData(form);

        // 6. Fetch
        fetch('/landing/Processregistration', {
            method: 'POST',
            body: formData
        })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'registered') {
                        window.location.href = '/payment';
                    } else {
                        // ERRO - Restaura UI
                        nextBtn.innerHTML = originalBtnText;
                        nextBtn.disabled = false;

                        // Remove inputs hidden criados
                        const hiddenP = form.querySelector('input[name="p"]');
                        const hiddenPConf = form.querySelector('input[name="p_conf"]');
                        if (hiddenP)
                            hiddenP.remove();
                        if (hiddenPConf)
                            hiddenPConf.remove();

                        // Tratamento de Erros
                        if (data.message && data.message.toLowerCase().includes('cpf')) {
                            jumpToStep(1);
                            showInputError('cpf', data.message);
                        } else if (data.message && data.message.toLowerCase().includes('senha')) {
                            jumpToStep(3);
                            showInputError('senha', "Por favor, digite a senha novamente.");
                        } else if (['exists', 'invalid_dns', 'invalid_format'].includes(data.status)) {
                            jumpToStep(3);
                            showInputError('email', data.message);
                        } else {
                            alert("Erro: " + data.message);
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Ocorreu um erro na comunicação com o servidor.');
                    nextBtn.innerHTML = originalBtnText;
                    nextBtn.disabled = false;
                });
    }

    // ============================================================
    // 4. FUNÇÕES AUXILIARES DO WIZARD
    // ============================================================

    function showStep(n) {
        steps.forEach(s => s.classList.remove('active'));
        steps[n].classList.add('active');

        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        if (prevBtn)
            prevBtn.style.display = (n === 0) ? "none" : "inline-block";

        if (nextBtn) {
            if (n === (steps.length - 1)) {
                nextBtn.innerHTML = 'Pagar e Assinar <i class="fas fa-check ms-2"></i>';
            } else {
                nextBtn.innerHTML = 'Próximo <i class="fas fa-arrow-right ms-2"></i>';
            }
        }

        indicators.forEach((ind, index) => {
            ind.classList.remove('active', 'completed');
            if (index < n)
                ind.classList.add('completed');
            if (index === n)
                ind.classList.add('active');
        });
    }

    function validateForm() {
        let valid = true;
        // Validação de segurança para evitar erro de console
        if (!steps || steps.length === 0 || !steps[currentStep]) {
            console.error("Erro estrutural no HTML do Wizard.");
            return false;
        }

        const currentInputs = steps[currentStep].querySelectorAll("input[required], select[required]");
        currentInputs.forEach(input => {
            if (!input.value) {
                input.classList.add("is-invalid");
                valid = false;
            } else {
                input.classList.remove("is-invalid");
            }
        });
        return valid;
    }

    function updateSummary() {
        const planRadio = document.querySelector('input[name="plano"]:checked');
        const cicloRadio = document.querySelector('input[name="ciclo"]:checked');

        if (planRadio && cicloRadio) {
            const planName = planRadio.getAttribute('data-name');
            let planPrice = parseFloat(planRadio.getAttribute('data-price'));
            const cicloId = cicloRadio.id;
            const cicloLabelElement = document.querySelector(`label[for="${cicloId}"]`);
            const cicloLabel = cicloLabelElement ? cicloLabelElement.innerText : 'Ciclo';

            let total = planPrice;
            if (cicloRadio.value === 'yearly') {
                let parcelas = 0;
                if ((12 - x_y) <= 0) {
                    parcelas = 12;
                } else {
                    parcelas = x_y;
                }
                total = planPrice * parcelas;
            }

            const elResumoPlano = document.getElementById('resumo-plano');
            const elResumoCiclo = document.getElementById('resumo-ciclo');
            const elResumoValor = document.getElementById('resumo-valor');

            if (elResumoPlano)
                elResumoPlano.innerText = planName;
            if (elResumoCiclo)
                elResumoCiclo.innerText = cicloLabel;
            if (elResumoValor)
                elResumoValor.innerText = formatCurrency(total);
        }
    }

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    }

    // ============================================================
    // 5. INTEGRAÇÕES (VIACEP & MÁSCARAS)
    // ============================================================

    // --- VIACEP ---
    const cepInput = document.getElementById('cep');
    if (cepInput) {
        cepInput.addEventListener('blur', function () {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                const loadingIcon = document.getElementById('cep-loading');
                if (loadingIcon)
                    loadingIcon.style.display = 'inline-block';

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                        .then(r => r.json())
                        .then(data => {
                            if (loadingIcon)
                                loadingIcon.style.display = 'none';

                            if (!data.erro) {
                                if (document.getElementById('logradouro'))
                                    document.getElementById('logradouro').value = data.logradouro;
                                if (document.getElementById('bairro'))
                                    document.getElementById('bairro').value = data.bairro;
                                if (document.getElementById('cidade'))
                                    document.getElementById('cidade').value = data.localidade;
                                if (document.getElementById('uf'))
                                    document.getElementById('uf').value = data.uf;

                                const elError = document.getElementById('cep-error');
                                if (elError)
                                    elError.classList.add('d-none');
                                if (document.getElementById('numero'))
                                    document.getElementById('numero').focus();
                            } else {
                                const elError = document.getElementById('cep-error');
                                if (elError)
                                    elError.classList.remove('d-none');
                                limparFormularioCEP();
                            }
                        })
                        .catch(() => {
                            if (loadingIcon)
                                loadingIcon.style.display = 'none';
                        });
            }
        });
    }

    // --- MÁSCARAS ---
    if (cpfInput) {
        cpfInput.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, "");
            v = v.replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d)/, "$1.$2").replace(/(\d{3})(\d{1,2})$/, "$1-$2");
            e.target.value = v;
        });
    }
    const celularInput = document.getElementById('celular');
    if (celularInput) {
        celularInput.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, "");
            v = v.replace(/^(\d{2})(\d)/g, "($1) $2").replace(/(\d)(\d{4})$/, "$1-$2");
            e.target.value = v;
        });
    }
    if (cepInput) {
        cepInput.addEventListener('input', e => {
            let v = e.target.value.replace(/\D/g, "");
            v = v.replace(/^(\d{5})(\d)/, "$1-$2");
            e.target.value = v;
        });
    }

    // ============================================================
    // 6. FUNÇÕES UTILITÁRIAS GLOBAIS
    // ============================================================

    function jumpToStep(stepIndex) {
        currentStep = stepIndex;
        showStep(currentStep);
    }

    function showInputError(inputId, message) {
        const input = document.getElementById(inputId);
        if (input) {
            input.classList.add('is-invalid');
            let feedback = input.nextElementSibling;
            if (feedback && (feedback.tagName === 'BUTTON' || feedback.classList.contains('input-group-text'))) {
                feedback = feedback.nextElementSibling;
            }
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.innerHTML = message;
                feedback.style.display = 'block';
            }
            input.focus();
            input.classList.add('shake');
            setTimeout(() => input.classList.remove('shake'), 500);
            input.addEventListener('input', function () {
                this.classList.remove('is-invalid');
            }, {once: true});
        }
    }

    function isCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf == '')
            return false;
        if (cpf.length != 11 ||
                cpf == "00000000000" || cpf == "11111111111" || cpf == "22222222222" ||
                cpf == "33333333333" || cpf == "44444444444" || cpf == "55555555555" ||
                cpf == "66666666666" || cpf == "77777777777" || cpf == "88888888888" ||
                cpf == "99999999999")
            return false;

        let add = 0;
        for (let i = 0; i < 9; i++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        let rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(9)))
            return false;

        add = 0;
        for (let i = 0; i < 10; i++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11)
            rev = 0;
        if (rev != parseInt(cpf.charAt(10)))
            return false;
        return true;
    }
});

// ============================================================
// FUNÇÕES EXTERNAS (Disponíveis para onclick no HTML)
// ============================================================

function limparFormularioCEP() {
    if (document.getElementById('logradouro'))
        document.getElementById('logradouro').value = "";
    if (document.getElementById('bairro'))
        document.getElementById('bairro').value = "";
    if (document.getElementById('cidade'))
        document.getElementById('cidade').value = "";
    if (document.getElementById('uf'))
        document.getElementById('uf').value = "";
}

function togglePass(campo, icon) {
    const senhaInput = document.getElementById(campo);
    const eyeIcon = document.getElementById(icon);

    if (senhaInput && eyeIcon) {
        if (senhaInput.type === 'password') {
            senhaInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            senhaInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
}