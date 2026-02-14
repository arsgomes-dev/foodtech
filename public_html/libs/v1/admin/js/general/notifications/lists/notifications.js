function loadNotification(code, title) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#notification").html("");
    var data = "code=" + code + "&title=" + title;
    $.post(dir + "/notifications/search/lists", data, function (response) {
        $('#notification').html(response);
    });
}
function updateSts(nots, title, code, st) {
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
    var data = "notification=" + code + "&sts=" + st;
    $.post(dir + "/notifications/controller/save", data, function (response) {
        var msg = response.split("->");
        if (msg[0] === "1") {
            Toast.fire({
                icon: 'success',
                title: " " + msg[1]
            });
            loadNotification(nots, title);
        } else if (msg[0] === "2") {
            Toast.fire({
                icon: "warning",
                title: " " + msg[1]
            });
        }

    });
}
function changeSts(nots, title, code) {
    if ($("#notification_" + code).is(":checked")) {
        updateSts(nots, title, code, 1);
    } else {
        updateSts(nots, title, code, 0);
    }
}

function notificationEdit(forms, code) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var description = document.getElementById('description_' + code);
    $(description).val(tinymce.get('description_not_' + code).getContent());
    if (validationForm(forms)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#form_notification_" + code).serialize();
        $.post(dir + "/notifications/controller/save", data, function (response) {
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