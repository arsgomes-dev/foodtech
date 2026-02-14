var pag = 1;
var limit = 20;
function loadTables() {
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
    var description = document.getElementById("table_name_search").value;
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
    $.post(dir + "/list/FoodTables/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodTables/pagination", data, function (response) {
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
    var description = document.getElementById("table_name_search").value;
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
    $.post(dir + "/list/FoodTables/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodTables/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("table_name_search").value = "";
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    document.getElementById("btn-clean-filter").style.display = "none";
    loadTables();
}
$(document).ready(function () {
    loadTables();
});

function cleanForm(form) {
    form.reset();
    $('#search-modal').modal('hide');
    $('#table-create').modal('hide');
    $('#table-update').modal('hide');
    cancelValidationForm(form);
    loadTables();
}
function loadBtnTables() {
    loadTables();
    document.getElementById("btn-clean-filter").style.display = "inline-block";
    $('#search-modal').modal('hide');
}
function createTable(forms) {
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
        $.post(dir + "/control/FoodTables/Save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(forms);
                loadTables();
                $('#table-create').modal('hide');
                $('#table-update').modal('hide');
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function table_edit(table, description) {
    document.querySelector("[name='code']").value = table;
    document.querySelector("[id='description_edit']").value = description;
    $('#table-update').modal('show');
}