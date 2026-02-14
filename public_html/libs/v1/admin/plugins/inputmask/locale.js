 // Define o formato de data baseado na localidade
    const formats = {
        'pt-BR': 'datetime',
        'en-US': 'datetime',
        'default': 'datetime'
    };
    const masks = {
        'pt-BR': {
            alias: 'datetime',
            inputFormat: 'dd/mm/yyyy',
            placeholder: 'dd/mm/yyyy',
            leapday: "29/02/",
            showMaskOnHover: false
        },
        'en-US': {
            alias: 'datetime',
            inputFormat: 'mm/dd/yyyy',
            placeholder: 'mm/dd/yyyy',
            leapday: "02/29/",
            showMaskOnHover: false
        }
    };
    
    
   //exemplo direto $('#birth').inputmask('dd/mm/yyyy', {'placeholder': 'dd/mm/yyyy'});
   
   //exemplo com locale  
    //var locale = document.getElementById("site_locale").value;
    //Inputmask(masks[locale]).mask(".data");