<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Gemini;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

class GeminiYouTubeTitleAnalyzer {

    private $powerWords = [
        'como', 'guia', 'tutorial', 'segredo', 'melhor', 'incrivel',
        'rapido', 'facil', 'gratis', '2024', '2025', 'erro', 'pare',
        'agora', 'surpreendente', 'verdade', 'finalmente'
    ];

    // ... (Mantenha a função analyze() igual à versão anterior) ...
    public function analyze($title) {
        $score = 0;
        $feedback = [];
        $title = trim($title);
        $length = mb_strlen($title);

        // Lógica simplificada para brevidade (use a completa do passo anterior)
        if ($length >= 40 && $length <= 65) {
            $score += 30;
            $feedback[] = "✅ Comprimento perfeito.";
        } else {
            $score += 10;
            $feedback[] = "⚠️ Atenção ao comprimento.";
        }

        if (preg_match('/[0-9]/', $title)) {
            $score += 15;
            $feedback[] = "✅ Contém números.";
        }
        if (strpos($title, '?') !== false) {
            $score += 10;
            $feedback[] = "✅ É uma pergunta.";
        }

        // Normalização rápida para o exemplo
        $score = min(100, max(10, $score));

        return [
            'title' => $title,
            'score' => $score,
            'color' => ($score >= 70) ? 'success' : (($score >= 50) ? 'warning' : 'danger'),
            'feedback' => $feedback
        ];
    }

    /**
     * Usa o Gemini 1.5 Flash para sugerir 3 títulos virais
     */
    public function suggestTitlesWithGemini($currentTitle) {
        $translate = new Translate();
        // 1. Verifica se pode usar antes de gastar
        $checkPlan = new CheckPlan;
        $check = $checkPlan->checkQuota();
        if (!$check['allowed']) {
            return ['status' => false, 'message' => $check['message']];
        }

        $geminiPrompt = new GeminiPromptAI();
        $prompt = $geminiPrompt->suggestTitlesPrompt(['title' => $currentTitle]);

        $gemini = new GeminiService;
        $response = $gemini->suggestTitlesWithGemini($prompt);

        $data = json_decode($response['response'], true);
        if ($data['status']) {
            // Tratamento da resposta para extrair o texto limpo
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $rawText = $data['candidates'][0]['content']['parts'][0]['text'];
                // Separa as linhas e remove vazias
                $suggestions = array_filter(explode("\n", $rawText));
                return [
                    "status" => true,
                    "suggestions" => $suggestions
                ];
            } else {
                return [
                    "status" => false,
                    "menssage" => $translate->translate("Não foi possível gerar sugestões no momento.", $_SESSION['client_lang'])
                ];
            }
        } else {
            return [
                "status" => false,
                "menssage" => $data['message']
            ];
        }
    }
}
