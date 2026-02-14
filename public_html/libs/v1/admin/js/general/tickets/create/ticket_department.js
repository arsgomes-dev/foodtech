function cleanForm(forms) {
    cancelValidationForm(forms);
    forms.reset();
}
function createDepartment(forms) {
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
        if (forms.id === "formDepartment") {
            var title = forms.title;
            var description = forms.description;
            title.value = "";
            description.value = "";
        }
        $.post(dir + "/tickets/departments/controller/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                $('.addDepartment').modal('hide');
                cancelValidationForm(forms);
                loadDepartments();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
