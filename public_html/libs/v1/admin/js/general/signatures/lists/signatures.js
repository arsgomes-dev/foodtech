var pag = 1;
var limit = 20;
$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
});

function loadSignatures() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
    }
    var start = "";
    var dateStart = document.getElementById("date_start_search").value;
    if (dateStart !== "") {
        start = "&start=" + dateStart;
    }
    var end = "";
    var dateEnd = document.getElementById("date_end_search").value;
    if (dateEnd !== "") {
        end = "&end=" + dateEnd;
    }
    var closureStart = "";
    var dateClosureStart = document.getElementById("date_closure_start_search").value;
    if (dateClosureStart !== "") {
        closureStart = "&closureStart=" + dateClosureStart;
    }
    var closureEnd = "";
    var dateClosureEnd = document.getElementById("date_closure_end_search").value;
    if (dateClosureEnd !== "") {
        closureEnd = "&closureEnd=" + dateClosureEnd;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var description = "";
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        description = "&description=" + description;
    }
    var data = "pag=" + pag + "&limit=" + limit + sts + ord + description + start + end + closureStart + closureEnd;
    $.post(dir + "/signatures/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signatures/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnSignatures() {
    loadSignatures();
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
    var start = "";
    var dateStart = document.getElementById("date_start_search").value;
    if (dateStart !== "") {
        start = "&start=" + dateStart;
    }
    var end = "";
    var dateEnd = document.getElementById("date_end_search").value;
    if (dateEnd !== "") {
        end = "&end=" + dateEnd;
    }
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
    }
    var closureStart = "";
    var dateClosureStart = document.getElementById("date_closure_start_search").value;
    if (dateClosureStart !== "") {
        closureStart = "&closureStart=" + dateClosureStart;
    }
    var closureEnd = "";
    var dateClosureEnd = document.getElementById("date_closure_end_search").value;
    if (dateClosureEnd !== "") {
        closureEnd = "&closureEnd=" + dateClosureEnd;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord_search');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var description = "";
    var description = document.getElementById("description_search").value;
    if (description !== "") {
        description = "&description=" + description;
    }
    var data = "pag=" + pag + "&limit=" + limit + sts + ord + description + start + end + closureStart + closureEnd;
    $.post(dir + "/signatures/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/signatures/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("description_search").value = "";
    document.getElementById("date_start_search").value = "";
    document.getElementById("date_end_search").value = "";
    document.getElementById("date_closure_start_search").value = "";
    document.getElementById("date_closure_end_search").value = "";
    document.querySelector('input[id=status5]').checked = true;
    $("#ord_search").prop("selectedIndex", 0).val();
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 1;
    loadSignatures();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadSignatures();
});
