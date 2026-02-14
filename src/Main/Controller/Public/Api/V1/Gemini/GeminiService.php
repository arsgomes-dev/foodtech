<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Gemini;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;
use Microfw\Src\Main\Common\Helpers\Public\Security\SecurityHelper;
use Microfw\Src\Main\Controller\Public\Api\V1\Gemini\GeminiExCurl;

class GeminiService {

    private $systemApiKey;
    private $models;
    private $quotaLimit;

    public function __construct() {
        $config = new McClientConfig;
        // $this->systemApiKey = getenv('SYSTEM_GEMINI_API_KEY');
        $this->systemApiKey = env('GOOGLE_GEMINI_API_KEY');
        $this->models = ['gemini-2.5-flash', 'gemini-2.0-flash'];
          //$this->models = ['Gemini 2.5 Pro'];
    }

    private function resolveApiKey() {
        $config = new McClientConfig;
        // ... (Mesma lógica da resposta anterior) ...
        // Se for Premium, retorna a systemApiKey
        // Se for Free, retorna a chave descriptografada do usuário
        if ($_SESSION['client_premium'] == $config->getApi_key_client_premium_system()) {
            return $this->systemApiKey;
        } else if ($_SESSION['client_premium'] == $config->getApi_key_client_premium_byok()) {
            return SecurityHelper::decryptKey($_SESSION['client_token_ai']);
        } else if ($_SESSION['client_premium'] == $config->getApi_key_client_free()) {
            $this->quotaLimit = 5;
            return $this->systemApiKey;
        }
    }

    private function checkRateLimit() {
        $translate = new Translate();
        $config = new McClientConfig;
        $rateLimitFile = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient() . "/client/" . $_SESSION['client_gcid'] . '/cache/gemini_rate_limit.json';

        $dir = dirname($rateLimitFile);
        if (!is_dir($dir))
            mkdir($dir, 0755, true);

        $currentTime = time();
        $history = [];

        if (file_exists($rateLimitFile)) {
            $content = file_get_contents($rateLimitFile);
            $history = $content ? json_decode($content, true) : [];
            if (!is_array($history))
                $history = [];
        }

        // Filtra últimos 60 segundos
        $history = array_filter($history, fn($t) => $currentTime - $t < 60);

        if (count($history) >= $this->quotaLimit) {
            return [
                "status" => false,
                "message" => $translate->translate("Limite de chamadas por minuto atingido. Aguarde alguns segundos.", $_SESSION['client_lang'])
            ];
        }

        $history[] = $currentTime;
        file_put_contents($rateLimitFile, json_encode(array_values($history)));
    }

    public function generateScript($prompt, $generationConfig) {
        $translate = new Translate();
        $checkPlan = new CheckPlan;
        try {
            $config = new McClientConfig();
            // 1. Verifica se pode usar antes de gastar

            $check = $checkPlan->checkQuota();
            if (!$check['allowed']) {
                return ['status' => false, 'message' => $check['message']];
            }

            $apiKey = $this->resolveApiKey();

            if ($_SESSION['client_premium'] === $config->getApi_key_client_free()) {
                $checkLimit = $this->checkRateLimit();
                if (!$checkLimit['status']) {
                    return ["status" => false, "message" => $checkLimit['message']];
                }
            }


            $models = ['gemini-2.5-flash', 'gemini-2.0-flash'];
            $maxRetries = 3;
            $retryDelay = 3;
            $postData = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => trim(preg_replace('/\s+/', ' ', $prompt))]
                        ]
                    ]
                ],
                'generationConfig' => $generationConfig, // Injeta a config correta
                'safetySettings' => [
                    [
                        'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                        'threshold' => 'BLOCK_ONLY_HIGH'
                    ]
                ]
            ];
