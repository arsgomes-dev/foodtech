function ticketSend() {
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
    if (document.querySelector("#code")) {
        var code_site = document.querySelector("#code");
        var code = "";
        if (code_site.value !== null && code_site.value !== "") {
            code = code_site.value;
        }
    }
    var forms = document.getElementById('messageSend');
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/tickets/controller/ticket_send_response",
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