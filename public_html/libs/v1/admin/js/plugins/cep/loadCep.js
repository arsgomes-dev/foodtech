function searchCep(input) {
    var inp = input.id;
    var returns = true;
    var cep = input.value.replace(/\D/g, '');
    if (cep.length === 8) {
        returns = loadCep(input);
    } else {
        returns = false;
    }
    if (returns === true) {
        input.classList.remove('is-invalid');
        document.getElementById("to_validation_invalid_" + inp).style.display = "none";
    } else {
        input.classList.add('is-invalid');
        document.getElementById("to_validation_invalid_" + inp).style.display = "block";
    }
    return returns;
}
function loadCep(input) {
    var inp = input.id;
    var retorno = true;
    cepFormat(input);
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var cep = input.value.replace(/\D/g, '');
    if (cep.length === 8) {
        var data = "cep=" + cep;
        $.post(dir + "/list/Address/getAddress", data, function (response) {
            if (response !== "") {
                let obj = JSON.parse(response);
                document.getElementById("avenue").value = obj.address;
                document.getElementById("neighborhood").value = obj.neighborhood;
                document.getElementById("city").value = obj.city;
                document.getElementById("state").value = obj.state;
                retorno = true;
            } else {
                document.getElementById("avenue").value = "";
                document.getElementById("neighborhood").value = "";
                document.getElementById("complement").value = "";
                document.getElementById("number").value = "";
                document.getElementById("city").value = "";
                document.getElementById("state").value = "";
                retorno = false;
            }
            if (retorno === true) {
                input.classList.remove('is-invalid');
                document.getElementById("to_validation_invalid_" + inp).style.display = "none";
            } else {
                input.classList.add('is-invalid');
                document.getElementById("to_validation_invalid_" + inp).style.display = "block";
            }
        });
    } else if (cep === "") {
        document.getElementById("avenue").value = "";
        document.getElementById("neighborhood").value = "";
        document.getElementById("complement").value = "";
        document.getElementById("number").value = "";
        document.getElementById("city").value = "";
        document.getElementById("state").value = "";
        retorno = false;
    } else {
        document.getElementById("avenue").value = "";
        document.getElementById("neighborhood").value = "";
        document.getElementById("complement").value = "";
        document.getElementById("number").value = "";
        document.getElementById("city").value = "";
        document.getElementById("state").value = "";
        retorno = false;
    }
    return retorno;
}