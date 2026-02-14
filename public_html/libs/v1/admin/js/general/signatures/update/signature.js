$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
});