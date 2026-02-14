<?php

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

header('Content-Type: text/html; charset=utf-8');

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;

$config = new McClientConfig();
$translate = new Translate();

use Microfw\Src\Main\Controller\Public\Api\V1\Gemini\GeminiService;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$planService = new CheckPlan;
$check = $planService->checkQuota();

if (!$check['allowed']) {
    echo '<div class="alert alert-warning small">' . $check['message'] . '</div>';
    exit;
}

$aiAnalize = new GeminiService();

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

$response_json = $aiAnalize->generateAnalize($promptText);
if (!$response_json['status']) {
    echo '<div class="alert alert-danger text-center">
            ' . htmlspecialchars($response_json['message']) . '
          </div>';
    exit;
}
if ($response_json['status']) {
    $data = json_decode($response_json['response'], true);
}
if (isset($data['error'])) {
    echo '<div class="alert alert-danger text-center">
            <strong>Erro Gemini:</strong> ' . htmlspecialchars($data['error']['message']) . '<br>
            <small>Código: ' . htmlspecialchars($data['error']['code']) . '</small>
          </div>';
    exit;
}

$output = $data['candidates'][0]['content']['parts'][0]['text'] ?? $data['candidates'][0]['content']['parts'][0]['data']['text'] ?? null;

if (!$output) {
    echo '<div class="alert alert-warning small">' . $translate->translate('Sem conteúdo retornado.', $_SESSION['client_lang']) . '</div>';
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
