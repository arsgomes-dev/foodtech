$(document).ready(function () {
    $(".data").datepicker();
    var locale = document.getElementById("site_locale").value;
    Inputmask(masks[locale]).mask(".data");
});
(function ($) {
    //função para adicionar descrições
    addDescriptionElement = function () {
        var table = "description_elements";
        var add_tipo;
        var count_inputs;
        var div_tipo = "#description_elements";
        if (document.getElementsByName('descriptions_elements[]')) {
            count_inputs = document.getElementsByName('descriptions_elements[]').length;
        }
        var department = document.getElementById('description_element');
        var val_input;
        var condicao;
        var return_blank_validation = false;
        if (department.value !== null && department.value !== "") {
            $("#validation_description_lang").html('');
        } else {
            //campo obrigatório
            document.getElementById("to_validation_blank_description").style.display = "block";
            department.style.borderColor = "red";
            department.focus();
            return_blank_validation = true;
        }
        if (return_blank_validation === false) {
            if (count_inputs > 0) {
                for (var n = 0; n < count_inputs; n++) {
                    $("input[name='descriptions_elements[]']").each(function () {
                        val_input = $(this).val();
                        if (val_input === department.value) {
                            condicao = 1;
                        }
                    });
                }
                if (condicao !== 1) {
                    //chama função que adiciona a linha na tabela de descrições
                    addDescriptionElementDiv(table, count_inputs, department.value);
                    cleanDescriptionElement();
                } else {
                    //descrição já consta na lista
                    const Toast = swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    Toast.fire({
                        icon: 'warning',
                        title: " " + language_subscription_validation_input_insert_description
                    });
                }
            } else {
                //chama função que adiciona a linha na tabela de descrições
                addDescriptionElementDiv(table, count_inputs, department.value);
                cleanDescriptionElement();
            }
        }
    };
//função de deletar descrição
    deleteDescriptionElement = function (descrip) {
        if (descrip !== "" || descrip !== null) {
            var gfg_down =
                    document.getElementById(descrip.id);
            gfg_down.parentNode.removeChild(gfg_down);
            count_inputs = document.getElementsByName('descriptions_elements[]').length;
            if (count_inputs <= 0) {
                document.getElementById("description_element_count").value = null;
            }
            return false;
        }
    };
})(jQuery);
//limpar descrição
function cleanDescriptionElement() {
    document.getElementById('description_element').value = "";
    document.getElementById("to_validation_blank_description_element_count").style.display = "none";
    document.getElementById('description_element').style.borderColor = "#ced4da";
}
//função adiciona linha  na tabela
function addDescriptionElementDiv(table, count, description) {
    var val_input_c;
    var value_c;
    var count_inputs_c = 0;
    if (document.getElementsByName('descriptions_elements_count[]')) {
        count_inputs_c = document.getElementsByName('descriptions_elements_count[]').length;
    }

    if (count_inputs_c > 0) {
        for (var n = 0; n < count_inputs_c; n++) {
            $("input[name='descriptions_elements_count[]']").each(function () {
                val_input_c = parseInt($(this).val());
                if (count === val_input_c) {
                    count = count + 1;
                }
            });
        }

    }
    var cols = "";
    cols += '<input type="hidden" name="descriptions_elements[]" id="descriptions_elements[]" value="' + description + '">';
    cols += '<input type="hidden" name="descriptions_elements_count[]" id="descriptions_elements_count[]" value="' + count + '">';
    cols += '<font>' + description + '&nbsp;&nbsp;</font>';
    cols += '<a title="' + language_delete_option + '" id="div_description_element_' + count + '" href="javascript:void(0)" onclick="deleteDescriptionElement(this);"><i class="nav-icon fas fa-xmark"></i></a>';
    document.getElementById("description_element_count").value = count + 1;
    const main = document.getElementById(table);
    let element = document.createElement("div");
    // adiciona id
    element.id = 'div_description_element_' + count;
// adiciona classes
    element.classList.add('description_element');
    element.innerHTML = cols;
    main.appendChild(element);
}
$(document).ready(function () {
    document.getElementById("description_element").onblur = function () {
        document.getElementById("div_description_elements").style.borderColor = "#ced4da";
    };
    $('#description_element').on('keydown', function (event) { // também pode usar keyup
        if (event.keyCode === 13) {
            addDescriptionElement();
        }
    });
});
function mouseClickDescription() {
    $("#description_element").select();
    document.getElementById("div_description_elements").style.borderColor = "#3c8dbc";
}
function updateAccessPlan(form) {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    if (validationForm(form)) {
        const Toast = swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        var data = $("#" + form.id).serialize();
        $.post(dir + "/AccessPlans/save", data, function (response) {
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
        document.getElementById("div_description_elements").style.borderColor = "#ced4da";
    } else {
        if (document.getElementById("description_element_count").value === "" || document.getElementById("description_element_count").value === null) {
            document.getElementById("div_description_elements").style.borderColor = "#dc3545";
        }
    }
}
function updateLocaleCurrency(id) {
    if (id) {
        var currencysTemp = document.getElementById('currency_' + id).value;
        const input = document.getElementById('price_currency');
        var currencys = currencysTemp.split("-");
        var locale = currencys[1].replace("_", "-");
        var currency = currencys[0];
        input.value = "";
        input.dataset.currency = currency;
        input.dataset.locale = locale;
        input.setAttribute('placeholder', currencys[2]);
        const formatter = new Intl.NumberFormat(locale, {style: 'currency', currency});
        const fraction = 2; // a maioria das moedas tem 2 casas; ajuste se precisar
        const base = 10 ** fraction;
        const apply = (target) => {
            const digits = target.value.replace(/\D/g, '');
            target.value = digits ? formatter.format(Number(digits) / base) : '';
        };
        input.addEventListener('input', (e) => apply(e.target));
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const txt = (e.clipboardData || window.clipboardData).getData('text');
            e.target.value = txt;
            apply(e.target);
        });
    }
}
const coin_currency = document.getElementById('coin_currency');
coin_currency.addEventListener('change', function () {
    const valueSelected = this.value;
    if (valueSelected) {
        updateLocaleCurrency(valueSelected);
    }
});
function cleanForm(forms) {
    cancelValidationForm(forms);
    document.getElementById("currency").value = "";
    document.getElementById("h5-save-title").style.display = "block";
    document.getElementById("h5-update-title").style.display = "none";
    document.getElementById("btn-save-title").style.display = "block";
    document.getElementById("btn-update-title").style.display = "none";
    forms.reset();
    var ordCurrencySelect = document.getElementById('coin_currency');
    var ordCurrency = ordCurrencySelect.options[ordCurrencySelect.selectedIndex].value;
    updateLocaleCurrency(ordCurrency);
}
function editPrice(code, currency, price, start, end, st) {
    if (code !== "" && currency !== "" && st !== "" && price !== "") {
        document.getElementById("currency").value = code;
        $("#status_subdepartment").val(st);
        $("#coin_currency").val(currency);
        document.getElementById("h5-save-title").style.display = "none";
        document.getElementById("h5-update-title").style.display = "block";
        document.getElementById("btn-save-title").style.display = "none";
        document.getElementById("btn-update-title").style.display = "block";
        updateLocaleCurrency(currency);
        document.getElementById("price_currency").value = price;
        document.getElementById("start_currency").value = start;
        document.getElementById("end_currency").value = end;
        $('.new-modal').modal('show');
    }
}
function updateAccessPlanPrice(forms) {
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
        $.post(dir + "/AccessPlans/saveplanprice", data, function (response) {
            var msg = response.split("->");
            if (msg[0] === "1") {
                Toast.fire({
                    icon: 'success',
                    title: " " + msg[1]
                });
                cleanForm(forms);
                $('.new-modal').modal('hide');
                loadPrices();
            } else if (msg[0] === "2") {
                Toast.fire({
                    icon: "warning",
                    title: " " + msg[1]
                });
            }

        });
    }
}