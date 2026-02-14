<?php

namespace Microfw\Src\Main\Controller\Public\Api\V1\Gemini;

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

class GeminiPromptAI {

   /* public function getGenerationPrompt($data) {
        return <<<PROMPT
# ROLE & CONTEXTO
Você é um roteirista profissional especialista no nicho de **{$data['niche']}**.
Sua missão é criar um roteiro de vídeo altamente engajador para o público: **{$data['target_audience']}**.
Sugira um title para o roteiro
{$data['title_base']}
{$data['text_base']}
{$data['keywords']}

# DIRETRIZES DA MARCA E TOM
{$data['tone']}
{$data['style']}
{$data['voice_rules']}
{$data['brand_guidelines']}
{$data['language_level']}

# OBJETIVOS DO VÍDEO
{$data['video_goal']}
{$data['unique_value']}
{$data['retention_focus']}
{$data['seo_focus']}
{$data['video_length']}

# 4. REGRAS ESTRUTURAIS (OBRIGATÓRIO SEGUIR)
# REGRAS ESTRUTURAIS RÍGIDAS
Estas definições moldam o esqueleto do roteiro:
{$data['hook_type']}
{$data['structure_rules']}
{$data['cta_type']}
{$data['priority_points']}
{$data['forbidden_words']}

# INSTRUÇÕES DE PRODUÇÃO
{$data['editing_style']}
{$data['reference_channels']}

# FORMATO DE SAÍDA (IMPORTANTÍSSIMO)
Entregue o roteiro **exclusivamente em HTML limpo**, sem Markdown, sem JSON, sem blocos ```.

Formato final:
- Use apenas <h2>, <h3>, <p>, <strong>, <em>, <ul>, <li>, <br>.
- Nada de código, JSON, objetos, arrays ou formatação Markdown.
- Não coloque aspas em volta do conteúdo.
- Não coloque barras invertidas, \n, \t, ou escapes.
- Não envolva o conteúdo em { }.
- Não devolva texto com crases (```).

Estrutura obrigatória seguindo a: {$data['structure_rules']}:

REGRAS DE FORMATAÇÃO:
1. NÃO use Markdown (nada de **, ##, ```).
2. NÃO inclua tags <html>, <head> ou <body>. Apenas o conteúdo interno.
3. NÃO use blocos de código ou JSON.
4. Use tags semânticas para estrutura visual:
   - <h2> para Títulos de Seções (ex: "INTRODUÇÃO", "CENA 1").
   - <h3> para Subseções ou indicações visuais (ex: "Visual:", "Áudio:").
   - <p> para o texto falado e descrições.
   - <strong> para ênfases na fala.
   - <ul>/<li> para listas.
   - <blockquote> para notas de produção ou dicas.

TEMPLATE DE SAÍDA ESPERADO A SEGUIR DE FORMA OBRIGATORIA:
<h2>TÍTULO DO VÍDEO</h2>
<p><em>Tempo estimado: X min</em></p>

<h3>00:00 - HOOK</h3>
<p><strong>(Visual):</strong> Descrição da cena...</p>
<p><strong>(Áudio):</strong> Fala do narrador...</p>
<hr>
<h3>01:00 - CONTEÚDO</h3>
...

O HTML deve estar pronto para ser colado diretamente dentro do TinyMCE.

{$data['additional_instructions']}
PROMPT;
    }
*/
    
