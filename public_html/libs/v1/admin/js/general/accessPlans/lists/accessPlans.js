var pag = 1;
var limit = 20;
function loadPlans() {
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
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + sts + ord;
    $.post(dir + "/AccessPlans/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/AccessPlans/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnPlans() {
    loadPlans();
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
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        var description_search = "&description=" + description;
    }

    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + sts + ord;
    $.post(dir + "/AccessPlans/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/AccessPlans/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
$(document).ready(function () {
    loadPlans();
});
function cleanSearch() {
    document.getElementById("description_search").value = "";
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 3;
    document.querySelector('input[id=status3]').checked = true;
    loadPlans();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}