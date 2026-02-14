function displayShowForm() {
    document.getElementById("show_departments").style.display = "block";
}
function displayHideForm() {
    document.getElementById("show_departments").style.display = "none";
}
function displayDepartments() {
    if ($("#agent_status").is(":checked")) {
        displayShowForm();
    } else {
        displayHideForm();
    }
}
function load() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
        }
    }
    var agent = "";
    var select = document.getElementById('user_agents');
    var value = select.options[select.selectedIndex].value;
    if (value !== null && value !== "") {
        agent = value;
    }
    $("#all").html("");
    var data = "code=" + agent;
    $.post(dir + "/list/Agents/list", data, function (response) {
        $('#all').html(response);
    });
}
function save(forms) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
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
        $.post(dir + "/control/Tickets/SaveSubdepartment", data, function (response) {
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