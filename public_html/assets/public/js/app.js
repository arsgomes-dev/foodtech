document.addEventListener('DOMContentLoaded', function () {

    // Inicialização da biblioteca de animação (AOS)
    AOS.init({
        once: true, // A animação roda apenas uma vez
        offset: 50, // Começa a animar 50px antes do elemento entrar em tela
        duration: 800, // Duração da animação em milissegundos
        easing: 'ease-out-cubic', // Tipo de transição suave
    });

    // Efeito de Scroll na Navbar
    // Adiciona uma sombra e cor de fundo mais sólida quando o usuário desce a página
    const navbar = document.getElementById('mainNavbar');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

});
// Variável de estado global
let currentPeriod = 'monthly';
const x_y = document.getElementById('x_y').value;

document.addEventListener('DOMContentLoaded', () => {
    // Inicializa os preços ao carregar a página
    updatePrices();
});

/**
 * Alterna entre Mensal e Anual
 * @param {string} period - 'monthly' ou 'yearly'
 */
function togglePeriod(period) {
    if (currentPeriod === period)
        return; // Evita recálculo desnecessário

    currentPeriod = period;

    // Atualiza classes dos botões
    document.getElementById('btn-monthly').classList.toggle('active', period === 'monthly');
    document.getElementById('btn-yearly').classList.toggle('active', period === 'yearly');

    // Seleciona todos os cards para aplicar efeito e trocar valores
    const cards = document.querySelectorAll('.plan-card');

    cards.forEach(card => {
        // Efeito visual de "loading" rápido
        card.style.opacity = '0.5';
        card.style.transform = 'scale(0.98)';

        setTimeout(() => {
            updateCardData(card);

            // Restaura visual
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';

            // Se for o recomendado, reaplica a escala maior se necessário pelo CSS, 
            // ou apenas remove o inline style para o CSS hover funcionar
            setTimeout(() => {
                card.style.transform = '';
            }, 200);
        }, 150);
    });
}

/**
 * Atualiza os valores de um card específico baseado no período atual
 */
function updateCardData(card) {
    const monthlyPrice = parseFloat(card.dataset.monthly);

    // Elementos do DOM dentro do card
    const priceEl = card.querySelector('.price-value');
    const periodEl = card.querySelector('.period-label');
    const economyLabel = card.querySelector('.economy-label');
    const economyValueEl = card.querySelector('.economy-value');

    if (currentPeriod === 'monthly') {
        // --- MODO MENSAL ---
        priceEl.innerText = formatCurrency(monthlyPrice);
        periodEl.innerText = '/mês';

        // Esconde a label de economia
        if (economyLabel)
            economyLabel.classList.add('d-none');

    } else {
        // --- MODO ANUAL ---
        // Regra: Mensal x 10 (2 meses grátis)

        let discount = 0;
        if ((12 - x_y) <= 0) {
            discount = 1;
        } else {
            discount = (12 - x_y);
        }
        let parcelas = 0;
        if ((12 - x_y) <= 0) {
            parcelas = 12;
        } else {
            parcelas = x_y;
        }

        const yearlyTotal = monthlyPrice * parcelas;
        const savings = monthlyPrice * discount;

        priceEl.innerText = formatCurrency(yearlyTotal);
        periodEl.innerText = '/ano';

        // Mostra e calcula a economia
        if (economyLabel && economyValueEl) {
            economyValueEl.innerText = 'R$ ' + formatCurrency(savings);
            economyLabel.classList.remove('d-none');
        }
    }
}

/**
 * Atualiza todos os preços (usado na inicialização)
 */
function updatePrices() {
    document.querySelectorAll('.plan-card').forEach(card => updateCardData(card));
}

/**
 * Formata número para padrão brasileiro (ex: 29,90)
 */
function formatCurrency(value) {
    return value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
}

/**
 * Lógica do botão "Ver tudo / Ver menos"
 */
function toggleFeatures(btn) {
    const card = btn.closest('.plan-card');
    // Seleciona itens a partir do 5º (index 4 em diante)
    const hiddenItems = card.querySelectorAll('.feature-item:nth-child(n+5)');

    // Verifica se o primeiro item oculto está visível atualmente
    const isExpanded = hiddenItems[0].style.display === 'flex';

    if (isExpanded) {
        // Colapsar
        hiddenItems.forEach(item => item.style.display = 'none');
        btn.innerHTML = 'Ver tudo <i class="fas fa-chevron-down ms-1"></i>';
    } else {
        // Expandir
        hiddenItems.forEach(item => item.style.display = 'flex');
        btn.innerHTML = 'Ver menos <i class="fas fa-chevron-up ms-1"></i>';
    }
}

/**
 * Ação do botão de assinar
 */
function selectPlan(btn) {
    const planId = btn.dataset.planId;
    const title = btn.dataset.title;
    redirecionarPost("/signatures", {plan: planId, period_plan: currentPeriod});
}

function redirecionarPost(url, dados) {
    var form = document.createElement("form");
    form.method = "POST";
    form.action = url;

    for (var chave in dados) {
        if (dados.hasOwnProperty(chave)) {
            var campo = document.createElement("input");
            campo.type = "hidden";
            campo.name = chave;
            campo.value = dados[chave];
            form.appendChild(campo);
        }
    }
    document.body.appendChild(form);
    form.submit();
}