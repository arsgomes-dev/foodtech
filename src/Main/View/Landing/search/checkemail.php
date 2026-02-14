<?php

header('Content-Type: application/json');

// 1. Verifica Método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
    exit;
}

// 2. Recebe e Limpa o Input
$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');

// 3. Validação de Sintaxe (Formato texto@texto.com)
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'invalid_format', 'message' => 'Formato inválido']); 
    exit;
}

// -----------------------------------------------------------
// NOVO: Validação de Domínio (DNS/MX)
// -----------------------------------------------------------
// Pega tudo que vem depois do @
$domain = substr(strrchr($email, "@"), 1);

// checkdnsrr: Verifica se existe registro de troca de e-mail (MX) no DNS
if (!checkdnsrr($domain, 'MX')) {
    echo json_encode([
        'status' => 'invalid_dns', 
        'message' => "O domínio @$domain não parece válido."
    ]);
    exit;
}
// -----------------------------------------------------------

// 4. Validação no Banco de Dados (Sua Lógica Original)
use Microfw\Src\Main\Controller\Api\Controller\GetEmail;

try {
    $emailSearch = new GetEmail;
    
    // Verifica se existe
    if ($emailSearch->getEmailRegistered($email)) {
        echo json_encode(['status' => 'exists']);
    } else {
        echo json_encode(['status' => 'available']);
    }
    
} catch (Exception $e) {
    // Caso sua classe GetEmail dê erro de conexão, não quebra o JSON
    echo json_encode(['status' => 'error', 'message' => 'Erro interno']);
}