// Executa fallback entre modelos
            foreach ($models as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    // Executa a requisição
                    $response = GeminiExCurl::exCurl($url, $postData, $apiKey);
                    if ($response['httpcode'] == 503 || $response['httpcode'] == 429) {
                        sleep($retryDelay);
                        continue;
                    }
                    break;
                }
                if ($response['httpcode'] == 200 && !$response['err'])
                    break;
            }
            // Verifica se houve erro HTTP no cURL antes de prosseguir
            // (Assumindo que executarCurl retorna o corpo da resposta)
            // 2. Contabiliza o uso

            $data = json_decode($response['response'], true);

            if ((int) $_SESSION['client_premium'] === (int) $config->getApi_key_client_premium_system()) {
                if (isset($data['usageMetadata']['totalTokenCount'])) {
                    $tokensGastos = $data['usageMetadata']['totalTokenCount'];
                    $checkPlan->updateUsage($tokensGastos);
                }
            }
            return ["status" => true, "response" => $response['response']];
        } catch (Throwable $e) {
            // detecta erro de quota
            if (str_contains($e->getMessage(), 'Quota exceeded')) {

                echo json_encode([
                    'success' => false,
                    'type' => 'quota',
                    'retry_after' => 60,
                    'message' => $translate->translate("Você atingiu 100% do seu limite mensal de IA. Faça um upgrade ou aguarde a renovação.", $_SESSION['client_lang'])
                ]);
                exit;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno ao gerar conteúdo'
            ]);
        }
    }

    public function generateAnalize($prompt) {
        $translate = new Translate();
        try {
            $config = new McClientConfig();
            // 1. Verifica se pode usar antes de gastar
            $checkPlan = new CheckPlan;
            $check = $checkPlan->checkQuota();
            if (!$check['allowed']) {
                return ['status' => false, 'message' => $check['message']];
            }

            $apiKey = $this->resolveApiKey();

            if ($_SESSION['client_premium'] === $config->getApi_key_client_free()) {
                $checkLimit = $this->checkRateLimit();
                if (!$checkLimit['status']) {
                    return ["status" => false, "message" => $checkLimit['message']];
                }
            }


            $models = $this->models;
            $maxRetries = 3;
            $retryDelay = 3;

            $postData = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => trim(preg_replace('/\s+/', ' ', $prompt))]
                        ]
                    ]
                ]
            ];
