<?php

use Microfw\Src\Main\Common\Entity\Admin\McConfig;

$config = new McConfig();
header('Content-Type: text/html; charset=utf-8');

$rateLimitFile = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileAdmin() . '/history_analyze_gemini/temp/gemini_rate_limit.json';
$maxCallsPerMinute = 15; // ajuste conforme sua cota
$currentTime = time();

// carrega histórico
$history = [];
if (file_exists($rateLimitFile)) {
    $history = json_decode(file_get_contents($rateLimitFile), true) ?: [];
}

// remove chamadas antigas (mais de 60s)
$history = array_filter($history, fn($t) => $currentTime - $t < 60);

// verifica limite
if (count($history) >= $maxCallsPerMinute) {
    echo '<div class="alert alert-warning">Limite de chamadas por minuto atingido. Aguarde alguns segundos.</div>';
    exit;
}

// adiciona chamada atual
$history[] = $currentTime;
file_put_contents($rateLimitFile, json_encode(array_values($history)));

$videoId = $_POST['videoId'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if (!$videoId || !$title) {
    echo '<div class="alert alert-warning small">Dados insuficientes para análise.</div>';
    exit;
}

$promptText = "Você é um assistente de SEO para YouTube. 
Dado o vídeo:
Título: {$title}
Descrição: {$description}

Gere:
1. Palavras-chave otimizadas
2. Sugestão de título atrativo
3. Sugestão de descrição SEO
Responda em formato claro, separado por tópicos e utilize '---' entre seções.";

$models = ['gemini-2.5-flash', 'gemini-2.0-flash'];
$maxRetries = 3;
$retryDelay = 3;

$postData = [
    "contents" => [
        [
            "parts" => [
                ["text" => trim(preg_replace('/\s+/', ' ', $promptText))]
            ]
        ]
    ]
];

$response = null;
$httpcode = null;

// Executa fallback entre modelos
foreach ($models as $model) {
    $url = "https://generativelanguage.googleapis.com/v1beta/models/$model:generateContent";

    for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "x-goog-api-key: " . env('GOOGLE_GEMINI_API_KEY'),
                "Content-Type: application/json"
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData)
        ]);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($httpcode == 503 || $httpcode == 429) {
            sleep($retryDelay);
            continue;
        }
        break;
    }

    if ($httpcode == 200 && !$err)
        break;
}

$data = json_decode($response, true);

if (isset($data['error'])) {
    echo '<div class="alert alert-danger text-center">
            <strong>Erro Gemini:</strong> ' . htmlspecialchars($data['error']['message']) . '<br>
            <small>Código: ' . htmlspecialchars($data['error']['code']) . '</small>
          </div>';
    exit;
}

$output = $data['candidates'][0]['content']['parts'][0]['text'] ?? $data['candidates'][0]['content']['parts'][0]['data']['text'] ?? null;

if (!$output) {
    echo '<div class="alert alert-warning small">Sem conteúdo retornado.</div>';
    exit;
}

// --- Extrair seções ---
$sectionsRaw = explode('---', $output);
$sections = [
    'Título' => '',
    'Descrição' => '',
    'Palavras-chave' => '',
    'Hashtags' => ''
];

foreach ($sectionsRaw as $section) {
    $section = trim($section);
    if (!$section)
        continue;

    if (preg_match('/^###\s*(.+)$/m', $section, $matches)) {
        $titleSection = trim($matches[1]);
        $content = preg_replace('/^###\s*.+$/m', '', $section);
    } else {
        $titleSection = '';
        $content = $section;
    }

    // Remover ``` da descrição
    $content = str_replace('```', '', $content);

    if (stripos($titleSection, 'título') !== false) {
        $sections['Título'] = $content;
    } elseif (stripos($titleSection, 'descrição') !== false) {
        $sections['Descrição'] = $content;
    } elseif (stripos($titleSection, 'palavras') !== false) {
        $sections['Palavras-chave'] = $content;
    } elseif (stripos($titleSection, 'hashtag') !== false) {
        $sections['Hashtags'] = $content;
    }
}

// --- HTML ---
$html = '<div class="container-fluid py-3" style="overflow-y:auto;">';
$html .= "<div class='mb-3'>
            <h5 class='fw-bold text-primary'>
                <i class='bi bi-play-btn'></i> " . htmlspecialchars($title) . "
            </h5>
            <span class='badge bg-secondary'>Modelo usado: " . htmlspecialchars($model) . "</span>
          </div>";

