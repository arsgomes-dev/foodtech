$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
    //Datemask dd/mm/yyyy
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
    if (validationForm(form) && emailSearch(form.email, form.code.value) && cpfSearch(form.cpf, form.code.value)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/users/controller/update", data, function (response) {
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
function updateDepartment(form) {
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
        $.post(dir + "/users/controller/department/update", data, function (response) {
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
        $.post(dir + "/users/controller/status", data, function (response) {
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
function updatePrivileges(form) {
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
        $.post(dir + "/users/controller/privilege/update", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                var select = document.getElementById('privileges');
                var option = select.children[select.selectedIndex];
                var texto = option.textContent;
                document.getElementById("tr-privileges").innerHTML = texto;
                location.reload();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
            $('#modal-privileges').modal('hide');
        });
    }
}
function updateAgent(form) {
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
        $.post(dir + "/users/controller/agent/update", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                location.reload();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }
            $('#modal-agent').modal('hide');
        });
    }
}
function emailSearch(input, code) {
    var returns = true;
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
        var inp = input.id;
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
            $.post(dir + "/users/controller/passwd", data, function (response) {
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
function loadOccupation() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var ordDepartment = "";
    edit = "#ocuppation_div";
    ordDepartment = document.getElementById('department');
    var code = "";
    $(edit).html("");
    var ordValueDepartment = ordDepartment.options[ordDepartment.selectedIndex].value;
    if (ordValueDepartment !== null && ordValueDepartment !== "") {
        code = "code=" + ordValueDepartment;
    }
    var data = code;
    $.post(dir + "/occupations/search/occupations", data, function (response) {
        $(edit).html(response);
    });
}
function loadOccupations(code, occupation) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var post = "code=" + code + "&occupation=" + occupation;
    var data = post;
    $.post(dir + "/occupations/search/occupations", data, function (response) {
        $('#ocuppation_div').html(response);
    });
}
function displayShowForm() {
    document.getElementById("show_departments").style.display = "block";
}
function displayHideForm() {
    document.getElementById("show_departments").style.display = "none";
}
function displayDepartments() {
    const select = document.getElementById("agent_status");
    const valueSelect = select.value;
    if (valueSelect === "1") {
        displayShowForm();
    } else {
        displayHideForm();
    }
}
function userPhotoSave() {
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

    var forms = document.getElementById('form_photo_user');/*$('#form_photo_user')[0];*/
    var form_d = new FormData(forms);
    $.ajax({
        url: dir + "/users/controller/photo",
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
            document.getElementById('div_modal_user_img').style.display = 'block';
            document.getElementById('div_modal_user_i_div').style.display = 'none';
            $('#div_modal_user_img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $('#user_photo').on("change", function () {
        readURL(this);
    });
});
function uploadPhoto() {
    $('#user_photo').trigger('click');
}
function cleanFormPhoto() {
    document.getElementById('div_modal_user_img').style.display = 'none';
    document.getElementById('div_modal_user_i_div').style.display = 'block';
    $('#div_modal_user_img').attr('src', '');
    $('#user_photo').attr('value', '');
}

const btnUploadPhoto = document.getElementById("div-upload-user-photo");
btnUploadPhoto.addEventListener("click", function () {
uploadPhoto();
});

const btnCleanFormPhoto = document.getElementById("btn-clean-form-photo");
btnCleanFormPhoto.addEventListener("click", function () {
cleanFormPhoto();
});

const btnSavePhoto = document.getElementById("btn-save-photo");
btnSavePhoto.addEventListener("click", function () {
userPhotoSave();
});

const btnUpdateStatus = document.getElementById("btn-update-status");
btnUpdateStatus.addEventListener("click", function () {
updateStatus(edit_status);
});

const btnUpdatePrivileges = document.getElementById("btn-update-privileges");
btnUpdatePrivileges.addEventListener("click", function () {
updatePrivileges(edit_privileges);
});

const btnUpdateAgent = document.getElementById("btn-update-agent");
btnUpdateAgent.addEventListener("click", function () {
    updateAgent(edit_agent);
});