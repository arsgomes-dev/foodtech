<?php

// --- INTEGRAÇÃO BACKEND ---
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;

// ==========================================
// FUNÇÃO DE VERIFICAÇÃO DE COOKIES (GDPR/LGPD)
// ==========================================
// Usamos "function_exists" para evitar erro caso o arquivo seja incluído 2x
if (!function_exists('hasCookieConsent')) {

    function hasCookieConsent($category) {
        $cookieName = 'youtubeos_privacy';

        // 1. Se o cookie não existe, retorna falso
        if (!isset($_COOKIE[$cookieName])) {
            return false;
        }

        // 2. Decodifica o JSON
        // O JS usa encodeURIComponent, então o PHP precisa lidar com isso.
        // O stripslashes às vezes é necessário dependendo da configuração do servidor (magic quotes)
        $cookieValue = $_COOKIE[$cookieName];
        $decodedValue = urldecode($cookieValue);
        $consentData = json_decode($decodedValue, true);

        // Debug (se precisar ver o que está chegando, descomente a linha abaixo e veja no código-fonte)
        // echo "";
        // 3. Verifica a categoria
        if (is_array($consentData) && isset($consentData[$category]) && $consentData[$category] === true) {
            return true;
        }
        return false;
    }

}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>YouTubeOS - O Sistema Operacional do Criador</title>
        <?php if (\hasCookieConsent('analytics')): ?>
            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-JTHPV6SJ43"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() {
                    dataLayer.push(arguments);
                }
                gtag('js', new Date());

                gtag('config', 'G-JTHPV6SJ43');
            </script>
        <?php endif; ?>

        <?php if (\hasCookieConsent('marketing')): ?>
            <script>
                !function (f, b, e, v, n, t, s)
                {
                    if (f.fbq)
                        return;
                    n = f.fbq = function () {
                        n.callMethod ?
                                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                    };
                    if (!f._fbq)
                        f._fbq = n;
                    n.push = n;
                    n.loaded = !0;
                    n.version = '2.0';
                    n.queue = [];
                    t = b.createElement(e);
                    t.async = !0;
                    t.src = v;
                    s = b.getElementsByTagName(e)[0];
                    s.parentNode.insertBefore(t, s)
                }(window, document, 'script',
                        'https://connect.facebook.net/en_US/fbevents.js');

                // fbq('init', 'SEU_PIXEL_ID'); 
                // fbq('track', 'PageView');
            </script>
        <?php endif; ?>



        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

        <link rel="stylesheet" href="/assets/public/css/style.css">
    </head>
    <body>
        <input type="hidden" id="x_y" value="<?php echo (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12); ?>">

        <?php
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/Landing/header.php");
        ?>

        <header class="hero-section text-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10" data-aos="zoom-in" data-aos-duration="1000">
                        <span class="badge-tech mb-4">
                            <i class="fas fa-bolt me-2"></i> Seu assistente com IA
                        </span>
                        <h1 class="hero-title mb-4">
                            O Sistema Operacional para <br>
                            <span class="text-gradient">Dominar o YouTube</span>
                        </h1>
                        <p class="lead text-muted mb-5 mx-auto" style="max-width: 700px;">
                            Automatize seus roteiros, valide thumbnails e gerencie seu calendário com o poder da Inteligência Artificial.
                        </p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="#planos" class="btn btn-cta">Ver Planos <i class="fas fa-arrow-right ms-2"></i></a>
                            <a href="#recursos" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold">Saiba Mais</a>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="col-lg-10">
                        <div class="mockup-container">
                            <img src="https://placehold.co/1000x550/13111c/A020F0?text=Dashboard+YouTubeOS" alt="Dashboard YouTubeOS" class="img-fluid">
                            <div class="glow-effect"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="sobre" class="py-5">
            <div class="container py-5">
                <div class="row align-items-center gy-5">
                    <div class="col-lg-6" data-aos="fade-right">
                        <h2 class="fw-bold text-white mb-4 display-6">
                            Chega de criar conteúdo<br>na base do "achismo".
                        </h2>
                        <p class="text-secondary mb-4 lead" style="font-size: 1.1rem;">
                            O YouTubeOS nasceu de uma inconformidade: criadores talentosos gastam mais tempo planilhando e tentando entender o algoritmo do que realmente criando.
                        </p>
                        <p class="text-secondary mb-4">
                            Somos uma plataforma <strong>Data-Driven</strong> (orientada a dados). Desenvolvemos um ecossistema que une a inteligência avançada do <strong>Google Gemini</strong> com as métricas reais do YouTube API para garantir que cada vídeo seu tenha o máximo potencial de performance.
                        </p>

                        <a href="#recursos" class="btn btn-outline-light rounded-pill px-4 py-2 fw-bold mt-2">
                            Conheça a Tecnologia
                        </a>
                    </div>

                    <div class="col-lg-5 offset-lg-1" data-aos="fade-left">
                        <div class="vision-card">
                            <div class="vision-header mb-4">
                                <h5 class="text-white fw-bold mb-0">Por que o YouTubeOS?</h5>
                                <span class="badge bg-dark border border-secondary text-secondary">Tech</span>
                            </div>

                            <div class="pillar-item">
                                <div class="pillar-icon bg-blue-gradient">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div>
                                    <h6 class="text-white fw-bold mb-1">Dados, não Sorte</h6>
                                    <p class="text-secondary small mb-0">Decisões baseadas em métricas reais de visualização e retenção.</p>
                                </div>
                            </div>

                            <div class="pillar-item">
                                <div class="pillar-icon bg-purple-gradient">
                                    <i class="fas fa-robot"></i>
                                </div>
                                <div>
                                    <h6 class="text-white fw-bold mb-1">IA Nativa</h6>
                                    <p class="text-secondary small mb-0">Integração profunda com LLMs para criar roteiros que engajam.</p>
                                </div>
                            </div>

                            <div class="pillar-item">
                                <div class="pillar-icon bg-green-gradient">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h6 class="text-white fw-bold mb-1">Privacidade Total</h6>
                                    <p class="text-secondary small mb-0">Seus dados e ideias de roteiros são criptografados e seguros.</p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="recursos" class="features-section py-5">
            <div class="container py-5">
                <div class="row mb-5 text-center" data-aos="fade-up">
                    <div class="col-lg-8 mx-auto">
                        <span class="badge-tech mb-3"><i class="fas fa-microchip me-2"></i>Tecnologia YouTubeOS</span>
                        <h2 class="fw-bold text-white display-5">O Poder de uma Agência <br>no seu Bolso</h2>
                        <p class="text-secondary lead">Ferramentas conectadas para dominar o algoritmo, do planejamento à viralização.</p>
                    </div>
                </div>

                <div class="row g-4">

                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="feature-box h-100 highlight-box">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="icon-glow bg-danger-gradient">
                                    <i class="fas fa-fire"></i>
                                </div>
                                <span class="badge bg-dark border border-secondary text-secondary">Hot</span>
                            </div>
                            <h3 class="text-white fw-bold mb-3">Busca de Vídeos Virais</h3>
                            <p class="text-secondary mb-4">
                                Não adivinhe, use dados. Pesquise por palavras-chaves e veja métricas reais (views, likes) para descobrir o que está em alta no seu nicho antes de gravar.
                            </p>

                            <div class="feature-mockup viral-mockup">
                                <div class="viral-card">
                                    <div class="skeleton-thumb"></div>
                                    <div class="skeleton-lines">
                                        <span class="line w-75"></span>
                                        <span class="line w-50"></span>
                                    </div>
                                    <div class="skeleton-stats text-success"><i class="fas fa-arrow-up"></i> 1.2M Views</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="feature-box h-100">
                            <div class="icon-glow bg-purple-gradient mb-4">
                                <i class="fas fa-columns"></i>
                            </div>
                            <h3 class="text-white fw-bold">Workflow Visual</h3>
                            <p class="text-secondary">
                                Organize sua produção com clareza total. Mova seus vídeos entre colunas (Ideia, Roteiro, Edição, Postado) e nunca mais perca um arquivo.
                            </p>

                            <div class="kanban-preview mt-4">
                                <div class="d-flex gap-2 justify-content-center">
                                    <div class="kanban-col">
                                        <span class="dot bg-secondary"></span> Ideias
                                    </div>
                                    <div class="kanban-col active">
                                        <span class="dot bg-warning"></span> Gravando
                                    </div>
                                    <div class="kanban-col">
                                        <span class="dot bg-success"></span> Pronto
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature-box h-100 telegram-box">
                            <div class="icon-glow bg-telegram-gradient mb-3">
                                <i class="fab fa-telegram-plane"></i>
                            </div>
                            <h4 class="text-white fw-bold">Notificações Telegram</h4>
                            <p class="text-secondary small mb-3">
                                Seu assistente pessoal. Receba alertas de horários de postagem e status direto no celular.
                            </p>

                            <div class="notification-bubble">
                                <i class="fab fa-telegram-plane"></i>
                                <div>
                                    <strong>YouTubeOS Bot</strong>
                                    <span>Postar vídeo em 30min! ⏰</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-box h-100">
                            <div class="icon-glow bg-blue-gradient mb-3">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h4 class="text-white fw-bold">Roteiros com IA</h4>
                            <p class="text-secondary small">
                                Criação completa, análise de retenção e sugestões de melhoria. Sua IA atua como um roteirista profissional focado em engajamento.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-box h-100">
                            <div class="icon-glow bg-green-gradient mb-3">
                                <i class="fas fa-search-dollar"></i>
                            </div>
                            <h4 class="text-white fw-bold">SEO Automático</h4>
                            <p class="text-secondary small">
                                Geração instantânea de Títulos magnéticos, Palavras-chaves de cauda longa e Descrições otimizadas para o algoritmo.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-box h-100">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-glow bg-orange-gradient icon-small">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h4 class="text-white fw-bold mb-0">Validador de Capas</h4>
                            </div>
                            <p class="text-secondary small">
                                IA que analisa cores, rostos e textos da sua miniatura para prever o CTR (Taxa de Clique) antes de você postar.
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-box h-100 d-flex flex-column justify-content-center">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-glow bg-pink-gradient icon-small">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <h4 class="text-white fw-bold mb-0">Calendário Editorial</h4>
                            </div>
                            <p class="text-secondary small mb-3">
                                Visualize sua consistência. Arraste e solte seus uploads, planeje datas comemorativas e organize seu mês.
                            </p>

                            <div class="calendar-strip">
                                <div class="cal-dot"></div>
                                <div class="cal-dot active"></div>
                                <div class="cal-dot"></div>
                                <div class="cal-dot"></div>
                                <div class="cal-dot active"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <?php