function renderCard($titleSection, $content) {
    $icon = 'bi bi-list-check';
    $color = 'bg-light';
    if (stripos($titleSection, 'palavras') !== false)
        $icon = 'bi bi-key';
    if (stripos($titleSection, 'título') !== false)
        $icon = 'bi bi-type';
    if (stripos($titleSection, 'descrição') !== false)
        $icon = 'bi bi-file-text';

    // Preparar texto para copiar
    $copyText = $content;

    if (stripos($titleSection, 'palavras') !== false) {
        $lines = explode("\n", trim($content));
        $keywords = [];
        foreach ($lines as $line) {
            $line = trim($line);
            // remover títulos/descritivos
            if (preg_match('/\*.*?\*\*$/', $line))
                continue;
            // remover o '*' do início
            $line = preg_replace('/^\*\s*/', '', $line);
            if ($line !== '')
                $keywords[] = $line;
        }
        $copyText = implode(', ', $keywords);
    }

    $html = "<div class='card mb-3 border-0 shadow-sm $color rounded-4'>";
    $html .= "<div class='card-header bg-white border-0 d-flex justify-content-between align-items-center rounded-top-4'>
                <div class='d-flex align-items-center'>
                    <i class='$icon text-primary me-2 fs-5'></i>
                    <strong class='text-dark fs-6'>" . htmlspecialchars($titleSection) . "</strong>
                </div>
                <button class='btn btn-outline-secondary btn-sm copy-btn' data-copy='" . htmlspecialchars($copyText) . "'>
                    <i class='bi bi-clipboard'></i> Copiar
                </button>
              </div>";

    $html .= "<div class='card-body pt-0 bg-white'><hr>";

    $lines = explode("\n", trim($content));
    $inList = false;
    foreach ($lines as $line) {
        $line = trim($line);
        if (str_starts_with($line, '*')) {
            if (!$inList) {
                $html .= '<ul class="list-unstyled ps-3">';
                $inList = true;
            }
            $lineText = preg_replace('/^\*\s*/', '', $line);
            $lineText = preg_replace('/#(\w+)/', '<span class="badge rounded-pill bg-primary-subtle text-primary fw-bold me-1">#$1</span>', htmlspecialchars($lineText));
            $lineText = preg_replace('/\*\*(.*?)\*\*/', '<span class="badge rounded-pill bg-success-subtle text-success fw-bold me-1">$1</span>', $lineText);
            $html .= '<li class="mb-1"><i class="bi bi-check2-circle text-success me-1"></i>' . $lineText . '</li>';
        } else {
            if ($inList) {
                $html .= '</ul>';
                $inList = false;
            }
            if ($line)
                $html .= '<p class="mb-2 text-secondary">' . nl2br(htmlspecialchars($line)) . '</p>';
        }
    }
    if ($inList)
        $html .= '</ul>';
    $html .= '</div></div>';

    return $html;
}

// Renderizar Título, Descrição e Palavras-chave
foreach (['Título', 'Descrição', 'Palavras-chave'] as $key) {
    if (!empty($sections[$key])) {
        $html .= renderCard($key, $sections[$key]);
    }
}

// Div separada só para Hashtags
if (!empty($sections['Hashtags'])) {
    $html .= "<div class='mt-3 p-3 bg-light rounded-4 border shadow-sm'>
                <h6 class='fw-bold text-primary mb-2'><i class='bi bi-hash'></i> Hashtags</h6>
                <p class='mb-0 text-secondary'>" . nl2br(htmlspecialchars($sections['Hashtags'])) . "</p>
              </div>";
}

// Script copiar texto
$html .= <<<JS
<script>
document.addEventListener('click', function(e){
    const btn = e.target.closest('.copy-btn');
    if(!btn) return;

    const txt = btn.dataset.copy || '';

    if(navigator.clipboard) {
        navigator.clipboard.writeText(txt).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-clipboard-check text-success"></i> Copiado!';
            setTimeout(() => btn.innerHTML = original, 1500);
        }).catch(() => alert('Erro ao copiar para a área de transferência.'));
    } else {
        // fallback para navegadores antigos
        const textarea = document.createElement('textarea');
        textarea.value = txt;
        textarea.style.position = 'fixed';
        textarea.style.top = '-9999px';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-clipboard-check text-success"></i> Copiado!';
            setTimeout(() => btn.innerHTML = original, 1500);
        } catch(e) {
            alert('Erro ao copiar para a área de transferência.');
        }
        document.body.removeChild(textarea);
    }
});
</script>
JS;

echo $html;
