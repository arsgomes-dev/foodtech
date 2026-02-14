function updateFood(forms) {
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

            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function saveMeasures(forms) {
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
        $.post(dir + "/foodhomemademeasures/controller/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(forms);
                loadFoodsMeasures();
                $('.measure_create').modal('hide');
                $('.measure_update').modal('hide');
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
var pag = 1;
var limit = 5;
function loadFoodsMeasures() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var description = document.getElementById("measure").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    var code_search = "";
    var code = document.getElementById("code").value;
    if (code !== "") {
        code_search = "&code=" + code;
    }

    var data = "pag=" + pag + "&limit=" + limit + code_search + ord + description_search;
    $.post(dir + "/foodhomemademeasures/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/foodhomemademeasures/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function pagination(pag) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var description = document.getElementById("measure").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    var code_search = "";
    var code = document.getElementById("code").value;
    if (code !== "") {
        code_search = "&code=" + code;
    }

    var data = "pag=" + pag + "&limit=" + limit + code_search + ord + description_search;
    $.post(dir + "/foodhomemademeasures/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/foodhomemademeasures/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function measure_edit(measure, description, quant) {
    document.querySelector("[name='codeMeasure']").value = measure;
    document.querySelector("[id='descriptionMeasure_edit']").value = description;
    document.querySelector("[id='quantitiesMeasure_edit']").value = quant;
    $('#measure-update').modal('show');
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
    loadFoodsMeasures();
});
function cleanSearch() {
    document.getElementById("measure").value = "";
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    loadFoodsMeasures();
    $('#search-modal').modal('hide');
}