    public function getGenerationPrompt($data) {
    return <<<PROMPT
Você é um roteirista profissional especialista no nicho de {$data['niche']}.
Sua missão é criar um roteiro altamente engajador para o público: {$data['target_audience']}.
Evite repetir cenas, palavras ou ganchos de roteiros anteriores.

NUNCA sugira título. Use apenas o título fornecido pelo usuário quando existir.

INFORMAÇÕES DO USUÁRIO:
{$data['title_base']}
{$data['text_base']}
{$data['keywords']}

DIRETRIZES DE MARCA, TOM E ESTILO:
{$data['tone']}
{$data['style']}
{$data['voice_rules']}
{$data['brand_guidelines']}
{$data['language_level']}

OBJETIVOS DO VÍDEO:
{$data['video_goal']}
{$data['unique_value']}
{$data['retention_focus']}
{$data['seo_focus']}
{$data['video_length']}

REGRAS ESTRUTURAIS:
{$data['hook_type']}
{$data['structure_rules']}
{$data['cta_type']}
{$data['priority_points']}
{$data['forbidden_words']}

INSTRUÇÕES DE PRODUÇÃO:
{$data['editing_style']}
{$data['reference_channels']}

# INSTRUÇÕES DE VARIAÇÃO
- Gere roteiros distintos a cada execução.
- Varie o título, a ordem das cenas, ganchos e expressões.
- Explore abordagens diferentes para cada cena.
- Crie textos frescos e envolventes, mantendo coerência.
- Não repita trechos de roteiros anteriores.

=====================================================================
⚠️ **REGRAS OBRIGATÓRIAS DA RESPOSTA**
Você DEVE retornar **somente o JSON final**, sem explicações, sem texto fora do JSON, sem comentários, sem markdown.

❌ Não use HTML.  
❌ Não use <tags>.  
❌ Não escreva nada antes ou depois do JSON.  
❌ Não coloque ```json ou ``` em nenhum lugar.  
❌ Não crie conteúdo fora da estrutura pedida.  
❌ Não adicione campos extras.  
❌ Não sugira título.

=====================================================================
📌 **FORMATO FINAL OBRIGATÓRIO DO JSON (sempre igual):**

{
  "title video": "TÍTULO FINAL DO VÍDEO",
  "estimated_time": "X min",
  "sections": [
    {
      "timestamp": "00:00",
      "nome_cena": "cena 1",
      "visual": "descrição visual da cena",
      "conteudo": "conteúdo falado da cena"
    },
    {
      "timestamp": "01:00",
      "nome_cena": "cena 2",
      "visual": "descrição visual da cena",
      "conteudo": "conteúdo falado da cena"
    },
    ...
  ]
}

=====================================================================
🧠 **INSTRUÇÕES DA GERAÇÃO DO CONTEÚDO**
- Preencher todas as cenas seguindo {$data['structure_rules']}.
- Criar timestamps progressivos (00:00, 01:00, 02:00...).
- "visual" = descrição da cena que aparece no vídeo.
- "conteudo" = fala do narrador ou apresentador.
- "estimated_time" deve refletir o tempo aproximado do vídeo.
- Nunca sair da estrutura do JSON.

=====================================================================
RESPONDA SOMENTE COM O JSON FINAL.
PROMPT;
}
    public function getAnalysisPrompt($data, $scriptToAnalyze) {
// $scriptToAnalyze é o texto que o usuário quer melhorar

        return <<<PROMPT
    # TAREFA DE ANÁLISE
    Você é um crítico de conteúdo e estrategista de algoritmo.
    Analise o roteiro fornecido abaixo com base nos seguintes parâmetros configurados:

    # PARÂMETROS DE REFERÊNCIA (O ALVO)
    - Tipo de Análise: {$data['analysis_type']}
    - Público Alvo: {$data['target_audience']}
    - Tom Esperado: {$data['tone']}
    - Foco de Retenção: {$data['retention_focus']}
    - Regras de Estrutura: {$data['structure_rules']}
    - Palavras Proibidas: {$data['forbidden_words']}

    # O ROTEIRO PARA ANALISAR
    """
    {$scriptToAnalyze}
    """

    # INSTRUÇÕES DE SAÍDA (JSON)
    Não responda com texto solto. Responda APENAS um objeto JSON com a seguinte estrutura:
    {
        "score": (inteiro de 0 a 100),
        "strengths": [(lista de 3 pontos fortes baseados no 'unique_value': {$data['unique_value']})],
        "weaknesses": [(lista de 3 pontos fracos que ferem o 'retention_focus')],
        "tone_check": (booleano: true se o tom bate com '{$data['tone']}', false se não),
        "forbidden_words_found": [(lista das palavras proibidas encontradas, se houver)],
        "suggestions": {
            "hook_rewrite": "Reescreva apenas o gancho para ficar mais agressivo no estilo '{$data['hook_type']}'",
            "cta_optimization": "Sugestão de melhoria para o CTA baseado no tipo '{$data['cta_type']}'",
            "seo_improvements": "Lista de palavras para trocar visando '{$data['seo_focus']}'"
        },
        "overall_feedback": "Resumo executivo de como melhorar este vídeo."
    }
    PROMPT;
    }

