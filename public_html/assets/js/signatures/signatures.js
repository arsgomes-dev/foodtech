var pag = 1;
var limit = 20;
$(document).ready(function () {
    var locale = document.getElementById("site_locale").value;
});

function loadPayments() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var signature_gcid = document.getElementById('signature_gcid').value;
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "code=" + signature_gcid + "&pag=" + pag + "&limit=" + limit + ord;
    $.post(dir + "/signature/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signature/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnPayments() {
    loadPayments();
    document.getElementById("btn-clean-filter").style.display = "inline-block";
    $('#search-modal').modal('hide');
}
function pagination(pag) {
    var signature_gcid = document.getElementById('signature_gcid').value;
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "code=" + signature_gcid + "&pag=" + pag + "&limit=" + limit + ord;
    $.post(dir + "/signature/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signature/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("date_start_search").value = "";
    document.getElementById("date_end_search").value = "";
    document.querySelector('input[id=status999]').checked = true;
    $("#ord_search").prop("selectedIndex", 0).val();
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 1;
    loadPayments();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadPayments();
});
