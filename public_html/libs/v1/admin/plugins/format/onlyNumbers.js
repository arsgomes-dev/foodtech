document.querySelectorAll('input[data-number]').forEach((input) => {
    input.addEventListener('input', () => {
      input.value = input.value.replace(/\D/g, ''); // remove tudo que não é número
    });
  });
  //exemplo de uso é acrescentar no input a tag data-number 