    public function getSuggestTitlesPrompt($data) {

        $prompt = "Atue como um especialista em YouTube SEO. ";
        $prompt .= "Analise o título original: '" . $data['title'] . "'. ";

        // Lógica ajustada: Contexto em vez de Obrigação
        if (!empty($data['keywords'])) {
            $prompt .= "CONTEXTO DO VÍDEO (TAGS): '" . $data['keywords'] . "'. ";
            $prompt .= "Use essas palavras como base para entender o assunto principal. ";
            $prompt .= "Você pode usar uma ou mais dessas palavras se fizer sentido, mas priorize a naturalidade e o alto CTR (taxa de clique) em vez de forçar todas elas. ";
        }

        $prompt .= "Gere EXATAMENTE 5 sugestões de títulos virais, altamente clicáveis, curtos (máximo 100 caracteres) e em Português. ";
        $prompt .= "IMPORTANTE: Retorne APENAS um Array JSON válido de strings. Exemplo: [\"Titulo 1\", \"Titulo 2\", \"Titulo 3\", \"Titulo 4\", \"Titulo 5\"]. Não use Markdown.";

        return $prompt;
    }

    /**
     * Gera uma descrição otimizada para SEO baseada no Título e Keywords
     */
    public function generateDescriptionPrompt($data) {
        $title = $data['title'];
        $keywords = $data['keywords'];

        $prompt = "Atue como um Especialista em YouTube SEO e Copywriting. \n";
        $prompt .= "Escreva uma DESCRIÇÃO DE VÍDEO altamente persuasiva e otimizada para busca.\n\n";

        $prompt .= "DADOS DO VÍDEO:\n";
        $prompt .= "TÍTULO: '{$title}'\n";
        $prompt .= "PALAVRAS-CHAVE: '{$keywords}'\n\n";

        $prompt .= "ESTRUTURA OBRIGATÓRIA DA DESCRIÇÃO:\n";
        $prompt .= "1. **Gancho (SEO Puro):** As primeiras 2 linhas DEVEM conter a palavra-chave principal e explicar o benefício do vídeo. Isso é para aparecer na busca do Google.\n";
        $prompt .= "2. **Corpo (Valor):** Um parágrafo curto ou bullet points resumindo o que será ensinado/mostrado.\n";
        $prompt .= "3. **CTA (Chamada para Ação):** Um convite curto para se inscrever ou comentar.\n";
        $prompt .= "4. **Hashtags:** Gere exatamente 3 hashtags relevantes no final.\n\n";

        $prompt .= "REGRAS DE TOM:\n";
        $prompt .= "- Use linguagem natural, empolgante e em Português.\n";
        $prompt .= "- Não use introduções como 'Aqui está a descrição'. Vá direto ao texto.\n";

        $prompt .= "FORMATO DE RESPOSTA (JSON OBRIGATÓRIO):\n";
        $prompt .= "Retorne APENAS um JSON com esta estrutura:\n";
        $prompt .= '{"description": "O texto completo da descrição aqui", "hashtags": ["#tag1", "#tag2", "#tag3"]}';

        return $prompt;
    }

    public function getThumbnailAnalysisPrompt($videoTitle) {
        return "Atue como um Especialista em Design de Thumbnails para YouTube e Algoritmo de Visão Computacional.
        
        CONTEXTO DO VÍDEO (TÍTULO): '$videoTitle'
        
        TAREFA: Analise a imagem enviada (Thumbnail) seguindo estes critérios rígidos:

        1. **SafeSearch:** A imagem contém algo impróprio, nojento ou proibido?
        2. **Legibilidade:** O texto na imagem é fácil de ler em um celular? Tem muito texto?
        3. **Fator Humano:** Existe um rosto? Ele expressa emoção? Há contato visual?
        4. **Qualidade:** A imagem é brilhante e contrastada ou escura e apagada?
        5. **Relevância:** A imagem combina com o Título fornecido?

        RETORNO (JSON OBRIGATÓRIO):
        {
            \"score\": (0 a 100),
            \"safe_search\": (true/false - true se for segura),
            \"text_readability\": \"Bom/Médio/Ruim\",
            \"faces_detected\": (sim/não),
            \"pros\": [\"Ponto positivo 1\", \"Ponto positivo 2\"],
            \"cons\": [\"Ponto negativo 1\", \"Ponto negativo 2\"],
            \"verdict\": \"Resumo final em 1 frase curta.\"
        }";
    }
    public function getThumbnailGenerationPrompt($title, $keywords) {
        return "A YouTube thumbnail image representing the video title: '$title'. " . 
               "Keywords context: $keywords. " .
               "The image should be expressive, have a clear focal point, hyper-realistic or illustrated style depending on the theme, " .
               "and visually striking colors to attract clicks.";
    }
}
