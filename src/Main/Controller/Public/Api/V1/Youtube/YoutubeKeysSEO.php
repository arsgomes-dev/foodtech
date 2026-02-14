<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Youtube;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;

$translate = new Translate();

use Microfw\Src\Main\Controller\Public\Api\V1\Youtube\YouTubeApi;


class YoutubeKeysSEO {

    public $retorno;

    public function __construct() {
        $this->retorno = [
            "demand" => [
                "low" => [
                    "text" => "Poucos vídeos abordam essa palavra-chave. Isso indica que o interesse do público é limitado ou muito específico. Pode ser útil para nichos, mas normalmente não atrai grandes volumes de tráfego orgânico.",
                    "icon" => "fas fa-arrow-down text-danger"
                ],
                "medium" => [
                    "text" => "Existe uma quantidade razoável de vídeos relacionados ao tema. Isso demonstra que há busca moderada e que o assunto tem potencial de atração, sem ser saturado. Geralmente é uma boa oportunidade para conteúdos de autoridade.",
                    "icon" => "fas fa-arrows-alt-h text-warning"
                ],
                "high" => [
                    "text" => "Muitos vídeos são publicados sobre essa palavra-chave. Isso indica alto volume de buscas e interesse crescente do público. É excelente para alcançar grandes audiências, porém tende a atrair mais concorrência.",
                    "icon" => "fas fa-arrow-up text-success"
                ]
            ],
            "competition" => [
                "high" => [
                    "text" => "Os vídeos existentes têm uma média de views muito alta, o que significa que grandes criadores dominam o tema. Entrar nesse nicho pode ser difícil e pode exigir conteúdo extremamente otimizado e autoridade elevada.",
                    "icon" => "fas fa-fire text-danger"
                ],
                "medium" => [
                    "text" => "Existe competição, mas ainda há espaço para vídeos de novos criadores. Se o conteúdo for bem estruturado, é possível ranquear com relativa facilidade, especialmente com títulos diferenciados e alta retenção.",
                    "icon" => "fas fa-balance-scale text-warning"
                ],
                "low" => [
                    "text" => "A média de views dos vídeos é baixa, o que indica que poucos conteúdos fortes disputam essa palavra-chave. É uma excelente oportunidade para ranquear rápido, ganhar visibilidade e atrair tráfego qualificado.",
                    "icon" => "fas fa-leaf text-success"
                ]
            ],
            "relevance" => [
                "low" => [
                    "text" => "Os vídeos encontrados têm pouco engajamento (poucos likes e comentários). Isso sugere que, apesar do tema existir, não desperta discussões ou envolvimento. Geralmente não gera forte conexão com o público.",
                    "icon" => "fas fa-circle text-danger"
                ],
                "medium" => [
                    "text" => "Os vídeos possuem engajamento razoável, mostrando que o público interage moderadamente com o assunto. É um bom sinal de interesse, principalmente se combinado com concorrência baixa.",
                    "icon" => "fas fa-adjust text-warning"
                ],
                "high" => [
                    "text" => "Os conteúdos possuem alto engajamento. Isso significa que a audiência valoriza muito esse tema, comenta, compartilha e responde. Uma palavra-chave com alta relevância tende a gerar melhores taxas de retenção e envolvimento.",
                    "icon" => "fas fa-star text-success"
                ]
            ],
            "score" => [
                "weak" => [
                    "text" => "0–39: Pouca demanda, concorrência muito alta ou relevância baixa demais. Normalmente não vale o esforço de produção, exceto se o assunto for extremamente nichado.",
                    "icon" => "fas fa-thumbs-down text-danger"
                ],
                "average" => [
                    "text" => "40–59: Existe algum potencial, mas não é uma keyword ideal. Pode funcionar em canais em crescimento, especialmente se houver boa diferenciação de conteúdo.",
                    "icon" => "fas fa-hand-paper text-warning"
                ],
                "good" => [
                    "text" => "60–79: O equilíbrio entre demanda, concorrência e relevância é favorável. É uma boa escolha para criar vídeos com bom potencial de alcance orgânico.",
                    "icon" => "fas fa-thumbs-up text-primary"
                ],
                "excellent" => [
                    "text" => "80–100: Alta demanda, baixa concorrência e forte engajamento. São palavras com enorme potencial de viralização e ranqueamento rápido — ideal para estratégias de crescimento e conteúdo de alto impacto.",
                    "icon" => "fas fa-trophy text-success"
                ]
            ]
        ];
    }

// Função para obter descrição e ícone
    function getDescription($type, $level) {
        $retorno = $this->retorno;
        if (isset($retorno[$type][$level])) {
            return $retorno[$type][$level];
        }
        return ["text" => "Nível desconhecido", "icon" => "fas fa-question-circle text-muted"];
    }

