<?php

session_start();
header('Content-Type: application/json');

Use Microfw\Src\Main\Controller\Landing\Controller\Register;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;

$translate = new Translate();

// Inclua aqui o arquivo onde sua função setRegister está definida
// require_once 'caminho/para/sua/funcao_ou_classe.php';
// ==========================================================
// 1. FUNÇÃO AUXILIAR DE VALIDAÇÃO DE CPF
// ==========================================================
function validaCPF($cpf) {
    // Extrai somente os números
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

// ==========================================================
// 2. RECEBIMENTO E NORMALIZAÇÃO
// ==========================================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método de requisição inválido.']);
    exit;
}

// Recebe e limpa os dados
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$passwd = $_POST['passw'] ?? ''; // Ajuste o name conforme seu input HTML
$passwd_conf = $_POST['passw_conf'] ?? ''; // Ajuste o name conforme seu input HTML
// Normalização: Remove tudo que não for número
$cpf = preg_replace('/\D/', '', $_POST['cpf'] ?? '');
$celular = preg_replace('/\D/', '', $_POST['celular'] ?? '');
$cep = preg_replace('/\D/', '', $_POST['cep'] ?? '');

// Endereço
$logradouro = trim($_POST['logradouro'] ?? '');
$numero = trim($_POST['numero'] ?? '');
$bairro = trim($_POST['bairro'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$uf = trim($_POST['uf'] ?? '');
$complemento = trim($_POST['complemento'] ?? '');

// Dados extras do plano (para salvar na sessão depois)
$plano_escolhido = $_POST['plano'] ?? null;
$ciclo_escolhido = $_POST['ciclo'] ?? null;

$terms = trim($_POST['termos'] ?? 0);

$birth = $_POST['nascimento'] ?? '';

// ==========================================================
// 3. VALIDAÇÕES PRÉVIAS (Fail Fast)
// ==========================================================
// Valida CPF antes de chamar o banco
if (!validaCPF($cpf)) {
    echo json_encode(['status' => 'error', 'message' => 'O CPF informado é inválido.']);
    exit;
}

// Verifica se senhas batem (caso sua função setRegister não faça isso)
if ($passwd !== $passwd_conf) {
    echo json_encode(['status' => 'error', 'message' => 'As senhas não coincidem.']);
    exit;
}

// ==========================================================
// 4. CHAMA A FUNÇÃO DE REGISTRO
// ==========================================================
// Instancie sua classe se necessário, ou chame a função diretamente
// $userController = new UserController();
// $resultadoJson = $userController->setRegister(...);
// Chamada direta conforme solicitado:
$register = new Register;
$resultadoJson = $register->setRegister(
        $nome,
        $cpf,
        $celular,
        $cep,
        $logradouro,
        $complemento,
        $numero,
        $bairro,
        $cidade,
        $uf,
        $email,
        $passwd,
        $passwd_conf,
        $terms,
        $birth
);

// ==========================================================
// 5. TRATAMENTO DO RETORNO E SESSÃO
// ==========================================================
// Decodifica o JSON que veio da sua função para podermos ler o status
$resultado = json_decode($resultadoJson, true);

if ($resultado['status'] === 'registered') {
    // --- SUCESSO: LOGAR O USUÁRIO ---
    $_SESSION['cart'] = [
        'plano_id' => $plano_escolhido,
        'ciclo' => $ciclo_escolhido
    ];

    // Retorna o JSON original da função (registered) para o Frontend redirecionar
    echo $resultadoJson;
} else {
    // --- ERRO (email existe, dns invalido, formato invalido, etc) ---
    // Apenas repassa o erro para o Frontend mostrar o alerta
    echo $resultadoJson;
}
