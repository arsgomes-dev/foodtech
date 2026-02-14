<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Huggingface;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

// Certifique-se de importar o seu helper corretamente

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Controller\Public\Api\V1\Huggingface\HuggingfaceExCurl;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Http/Config/Api/V1/Huggingface/HuggingfaceConfig.php';

class HuggingfaceService {

    private $hfToken;
    // Modelos ativos confiáveis
    private $models = [
        ["id" => "stabilityai/stable-diffusion-2-1", "field" => "prompt"],
        ["id" => "stabilityai/stable-diffusion-2-1-base", "field" => "prompt"],
        ["id" => "stabilityai/stable-diffusion-xl-refiner-1.0", "field" => "inputs"],
        ["id" => "black-forest-labs/FLUX.1-dev", "field" => "prompt"],
        ["id" => "black-forest-labs/FLUX.1-schnell", "field" => "inputs"]
    ];

    public function __construct() {
        if (!defined('HUGGINGFACE_API_KEY') || empty(HUGGINGFACE_API_KEY)) {
            throw new Exception("Token Hugging Face não configurado.");
        }
        $this->hfToken = HUGGINGFACE_API_KEY;
    }

    public function generateImageWithHuggingFace($prompt) {

        $finalPrompt = "YouTube thumbnail, 16:9, professional, high contrast, vivid colors, cinematic lighting, 8k, detailed, sharp focus, catchy composition. " . $prompt;

        $lastError = "";

        foreach ($this->models as $m) {
            $model = $m["id"];
            $field = $m["field"];
            //"https://router.huggingface.co/hf-inference/models/black-forest-labs/FLUX.1-dev"
            $url = "https://router.huggingface.co/hf-inference/models/" . $model;

            // Ajuste apenas para o FLUX
            if ($model === "black-forest-labs/FLUX.1-dev") {
                $payload = json_encode([
                    "inputs" => $finalPrompt,
                    "options" => ["wait_for_model" => true],
                    "parameters" => [
                        "width" => 1280, // largura padrão YouTube
                        "height" => 720, // altura padrão YouTube
                        "guidance_scale" => 7.5, // opcional, para manter coerência
                        "num_inference_steps" => 25 // opcional, para qualidade
                    ]
                ]);
            } else {
                $payload = json_encode([
                    $field => $finalPrompt,
                    "options" => ["wait_for_model" => true],
                    "parameters" => [
                        "width" => 1280, // largura padrão YouTube
                        "height" => 720, // altura padrão YouTube
                        "guidance_scale" => 7.5, // opcional, para manter coerência
                        "num_inference_steps" => 25 // opcional, para qualidade
                    ]
                ]);
            }
            /* $payload = json_encode([
              "inputs" => $finalPrompt,
              "options" => ["wait_for_model" => true]
              ]); */
            $headers = [
                "Authorization: Bearer {$this->hfToken}",
                "Content-Type: application/json"
                    //   ,"Accept: image/png, image/jpeg"
            ];

            $exCurl = HuggingfaceExCurl::exCurl($url, $payload, $this->hfToken, $headers);

            // Se erro de rede, tenta próximo modelo
            if (!empty($exCurl['curlError'])) {
                $lastError = "Erro cURL ($model): " . $exCurl['err'];
                continue;
            }

            // Detecta JSON de erro
            $isJson = strpos($exCurl['contentType'], 'application/json') !== false;

            if ($isJson) {
                $json = json_decode($exCurl['response'], true);

                if (isset($json['error'])) {
                    $errMsg = $json['error'];

                    // Modelo carregando, tenta próximo
                    if (strpos($errMsg, 'loading') !== false) {
                        $lastError = "Modelo $model carregando...";
                        continue;
                    }

                    $lastError = "Erro API ($model): " . $errMsg;
                    continue;
                }
            }

            // Sucesso real: retorno binário da imagem
            if ($exCurl['httpcode'] == 200 && !$isJson) {

                $mime = "image/jpeg"; // fallback
                if (strpos($exCurl['contentType'], "png") !== false) {
                    $mime = "image/png";
                }

                return [
                    "status" => true,
                    "image_base64" => base64_encode($exCurl['response']),
                    "mime_type" => $mime,
                    "model_used" => $model
                ];
            }

            $lastError = "Erro HTTP " . $exCurl['httpcode'] . " no modelo $model";
        }

        return ["status" => false, "message" => "Falha na geração: " . $lastError];
    }
}

?>