    function evaluateKeywordSEO($keyword) {
        $search = new YouTubeApi(env('GOOGLE_YOUTUBE_API_KEY'));
        $translate = new Translate();

        if (empty($keyword)) {
            return [
                'keyword' => '',
                'score' => 0,
                'status' => $translate->translate('Digite uma palavra-chave.', $_SESSION['client_lang'])
            ];
        }

        // 1. Buscar vídeos
        $videoIds = $search->getVideosID($keyword, 30);

        if (empty($videoIds)) {
            return [
                'keyword' => $keyword,
                'videos_found' => 0,
                'score' => 0,
                'status' => $translate->translate('Nenhum vídeo encontrado para essa palavra-chave.', $_SESSION['client_lang'])
            ];
        }


        // 2. Estatísticas
        $stats = $search->getVideosStats($videoIds);

        if (empty($stats))
            return;

        $totalViews = $stats['totalViews'] ?? 0;
        $videoCount = $stats['totalVideos'] ?? 0;
        $engagement = $stats['engagement'] ?? 0;
        $recentCount = $stats['recentCount'] ?? 0;

        $avgViews = $videoCount > 0 ? round($totalViews / $videoCount) : 0;
        $avgEngagement = $videoCount > 0 ? round($engagement / $videoCount) : 0;
        $recentPercent = $videoCount > 0 ? round(($recentCount / $videoCount) * 100) : 0;

        /*
          |--------------------------------------------------------------------------
          | NOVA PONTUAÇÃO SEO PROFISSIONAL
          |--------------------------------------------------------------------------
         */

        $demand_score = 0;
        $competition_score = 0;
        $relevance_score = 0;
        $score = 0;
        $demand_return = [];
        $competition_return = [];
        $relevance_return = [];
        $score_return = [];

  if ($videoCount <= 5) {
        $demand_score = 20;
    } elseif ($videoCount <= 20) {
        $demand_score = 40;
    } elseif ($videoCount <= 100) {
        $demand_score = 70;
    } elseif ($videoCount <= 300) {
        $demand_score = 85;
    } else {
        $demand_score = 100;
    }


    /* ------------------------------
       2) CONCORRÊNCIA (log scale ideal para YouTube)
       ------------------------------ */

    // Faixas reais usadas em keyword tools
    // Valores altos = concorrência alta -> score baixo
    if ($avgViews <= 5000) {
        $competition_score = 90;     // baixa concorrência
    } elseif ($avgViews <= 50000) {
        $competition_score = 70;     // média-baixa
    } elseif ($avgViews <= 200000) {
        $competition_score = 50;     // média
    } elseif ($avgViews <= 1000000) {
        $competition_score = 30;     // média-alta
    } else {
        $competition_score = 10;     // concorrência muito alta
    }


    /* ------------------------------
       3) RELEVÂNCIA (engajamento médio)
       ------------------------------ */

    if ($avgEngagement <= 200) {
        $relevance_score = 20;
    } elseif ($avgEngagement <= 1000) {
        $relevance_score = 50;
    } elseif ($avgEngagement <= 5000) {
        $relevance_score = 75;
    } else {
        $relevance_score = 95;
    }


    /* ------------------------------
       4) SCORE FINAL (ponderado)
       ------------------------------ */

    // pesos profissionais usados por ferramentas como vidIQ/TubeBuddy
    $score_final = (
        ($demand_score * 0.35) +
        ($competition_score * 0.35) +
        ($relevance_score * 0.30)
    );

    $score = round($score_final);

// 5. Retornos automáticos com descrição e ícone
        $demand_return = $this->getDescription('demand', $demand_score <= 30 ? 'low' : ($demand_score <= 70 ? 'medium' : 'high'));
        $competition_return = $this->getDescription('competition', $competition_score <= 30 ? 'high' : ($competition_score <= 70 ? 'medium' : 'low'));
        $relevance_return = $this->getDescription('relevance', $relevance_score <= 30 ? 'low' : ($relevance_score <= 70 ? 'medium' : 'high'));
        $score_return = $this->getDescription('score', $score <= 39 ? 'weak' : ($score <= 59 ? 'average' : ($score <= 79 ? 'good' : 'excellent')));

        return [
            'keyword' => $keyword,
            // dados brutos
            'videos_found' => $videoCount,
            'views_average' => $avgViews,
            'average_engagement' => $avgEngagement,
            // nova pontuação detalhada 🔥
            'demand_score' => min(100, $demand_score),
            'competition_score' => min(100, $competition_score),
            'relevance_score' => min(100, $relevance_score),
            // score final (0–100)
            'score' => min(100, $score),
            //return demanda
            'demand_return' => $demand_return,
            //return competição
            'competition_return' => $competition_return,
            //return relevancia
            'relevance_return' => $relevance_return,
            //return score
            'score_return' => $score_return,
            // status
            'status' => $score >= 60 ? $translate->translate('Boa Palavra-Chave para SEO', $_SESSION['client_lang']) : $translate->translate('Palavra-Chave fraca ou concorrência alta', $_SESSION['client_lang'])
        ];
    }
}

/*      return [
            'keyword' => $keyword,
            // dados brutos
            'videos_found' => $videoCount,
            'views_average' => $avgViews,
            'average_engagement' => $avgEngagement,
            // nova pontuação detalhada 🔥
            'demand_score' => min(100, $demand_score),
            'competition_score' => min(100, $competition_score),
            'relevance_score' => min(100, $relevance_score),
            // score final (0–100)
            'score' => min(100, $score),
            //return demanda
            'demand_return' => $demand_return (retorna o texto e o icon referente a base de pontuação),
            //return competição
            'competition_return' => $competition_return (retorna o texto e o icon referente a base de pontuação),
            //return relevancia
            'relevance_return' => $relevance_return (retorna o texto e o icon referente a base de pontuação),
            //return score
            'score_return' => $score_return (retorna o texto e o icon referente a base de pontuação),
            // status
            'status' => $score >= 60 ? $translate->translate('Boa Palavra-Chave para SEO', $_SESSION['client_lang']) : $translate->translate('Palavra-Chave fraca ou concorrência alta', $_SESSION['client_lang'])
        ];*/