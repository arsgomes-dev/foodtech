<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos de Uso - YouTubeOS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="/assets/public/css/style.css">

    <style>
        /* Header Minimalista */
        .legal-header {
            padding: 120px 0 60px;
            background: radial-gradient(circle at 50% -20%, #2a0a40 0%, var(--bg-body) 70%);
            border-bottom: 1px solid var(--border-color);
        }

        /* Layout do Conteúdo */
        .legal-box {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
        }

        /* Tipografia Legal */
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

        .legal-content ul {
            padding-left: 20px;
        }

        .legal-content strong {
            color: #fff;
        }

        /* Sidebar de Navegação (Sticky) */
        .legal-nav {
            position: sticky;
            top: 100px; /* Altura da Navbar + Espaço */
        }

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

        /* Links no texto */
        .legal-content a {
            color: var(--primary-color);
            text-decoration: underline;
            text-decoration-thickness: 1px;
            text-underline-offset: 3px;
        }

        .legal-content a:hover {
            color: #fff;
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
            <h1 class="fw-bold text-white mb-2">Termos de Uso</h1>
            <p class="text-secondary">Última atualização: 24 de Janeiro de 2026</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row g-5">
                
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="legal-nav">
                        <h6 class="text-uppercase text-secondary fw-bold mb-3 small ps-3">Índice</h6>
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" href="#intro">1. Aceitação</a>
                            <a class="nav-link" href="#services">2. O Serviço</a>
                            <a class="nav-link" href="#account">3. Conta e Segurança</a>
                            <a class="nav-link" href="#ai-terms">4. Uso de IA e Conteúdo</a>
                            <a class="nav-link" href="#youtube-api">5. Termos do YouTube</a>
                            <a class="nav-link" href="#subscription">6. Assinatura e Pagamentos</a>
                            <a class="nav-link" href="#liability">7. Limitação de Responsabilidade</a>
                            <a class="nav-link" href="#contact">8. Contato</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="legal-box legal-content">
                        
                        <div id="intro" class="mb-5">
                            <h2>1. Aceitação dos Termos</h2>
                            <p>Bem-vindo ao <strong>YouTubeOS</strong>. Ao criar uma conta, acessar ou utilizar nosso software ("Serviço"), você concorda em cumprir estes Termos de Uso ("Termos"). Se você não concordar com qualquer parte destes termos, você não deve utilizar o Serviço.</p>
                            <p>Estes Termos aplicam-se a todos os visitantes, usuários e outras pessoas que acessam ou usam o Serviço.</p>
                        </div>

                        <div id="services" class="mb-5">
                            <h2>2. Descrição do Serviço</h2>
                            <p>O YouTubeOS é uma ferramenta de produtividade para criadores de conteúdo que oferece:</p>
                            <ul>
                                <li>Gestão de calendário editorial.</li>
                                <li>Geração e análise de roteiros assistida por Inteligência Artificial.</li>
                                <li>Análise de SEO e palavras-chave.</li>
                                <li>Análise preditiva de thumbnails.</li>
                            </ul>
                            <p>Reservamo-nos o direito de modificar, suspender ou descontinuar qualquer parte do Serviço a qualquer momento, com ou sem aviso prévio.</p>
                        </div>

                        <div id="account" class="mb-5">
                            <h2>3. Conta e Segurança</h2>
                            <p>Para utilizar o Serviço, você deve criar uma conta fornecendo informações precisas e completas. Você é o único responsável pela atividade que ocorre em sua conta e deve manter a senha da sua conta segura.</p>
                            <p>Você deve nos notificar imediatamente sobre qualquer violação de segurança ou uso não autorizado de sua conta. O YouTubeOS não será responsável por quaisquer perdas causadas por qualquer uso não autorizado de sua conta.</p>
                        </div>

                        <div id="ai-terms" class="mb-5">
                            <h2>4. Uso de IA e Conteúdo Gerado</h2>
                            <p>O YouTubeOS utiliza modelos de Inteligência Artificial (como o Google Gemini) para gerar sugestões de texto, roteiros e análises.</p>
                            <ul>
                                <li><strong>Natureza Sugestiva:</strong> O conteúdo gerado pela IA é apenas uma sugestão. Você, como criador, é totalmente responsável por revisar, editar e verificar a precisão do conteúdo antes de publicá-lo.</li>
                                <li><strong>Propriedade Intelectual:</strong> Você detém os direitos sobre o conteúdo final (vídeos, roteiros editados) que você cria utilizando nossas ferramentas.</li>
                                <li><strong>Alucinações de IA:</strong> A IA pode ocasionalmente gerar informações incorretas ou tendenciosas. O YouTubeOS não garante a exatidão das informações geradas.</li>
                            </ul>
                        </div>

                        <div id="youtube-api" class="mb-5">
                            <h2>5. Termos do YouTube e Google</h2>
                            <p>O YouTubeOS utiliza os serviços de API do YouTube para fornecer certas funcionalidades (como busca de vídeos virais e análise de métricas).</p>
                            <p>Ao utilizar nosso Serviço, você concorda em estar vinculado aos <a href="https://www.youtube.com/t/terms" target="_blank">Termos de Serviço do YouTube</a> e à <a href="https://policies.google.com/privacy" target="_blank">Política de Privacidade do Google</a>.</p>
                        </div>

                        <div id="subscription" class="mb-5">
                            <h2>6. Assinaturas, Cancelamentos e Reembolsos</h2>
                            <p><strong>Faturamento:</strong> O Serviço é cobrado antecipadamente em uma base mensal ou anual. A assinatura será renovada automaticamente, a menos que seja cancelada.</p>
                            <p><strong>Cancelamento:</strong> Você pode cancelar sua assinatura a qualquer momento através do painel de controle. O cancelamento entrará em vigor no final do período de faturamento atual.</p>
                            <p><strong>Reembolsos:</strong> Para planos anuais, oferecemos um período de garantia de 7 dias. Após esse período, não oferecemos reembolsos parciais por períodos não utilizados.</p>
                        </div>

                        <div id="liability" class="mb-5">
                            <h2>7. Limitação de Responsabilidade</h2>
                            <p>Em nenhuma circunstância o YouTubeOS, seus diretores, funcionários ou agentes serão responsáveis por quaisquer danos diretos, indiretos, incidentais ou consequentes resultantes de:</p>
                            <ul>
                                <li>Erros ou imprecisões de conteúdo.</li>
                                <li>Perda de dados ou interrupção de negócios.</li>
                                <li>Qualquer acesso não autorizado aos nossos servidores.</li>
                                <li>Suspensão ou banimento do seu canal no YouTube (você é responsável por seguir as Diretrizes da Comunidade do YouTube).</li>
                            </ul>
                        </div>

                        <div id="contact" class="mb-0">
                            <h2>8. Contato</h2>
                            <p>Se você tiver alguma dúvida sobre estes Termos, entre em contato conosco:</p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-envelope me-2 text-primary"></i> legal@youtubeos.com</li>
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
        // Script simples para Scroll Suave ao clicar no menu lateral
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                // Remove classe ativa de todos
                document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
                // Adiciona ao clicado
                this.classList.add('active');

                const target = document.querySelector(this.getAttribute('href'));
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });
            });
        });
    </script>
</body>
</html>