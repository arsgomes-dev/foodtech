function emailValidation(input) {
    // Expressão regular para verificar o formato do e-mail
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    if (input.value !== "") {
        if (!regex.test(input.value)) {
            input.classList.add('is-invalid');
            document.getElementById("to_validation_invalid_email").style.display = "block";
            document.getElementById("to_validation_already_registered_email").style.display = "none";
            document.getElementById("to_validation_blank_email").style.display = "none";
            return false;
        } else {
            input.classList.remove('is-invalid');
            document.getElementById("to_validation_invalid_email").style.display = "none";
            document.getElementById("to_validation_already_registered_email").style.display = "none";
            document.getElementById("to_validation_blank_email").style.display = "none";
            return true;
        }
    } else {
        input.classList.remove('is-invalid');
        document.getElementById("to_validation_invalid_email").style.display = "none";
        return false;
    }

}

// Exemplo de uso
//const email = "exemplo@dominio.com";
//validarEmail(email);