var pag = 1;
var limit = 20;
function loadUsers() {
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
    var description = document.getElementById("name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var cpf = document.getElementById("cpf_search").value;
    if (cpf !== "") {
        description_search += "&cpf=" + cpf;
    }
    var email = document.getElementById("email_search").value;
    if (email !== "") {
        description_search += "&email=" + email;
    }
    var ordDepartment = document.getElementById('department_search');
    var ordValueDepartment = ordDepartment.options[ordDepartment.selectedIndex].value;
    if (ordValueDepartment !== null && ordValueDepartment !== "") {
        description_search += "&department=" + ordValueDepartment;
    }
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        description_search += "&status=" + status;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/users/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/users/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnUsers() {
    loadUsers();
    document.getElementById("btn-clean-filter").style.display = "inline-block";
    $('#search-modal').modal('hide');
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
    var description = document.getElementById("name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var cpf = document.getElementById("cpf_search").value;
    if (cpf !== "") {
        description_search += "&cpf=" + cpf;
    }
    var email = document.getElementById("email_search").value;
    if (email !== "") {
        description_search += "&email=" + email;
    }
    var ordDepartment = document.getElementById('department_search');
    var ordValueDepartment = ordDepartment.options[ordDepartment.selectedIndex].value;
    if (ordValueDepartment !== null && ordValueDepartment !== "") {
        description_search += "&department=" + ordValueDepartment;
    }
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        description_search += "&status=" + status;
    }

    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
        $.post(dir + "/users/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/users/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("name_search").value = "";
    document.getElementById("cpf_search").value = "";
    document.getElementById("email_search").value = "";
    select_boxDepartment = document.getElementById("department_search");
    select_boxDepartment.selectedIndex = 0;
    document.querySelector('input[id=status3]').checked = true;
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 3;
    loadUsers();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadUsers();
});