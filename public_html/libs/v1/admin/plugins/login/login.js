function formhash(form, ps) {
    // Create a new element input, this will be our hashed password field. 

    if ("ActiveXObject" in window && document.documentMode <= 11) {
        alert('Devido as incompatibilidades encontradas no Internet Explore, isso afeta a experiência de utilização do software. Com isso indicamos a utilização do Google Chrome!');
        //$(form).append('<input type="hidden" name="p" id="p" value="'+hex_sha512(password.value)+'" />');
    } else {
        // Add the new element to our form.    
        var p = document.createElement("input");
        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        //p.value = hex_sha512(ps.value);
        p.value = md5c(ps.value);
        // Make sure the plaintext password doesn't get sent. 
        ps.value = "";
    }
}