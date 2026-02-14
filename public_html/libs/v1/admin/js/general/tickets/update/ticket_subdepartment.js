function editSubdepartment(subdepart, title, st, pr) {
    if (subdepart !== "" && st !== "" && pr !== "") {
        document.getElementById("sub_code").value = subdepart;
        document.getElementById("title_subdepartment").value = title;
        $("#status_subdepartment").val(st);
        $("#status_priority").val(pr);
        $('.modal-new-subdepartment').modal('show');
        document.getElementById("h5-save-title").style.display = "none";
        document.getElementById("h5-update-title").style.display = "block";
        document.getElementById("btn-save-title").style.display = "none";
        document.getElementById("btn-update-title").style.display = "block";
    }
}