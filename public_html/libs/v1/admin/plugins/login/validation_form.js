function validation_form(form){
    var returns = true;
    const to_need = document.getElementById(form).getElementsByClassName("to_need");
    var to_need_count = to_need.length;
            for (var i = 0; i < to_need_count; i++) {
                if(to_need[i].value === "" || to_need[i].value === null){
                to_need[i].classList.add('is-invalid');
                returns = false;
                }else{
                to_need[i].classList.remove('is-invalid');
            }
        }
        return returns; 
 }
 function cancelValidationForm(form){
    const to_need = document.getElementById(form).getElementsByClassName("to_need");
    var to_need_count = to_need.length;
            for (var i = 0; i < to_need_count; i++) {
                to_need[i].classList.remove('is-invalid');
                    }
 }