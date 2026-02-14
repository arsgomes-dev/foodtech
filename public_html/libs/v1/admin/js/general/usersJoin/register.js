$(document).ready(function(){
     $(".data").datepicker({
dateFormat: 'dd/mm/yy',
dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'],
dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
nextText: 'Próximo',
prevText: 'Anterior'
});  
   $('.cpf').mask('000.000.000-00');  
   $('.phone').mask('(00) 00000-0000');  
});
 function user_new(){
    if (document.querySelector("#dir_site")){
    var dir_site = document.querySelector("#dir_site");
    var dir = "";
    if(dir_site.value !== null && dir_site.value !== ""){
       dir = dir_site.value;  
    }}
    if(validation_form("user_form")){
        var data = $("#user_form").serialize();
        $.post(dir+"function/users/save", data, function (response) {
                                        var msg = response.split("->");       
                                         if(msg[0] === "1"){
                                            toastr.success(msg[1]);
                                        } else if(msg[0] === "2"){
                                            toastr.warning(msg[1]);
                                        }  
        });
    }
 }