$("#percentage").maskMoney({suffix: '%', allowNegative: true, thousands: '.', decimal: ',', affixesStay: false});
function cleanForm(forms) {
    cancelValidationForm(forms);
}
function updateCoupon(forms) {
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
        $.post(dir + "/coupons/save", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }else{
         if(document.getElementById("description_element_count").value === "" || document.getElementById("description_element_count").value === null){
             document.getElementById("div_description_elements").style.borderColor = "#dc3545";
         }
        
    }
}
const btnUpdateCoupon = document.getElementById("div-update-coupon");
btnUpdateCoupon.addEventListener("click", function () {
    updateCoupon(update_coupon);
});

