function formhash(form, input, output) {
    if ("ActiveXObject" in window && document.documentMode <= 11) {
        alert('Devido as incompatibilidades encontradas no Internet Explore, isso afeta a experiência de utilização do software. Com isso indicamos a utilização do Google Chrome!');
    } else {
        var p = document.createElement("input");
        form.appendChild(p);
        p.name = output;
        p.type = "hidden";
        p.value = md5c(input.value);
        input.value = "";
    }
}