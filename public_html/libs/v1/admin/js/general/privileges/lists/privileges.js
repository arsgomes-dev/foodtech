var pag = 1;
var limit = 20;
function loadPrivileges() {
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
        var description_search = "&description=" + description;
    }
    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/privileges/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/privileges/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
function loadBtnPrivileges() {
    loadPrivileges();
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
        var description_search = "&description=" + description;
    }

    var ord = "";
    var ordSelect = document.getElementById('ord');
    var ordValue = ordSelect.options[ordSelect.selectedIndex].value;
    if (ordValue !== null && ordValue !== "") {
        ord = "&ord=" + ordValue;
    }
    var data = "pag=" + pag + "&limit=" + limit + description_search + ord;
    $.post(dir + "/privileges/search/lists", data, function (response) {
        $('#list').html(response);
    });
    $.post(dir + "/privileges/search/pagination", data, function (response) {
        $('#pagination').html(response);
    });
}
$(document).ready(function () {
    loadPrivileges();
});
function cleanSearch() {
    document.getElementById("name").value = "";
    select_box = document.getElementById("ord");
    select_box.selectedIndex = 3;
    loadPrivileges();
    document.getElementById("btn-clean-filter").style.display = "none";
    $('#search-modal').modal('hide');
}
function cleanForm(forms) {
    cancelValidationForm(forms);
    const form = document.getElementById(forms.id);
    form.reset();
}
function createPrivilege(forms) {
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
        $.post(dir + "/privileges/controller/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                $('.addPrivilege').modal('hide');
                loadPrivileges();
                cleanForm(forms);

            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}
 const btnCreate = document.getElementById("btn-privilege");
 const btnCleanForm = document.getElementById("btn-cleanForm");
 const btnCleanSearch = document.getElementById("btn-cleanSearch");
 const btnLoadSearch = document.getElementById("btn-loadSearch");
  // Adiciona o evento de clique
  btnCreate.addEventListener("click", function() {
    createPrivilege(form_privilege);
  });
  btnCleanForm.addEventListener("click", function() {
    cleanForm(form_privilege);
  });
  btnCleanSearch.addEventListener("click", function() {
    cleanSearch();
  });
  btnLoadSearch.addEventListener("click", function() {
    loadBtnPrivileges();
  });