var pag = 1;
var limit = 20;
function loadPrices() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (document.querySelector("#code")) {
        var code_site = document.querySelector("#code");
        var code = "";
        if (code_site.value !== null && code_site.value !== "") {
            code = "&code=" + code_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var data = "pag=" + pag + "&limit=" + limit + code;
    $.post(dir + "/AccessPlans/search/list_currency", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/AccessPlans/search/pagination_currency", data, function (response) {
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
    if (document.querySelector("#code")) {
        var code_site = document.querySelector("#code");
        var code = "";
        if (code_site.value !== null && code_site.value !== "") {
            code = "&code=" + code_site.value;
        }
    }
    var data = "pag=" + pag + "&limit=" + limit + code;
    $.post(dir + "/AccessPlans/search/list_currency", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/AccessPlans/search/pagination_currency", data, function (response) {
        $('#pagination').html(response);
    });
}
$(document).ready(function () {
    loadPrices();
});