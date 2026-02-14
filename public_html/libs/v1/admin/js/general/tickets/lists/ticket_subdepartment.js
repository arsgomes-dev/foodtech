var pag = 1;
var limit = 20;
function loadSubdepartment() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var description_search = "";
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    if (document.querySelector("#code")) {
        var code_site = document.querySelector("#code");
        var code = "";
        if (code_site.value !== null && code_site.value !== "") {
            code = "&code=" + code_site.value;
        }
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    $("#list").html("");
    $("#pagination").html("");
    var data = "pag=" + pag + "&limit=" + limit + code + description_search + ord;
    $.post(dir + "/tickets/subdepartments/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/tickets/subdepartments/pagination", data, function (response) {
        $('#pagination').html(response);
    });
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
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    if (document.querySelector("#code")) {
        var code_site = document.querySelector("#code");
        var code = "";
        if (code_site.value !== null && code_site.value !== "") {
            code = "&code=" + code_site.value;
        }
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + code + description_search + ord;
    $.post(dir + "/tickets/subdepartments/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/tickets/subdepartments/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 3;
    document.getElementById('description_search').value = "";
    loadSubdepartment();
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadSubdepartment();
});