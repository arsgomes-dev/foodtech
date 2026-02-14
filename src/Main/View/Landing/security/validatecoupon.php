<?php

// Carregue o autoloader ou as classes necessárias
// require_once __DIR__ . '/vendor/autoload.php'; 
// ou include dos arquivos das classes GetCoupon e AccessPlansCoupon

session_start();

use Microfw\Src\Main\Controller\Landing\Controller\GetCoupon;

header('Content-Type: application/json');

// 1. Verifica Método
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
    exit;
}

// 2. Sanitiza o Código recebido
$code = isset($_POST['code']) ? strtoupper(trim($_POST['code'])) : '';

if (empty($code)) {
    echo json_encode(['status' => 'error', 'message' => 'Digite um código.']);
    exit;
}

try {
    // 3. Instancia sua classe e faz a busca
    $couponSearch = new GetCoupon();
    $result = $couponSearch->searchCoupon($code);

    // 4. Formata a resposta para o JavaScript
    // O JS espera 'status' => 'success' ou 'error'
    if ($result['status'] === true) {
        echo json_encode([
            'status' => 'success',
            'discount_percent' => (float) $result['discount_percent'],
            'message' => $result['message']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }
} catch (Exception $e) {
    // Log do erro para debug se necessário
    // error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Erro ao validar cupom2.']);
}