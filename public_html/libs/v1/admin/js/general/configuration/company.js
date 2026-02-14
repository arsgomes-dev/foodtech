$(document).ready(function () {
    cnpjFormat(document.getElementById("cnpj"));
    cepFormat(document.getElementById("cep"));
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
    $('#cep').inputmask('99999-999', {'placeholder': '#####-###'});
    $('#contact').inputmask('(99) 99999-9999', {'placeholder': '(##) #####-####'});
});
function updateCompany(form) {
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
        $.post(dir + "/configuration/updatecompany", data, function (response) {
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

function logoPhotoSave() {
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

    var forms = document.getElementById('form_photo_logo');/*$('#form_photo_logo')[0];*/
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/configuration/updatelogo",
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
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            document.getElementById('div_modal_logo_img').style.display = 'block';
            document.getElementById('div_modal_logo_i_div').style.display = 'none';
            $('#div_modal_logo_img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $('#logo_photo').on("change", function () {
        readURL(this);
    });
});
function uploadPhoto() {
    $('#logo_photo').trigger('click');
}
function cleanFormPhoto() {
    document.getElementById('div_modal_logo_img').style.display = 'none';
    document.getElementById('div_modal_logo_i_div').style.display = 'block';
    $('#div_modal_logo_img').attr('src', '');
    $('#logo_photo').attr('value', '');
}
document.addEventListener("DOMContentLoaded", () => {
    const btnupdateCompany = document.getElementById("btn-update-company");
    btnupdateCompany.addEventListener("click", function () {
        updateCompany(update_company);
    });
    const btnUploadPhoto = document.getElementById("div-upload-logo-photo");
    btnUploadPhoto.addEventListener("click", function () {
        uploadPhoto();
    });

    const btnCleanFormPhoto = document.getElementById("btn-clean-form-photo");
    btnCleanFormPhoto.addEventListener("click", function () {
        cleanFormPhoto();
    });

    const btnSavePhoto = document.getElementById("btn-save-photo");
    btnSavePhoto.addEventListener("click", function () {
        logoPhotoSave();
    });
});