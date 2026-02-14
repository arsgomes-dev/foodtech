function formatDateBr(dateStr) {
    if (!dateStr)
        return "";
    const [year, month, day] = dateStr.split("-");
    return `${day}/${month}/${year}`;
}

async function getCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, ''); // mantém só números
    if (cnpj.length !== 14) {
        console.warn("CNPJ inválido");
        return;
    }

    const cleanNumber = (str) => str ? str.toString().replace(/^0+/, '') : "";

    const setValue = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.value = value || '';
    };

    const formatDateBr = (dateStr) => {
        if (!dateStr.includes('-')) return dateStr;
        const [ano, mes, dia] = dateStr.split("-");
        return `${dia}/${mes}/${ano}`;
    };

    // timeout para evitar travamento se a API não responder
    const fetchWithTimeout = (url, ms) => {
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), ms);
        return fetch(url, { signal: controller.signal })
            .finally(() => clearTimeout(timeout));
    };

    try {
        const res = await fetchWithTimeout(`https://brasilapi.com.br/api/cnpj/v1/${cnpj}`, 7000);
        if (!res.ok) throw new Error("Erro na requisição da API");

        const data = await res.json();
      //  console.log("CNPJ DATA:", data);

        // Campos principais
        setValue("name", data.razao_social);
        setValue("fantasy", data.nome_fantasia);
        setValue("cep", data.cep);
        setValue("complement", data.complemento);
        setValue("number", cleanNumber(data.numero));

        // Data BR
        if (data.data_inicio_atividade) {
            const dataBr = formatDateBr(data.data_inicio_atividade);
            setValue("birth", dataBr);
        }

        // Preenche endereço se cep válido
        if (data.cep) {
            await searchCep(document.getElementById("cep"));
            if (!document.getElementById("avenue").value)
                setValue("avenue", data.logradouro);
            if (!document.getElementById("neighborhood").value)
                setValue("neighborhood", data.bairro);
        }

    } catch (e) {
        if (e.name === 'AbortError') {
            console.error("Tempo limite atingido na consulta do CNPJ.");
        } else {
            console.error("Erro ao consultar CNPJ:", e);
        }
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