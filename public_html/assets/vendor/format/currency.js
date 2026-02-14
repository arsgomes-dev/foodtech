document.querySelectorAll('input[data-currency]').forEach((el) => {
    const locale = el.dataset.locale || 'pt-BR';
    const currency = el.dataset.currency || 'BRL';
    const formatter = new Intl.NumberFormat(locale, {style: 'currency', currency});

    const fraction = 2; // a maioria das moedas tem 2 casas; ajuste se precisar
    const base = 10 ** fraction;

    const aplicar = (target) => {
        const digits = target.value.replace(/\D/g, '');
        target.value = digits ? formatter.format(Number(digits) / base) : '';
    };

    el.addEventListener('input', (e) => aplicar(e.target));
    el.addEventListener('paste', (e) => {
        e.preventDefault();
        const txt = (e.clipboardData || window.clipboardData).getData('text');
        e.target.value = txt;
        aplicar(e.target);
    });
});
function applyCurrencyFormatting() {
    document.querySelectorAll('input[data-currency]').forEach((el) => {
        // Evita duplicar listener
        if (el.dataset.currencyBound === 'true') return;
        el.dataset.currencyBound = 'true';

        const locale = el.dataset.locale || 'pt-BR';
        const currency = el.dataset.currency || 'BRL';
        const formatter = new Intl.NumberFormat(locale, { style: 'currency', currency });

        const fraction = 2;
        const base = 10 ** fraction;

        const aplicar = (target) => {
            const digits = target.value.replace(/\D/g, '');
            target.value = digits ? formatter.format(Number(digits) / base) : '';
        };

        el.addEventListener('input', (e) => aplicar(e.target));
        el.addEventListener('paste', (e) => {
            e.preventDefault();
            const txt = (e.clipboardData || window.clipboardData).getData('text');
            e.target.value = txt;
            aplicar(e.target);
        });
    });
}
//exemplo de uso é acrescentar ao input
//data-currency="BRL" data-locale="pt-BR" ou o correspondente a moeda que desejar