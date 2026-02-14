function cpfValidation(cpf) {
    // Remove non-numeric characters
    cpf = cpf.replace(/[^\d]+/g, '');

    // Check if CPF has exactly 11 digits
    if (cpf.length !== 11) {
        return false;
    }

    // Check if CPF is a sequence of identical digits, like "111.111.111-11"
    if (/^(\d)\1{10}$/.test(cpf)) {
        return false;
    }

    // Validate the first check digit
    let sum = 0;
    let weight = 10;
    for (let i = 0; i < 9; i++) {
        sum += parseInt(cpf.charAt(i)) * weight--;
    }
    let firstCheckDigit = 11 - (sum % 11);
    if (firstCheckDigit === 10 || firstCheckDigit === 11) {
        firstCheckDigit = 0;
    }

    // Validate the second check digit
    sum = 0;
    weight = 11;
    for (let i = 0; i < 10; i++) {
        sum += parseInt(cpf.charAt(i)) * weight--;
    }
    let secondCheckDigit = 11 - (sum % 11);
    if (secondCheckDigit === 10 || secondCheckDigit === 11) {
        secondCheckDigit = 0;
    }

    // Check if both check digits match the provided ones
    if (parseInt(cpf.charAt(9)) !== firstCheckDigit || parseInt(cpf.charAt(10)) !== secondCheckDigit) {
        return false;
    }

    return true;
}
function cpfValidate(input) {
    if (input.value !== "") {
        if (!cpfValidation(input.value)) {
            input.classList.add('is-invalid');
            document.getElementById("to_validation_invalid_cpf").style.display = "block";
            document.getElementById("to_validation_blank_cpf").style.display = "none";
            document.getElementById("to_validation_already_registered_cpf").style.display = "none";
            return false;
        } else {
            input.classList.remove('is-invalid');
            document.getElementById("to_validation_invalid_cpf").style.display = "none";
            document.getElementById("to_validation_blank_cpf").style.display = "none";
            document.getElementById("to_validation_already_registered_cpf").style.display = "none";
            return true;
        }
    } else {
        input.classList.remove('is-invalid');
        document.getElementById("to_validation_invalid_cpf").style.display = "none";
        return false;
    }

}
// Teste da função
//let cpfTeste = "123.456.789-09";
//console.log(validarCPF(cpfTeste) ? `${cpfTeste} é válido.` : `${cpfTeste} é inválido.`);