$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
});
$(document).ready(function () {
    //Contact (##) #####-####
    $('#contact').inputmask('(99) 99999-9999', {'placeholder': '(##) #####-####'});
    //cpf
    cpfFormat(document.getElementById("cpf"));
});
function update(form) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(form) && emailSearchProfile(form.email) === true && cpfSearchProfile(form.cpf) === true) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/profile/save", data, function (response) {
            var msg = response.trim().split("->");
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
function emailSearchProfile(input) {
    var returns = true;
    if (emailValidation(input)) {
        if (document.querySelector("#dir_site")) {
            var dir_site = document.querySelector("#dir_site");
            var dir = "";
            if (dir_site.value !== null && dir_site.value !== "") {
                dir = "/" + dir_site.value;
            }
        }
        $.post(dir + "/profile/search_email", "email=" + input.value, function (response) {
            if (response === "1") {
                input.classList.add('is-invalid');
                document.getElementById("to_validation_already_registered_email").style.display = "block";
                document.getElementById("to_validation_blank_email").style.display = "none";
                returns = false;
            } else {
                input.classList.remove('is-invalid');
                document.getElementById("to_validation_already_registered_email").style.display = "none";
                document.getElementById("to_validation_blank_email").style.display = "none";
                returns = true;
            }

        });
    } else {
        document.getElementById("to_validation_already_registered_email").style.display = "none";
    }
    return returns;
}

function cpfSearchProfile(input) {
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
        $.post(dir + "/profile/search_cpf", posts, function (response) {
            if (response === "1") {
                input.classList.add('is-invalid');
                document.getElementById("to_validation_already_registered_cpf").style.display = "block";
                document.getElementById("to_validation_blank_cpf").style.display = "none";
                returns = false;
            } else {
                input.classList.remove('is-invalid');
                document.getElementById("to_validation_already_registered_cpf").style.display = "none";
                document.getElementById("to_validation_blank_cpf").style.display = "none";
                returns = true;
            }

        });
    } else {
        document.getElementById("to_validation_already_registered_cpf").style.display = "none";
    }
    return returns;
}

function profilePhotoSave() {
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

    var forms = document.getElementById('form_photo_profile');/*$('#form_photo_profile')[0];*/
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/profile/photo",
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
            document.getElementById('div_modal_profile_img').style.display = 'block';
            document.getElementById('div_modal_profile_i_div').style.display = 'none';
            $('#div_modal_profile_img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $('#profile_photo').on("change", function () {
        readURL(this);
    });
});
function uploadPhoto() {
    $('#profile_photo').trigger('click');
}
function cleanFormPhoto() {
    document.getElementById('div_modal_profile_img').style.display = 'none';
    document.getElementById('div_modal_profile_i_div').style.display = 'block';
    $('#div_modal_profile_img').attr('src', '');
    $('#profile_photo').attr('value', '');
}
function passVerify() {
    var pass = document.getElementById('passNew').value;
    var pass_conf = document.getElementById('passConfirm').value;
    var response = false;
    if (pass !== null && pass !== '') {
        if (pass !== null && pass !== '' && pass_conf !== null && pass_conf !== '') {

            if ((pass.length) < 8) {
                //A senha deve ter pelo menos 8 caracteres. Por favor, digite novamente!
                document.getElementById('to_validation_blank_passNew').style.display = 'none';
                document.getElementById('to_validation_passNewQuantity').style.display = 'block';
                document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
                document.getElementById('passNew').value = "";
                document.getElementById('passConfirm').value = "";
                $("#passNew").focus();
                response = false;
            } else {
                var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@-_.$%]).{8,}/;
                if (!re.test(document.getElementById('passNew').value)) {
                    //A senha deve conter números e letras (maiúscula e minúscula)!    
                    document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'block';
                    document.getElementById('to_validation_passNewQuantity').style.display = 'none';
                    document.getElementById('passNew').value = "";
                    document.getElementById('passConfirm').value = "";
                    $("#passNew").focus();
                    response = false;
                } else {
                    document.getElementById('to_validation_passNewQuantity').style.display = 'none';
                    document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
                    response = passConfirm();
                }
            }

        } else if (pass !== null && pass !== '') {
            if ((pass.length) < 8) {
                //A senha deve ter pelo menos 8 caracteres. Por favor, digite novamente!             
                document.getElementById('to_validation_blank_passNew').style.display = 'none';
                document.getElementById('to_validation_passNewQuantity').style.display = 'block';
                document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
                document.getElementById('passNew').value = "";
                document.getElementById('passConfirm').value = "";
                $("#passNew").focus();
                response = false;
            } else {
                var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@-_.$%]).{8,}/;
                if (!re.test(document.getElementById('passNew').value)) {
                    //A senha deve conter números e letras (maiúscula e minúscula)!
                    document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'block';
                    document.getElementById('to_validation_passNewQuantity').style.display = 'none';
                    document.getElementById('passNew').value = "";
                    document.getElementById('passConfirm').value = "";
                    $("#passNew").focus();
                    response = false;
                } else {
                    document.getElementById('to_validation_passNewQuantity').style.display = 'none';
                    document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
                    response = passConfirm();
                }
            }
        }
    } else {
        //A senha não pode ficar em branco!
        document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
        document.getElementById('to_validation_blank_passNew').style.display = 'block';
        response = false;

    }
    return response;
}
function passConfirm() {
    var pass = document.getElementById('passNew').value;
    var pass_conf = document.getElementById('passConfirm').value;
    var response = false;
    if (pass !== null && pass !== '' && pass_conf !== null && pass_conf !== '') {
        if (pass === pass_conf) {
            document.getElementById('to_validation_blank_passNew').style.display = 'none';
            document.getElementById('to_validation_passNewQuantity').style.display = 'none';
            document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
            document.getElementById('to_validation_blank_passConfirm').style.display = 'none';
            document.getElementById('to_validation_passConfirmNew').style.display = 'none';
            response = true;
        } else {
            //As senhas não coencidem. Digite novamente!
            document.getElementById('to_validation_passConfirmNew').style.display = 'block';
            $("passConfirm").focus();
            response = false;
        }
    } else if (pass === null || pass === '') {
        //O campo da senha não pode ficar em branco!
        document.getElementById('to_validation_blank_passNew').style.display = 'block';
        document.getElementById('to_validation_passNewQuantity').style.display = 'none';
        document.getElementById('to_validation_passNewComplexifyAlphaNumber').style.display = 'none';
        document.getElementById('passNew').value = "";
        document.getElementById('passConfirm').value = "";
        $("#passNew").focus();
        response = false;

    } else if (pass_conf === null && pass_conf === '' && pass !== null || pass !== '') {
        //O campo da confirmação da senha não pode está em branco!
        document.getElementById('to_validation_blank_passConfirm').style.display = 'block';
        document.getElementById('to_validation_passConfirmNew').style.display = 'none';
        document.getElementById('passConfirm').value = "";
        $("#passConfirm").focus();
        response = false;
    }
    return response;
}
function passwSave() {
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
    if (passVerify() === true) {
        var sc = document.getElementById("passCurrent");
        formhash(pass_profile, sc, 'passCurrentProfile');
        var sn = document.getElementById("passNew");
        formhash(pass_profile, sn, 'passNewProfile');
        var snc = document.getElementById("passConfirm");
        formhash(pass_profile, snc, 'passConfirmProfile');
        var data = $("#pass_profile").serialize();
        $.post(dir + "/profile/update_pwd", data, function (response) {
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