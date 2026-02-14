 function loadNotification(code, title){
   if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#notification").html("");
    var data = "code="+code+"&title="+title;
     $.post(dir + "/list/Notifications/list", data, function (response) {
         $('#notification').html(response);
     });
}
function updateSts(nots, title, code, st){
    if (document.querySelector("#dir_site")){
    var dir_site = document.querySelector("#dir_site");
    var dir = "";
    if(dir_site.value !== null && dir_site.value !== ""){
       dir = dir_site.value;  
    }}
      const Toast = swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
    var data = "notification="+code+"&sts="+st;
    $.post(dir+"/functions/notification/save", data, function (response) {
                                    var msg = response.split("->");       
                                    if(msg[0] === "1"){
  Toast.fire({
  icon: 'success',
  title: " "+msg[1]
});
        loadNotification(nots, title);
                                    } else if(msg[0] === "2"){
   Toast.fire({
      icon: "warning",
      title: " "+msg[1]
    });
                                    }
                                       
    });
    }
    function changeSts(nots, title, code){
     if($("#notification_"+code).is(":checked")){
        updateSts(nots, title, code, 1);
    } else {
        updateSts(nots, title, code, 0);
    }
    }
    
    function notificationEdit(forms, code){
           if (document.querySelector("#dir_site")){
    var dir_site = document.querySelector("#dir_site");
    var dir = "";
    if(dir_site.value !== null && dir_site.value !== ""){
       dir = dir_site.value;  
    }}    
    if(validation_form(forms, code) === 1){
      const Toast = swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });    
    var description = forms.description;
   $(description).val(tinymce.get('description_not_'+code).getContent());     
   var data = $("#form_notification_"+code).serialize();
      $.post(dir+"/functions/notification/save", data, function (response) {
                                    var msg = response.split("->");       
                                    if(msg[0] === "1"){
  Toast.fire({
  icon: 'success',
  title: " "+msg[1]
});
       setTimeout(function() {
           location.reload(true);
       }, 1000);
                                    } else if(msg[0] === "2"){
   Toast.fire({
      icon: "warning",
      title: " "+msg[1]
    });
       setTimeout(function() {
           location.reload(true);
       }, 1000);
                                    }
                                       
    });    
    }
    }
    function validation_form(forms, code){
     var input_blank = "<span style='display: block;' id='exampleInputEmail1-error' class='error invalid-feedback'><font style='vertical-align: inherit;'><font style='vertical-align: inherit;'>"+language_validation_input_blank+"</font></font></span>";
     var title = forms.title;
     var description = forms.description_not;
     if(title.value === "" || tinymce.get('description_not_'+code).getContent() === "" ){
      if(title.value === ""){    
         $("#validation_title_"+code).html('');
         $("#validation_title_"+code).append(input_blank);
         title.style.borderColor = "red";
         title.focus();
     }else{
         $("#validation_title_"+code).append("");
         $("#validation_title_"+code).html('');
         title.style.borderColor = "#ced4da"; 
     }
       if(tinymce.get('description_not_'+code).getContent() === ""){
         $("#validation_description_"+code).html('');
         $("#validation_description_"+code).append(input_blank);
         description.style.borderColor = "red";
         description.focus();
         
     }else{
         $("#validation_description_"+code).append("");
         $("#validation_description_"+code).html('');
         description.style.borderColor = "#ced4da";
     }    
     }else{
         $("#validation_title_"+code).append("");
         $("#validation_title_"+code).html('');
         title.style.borderColor = "#ced4da"; 
         $("#validation_description_"+code).append("");
         $("#validation_description_"+code).html('');
         description.style.borderColor = "#ced4da";
         return 1;
     }
}