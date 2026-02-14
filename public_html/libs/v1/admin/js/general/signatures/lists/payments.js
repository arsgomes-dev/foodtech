var pag = 1;
var limit = 20;

function cleanDatepickerDiv() {
    const datepickerDiv = document.getElementById('ui-datepicker-div');
    if (datepickerDiv) {
        datepickerDiv.remove();
    }
}
function loadSignaturesPayment() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    cleanDatepickerDiv();
    $("#list").html("");
    $("#pagination").html("");
    var code = "";
    var code = document.getElementById("code").value;
    if (code !== "") {
        code = "&code=" + code;
    }
    var data = "pag=" + pag + "&limit=" + limit + code;
    $.post(dir + "/signatures/search/lists_payments", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signatures/search/pagination_payments", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnSignaturesPayment() {
    loadSignaturesPayment();
}
function pagination(pag) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var code = "";
    var code = document.getElementById("code").value;
    if (code !== "") {
        code = "&code=" + code;
    }
    var data = "pag=" + pag + "&limit=" + limit + code;
    $.post(dir + "/signatures/search/lists_payments", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signatures/search/pagination_payments", data, function (response) {
        $('#pagination').html(response);
    });
}
$(document).ready(function () {
    loadSignaturesPayment();
});
function loadPayment(gcid) {
    let dir = "";
    const dirInput = document.querySelector("#dir_site");
    if (dirInput && dirInput.value.trim() !== "") {
        dir = "/" + dirInput.value.trim();
    }

    // Limpa o modal antes de carregar
    $("#payment-modal-div").html("");
    $.ajax({
        url: dir + "/signatures/search/payment",
        type: 'POST',
        data: {
            code: gcid
        },
        success: function (response) {
            $('#payment-modal-div').html(response);
            $('#payment-modal').modal('show');
        }
    });
}
function cleanPayment() {
    cleanDatepickerDiv();
    $('#payment-modal-div').html("");
    $('#payment-modal').modal('hide');
}
function updateInvoice(form) {
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
    var forms = document.getElementById('update_invoice');
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/signatures/controller/update_invoice",
        type: 'POST',
        enctype: 'multipart/form-data',
        data: form_d,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanDatepickerDiv();
                document.location.reload(true);
            } else if (msg[0] !== "1") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
        }
    });
    }
}
function updateInvoiceSend(form) {
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
    var forms = document.getElementById('update_invoice');
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/signatures/controller/update_invoice_send",
        type: 'POST',
        enctype: 'multipart/form-data',
        data: form_d,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanDatepickerDiv();
                document.location.reload(true);
            } else if (msg[0] !== "1") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
        }
    });
    }
}

function sendInvoice(form) {
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
    var forms = document.getElementById('update_invoice');
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/signatures/controller/send_invoice",
        type: 'POST',
        enctype: 'multipart/form-data',
        data: form_d,
        processData: false, // tell jQuery not to process the data
        contentType: false, // tell jQuery not to set contentType
        success: function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanDatepickerDiv();
                document.location.reload(true);
            } else if (msg[0] !== "1") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
        }
    });
    }
}
function deleteTypeInvoices(gcid, type) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (gcid !== "" && gcid !== null && type !== "" && type !== null) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = "code=" + gcid + "&type=" + type;
        $.post(dir + "/signatures/controller/delete_file_invoice", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                $('#payment-modal').modal('hide');
                $('#payment-modal-div').html("");
                loadSignaturesPayment();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}