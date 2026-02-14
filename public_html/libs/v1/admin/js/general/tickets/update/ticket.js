function cleanFormClose(forms) {
    document.getElementById("description_close").value = "";
    const radios = document.querySelectorAll('input[name="stTicket"]');
    radios.forEach(radio => radio.checked = false);
    cancelValidationForm(forms);
}
function validationSt() {
    const stResolved = document.getElementById('status1');
    const stUnresolved = document.getElementById('status2');
    if (stResolved.checked || stUnresolved.checked) {
        var id_input = "to_validation_blank_stTicket";
        document.getElementById(id_input).style.display = "none";
        return true;
    } else {
        var id_input = "to_validation_blank_stTicket";
        document.getElementById(id_input).style.display = "block";
    }
}
function deactivateTicket(forms) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if(validationSt()){
    if (validationForm(forms)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + forms.id).serialize();
        $.post(dir + "/tickets/controller/ticket_close", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
                setTimeout(function () {
                    location.reload(true);
                }, 1000);
            }

        });
    }
    }
}
function reactivateTicket(code) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    const Toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    var data = "code=" + code;
    $.post(dir + "/tickets/controller/ticket_reopen", data, function (response) {
        var msg = response.split("->");
        if (msg[0] === "1") {
            Toast.fire({
                icon: 'success',
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        } else if (msg[0] === "2") {
            Toast.fire({
                icon: "warning",
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        }

    });
}
function statusMessageSend(code, status) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    const Toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    var data = "code=" + code + "&status=" + status;
    $.post(dir + "/tickets/controller/update_status_response", data, function (response) {
        var msg = response.split("->");
        if (msg[0] === "1") {
            Toast.fire({
                icon: 'success',
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        } else if (msg[0] === "2") {
            Toast.fire({
                icon: "warning",
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        }

    });
}
function trashMessageSend(code) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    const Toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    var data = "code=" + code;
    $.post(dir + "/tickets/controller/trash_response", data, function (response) {
        var msg = response.split("->");
        if (msg[0] === "1") {
            Toast.fire({
                icon: 'success',
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        } else if (msg[0] === "2") {
            Toast.fire({
                icon: "warning",
                title: " " + msg[1]
            });
            setTimeout(function () {
                location.reload(true);
            }, 1000);
        }

    });
}
function cleanForm(form) {
    form.reset();
}