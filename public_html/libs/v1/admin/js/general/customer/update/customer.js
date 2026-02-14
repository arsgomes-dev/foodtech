$(document).ready(function () {
    //Datemask dd/mm/yyyy
    $('#birth').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
    //contact
    $('#contact').inputmask('(99) 99999-9999', {'placeholder': '(##) #####-####'});
    //cpfCpnj
    cpfFormat(document.getElementById("cpf"));
});

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
        $.post(dir + "/customers/search/search_email", posts, function (response) {
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
function updateCustomer(form) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(form) && emailSearch(form.email, form.code.value)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/customers/save", data, function (response) {
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
function recoveryPasswd(code) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    swal.fire({
        title: recoveryTitle,
        text: recoveryText,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: recoveryButton
    }).then((result) => {
        if (result.isConfirmed) {
            const Toast = swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            var data = "code=" + code;
            $.post(dir + "/customers/recover", data, function (response) {
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
    });
}
function updateStatus(form) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(form)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/customers/update_status", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                var select = document.getElementById('status');
                var option = select.children[select.selectedIndex];
                var texto = option.textContent;
                document.getElementById("tr-status").innerHTML = texto;
                location.reload();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
            $('#modal-status').modal('hide');
        });
    }
}