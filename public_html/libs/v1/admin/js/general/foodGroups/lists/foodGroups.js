var pag = 1;
var limit = 20;
function loadGroups() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var description_search = "";
    var description = document.getElementById("group_name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/list/FoodGroups/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodGroups/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function pagination(pag) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var description_search = "";
    var description = document.getElementById("group_name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/list/FoodGroups/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodGroups/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("group_name_search").value = "";
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    document.getElementById("btn-clean-filter").style.display = "none";
    loadGroups();
}
$(document).ready(function () {
    loadGroups();
});

function cleanForm(form) {
    form.reset();
    $('#search-modal').modal('hide');
    $('#group-create').modal('hide');
    $('#group-update').modal('hide');
    cancelValidationForm(form);
    loadGroups();
}
function loadBtnGroups() {
    loadGroups();
    document.getElementById("btn-clean-filter").style.display = "inline-block";
    $('#search-modal').modal('hide');
}
function createGroup(forms) {
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
        $.post(dir + "/control/FoodGroups/Save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(forms);
                loadGroups();
                $('.group-create').modal('hide');
                $('.group-update').modal('hide');
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function group_edit(group, description) {
    document.querySelector("[name='code']").value = group;
    document.querySelector("[id='description_edit']").value = description;
    $('#group-update').modal('show');
}