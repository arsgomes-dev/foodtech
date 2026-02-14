function cpfCnpjFormat(input) {
    // Remove qualquer caractere que não seja número
    let cnpjCpf = input.value.replace(/\D/g, '');
    // Limita o CPF/CNPJ a 14 caracteres
    cnpjCpf = cnpjCpf.substring(0, 14);
    // Formata o CPF para o padrão xxx.xxx.xxx-xx
    if (cnpjCpf.length <= 11) {
        cnpjCpf = cnpjCpf.replace(/(\d{3})(\d)/, '$1.$2');
        cnpjCpf = cnpjCpf.replace(/(\d{3})(\d)/, '$1.$2');
        cnpjCpf = cnpjCpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    } else if (cnpjCpf.length > 11 && cnpjCpf.length <= 14) {
        cnpjCpf = cnpjCpf.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3/\$4-\$5");
    }
    // Atribui o valor formatado ao campo de entrada
    input.value = cnpjCpf;
}