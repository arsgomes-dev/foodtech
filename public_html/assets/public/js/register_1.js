document.addEventListener('DOMContentLoaded', function () {

    // --- VARIÁVEIS GLOBAIS DO WIZARD ---
    let currentStep = 0;
    const steps = document.querySelectorAll(".step-content");
    const indicators = document.querySelectorAll(".step-item");
    const cicloRadios = document.querySelectorAll('input[name="ciclo"]');

    // INICIALIZAÇÃO VISUAL
    showStep(currentStep);
    updateCardPrices(); 

    // ============================================================
    // 1. LÓGICA DE VERIFICAÇÃO DE E-MAIL (AJAX)
    // ============================================================
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');

    if (emailInput) {
        // Evento ao sair do campo (Blur)
        emailInput.addEventListener('blur', function () {
            const email = this.value;

            // Validação visual básica
            if (!email || !email.includes('@')) return;

            // Limpa estados anteriores
            emailInput.classList.remove('is-invalid', 'is-valid');

            fetch('/landing/checkemail', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email })
            })
                .then(response => response.json())
                .then(data => {
                    emailInput.classList.remove('is-invalid', 'is-valid');

                    if (data.status === 'available') {
                        // SUCESSO
                        emailInput.classList.add('is-valid');
                    } else if (data.status === 'exists') {
                        // JÁ CADASTRADO
                        emailInput.classList.add('is-invalid');
                        if (emailFeedback) emailFeedback.innerHTML = 'Este e-mail já possui conta. <a href="/app/login" class="fw-bold text-danger text-decoration-none">Clique aqui para entrar</a>.';
                    } else if (data.status === 'invalid_dns') {
                        // DOMÍNIO INVÁLIDO
                        emailInput.classList.add('is-invalid');
                        if (emailFeedback) emailFeedback.innerHTML = `<i class="fas fa-wifi me-1"></i> ${data.message}`;
                    } else {
                        // FORMATO INVÁLIDO
                        emailInput.classList.add('is-invalid');
                        if (emailFeedback) emailFeedback.innerText = 'Por favor, insira um e-mail válido.';
                    }
                })
                .catch(error => console.error('Erro ao verificar email:', error));
        });

        // Limpa erro ao digitar
        emailInput.addEventListener('input', function () {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
            }
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
            if (cpfValue.length === 0) return;

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
        // Quando sair do campo "Confirmar Senha"
        confSenhaInput.addEventListener('blur', function () {
            const senha = senhaInput.value;
            const confirmacao = this.value;

            this.classList.remove('is-invalid', 'is-valid');
            if (confirmacao === '') return;

            if (senha !== confirmacao) {
                // ERRO
                this.classList.add('is-invalid');
                let feedback = this.nextElementSibling;
                // Se o próximo for botão (ex: ver senha), pula
                if (feedback && (feedback.tagName === 'BUTTON' || feedback.classList.contains('input-group-text'))) {
                    feedback = feedback.nextElementSibling;
                }
                if (feedback) feedback.innerText = "As senhas não coincidem.";
            } else {
                // SUCESSO
                this.classList.add('is-valid');
            }
        });

        confSenhaInput.addEventListener('input', function () {
            this.classList.remove('is-invalid', 'is-valid');
        });

        // Se mudar a senha principal, reseta a confirmação
        senhaInput.addEventListener('input', function () {
            confSenhaInput.classList.remove('is-valid');
            if (confSenhaInput.value !== '') {
                // Opcional: Revalidar instantaneamente ou limpar erro
                if (this.value !== confSenhaInput.value) {
                    confSenhaInput.classList.remove('is-valid');
                    // Não mostra erro imediatamente para não atrapalhar digitação
                }
            }
        });
    }

    // ============================================================
    // 2. LÓGICA DE PLANOS E PREÇOS (MENSAL/ANUAL)
    // ============================================================
    cicloRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            updateCardPrices(); 
            updateSummary();    
        });
    });

    function updateCardPrices() {
        const anualInput = document.getElementById('ciclo_anual');
        const isAnual = anualInput ? anualInput.checked : false;
        const planInputs = document.querySelectorAll('input[name="plano"]');

        planInputs.forEach(input => {
            let basePrice = parseFloat(input.getAttribute('data-price'));
            let displayPrice = basePrice;
            let label = input.nextElementSibling;
            let priceElement = label.querySelector('.price-display');

            if (isAnual) {
                displayPrice = basePrice * 10;
            } else {
                displayPrice = basePrice;
            }

            if (priceElement) {
                const priceText = formatCurrency(displayPrice);
                const suffix = isAnual ? ' <small class="fs-6 text-muted fw-normal">/ano</small>' : ' <small class="fs-6 text-muted fw-normal">/mês</small>';
                priceElement.innerHTML = priceText + suffix;
            }
        });
    }

    // ============================================================
    // 3. NAVEGAÇÃO DO WIZARD (nextPrev - O CÉREBRO)
    // ============================================================

    window.nextPrev = function (n) {
        
        // --- TRAVA DE CPF (Passo 1 -> 2) ---
        if (n === 1 && currentStep === 1) {
            const cpfInput = document.getElementById('cpf');
            if (cpfInput && !isCPF(cpfInput.value)) {
                showInputError('cpf', 'CPF inválido. Verifique os números.');
                return false; 
            }
        }

        // --- TRAVA DE E-MAIL E SENHA (Passo 3 -> 4) ---
        if (n === 1 && currentStep === 3) {
            
            // 1. Validação de Email
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

            // 2. Validação de Senha (NOVO)
            const senhaEl = document.getElementById('senha');
            const confSenhaEl = document.getElementById('conf_senha');
            
            if (senhaEl && confSenhaEl) {
                if (senhaEl.value !== confSenhaEl.value) {
                    showInputError('conf_senha', 'As senhas não coincidem.');
                    return false;
                }
            }
        }

        // Validação genérica de campos vazios (required)
        if (n === 1 && !validateForm()) return false;

        // Se estiver indo para o Resumo (Passo 2 -> 3), atualiza valores
        if (n === 1 && currentStep === 2) updateSummary();

        currentStep += n;

        // ============================================================
        //  SUBMIT FINAL (ENVIO PARA O PHP)
        // ============================================================
        if (currentStep >= steps.length) {

            // 1. Trava de segurança visual final
            if (emailInput && emailInput.classList.contains('is-invalid')) {
                currentStep--;
                showStep(currentStep);
                emailInput.focus();
                return false;
            }

            // 2. Prepara UI para carregamento
            const nextBtn = document.getElementById("nextBtn");
            const originalBtnText = nextBtn.innerHTML;

            nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processando...';
            nextBtn.disabled = true;

            const form = document.getElementById("wizardForm");
            
            // ------------------------------------------------------------
            // NOVO: CRIPTOGRAFIA (MD5) ANTES DE GERAR O FORMDATA
            // ------------------------------------------------------------
            const senhaInput = document.getElementById('senha');
            const confSenhaInput = document.getElementById('conf_senha');

            if (senhaInput && typeof md5c === 'function') {
                // 1. Cria o input hidden 'p' para a senha
                var p = document.createElement("input");
                p.name = "passw";
                p.type = "hidden";
                p.value = md5c(senhaInput.value);
                form.appendChild(p);

                // (Opcional) Se quiser enviar a confirmação hash também
                
                var p_conf = document.createElement("input");
                p_conf.name = "passw_conf";
                p_conf.type = "hidden";
                p_conf.value = md5c(confSenhaInput.value);
                form.appendChild(p_conf);
                

                // 2. Limpa os campos originais para não enviar texto plano
                // O FormData vai ler esses campos como vazios ""
                senhaInput.value = "";
                if(confSenhaInput) confSenhaInput.value = "";
            } 
            else if (senhaInput && typeof md5c === 'undefined') {
                console.error("Erro: Função md5c não encontrada. Enviando sem hash.");
            }
            // ------------------------------------------------------------

            // 3. Coleta dados (AGORA O FORMDATA PEGA O HIDDEN 'p' E AS SENHAS VAZIAS)
            const formData = new FormData(form);

            // 4. Envia via AJAX (Fetch)
            fetch('/landing/processregistration', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'registered') {
                    // SUCESSO
                    window.location.href = '/payment';
                } else {
                    // ERRO
                    nextBtn.innerHTML = originalBtnText;
                    nextBtn.disabled = false;

                    // Se der erro, precisamos "limpar" o input hidden criado para não duplicar
                    // e pedir para o usuário digitar a senha novamente (já que limpamos ela)
                    if(senhaInput) {
                        const hiddenP = form.querySelector('input[name="p"]');
                        if(hiddenP) hiddenP.remove();
                    }

                    // Lógica de retorno inteligente
                    if (data.message && data.message.toLowerCase().includes('cpf')) {
                        jumpToStep(1); 
                        showInputError('cpf', data.message);
                    }
                    else if (data.message && data.message.toLowerCase().includes('senha')) {
                        jumpToStep(3); 
                        showInputError('senha', data.message); // Usuário terá que digitar de novo
                    }
                    else if (['exists', 'invalid_dns', 'invalid_format'].includes(data.status)) {
                        jumpToStep(3); 
                        showInputError('email', data.message);
                    }
                    else {
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

            return false; // Impede o envio tradicional
        }

        showStep(currentStep);
    }

    // ============================================================
    // 4. FUNÇÕES AUXILIARES DO WIZARD
    // ============================================================

    function showStep(n) {
        steps.forEach(s => s.classList.remove('active'));
        steps[n].classList.add('active');

        const prevBtn = document.getElementById("prevBtn");
        const nextBtn = document.getElementById("nextBtn");

        if (prevBtn) prevBtn.style.display = (n === 0) ? "none" : "inline-block";

        if (nextBtn) {
            if (n === (steps.length - 1)) {
                nextBtn.innerHTML = 'Pagar e Assinar <i class="fas fa-check ms-2"></i>';
            } else {
                nextBtn.innerHTML = 'Próximo <i class="fas fa-arrow-right ms-2"></i>';
            }
        }

        indicators.forEach((ind, index) => {
            ind.classList.remove('active', 'completed');
            if (index < n) ind.classList.add('completed');
            if (index === n) ind.classList.add('active');
        });
    }

  function validateForm() {
        let valid = true;

        // --- CORREÇÃO DO ERRO ---
        // Verifica se a lista de passos existe e se o passo atual é válido
        if (!steps || steps.length === 0) {
            console.error("ERRO CRÍTICO: Não encontrei nenhuma div com a classe '.step-content'. Verifique seu HTML.");
            return false;
        }

        if (!steps[currentStep]) {
            console.error("ERRO: Tentando validar o passo " + currentStep + ", mas ele não existe.");
            return false;
        }
        // ------------------------

        // Agora é seguro chamar querySelectorAll
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
            if (cicloRadio.value === 'anual') {
                total = planPrice * 10;
            }

            const elResumoPlano = document.getElementById('resumo-plano');
            const elResumoCiclo = document.getElementById('resumo-ciclo');
            const elResumoValor = document.getElementById('resumo-valor');

            if (elResumoPlano) elResumoPlano.innerText = planName;
            if (elResumoCiclo) elResumoCiclo.innerText = cicloLabel;
            if (elResumoValor) elResumoValor.innerText = formatCurrency(total);
        }
    }

    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
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
                if (loadingIcon) loadingIcon.style.display = 'inline-block';

                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(r => r.json())
                    .then(data => {
                        if (loadingIcon) loadingIcon.style.display = 'none';

                        if (!data.erro) {
                            if (document.getElementById('logradouro')) document.getElementById('logradouro').value = data.logradouro;
                            if (document.getElementById('bairro')) document.getElementById('bairro').value = data.bairro;
                            if (document.getElementById('cidade')) document.getElementById('cidade').value = data.localidade;
                            if (document.getElementById('uf')) document.getElementById('uf').value = data.uf;

                            const elError = document.getElementById('cep-error');
                            if (elError) elError.classList.add('d-none');

                            if (document.getElementById('numero')) document.getElementById('numero').focus();
                        } else {
                            const elError = document.getElementById('cep-error');
                            if (elError) elError.classList.remove('d-none');
                        }
                    })
                    .catch(() => {
                        if (loadingIcon) loadingIcon.style.display = 'none';
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
            // Se for input group com botão, pula
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
            }, { once: true });
        }
    }

    function isCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf == '') return false;
        // Elimina CPFs conhecidos inválidos
        if (cpf.length != 11 ||
            cpf == "00000000000" ||
            cpf == "11111111111" ||
            cpf == "22222222222" ||
            cpf == "33333333333" ||
            cpf == "44444444444" ||
            cpf == "55555555555" ||
            cpf == "66666666666" ||
            cpf == "77777777777" ||
            cpf == "88888888888" ||
            cpf == "99999999999")
            return false;

        let add = 0;
        for (let i = 0; i < 9; i++)
            add += parseInt(cpf.charAt(i)) * (10 - i);
        let rev = 11 - (add % 11);
        if (rev == 10 || rev == 11) rev = 0;
        if (rev != parseInt(cpf.charAt(9))) return false;

        add = 0;
        for (let i = 0; i < 10; i++)
            add += parseInt(cpf.charAt(i)) * (11 - i);
        rev = 11 - (add % 11);
        if (rev == 10 || rev == 11) rev = 0;
        if (rev != parseInt(cpf.charAt(10))) return false;

        return true;
    }
});
    function limparFormularioCEP() {
            document.getElementById('logradouro').value = "";
            document.getElementById('bairro').value = "";
            document.getElementById('cidade').value = "";
            document.getElementById('uf').value = "";
        }

        // --- 3. TOGGLE PASSWORD ---
        function togglePass(campo, icon) {
            const senhaInput = document.getElementById(campo);
            const eyeIcon = document.getElementById(icon);
            
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