$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
    //Datemask dd/mm/yyyy
    $('#contact').inputmask('(99) 99999-9999', {'placeholder': '(##) #####-####'});
    //cpf
    cpfFormat(document.getElementById("cpf"));
});
function cleanForm(form) {
    form.reset();
    var returns = true;
    const to_validations = document.getElementById(form.id).getElementsByClassName("to_validations");
    var to_validations_count = to_validations.length;
    for (var i = 0; i < to_validations_count; i++) {
        to_validations[i].classList.remove('is-invalid');
        var id_input = "to_validation_blank_" + to_validations[i].id;
        document.getElementById(id_input).style.display = "none";
        if ((to_validations[i].id.indexOf("email") !== -1)) {
            document.getElementById("to_validation_already_registered_email").style.display = "none";
        }
        if ((to_validations[i].id.indexOf("cpf") !== -1)) {
            document.getElementById("to_validation_already_registered_cpf").style.display = "none";
            document.getElementById("to_validation_invalid_cpf").style.display = "none";
        }
    }
    return returns;
}
function createUser(form) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(form) && emailSearch(form.email) && cpfSearch(form.cpf)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/users/controll/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(form);
                loadUsers();
                $('.new-modal').modal('hide');
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function emailSearch(input, code) {
    var returns = true;
    var inp = input.id;
    if (emailValidation(input)) {
        if (document.querySelector("#dir_site")) {
            var dir_site = document.querySelector("#dir_site");
            var dir = "";
            if (dir_site.value !== null && dir_site.value !== "") {
                dir = "/" + dir_site.value;
            }
        }
        var posts = "email=" + input.value;
        if (code !== "" && code !== null) {
            posts += "&code=" + code;
        }
        $.post(dir + "/users/search/search_email", posts, function (response) {
            if (response === "1") {
                input.classList.add('is-invalid');
                document.getElementById("to_validation_already_registered_" + inp).style.display = "block";
                document.getElementById("to_validation_blank_" + inp).style.display = "none";
                returns = false;
            } else {
                input.classList.remove('is-invalid');
                document.getElementById("to_validation_already_registered_" + inp).style.display = "none";
                document.getElementById("to_validation_blank_" + inp).style.display = "none";
                returns = true;
            }

        });
    } else {
        document.getElementById("to_validation_already_registered_" + inp).style.display = "none";
    }
    return returns;
}

function cpfSearch(input, code) {
    var returns = true;
    if (cpfValidate(input)) {
        if (document.querySelector("#dir_site")) {
            var dir_site = document.querySelector("#dir_site");
            var dir = "";
            if (dir_site.value !== null && dir_site.value !== "") {
                dir = "/" + dir_site.value;
            }
        }
        var posts = "cpf=" + input.value;
        if (code !== "" && code !== null) {
            posts += "&code=" + code;
        }
        var inp = input.id;
        $.post(dir + "/users/search/search_cpf", posts, function (response) {
            if (response === "1") {
                input.classList.add('is-invalid');
                document.getElementById("to_validation_already_registered_" + inp).style.display = "block";
                document.getElementById("to_validation_blank_" + inp).style.display = "none";
                returns = false;
            } else {
                input.classList.remove('is-invalid');
                document.getElementById("to_validation_already_registered_" + inp).style.display = "none";
                document.getElementById("to_validation_blank_" + inp).style.display = "none";
                returns = true;
            }

        });
    } else {
        var inp = input.id;
        document.getElementById("to_validation_already_registered_" + inp).style.display = "none";
    }
    return returns;
}

function loadOccupation(edits) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var ordDepartment = "";
    var edit = "";
    if (edits === true) {
        edit = "#ocuppationEdit_div";
        ordDepartment = document.getElementById('departmentEdit');
    } else {
        edit = "#ocuppation_div";
        ordDepartment = document.getElementById('department');
    }
    var code = "";
    $(edit).html("");
    var ordValueDepartment = ordDepartment.options[ordDepartment.selectedIndex].value;
    if (ordValueDepartment !== null && ordValueDepartment !== "") {
        code = "code=" + ordValueDepartment;
    }
    var data = code;
    $.post(dir + "/Occupations/search/select_occupations", data, function (response) {
        $(edit).html(response);
    });
}