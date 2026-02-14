<?php
if (!isset($_SESSION)) {
    session_cache_expire(1);
    session_start();
}

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\StConfig;
use Microfw\Src\Main\Common\Settings\Public\Google\GoogleConfig;

$tradutor = new Translate();
$stConfig = new StConfig();
$stConfig = $stConfig->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);
$website_title = (isset($st) ? $st->getTitle() : "");
$website_logo = (isset($st) ? $st->getLogo() : "");
?>
<!doctype html>
<html lang="pt-br"><head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $website_title; ?></title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">

        <link rel="stylesheet" href="/assets/fonts/css/all.min.css">

        <link rel="stylesheet" href="/assets/vendor/icheck-bootstrap/icheck-bootstrap.min.css">

        <link rel="stylesheet" href="/assets/vendor/lte/css/adminlte.min.css?v=3.2.0">
    </head>
    <body class="login-page" style="min-height: 496.781px;">
        <div class="login-box">

            <style>
                /* Wrapper geral */
                .login-wrapper {
                    min-height: 100vh;
                }

                /* Card */
                .login-card {
                    max-width: 420px;
                    width: 100%;
                    border-radius: 18px;
                    animation: fadeIn 0.5s ease;
                }

                /* Ícone superior */
                .login-icon {
                    font-size: 55px;
                    color: #0d6efd;
                    margin-bottom: 5px;
                }

                /* Inputs */
                .input-group-elegant .input-group-text {
                    background: #fff;
                    border-right: 0;
                    border-radius: 10px 0 0 10px;
                    color: #0d6efd;
                }

                .input-group-elegant .form-control {
                    border-radius: 0 10px 10px 0;
                    border-left: 0;
                    padding: 0.75rem;
                    transition: 0.25s ease-in-out;
                }

                .input-group-elegant .form-control:focus {
                    border-color: #0d6efd;
                    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
                }

                /* Link esqueci senha */
                .forgot-link {
                    font-size: 0.85rem;
                    color: #0d6efd;
                    text-decoration: none;
                }

                .forgot-link:hover {
                    text-decoration: underline;
                }

                /* Botão Login */
                .btn-login {
                    border-radius: 10px;
                    font-size: 1rem;
                }

                /* Separador */
                .separator {
                    color: #6c757d;
                    font-size: 0.85rem;
                }

                /* Botão Google */
                .btn-google {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 10px;
                    padding: 10px;
                    background: #fff;
                    border: 1px solid #d0d5dd;
                    font-weight: 500;
                    transition: 0.25s ease;
                }

                .btn-google:hover {
                    background: #f8f9fa;
                    border-color: #c1c7d0;
                }

                /* Animação */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to   {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }


            </style>
            <div class="login-wrapper d-flex justify-content-center align-items-center">

                <div class="login-card card shadow-lg p-4 border-0">

                    <div class="text-center mb-4">
                        <div class="login-logo">
                            <a href="/">Thetec<b>inf</b>or<b> ®</b></a>
                        </div>
                        <?php
                        if (isset($_SESSION['erro_log'])) {
                            ?>
                            <div class="card card-warning shadow">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <?php
                                        echo $tradutor->translate($_SESSION['erro_log'], "pt_br");
                                        $_SESSION['erro_log'] = null;
                                        ?>
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" " class="ml-2 mb-1 close" data-card-widget="remove"><span aria-hidden="true">×</span></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <?php
                        }
                        ?>
                        <h4 class="fw-bold">Acesso ao Sistema</h4>
                        <p class="text-muted">Entre com suas credenciais para continuar</p>
                    </div>

                    <form name="form_login" id="form_login" 
                          action="<?php echo '/' . env('URL_PUBLIC'); ?>/login" 
                          method="post">

                        <input type="hidden" name="language" id="language" value="1">

                        <!-- Email -->
                        <div class="input-group mb-3 input-group-elegant">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" placeholder="Seu e-mail" 
                                   name="email" id="email" required>
                        </div>

                        <!-- Senha -->
                        <div class="input-group mb-3 input-group-elegant">
                            <span class="input-group-text">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" class="form-control" placeholder="Sua senha" 
                                   name="pass" id="pass" required>
                        </div>

                        <div class="d-flex justify-content-end mb-3">
                            <a href="<?php echo '/' . env('URL_PUBLIC'); ?>/recovery" class="forgot-link">
                                Esqueci minha senha
                            </a>
                        </div>

                        <button type="button" onclick="login();" 
                                class="btn btn-primary w-100 py-2 fw-bold mb-3 btn-login">
                            Entrar
                        </button>

                        <div class="separator text-center my-3">ou continue com</div>

                        <!-- Google -->
                        <?php
                        $client = new GoogleConfig();
                        $client = $client->config();
                        // Gera a URL de autenticação
                        $authUrl = $client->createAuthUrl();
                        ?>
                        <a href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>" class="btn btn-google w-100">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20">
                            <span class="ms-2"> Google</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
        <script src="/assets/vendor/jquery/jquery.min.js"></script>
        <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/vendor/login/popper.min.js"></script>
        <script src="/assets/vendor/lte/js/adminlte.min.js?v=3.2.0"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo env('GOOGLE_RE_SITE_KEY'); ?>"></script>
        <script src="/assets/vendor/login/md5.js"></script>
        <script src="/assets/vendor/login/login.js"></script>
        <script>
                            function login() {
                                grecaptcha.execute("<?php echo env('GOOGLE_RE_SITE_KEY'); ?>", {
                                    action: "lg"
                                })
                                        .then((token) => {
                                            input = document.createElement('input');
                                            input.type = 'hidden';
                                            input.name = 'g-recaptcha';
                                            input.value = token;
                                            form_login.append(input);

                                            var s = document.getElementById("pass");
                                            formhash(form_login, s);

                                            form_login.submit();
                                        });
                            }
                            const em = document.getElementById('email');
                            em.addEventListener('keypress', function (event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    login();
                                }
                            });
                            const ps = document.getElementById('pass');
                            ps.addEventListener('keypress', function (event) {
                                if (event.key === 'Enter') {
                                    event.preventDefault();
                                    login();
                                }
                            });
        </script>
    </body>
</html>