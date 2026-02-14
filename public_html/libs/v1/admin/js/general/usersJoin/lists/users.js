var pag = 1; var limit = 20;
function load_clients() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var description_search = "";
    var description = document.getElementById("name").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/list/Users/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/Users/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function pagination(pag) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
        }
    }
    var description_search = "";
    var description = document.getElementById("name").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }

    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/list/Users/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/Users/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch(){
    document.getElementById("name").value = "";
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    load_clients();
}
function register_user(){
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
        }
    }
     window.location.href = dir + "/Users/new";
}

$(document).ready(function () {
    load_clients();
});