// Inicializa e busca os planos do banco
        $planEntity = new AccessPlan;
        $plans = $planEntity->getQuery(
                customWhere: [['column' => 'status', 'value' => 1]],
                order: 'price ASC'
        );
        ?>

        <section id="planos" class="pricing-section py-5">
            <div class="container">

                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold text-white">Escolha seu Plano</h2>
                    <p class="text-secondary">Potencialize seu canal com o plano ideal</p>
                </div>

                <div class="row justify-content-center mb-5" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-auto">
                        <div class="plan-toggle">
                            <button id="btn-monthly" class="btn active" onclick="togglePeriod('monthly')">Mensal</button>
                            <button id="btn-yearly" class="btn" onclick="togglePeriod('yearly')">
                                Anual
                                <?php
                                if (env('PAG_CYCLE_ANUAL_X_PRICE') > 0) {
                                    echo '<span class="yearly-badge">';
                                    if (env('PAG_CYCLE_ANUAL_X_PRICE') < 12) {
                                        if (env('PAG_CYCLE_ANUAL_X_PRICE') === 1) {
                                            echo "(1 mês grátis)";
                                        } else {
                                            echo "(" . (12 - env('PAG_CYCLE_ANUAL_X_PRICE')) . " meses grátis)";
                                        }
                                    }
                                    echo '</span>';
                                }
                                ?>           
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row g-4 justify-content-center">

                    <?php if (!empty($plans)): ?>
                        <?php foreach ($plans as $plan): ?>

                            <?php
                            // Ignora planos sem preço ou zerados (opcional)
                            if ($plan->getPrice() <= 0)
                                continue;


                            // Verifica se é recomendado
                            $isRecommended = ($plan->getRecommended() === 1);

                            // Processa a descrição (separa por ;)
                            $features = array_filter(array_map('trim', explode(';', $plan->getDescription())));
                            ?>

                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="plan-card <?= $isRecommended ? 'recommended' : '' ?>"
                                     data-monthly="<?= $plan->getPrice() ?>"
                                     data-plan-id="<?= $plan->getGcid() ?>">

                                    <?php if ($isRecommended): ?>
                                        <div class="plan-ribbon" style="text-align: center;"><i class="fas fa-star me-1"></i> Recomendado</div>
                                    <?php endif; ?>

                                    <div class="text-center mb-4 mt-2">
                                        <h3 class="plan-title"><?= htmlspecialchars($plan->getTitle()) ?></h3>
                                        <p class="plan-obs"><?= htmlspecialchars($plan->getObservation()) ?></p>
                                    </div>

                                    <div class="price-container text-center mb-2">
                                        <div class="price-wrapper">
                                            <span class="currency">R$</span>
                                            <span class="price-value"></span> <span class="period-label">/mês</span>
                                        </div>
                                    </div>

                                    <div class="economy-container text-center mb-4">
                                        <span class="economy-label d-none">
                                            Economize <span class="economy-value"></span>
                                        </span>
                                    </div>

                                    <button class="btn btn-subscribe w-100 mb-4"
                                            data-plan-id="<?= $plan->getGcid() ?>"
                                            data-title="<?= htmlspecialchars($plan->getTitle()) ?>"
                                            onclick="selectPlan(this)">
                                        Assinar Agora
                                    </button>

                                    <div class="features-wrapper">
                                        <ul class="plan-features list-unstyled">
                                            <?php foreach ($features as $index => $desc): ?>
                                                <li class="feature-item" style="<?= $index >= 4 ? 'display:none' : 'flex' ?>">
                                                    <i class="fas fa-check-circle feature-icon"></i>
                                                    <span><?= htmlspecialchars($desc) ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <?php if (count($features) > 4): ?>
                                            <div class="text-center mt-3">
                                                <button class="btn-show-more" onclick="toggleFeatures(this)">
                                                    Ver tudo <i class="fas fa-chevron-down ms-1"></i>
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>
        </section>
        <div id="cookie-banner" class="cookie-banner d-none">
            <div class="d-flex align-items-start gap-3">
                <div class="cookie-icon-box">
                    <i class="fas fa-cookie-bite"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-white mb-1">Sua privacidade importa</h6>
                    <p class="text-secondary small mb-3">
                        Usamos cookies para melhorar sua experiência, analisar tráfego e personalizar conteúdo. 
                        Veja nossa <a href="/privacidade" class="text-primary text-decoration-underline">Política de Privacidade</a>.
                    </p>
                    <div class="d-flex gap-2 flex-wrap">
                        <button id="btn-accept-all" class="btn btn-sm btn-primary-gradient fw-bold px-3">
                            Aceitar Tudo
                        </button>
                        <button id="btn-reject-all" class="btn btn-sm btn-outline-light px-3">
                            Rejeitar
                        </button>
                        <button id="btn-manage" class="btn btn-sm btn-link text-muted text-decoration-none px-0 ms-2">
                            Preferências
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cookieModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content cookie-modal-content">
                    <div class="modal-header border-bottom border-secondary border-opacity-25">
                        <h5 class="modal-title fw-bold text-white">
                            <i class="fas fa-sliders-h me-2 text-primary"></i>Preferências de Cookies
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-secondary small mb-4">
                            Gerencie como usamos os cookies. Os "Essenciais" não podem ser desativados pois o site não funciona sem eles.
                        </p>

                        <div class="cookie-option">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-white">Essenciais (Obrigatório)</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked disabled>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Login, segurança e salvamento destas preferências.</p>
                        </div>

                        <div class="cookie-option">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-white">Analíticos</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="check-analytics">
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Nos ajuda a melhorar o site analisando como você o usa (Google Analytics).</p>
                        </div>

                        <div class="cookie-option">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-white">Marketing</span>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="check-marketing">
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Usados para mostrar anúncios relevantes para você.</p>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary border-opacity-25">
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="btn-save-preferences" class="btn btn-primary-gradient btn-sm fw-bold">Salvar Preferências</button>
                    </div>
                </div>
            </div>
        </div>

        <button id="btn-reopen-cookies" class="cookie-reopen-btn d-none" title="Preferências de Cookies">
            <i class="fas fa-cookie-bite"></i>
        </button>
        <?php
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/Landing/footer.php");
        ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script src="/assets/public/js/app.js"></script>
        <script src="/assets/public/js/cookies.js"></script>
    </body>
</html>