// Executa fallback entre modelos
            foreach ($models as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    // Executa a requisição
                    $response = GeminiExCurl::exCurl($url, $postData, $apiKey);
                    if ($response['httpcode'] == 503 || $response['httpcode'] == 429) {
                        sleep($retryDelay);
                        continue;
                    }
                    break;
                }
                if ($response['httpcode'] == 200 && !$response['err'])
                    break;
            }
            // Verifica se houve erro HTTP no cURL antes de prosseguir
            // (Assumindo que executarCurl retorna o corpo da resposta)
            // 2. Contabiliza o uso
            $data = json_decode($response['response'], true);

            if ((int) $_SESSION['client_premium'] === (int) $config->getApi_key_client_premium_system()) {
                if (isset($data['usageMetadata']['totalTokenCount'])) {
                    $tokensGastos = $data['usageMetadata']['totalTokenCount'];
                    $checkPlan->updateUsage($tokensGastos);
                }
            }
            return ["status" => true, "response" => $response['response']];
        } catch (Throwable $e) {
            // detecta erro de quota
            if (str_contains($e->getMessage(), 'Quota exceeded')) {

                echo json_encode([
                    'success' => false,
                    'type' => 'quota',
                    'retry_after' => 60,
                    'message' => $translate->translate("Você atingiu 100% do seu limite mensal de IA. Faça um upgrade ou aguarde a renovação.", $_SESSION['client_lang'])
                ]);
                exit;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno ao gerar conteúdo'
            ]);
        }
    }

    public function suggestTitlesWithGemini($prompt) {
        $translate = new Translate();
        try {
            $config = new McClientConfig();

            // ---------------------------------------------------------
            // 1. VERIFICAÇÃO DE COTA E PLANO
            // ---------------------------------------------------------
            $checkPlan = new CheckPlan;
            $check = $checkPlan->checkQuota();
            if (!$check['allowed']) {
                return ['status' => false, 'message' => $check['message']];
            }

            $apiKey = $this->resolveApiKey();

            // ---------------------------------------------------------
            // 2. RATE LIMITING (CLIENTES FREE)
            // ---------------------------------------------------------
            if ($_SESSION['client_premium'] === $config->getApi_key_client_free()) {
                $checkLimit = $this->checkRateLimit();
                if (!$checkLimit['status']) {
                    return ["status" => false, "message" => $checkLimit['message']];
                }
            }

            // ---------------------------------------------------------
            // 3. PREPARAÇÃO E ENVIO DA REQUISIÇÃO
            // ---------------------------------------------------------
            $models = $this->models;
            $maxRetries = 3;
            $retryDelay = 2;

            $postData = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => trim(preg_replace('/\s+/', ' ', $prompt))]
                        ]
                    ]
                ]
            ];

            $response = null;

            // Loop de Fallback entre modelos e Retries
            foreach ($models as $model) {
                $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    $response = GeminiExCurl::exCurl($url, $postData, $apiKey);

                    // Retry se erro temporário (503, 429)
                    if ($response['httpcode'] == 503 || $response['httpcode'] == 429) {
                        sleep($retryDelay);
                        continue;
                    }
                    break;
                }

                if ($response['httpcode'] == 200 && empty($response['err'])) {
                    break;
                }
            }

            // ---------------------------------------------------------
            // 4. PROCESSAMENTO DA RESPOSTA E COBRANÇA
            // ---------------------------------------------------------
            $data = json_decode($response['response'], true);

            // Contabiliza tokens (Premium)
            if ((int) $_SESSION['client_premium'] === (int) $config->getApi_key_client_premium_system()) {
                if (isset($data['usageMetadata']['totalTokenCount'])) {
                    $tokensGastos = $data['usageMetadata']['totalTokenCount'];
                    $checkPlan->updateUsage($tokensGastos);
                }
            }

            // Verifica erros da API
            if (isset($data['message'])) {
                return [
                    "status" => false,
                    "message" => htmlspecialchars($data['message']['message']) // Corrigido typo 'menssage' para 'message'
                ];
            }

            // ---------------------------------------------------------
            // 5. PARSE INTELIGENTE (JSON / LINHAS / REGEX)
            // ---------------------------------------------------------
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $data['candidates'][0]['content']['parts'][0]['text'];

                // ETAPA A: Tenta limpar Markdown e ler como JSON nativo
                $cleanText = preg_replace('/```json\s*|```/i', '', $rawText);
                $cleanText = trim($cleanText);
                $suggestions = json_decode($cleanText, true);

                // ETAPA B: Se falhou o JSON, tenta quebrar por linhas
                if (json_last_error() !== JSON_ERROR_NONE || !is_array($suggestions)) {
                    // Quebra por nova linha (\n ou \r\n)
                    $lines = preg_split('/\r\n|\r|\n/', $cleanText);
                    $suggestions = [];

                    foreach ($lines as $line) {
                        // Remove numeração de lista "1.", "*", "- "
                        $cleanLine = trim(preg_replace('/^[\d\.\-\*]+\s*/', '', $line));
                        if (!empty($cleanLine)) {
                            $suggestions[] = $cleanLine;
                        }
                    }
                }

                // ETAPA C (A CORREÇÃO CRUCIAL): Verifica se veio tudo grudado
                // Ex: "Titulo Um!Titulo Dois?Titulo Três"
                // Se só temos 1 item no array e ele é muito longo, a IA falhou na quebra
                if (count($suggestions) == 1 && mb_strlen($suggestions[0]) > 60) {
                    $longString = $suggestions[0];

                    // Regex: Corta quando encontra (. ou ! or ?) seguido de Espaço(opcional) e Letra Maiúscula/Número
                    // Isso separa "Revelada!Operadora" em ["Revelada!", "Operadora"]
                    $splitParts = preg_split('/(?<=[.?!])\s*(?=[A-Z0-9])/', $longString);

                    // Remove vazios e reindexa
                    $suggestions = array_values(array_filter($splitParts));
                }

                // Sucesso Final
                return [
                    "status" => true,
                    "suggestions" => $suggestions
                ];
            } else {
                return [
                    "status" => false,
                    "message" => $translate->translate("Não foi possível gerar sugestões no momento.", $_SESSION['client_lang'])
                ];
            }
        } catch (Throwable $e) {
            // detecta erro de quota
            if (str_contains($e->getMessage(), 'Quota exceeded')) {

                echo json_encode([
                    'success' => false,
                    'type' => 'quota',
                    'retry_after' => 60,
                    'message' => $translate->translate("Você atingiu 100% do seu limite mensal de IA. Faça um upgrade ou aguarde a renovação.", $_SESSION['client_lang'])
                ]);
                exit;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno ao gerar conteúdo'
            ]);
        }
    }

    public function suggestWithGemini($prompt, $type = 'titles') {
        $translate = new Translate();
        try {
            $config = new McClientConfig();
            $checkPlan = new CheckPlan;

            // ----------------------------
            // 1. COTA / PLANO
            // ----------------------------
            $check = $checkPlan->checkQuota();
            if (!$check['allowed']) {
                return ['status' => false, 'message' => $check['message']];
            }

            $apiKey = $this->resolveApiKey();
            if (empty($apiKey)) {
                return ['status' => false, 'message' => $translate->translate("API Key não configurada.", $_SESSION['client_lang'])];
            }

            // ----------------------------
            // 2. RATE LIMIT FREE
            // ----------------------------
            if ($_SESSION['client_premium'] === $config->getApi_key_client_free()) {
                $this->checkRateLimit($config, $translate);
            }

            // ----------------------------
            // 3. MONTA PAYLOAD
            // ----------------------------
            $postData = [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => trim(preg_replace('/\s+/', ' ', $prompt))]
                        ]
                    ]
                ],
                "generationConfig" => [
                    "responseMimeType" => "application/json"
                ]
            ];

            $response = null;
            $maxRetries = 3;

            // ----------------------------
            // 4. LOOP DE MODELOS
            // ----------------------------
            foreach ($this->models as $model) {

                $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

                for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
                    $response = GeminiExCurl::exCurl($url, $postData, $apiKey);

                    // Se erro temporário → retry
                    if ($response['httpcode'] == 503 || $response['httpcode'] == 429) {
                        sleep(2);
                        continue;
                    }
                    break;
                }

                if ($response['httpcode'] == 200 && empty($response['err'])) {
                    break;
                }
            }

            // ----------------------------
            // 5. VALIDAÇÃO DA RESPOSTA HTTP
            // ----------------------------
            if (empty($response['response'])) {
                return [
                    "status" => false,
                    "message" => $translate->translate("A API do Gemini retornou uma resposta vazia.", $_SESSION['client_lang'])
                ];
            }

            $data = json_decode($response['response'], true);

            if (!is_array($data)) {
                return [
                    "status" => false,
                    "message" => $translate->translate("Resposta inválida do Gemini (JSON inválido).", $_SESSION['client_lang'])
                ];
            }

            // ----------------------------
            // 6. ERRO EXPLÍCITO DA API
            // ----------------------------
            if (isset($data['message'])) {
                return [
                    "status" => false,
                    "message" => htmlspecialchars($data['message']['message'])
                ];
            }

            // ----------------------------
            // 7. SEGURANÇA MÁXIMA – EXTRAI TEXTO
            // ----------------------------
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
            // Contabiliza tokens (Premium)
            if ((int) $_SESSION['client_premium'] === (int) $config->getApi_key_client_premium_system()) {
                if (isset($data['usageMetadata']['totalTokenCount'])) {
                    $tokensGastos = $data['usageMetadata']['totalTokenCount'];
                    $checkPlan->updateUsage($tokensGastos);
                }
            }

            if (!$text) {
                return [
                    "status" => false,
                    "message" => $translate->translate("A IA não retornou texto válido.", $_SESSION['client_lang'])
                ];
            }

            // Limpa markdown
            $cleanText = preg_replace('/```json\s*|```/i', '', $text);
            $cleanText = trim($cleanText);

            // ----------------------------
            // 8. PARSE POR TIPO
            // ----------------------------
            // === TITLES ===
            if ($type === 'titles') {
                $result = json_decode($cleanText, true);

                // fallback linha a linha
                if (!is_array($result)) {
                    $lines = preg_split('/\r\n|\r|\n/', $cleanText);
                    $result = [];

                    foreach ($lines as $line) {
                        $cleanLine = trim(preg_replace('/^[\d\.\-\*]+\s*/', '', $line));
                        if (!empty($cleanLine)) {
                            $result[] = $cleanLine;
                        }
                    }
                }

                // fallback regex
                if (count($result) == 1 && mb_strlen($result[0]) > 80) {
                    $parts = preg_split('/(?<=[.!?])\s+(?=[A-Z0-9])/', $result[0]);
                    $result = array_values(array_filter($parts));
                }

                return ["status" => true, "suggestions" => $result];
            }

            // === DESCRIPTION ===
            if ($type === 'description') {
                $decoded = json_decode($cleanText, true);

                if (is_array($decoded)) {
                    return ["status" => true, "suggestions" => [$decoded]];
                }

                return [
                    "status" => true,
                    "suggestions" => [
                        ["description" => $cleanText, "hashtags" => []]
                    ]
                ];
            }

            // === GENERIC (TEXTO PURO) ===
            if ($type === 'generic') {
                return ["status" => true, "suggestions" => [$cleanText]];
            }
        } catch (Throwable $e) {
            // detecta erro de quota
            if (str_contains($e->getMessage(), 'Quota exceeded')) {

                echo json_encode([
                    'success' => false,
                    'type' => 'quota',
                    'retry_after' => 60,
                    'message' => $translate->translate("Você atingiu 100% do seu limite mensal de IA. Faça um upgrade ou aguarde a renovação.", $_SESSION['client_lang'])
                ]);
                exit;
            }

            echo json_encode([
                'success' => false,
                'message' => 'Erro interno ao gerar conteúdo'
            ]);
        }

        return ["status" => false, "message" => $translate->translate("Erro desconhecido.", $_SESSION['client_lang'])];
    }

    /**
     * Analisa Imagem + Texto (Multimodal)
     * @param string $prompt Instruções
     * @param string $base64Image Dados da imagem
     * @param string $mimeType Tipo (image/jpeg ou image/png)
     */
    /**
     * Versão Atualizada e Blindada para Análise de Imagem
     */

    /**
     * Versão com Fallback de Modelos para evitar erro "Model Not Found"
     */
    public function analyzeImageWithGemini($prompt, $base64Image, $mimeType = 'image/jpeg') {
        $translate = new Translate();
        // 1. LISTA DE MODELOS DE VISÃO (Tenta na ordem)
        // Se o 'flash' simples falhar, tenta o 'latest', depois o '001', depois o 'pro'

        try {
            $config = new McClientConfig();

            // ---------------------------------------------------------
            // 1. VERIFICAÇÃO DE COTA E PLANO
            // ---------------------------------------------------------
            $checkPlan = new CheckPlan;
            $check = $checkPlan->checkQuota();
            if (!$check['allowed']) {
                return ['status' => false, 'message' => $check['message']];
            }

            $apiKey = $this->resolveApiKey();

            // ---------------------------------------------------------
            // 2. RATE LIMITING (CLIENTES FREE)
            // ---------------------------------------------------------
            if ($_SESSION['client_premium'] === $config->getApi_key_client_free()) {
                $checkLimit = $this->checkRateLimit();
                if (!$checkLimit['status']) {
                    return ["status" => false, "message" => $checkLimit['message']];
                }
            }

            // ---------------------------------------------------------
            // 3. PREPARAÇÃO E ENVIO DA REQUISIÇÃO
            // ---------------------------------------------------------
        } catch (Exception $e) {
            return ['status' => false, 'message' => $translate->translate("Erro de config: ", $_SESSION['client_lang']) . $e->getMessage()];
        }
        $models = $this->models;

        // 2. Payload Padrão
        $postData = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt],
                        [
                            "inlineData" => [
                                "mimeType" => $mimeType,
                                "data" => $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            "safetySettings" => [
                ["category" => "HARM_CATEGORY_HARASSMENT", "threshold" => "BLOCK_ONLY_HIGH"],
                ["category" => "HARM_CATEGORY_HATE_SPEECH", "threshold" => "BLOCK_ONLY_HIGH"],
                ["category" => "HARM_CATEGORY_SEXUALLY_EXPLICIT", "threshold" => "BLOCK_ONLY_HIGH"],
                ["category" => "HARM_CATEGORY_DANGEROUS_CONTENT", "threshold" => "BLOCK_ONLY_HIGH"]
            ],
            "generationConfig" => [
                "responseMimeType" => "application/json",
                "temperature" => 0.4
            ]
        ];

        $response = null;
        $lastError = "";

        // 3. LOOP DE TENTATIVAS (O Segredo para não falhar)
        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

            // Tenta executar
            $response = GeminiExCurl::exCurl($url, $postData, $apiKey);
            $data = json_decode($response['response'], true);

            // Se deu erro de "Model not found" (404) ou "Method not supported" (400), tenta o próximo
            if (isset($data['message'])) {
                $lastError = $data['message']['message'];
                continue; // Pula para o próximo modelo do array
            }

            // Se chegou aqui, funcionou!
            break;
        }

        // 4. Processamento da Resposta (Se algum modelo funcionou)
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $rawText = $data['candidates'][0]['content']['parts'][0]['text'];

            $cleanText = preg_replace('/```json\s*|```/i', '', $rawText);
            $cleanText = trim($cleanText);

            $json = json_decode($cleanText, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if ((int) $_SESSION['client_premium'] === (int) $config->getApi_key_client_premium_system()) {
                    if (isset($data['usageMetadata']['totalTokenCount'])) {
                        $tokensGastos = $data['usageMetadata']['totalTokenCount'];
                        $checkPlan->updateUsage($tokensGastos);
                    }
                }
                return ["status" => true, "data" => $json];
            } else {
                return ["status" => false, "message" => $translate->translate("IA respondeu com formato inválido.", $_SESSION['client_lang'])];
            }
        }

        // Se saiu do loop e nada funcionou
        return [
            "status" => false,
            "message" => $translate->translate("Nenhum modelo de IA disponível aceitou a requisição. Último erro: ", $_SESSION['client_lang']) . $lastError
        ];
    }
}

?>