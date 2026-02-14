document.querySelectorAll('input[data-number]').forEach((input) => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/\D/g, ''); // remove tudo que não é número
    });
  });
  //exemplo de uso é acrescentar no input a tag data-number 
  document.querySelectorAll('[data-number-decimal="true"]').forEach(input => {

    input.addEventListener('keypress', function (e) {
        const char = String.fromCharCode(e.which);

        // permite números
        if (/^[0-9]$/.test(char)) return;

        // permite um único ponto decimal e não pode ser o primeiro caractere
        if (char === '.' && !this.value.includes('.') && this.value.length > 0) return;

        // permite teclas especiais (backspace, delete, arrows)
        if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight'].includes(e.key)) return;

        // bloqueia qualquer outra coisa
        e.preventDefault();
    });

    // bloqueia colar textos inválidos
    input.addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (!/^[0-9]*\.?[0-9]*$/.test(pasted)) {
            e.preventDefault();
        }
    });

    // sanitiza automaticamente se algo escapar
    input.addEventListener('input', function () {
        this.value = this.value
            .replace(/[^0-9.]/g, '')      // remove tudo que não for número ou ponto
            .replace(/(\..*)\./g, '$1');  // impede dois pontos
    });
});