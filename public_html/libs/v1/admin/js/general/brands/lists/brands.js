var pag = 1;
var limit = 20;
function loadBrands() {
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
    var description = document.getElementById("brand_name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var status = document.querySelector('input[name=brand_status_search]:checked').value;
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
    $.post(dir + "/list/FoodBrands/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodBrands/pagination", data, function (response) {
        $('#pagination').html(response);
    });
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
    var description = document.getElementById("brand_name_search").value;
    if (description !== "") {
        description_search = "&description=" + description;
    }
    var status = document.querySelector('input[name=brand_status_search]:checked').value;
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
    $.post(dir + "/list/FoodBrands/list", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/list/FoodBrands/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function cleanSearch() {
    document.getElementById("brand_name_search").value = "";
    document.querySelector('input[id=status3]').checked = true;
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    document.getElementById("btn-clean-filter").style.display = "none";
    loadBrands();
}
$(document).ready(function () {
    loadBrands();
});

function cleanForm(form) {
    form.reset();
    $('#search-modal').modal('hide');
    $('#brand-create').modal('hide');
    $('#brand-update').modal('hide');
    cancelValidationForm(form);
    loadBrands();
}
function loadBtnBrands() {
    loadBrands();
    document.getElementById("btn-clean-filter").style.display = "inline-block";
    $('#search-modal').modal('hide');
}
function createBrands(forms) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(forms)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + forms.id).serialize();
        $.post(dir + "/control/FoodBrands/Save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(forms);
                loadBrands();
                $('.brand-create').modal('hide');
                $('.brand-update').modal('hide');
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
function brand_edit(brand, description, status) {
    document.querySelector("[name='code']").value = brand;
    document.querySelector("[id='description_edit']").value = description;
    if (status === 1 || status === 0) {
        document.getElementById('status_edit').value = status;
    } else {
        document.getElementById('status_edit').value = 0;
    }
    $('#brand-update').modal('show');
}