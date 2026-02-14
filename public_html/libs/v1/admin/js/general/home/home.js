function redirectPost(url, data) {
  const form = document.createElement("form");
  form.method = "POST";
  form.action = url;

  // Adiciona os campos
  for (const key in data) {
    if (data.hasOwnProperty(key)) {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = data[key];
      form.appendChild(input);
    }
  }

  document.body.appendChild(form);
  form.submit();
}
function loadCharts() {
    if (document.querySelector("#dir_site")) {
        var dir_site = document.querySelector("#dir_site");
        var dir = "";
        if (dir_site.value !== null && dir_site.value !== "") {
            dir = "/" + dir_site.value;
        }
    }
    $("#signatureChartDiv").html("");
    $.post(dir + "/dashboard/chart/signatures", "", function (response) {
        $('#signatureChartDiv').html(response);
    });
    $("#ticketsChartDiv").html("");
    $.post(dir + "/dashboard/chart/tickets", "", function (response) {
        $('#ticketsChartDiv').html(response);
    });
}
$(document).ready(function () {
    loadCharts();
});
// Exemplo de uso - redirectPost("destino.php", { nome: "Maria", idade: 30 });
/*
fetch("destino.php", {
  method: "POST",
  headers: { "Content-Type": "application/x-www-form-urlencoded" },
  body: "nome=Maria&idade=30"
})
.then(response => response.text())
.then(data => {
  // Redireciona após resposta
  window.location.href = "outra-pagina.php";
}); 
 
 *Se você precisa que o usuário realmente chegue na próxima página com dados vindos de um POST → use o Exemplo 1 (formulário).

Se você só precisa enviar dados antes de mudar de página → pode usar o Exemplo 2 (fetch).
 *
 **/