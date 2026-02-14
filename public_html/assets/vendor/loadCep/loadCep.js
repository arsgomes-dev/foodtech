// limpa todos os campos de endereço
function searchCep(input) {
    var inp = input.id;
    var returns = true;
    var cep = input.value.replace(/\D/g, '');
    if (cep.length === 8) {
        returns = loadCep(input);
    } else {
        returns = false;
    }
    if (returns === true) {
        input.classList.remove('is-invalid');
        document.getElementById("to_validation_invalid_" + inp).style.display = "none";
    } else {
        input.classList.add('is-invalid');
        document.getElementById("to_validation_invalid_" + inp).style.display = "block";
    }
    return returns;
}
function clearAddressFields() {
    ["avenue", "neighborhood", "complement", "number", "city", "state"].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = "";
    });
}

// função principal
async function loadCep(input) {
    const cep = input.value.replace(/\D/g, '');

    // se o CEP for menor que 8 dígitos → limpa e sai
    if (cep.length < 8) {
        clearAddressFields();
        input.classList.remove('is-invalid'); // não marca erro enquanto digita
        const errEl = document.getElementById("to_validation_invalid_" + input.id);
        if (errEl) errEl.style.display = "none";
        return;
    }

    // cria um timeout de 2 segundos
    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 2000);

    try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`, { signal: controller.signal });
        clearTimeout(timeout);

        if (!res.ok) throw new Error("Erro na requisição");
        const data = await res.json();

        if (data.erro) throw new Error("CEP inválido");

        // preenche os campos
        document.getElementById("avenue").value       = data.logradouro || '';
        document.getElementById("neighborhood").value = data.bairro || '';
        document.getElementById("city").value         = data.localidade || '';
        document.getElementById("state").value        = data.estado || '';

        input.classList.remove('is-invalid');
        const errEl = document.getElementById("to_validation_invalid_" + input.id);
        if (errEl) errEl.style.display = "none";

    } catch (err) {
        clearTimeout(timeout);
        clearAddressFields();

        // marca campo como inválido imediatamente
        input.classList.add('is-invalid');
        const errEl = document.getElementById("to_validation_invalid_" + input.id);
        if (errEl) errEl.style.display = "block";
    }
}

// aplica debounce para evitar requisições contínuas
function debounce(func, delay = 400) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

const cepInput = document.getElementById("cep");
cepInput.addEventListener('input', debounce(function() {
    loadCep(this);
}, 400));
