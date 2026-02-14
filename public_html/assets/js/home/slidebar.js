function displayMessage(icon, message) {
    const Toast = swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    Toast.fire({
        icon: icon,
        title: " " + message
    });
}
document.addEventListener('DOMContentLoaded', () => {
    // Configuração de Diretório (Compatibilidade)
    const dirSite = $("#dir_site").val();
    const dir = dirSite ? "/" + dirSite : "";

    const buttons = document.querySelectorAll('.select-workspace');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {

            const gcid = btn.getAttribute('data-gcid');
            const title = btn.getAttribute('data-title');

            // Envio AJAX
            fetch(dir + "/channels/workspace", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({workspace: gcid})
            })
                    .then(res => res.json())
                    .then(data => {

                        if (data.success) {
                            // atualiza sem recarregar muito a UI
                            window.location.reload();
                        } else {
                            displayMessage('error', data.error);
                        }

                    });

        });
    });

});