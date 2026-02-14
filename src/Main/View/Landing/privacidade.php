<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Política de Privacidade - YouTubeOS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
       <link rel="stylesheet" href="/assets/public/css/style.css">

    <style>
        .legal-header {
            padding: 120px 0 60px;
            background: radial-gradient(circle at 50% -20%, #2a0a40 0%, var(--bg-body) 70%);
            border-bottom: 1px solid var(--border-color);
        }

        .legal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
        }

        .legal-content h2 {
            color: #fff;
            font-size: 1.5rem;
            margin-top: 40px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .legal-content h2:first-child { margin-top: 0; }

        .legal-content p, .legal-content li {
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 0.95rem;
        }

        .legal-content strong { color: #fff; }

        /* Highlight box para avisos do Google */
        .google-notice {
            background: rgba(66, 133, 244, 0.1);
            border-left: 4px solid #4285f4;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* Sidebar Navigation */
        .legal-nav { position: sticky; top: 100px; }
        
        .nav-pills .nav-link {
            color: var(--text-muted);
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.2s;
            font-size: 0.9rem;
            text-align: left;
            padding: 10px 15px;
        }

        .nav-pills .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        .nav-pills .nav-link.active {
            background-color: rgba(160, 32, 240, 0.1);
            color: var(--primary-color);
            font-weight: 600;
            border-left: 3px solid var(--primary-color);
            border-radius: 0 8px 8px 0;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand text-white" href="/">
                <i class="fab fa-youtube me-2" style="color: var(--primary-color);"></i>YouTube<span style="color: var(--primary-color);">OS</span>
            </a>
            <div class="ms-auto">
                <a href="/" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="fas fa-arrow-left me-2"></i> Voltar
                </a>
            </div>
        </div>
    </nav>

    <header class="legal-header text-center">
        <div class="container">
            <h1 class="fw-bold text-white mb-2">Política de Privacidade</h1>
            <p class="text-secondary">Sua confiança é nossa prioridade. Última atualização: 24 de Janeiro de 2026</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="legal-nav">
                        <h6 class="text-uppercase text-secondary fw-bold mb-3 small ps-3">Navegação</h6>
                        <div class="nav flex-column nav-pills">
                            <a class="nav-link active" href="#coleta">1. Dados Coletados</a>
                            <a class="nav-link" href="#youtube-data">2. Dados do YouTube (Google)</a>
                            <a class="nav-link" href="#uso">3. Como Usamos</a>
                            <a class="nav-link" href="#ia">4. Processamento via IA</a>
                            <a class="nav-link" href="#seguranca">5. Segurança</a>
                            <a class="nav-link" href="#direitos">6. Seus Direitos</a>
                            <a class="nav-link" href="#contato">7. Contato</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="legal-box legal-content">
                        
                        <div id="coleta" class="mb-5">
                            <h2>1. Dados que Coletamos</h2>
                            <p>Ao utilizar o YouTubeOS, coletamos as seguintes categorias de informações:</p>
                            <ul>
                                <li><strong>Informações de Cadastro:</strong> Nome, endereço de e-mail e foto de perfil (fornecidos via Login do Google).</li>
                                <li><strong>Dados de Uso:</strong> Roteiros criados, histórico de pesquisas de palavras-chave, e configurações de calendário.</li>
                                <li><strong>Dados de Pagamento:</strong> Processados de forma segura por nosso provedor (Stripe/PayPal), não armazenamos dados completos de cartão de crédito.</li>
                            </ul>
                        </div>

                        <div id="youtube-data" class="mb-5">
                            <h2>2. Dados de Usuário do Google (YouTube API)</h2>
                            <p>O YouTubeOS utiliza os Serviços de API do YouTube para fornecer funcionalidades essenciais. O acesso, uso, armazenamento e compartilhamento de dados de usuário do Google aderem estritamente à <a href="https://developers.google.com/terms/api-services-user-data-policy" target="_blank" class="text-primary">Política de Dados do Usuário dos Serviços de API do Google</a>, incluindo os requisitos de uso limitado.</p>
                            
                            <div class="google-notice">
                                <h6 class="text-white fw-bold mb-2"><i class="fab fa-google me-2"></i>Como tratamos seus dados do YouTube:</h6>
                                <ul class="mb-0 text-white-50">
                                    <li><strong>Acesso:</strong> Acessamos apenas os dados necessários para análise (visualizações, inscritos, metadados de vídeos).</li>
                                    <li><strong>Uso:</strong> Usamos esses dados exclusivamente para gerar relatórios e sugestões dentro do painel.</li>
                                    <li><strong>Armazenamento:</strong> Tokens de acesso são armazenados com criptografia. Dados estatísticos são armazenados temporariamente para performance (cache).</li>
                                    <li><strong>Compartilhamento:</strong> Não compartilhamos seus dados do YouTube com terceiros, exceto para processamento de IA (ver seção 4) ou conforme exigido por lei.</li>
                                </ul>
                            </div>
                        </div>

                        <div id="uso" class="mb-5">
                            <h2>3. Como Usamos seus Dados</h2>
                            <p>Utilizamos as informações coletadas para:</p>
                            <ul>
                                <li>Fornecer, operar e manter nosso serviço.</li>
                                <li>Melhorar, personalizar e expandir nossas funcionalidades.</li>
                                <li>Enviar notificações importantes (como lembretes do calendário via Telegram).</li>
                                <li>Detectar e prevenir fraudes.</li>
                            </ul>
                        </div>

                        <div id="ia" class="mb-5">
                            <h2>4. Compartilhamento e Processamento via IA</h2>
                            <p>Para fornecer funcionalidades como "Geração de Roteiros" e "Análise de Thumbnails", dados específicos (como o tema do seu vídeo ou a imagem da capa) são enviados para processamento via API do <strong>Google Gemini</strong>.</p>
                            <p>Os dados enviados para a IA são utilizados estritamente para gerar a resposta solicitada e não são utilizados para treinar os modelos públicos da IA, conforme os termos de uso empresariais da API.</p>
                        </div>

                        <div id="seguranca" class="mb-5">
                            <h2>5. Segurança e Armazenamento</h2>
                            <p>Levamos a segurança a sério. Utilizamos práticas padrão da indústria, incluindo:</p>
                            <ul>
                                <li>Criptografia SSL/TLS em todas as comunicações.</li>
                                <li>Armazenamento criptografado de chaves de API e tokens OAuth2.</li>
                                <li>Servidores protegidos com firewalls e controle de acesso restrito.</li>
                            </ul>
                            <p>Apesar de nossos esforços, nenhum método de transmissão pela Internet ou armazenamento eletrônico é 100% seguro.</p>
                        </div>

                        <div id="direitos" class="mb-5">
                            <h2>6. Seus Direitos (LGPD e GDPR)</h2>
                            <p>Você tem o direito de:</p>
                            <ul>
                                <li><strong>Acessar:</strong> Solicitar uma cópia dos seus dados pessoais.</li>
                                <li><strong>Retificar:</strong> Corrigir dados incompletos ou imprecisos.</li>
                                <li><strong>Excluir:</strong> Solicitar a exclusão de sua conta e dados associados ("Direito ao Esquecimento"). Você pode fazer isso diretamente no painel de configurações ou entrando em contato conosco.</li>
                                <li><strong>Revogar Acesso:</strong> Você pode revogar o acesso do YouTubeOS à sua conta Google a qualquer momento através da página de <a href="https://myaccount.google.com/permissions" target="_blank" class="text-primary">Permissões de Segurança do Google</a>.</li>
                            </ul>
                        </div>

                        <div id="contato" class="mb-0">
                            <h2>7. Contato</h2>
                            <p>Para questões relacionadas à privacidade ou para exercer seus direitos, entre em contato com nosso Encarregado de Proteção de Dados (DPO):</p>
                            <ul class="list-unstyled mt-3">
                                <li><i class="fas fa-envelope me-2" style="color: var(--primary-color);"></i> privacidade@youtubeos.com</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

     <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/Landing/footer.php");
                ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll suave para links da sidebar
        document.querySelectorAll('.nav-link[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const target = document.querySelector(this.getAttribute('href'));
                const offset = 100;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = target.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });
            });
        });
    </script>
</body>
</html>