function validationForm(form){
    var returns = true;
    const to_validations = document.getElementById(form.id).getElementsByClassName("to_validations");
   var to_validations_count = to_validations.length;
            for (var i = 0; i < to_validations_count; i++) {
                if(to_validations[i].value === "" || to_validations[i].value === null){
                to_validations[i].classList.add('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "block";
                returns = false;
                }else{
                to_validations[i].classList.remove('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "none";
            }
        }
        return returns; 
 }
 
 function cancelValidationForm(form){
    const to_validations = document.getElementById(form.id).getElementsByClassName("to_validations");
    var to_validations_count = to_validations.length;
            for (var i = 0; i < to_validations_count; i++) {
                to_validations[i].classList.remove('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "none";
                    }
 }
 function validationOther(idField){
    var returns = true;
    const to_validations = document.getElementById(idField).getElementsByClassName("to_validations");
   var to_validations_count = to_validations.length;
            for (var i = 0; i < to_validations_count; i++) {
                if(to_validations[i].value === "" || to_validations[i].value === null){
                to_validations[i].classList.add('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "block";
                returns = false;
                }else{
                to_validations[i].classList.remove('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "none";
            }
        }
        return returns; 
 }
 
 function cancelValidationOther(idField){
    const to_validations = document.getElementById(idField).getElementsByClassName("to_validations");
    var to_validations_count = to_validations.length;
            for (var i = 0; i < to_validations_count; i++) {
                to_validations[i].classList.remove('is-invalid');
                var id_input = "to_validation_blank_"+to_validations[i].id;
                document.getElementById(id_input).style.display = "none";
                    }
 }