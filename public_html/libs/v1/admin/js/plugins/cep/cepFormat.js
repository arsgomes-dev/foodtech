function cepFormat(input) {
     // Remove qualquer caractere que não seja número
    let cep = input.value.replace(/\D/g, '');
    cep = cep.substring(0, 8);
   if (cep.length <= 8) {
        cep = cep.replace(/(\d{5})(\d{3})/, '$1-$2');
    }
    // Atribui o valor formatado ao campo de entrada
    input.value = cep;
}