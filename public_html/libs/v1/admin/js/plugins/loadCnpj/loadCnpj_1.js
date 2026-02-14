function formatDateBr(dateStr) {
    if (!dateStr)
        return "";
    const [year, month, day] = dateStr.split("-");
    return `${day}/${month}/${year}`;
}

async function getCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, ''); // só números
    if (cnpj.length !== 14) {
        console.warn("CNPJ inválido");
        return;
    }
    const cleanNumber = (str) => {
        if (!str)
            return "";
        const cleaned = str.toString().replace(/^0+/, '');
        return cleaned === '' ? '' : cleaned;
    };

    try {
        const res = await fetch(`https://brasilapi.com.br/api/cnpj/v1/${cnpj}`);
        if (!res.ok)
            throw new Error("Erro na requisição");

        const data = await res.json();
        console.log(data);

        // Exemplo de uso:
        document.getElementById("name").value = data.razao_social || '';
        document.getElementById("fantasy").value = data.nome_fantasia || '';
        document.getElementById("cep").value = data.cep || '';
        document.getElementById("complement").value = data.complemento || '';
        document.getElementById("number").value = cleanNumber(data.numero);
        ;
        // Converte data de abertura para formato BR
        if (data.data_inicio_atividade) {
            data.data_inicio_atividade_br = formatDateBr(data.data_inicio_atividade);
            document.getElementById("birth").value = data.data_inicio_atividade_br || '';
        }
        if (data.cep) {
            searchCep(document.getElementById("cep"));
            if (document.getElementById("avenue").value === "") {
                document.getElementById("avenue").value = data.logradouro || '';
            }
            if (document.getElementById("neighborhood").value === "") {
            document.getElementById("neighborhood").value = data.bairro || '';
        }
        }
    } catch (e) {
        console.error("Erro ao consultar CNPJ:", e);
    }
}
async function callGetCnpj(cnpj) {
    const data = await getCnpj(cnpj);
    if (!data)
        return;

    // Atualiza os campos de forma independente
    document.querySelector("#razaoSocial").value = data.razao_social || "";
    document.querySelector("#nomeFantasia").value = data.nome_fantasia || "";
    document.querySelector("#cep").value = data.cep || "";
    document.querySelector("#endereco").value = data.logradouro || "";
    document.querySelector("#bairro").value = data.bairro || "";
    document.querySelector("#cidade").value = data.municipio || "";
    document.querySelector("#estado").value = data.uf || "";
}