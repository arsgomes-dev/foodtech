function cnpjFormat(input) {
    // Remove qualquer caractere que não seja número
    let cnpj = input.value.replace(/\D/g, '');
    // Limita o CNPJ a 14 caracteres
    cnpj = cnpj.substring(0, 14);
   if (cnpj.length <= 14) {
        cnpj = cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
    }
    // Atribui o valor formatado ao campo de entrada
    input.value = cnpj;
}