function displayShowForm(){
    document.getElementById("show_departments").style.display = "block";
}
function displayHideForm(){
    document.getElementById("show_departments").style.display = "none";
}
function displayDepartments(){
     if($("#agent_status").is(":checked")){
         displayShowForm();
     }else{
         displayHideForm();
     }
}
function load(){
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/"+dir_site.value;
        }
    }
    var agent = "";
    var select = document.getElementById('user_agents');
    var value = select.options[select.selectedIndex].value;
    if(value !== null && value !== ""){
       agent = value;  
    }
    $("#all").html("");
    var data = "code="+agent;
     $.post(dir+"/list/Agents/list", data, function (response) {
         $('#all').html(response);
     });
}