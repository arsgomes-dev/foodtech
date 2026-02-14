let selectedPlan = null;
let currentPeriod = 'monthly';
const x_y = document.getElementById('x_y').value;

function formatPrice(value) {
    return 'R$ ' + value.toFixed(2).replace('.', ',');
}

function togglePeriod(period) {
    currentPeriod = period;

    document.querySelectorAll('.plan-card').forEach(card => {
        card.classList.add('opacity-50');

        setTimeout(() => {
            const monthly = Number(card.dataset.monthly);

            const priceEl = card.querySelector('.price-value');
            const periodEl = card.querySelector('.period-label');
            const economyBox = card.querySelector('.economy-label');
            const economyValue = card.querySelector('.economy-value');

            if (period === 'monthly') {
                priceEl.innerText = formatPrice(monthly);
                periodEl.innerText = '/mês';
                if (economyBox)
                    economyBox.classList.add('d-none');
            } else {
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
                
                const yearlyWithDiscount = monthly * parcelas;
                const economy = monthly * discount;

                priceEl.innerText = formatPrice(yearlyWithDiscount);
                periodEl.innerText = '/ano';

                if (economyBox && economyValue) {
                    economyValue.innerText = formatPrice(economy);
                    economyBox.classList.remove('d-none');
                }
            }

            card.classList.remove('opacity-50');
        }, 120);
    });

    document.getElementById('btn-monthly')
            .classList.toggle('active', period === 'monthly');
    document.getElementById('btn-yearly')
            .classList.toggle('active', period === 'yearly');
}

// botões mensal / anual
document.getElementById('btn-monthly').onclick = () => togglePeriod('monthly');
document.getElementById('btn-yearly').onclick = () => togglePeriod('yearly');

// inicializa como mensal
togglePeriod('monthly');

function resetButton(btn) {
    btn.classList.remove('btn-loading');
    btn.innerHTML = 'Assinar plano';
}
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el);
});
document.querySelectorAll('.btn-show-more').forEach(btn => {
    btn.addEventListener('click', () => {
        const features = btn.previousElementSibling.querySelectorAll('li:nth-child(n+4)');
        const isHidden = features[0].style.display === 'none' || !features[0].style.display;

        features.forEach(li => {
            li.style.display = isHidden ? 'flex' : 'none'; // flex para alinhar com ícone ✔
        });

        btn.innerText = isHidden ? 'Mostrar menos' : 'Mostrar mais';
    });
});


