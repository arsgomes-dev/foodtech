function cleanSubdepartment(forms) {
    cancelValidationForm(forms);
    document.getElementById("sub_code").value = "";
    document.getElementById("h5-save-title").style.display = "block";
    document.getElementById("h5-update-title").style.display = "none";
    document.getElementById("btn-save-title").style.display = "block";
    document.getElementById("btn-update-title").style.display = "none";
    forms.reset();
}
function createSubdepartment(forms) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(forms)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + forms.id).serialize();
        $.post(dir + "/tickets/subdepartments/controller/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                $('.modal-new-subdepartment').modal('hide');
                loadSubdepartment();
                cleanSubdepartment(forms);
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function editSubdepartment(subdepart, title, st, pr) {
    if (subdepart !== "" && st !== "" && pr !== "") {
        document.getElementById("sub_code").value = subdepart;
        document.getElementById("title").value = title;
        $("#status").val(st);
        $("#status_priority").val(pr);
        document.getElementById("h5-save-title").style.display = "none";
        document.getElementById("h5-update-title").style.display = "block";
        document.getElementById("btn-save-title").style.display = "none";
        document.getElementById("btn-update-title").style.display = "block";
        $('.modal-new-subdepartment').modal('show');
    }
}