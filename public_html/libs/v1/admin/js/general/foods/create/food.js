function createFood(forms) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(forms) === true) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + forms.id).serialize();
        $.post(dir + "/foods/controller/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                window.location.href = dir + "/foods/create";
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function cleanForm(form) {
    cancelValidationForm(form);
    form.reset();
}
$(document).ready(function () {
    $('.floatTwo').inputmask({
        alias: 'numeric',
        groupSeparator: '.', // separador de milhar
        radixPoint: ',', // separador decimal (vírgula)
        digits: 2, // casas decimais
        digitsOptional: false, // sempre mostrar 2 casas decimais
        autoGroup: true, // aplica agrupamento (milhar)
        rightAlign: false, // alinha à esquerda
        allowMinus: false, // impede números negativos
        placeholder: '0',
        removeMaskOnSubmit: true  // opcional: remove máscara ao enviar form);   
    });
});

