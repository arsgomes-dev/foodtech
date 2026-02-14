function cpfFormat(input) {
    // Remove qualquer caractere que não seja número
    let cpf = input.value.replace(/\D/g, '');
    // Limita o CPF a 11 caracteres
    cpf = cpf.substring(0, 11);
    // Formata o CPF para o padrão xxx.xxx.xxx-xx
    if (cpf.length <= 11) {
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    // Atribui o valor formatado ao campo de entrada
    input.value = cpf;
}