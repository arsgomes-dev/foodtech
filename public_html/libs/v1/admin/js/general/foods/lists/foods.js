var pag = 1;
var limit = 20;
function loadFoods() {
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
    var description = document.getElementById("food_name").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var group = "";
    var groupSelect = document.getElementById('group_search');
    var groupValue = groupSelect.options[groupSelect.selectedIndex].value;
    if (groupValue !== null && groupValue !== "") {
        group = "&group=" + groupValue;
    }
    var brand = "";
    var brandSelect = document.getElementById('brand_search');
    var brandValue = brandSelect.options[brandSelect.selectedIndex].value;
    if (brandValue !== null && brandValue !== "") {
        brand = "&brand=" + brandValue;
    }
    var table = "";
    var tableSelect = document.getElementById('table_search');
    var tableValue = tableSelect.options[tableSelect.selectedIndex].value;
    if (tableValue !== null && tableValue !== "") {
        table = "&table=" + tableValue;
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
    var data = "pag=" + pag + "&limit=" + limit + description_search + group + brand + table + ord;
    $.post(dir + "/foods/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/foods/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnFoods() {
    loadFoods();
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
    var description = document.getElementById("food_name").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var group = "";
    var groupSelect = document.getElementById('group_search');
    var groupValue = groupSelect.options[groupSelect.selectedIndex].value;
    if (groupValue !== null && groupValue !== "") {
        group = "&group=" + groupValue;
    }
    var brand = "";
    var brandSelect = document.getElementById('brand_search');
    var brandValue = brandSelect.options[brandSelect.selectedIndex].value;
    if (brandValue !== null && brandValue !== "") {
        brand = "&brand=" + brandValue;
    }
    var table = "";
    var tableSelect = document.getElementById('table_search');
    var tableValue = tableSelect.options[tableSelect.selectedIndex].value;
    if (tableValue !== null && tableValue !== "") {
        table = "&table=" + tableValue;
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
    var data = "pag=" + pag + "&limit=" + limit + description_search + group + brand + table + ord;
    $.post(dir + "/foods/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/foods/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("food_name").value = "";
    select_boxGroup = document.getElementById("group_search");
    select_boxGroup.selectedIndex = 0;
    select_boxBrand = document.getElementById("brand_search");
    select_boxBrand.selectedIndex = 0;
    select_boxTable = document.getElementById("table_search");
    select_boxTable.selectedIndex = 0;
    document.querySelector('input[id=status3]').checked = true;
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    loadFoods(); 
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
$(document).ready(function () {
    loadFoods();
});