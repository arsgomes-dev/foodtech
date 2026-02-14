var pag = 1;
var limit = 20;
function loadCustomers() {
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
    var description = document.getElementById("name").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var cpf = document.getElementById("cpf").value;
    if (cpf !== "") {
        description_search += "&cpf=" + cpf;
    }
    var email = document.getElementById("email").value;
    if (email !== "") {
        description_search += "&email=" + email;
    }
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        description_search += "&status=" + status;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/customers/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/customers/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnCustomers() {
    loadCustomers();
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
    var description = document.getElementById("name").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var cpf = document.getElementById("cpf").value;
    if (cpf !== "") {
        description_search += "&cpf=" + cpf;
    }
    var email = document.getElementById("email").value;
    if (email !== "") {
        description_search += "&email=" + email;
    }
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        description_search += "&status=" + status;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/customers/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/customers/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
$(document).ready(function () {
    loadCustomers();
});

function cleanSearch() {
    document.getElementById("name").value = "";
    document.getElementById("cpf").value = "";
    document.getElementById("email").value = "";
    document.querySelector('input[id=status3]').checked = true;
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    loadCustomers();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}