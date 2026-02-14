
var pag = 1;
var limit = 20;
function loadTickets() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#list").html("");
    $("#pagination").html("");
    var depart = "";
    var departSelect = document.getElementById('department_search');
    var departValue = departSelect.options[departSelect.selectedIndex].value;
    if (departValue !== null && departValue !== "") {
        depart = "&department=" + departValue;
    }
    var priority = "";
    var prioritySelect = document.getElementById('priority_search');
    var priorityValue = prioritySelect.options[prioritySelect.selectedIndex].value;
    if (priorityValue !== null && priorityValue !== "") {
        priority = "&priority=" + priorityValue;
    }
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
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
    var data = "pag=" + pag + "&limit=" + limit + depart + priority + sts + ord + description;
    $.post(dir + "/Tickets/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/Tickets/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
    if (ordValue === "8" || ordValue === "9") {
        document.getElementById("btn-clean-filter").style.display = "inline-block";
    }
}
function loadBtnTickets() {
    loadTickets();
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
    var depart = "";
    var departSelect = document.getElementById('department_search');
    var departValue = departSelect.options[departSelect.selectedIndex].value;
    if (departValue !== null && departValue !== "") {
        depart = "&department=" + departValue;
    }
    var priority = "";
    var prioritySelect = document.getElementById('priority_search');
    var priorityValue = prioritySelect.options[prioritySelect.selectedIndex].value;
    if (priorityValue !== null && priorityValue !== "") {
        priority = "&priority=" + priorityValue;
    }
    var sts = "";
    var status = document.querySelector('input[name=status]:checked').value;
    if (status !== "") {
        sts = "&sts=" + status;
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
    var data = "pag=" + pag + "&limit=" + limit + depart + priority + sts + ord + description;
    $.post(dir + "/Tickets/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/Tickets/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("description_search").value = "";
    $("#department_search").prop("selectedIndex", 0).val();
    $("#priority_search").prop("selectedIndex", 0).val();
    document.querySelector('input[id=status3]').checked = true;
    $("#ord_search").prop("selectedIndex", 0).val();
    select_box = document.getElementById("ord_search");
    select_box.selectedIndex = 6;
    loadTickets();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadTickets();
});
