<?php
if (!isset($_SESSION)) {
    session_cache_expire(1);
    session_start();
}

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;

$tradutor = new Translate();
$config = new McClientConfig();
$stConfig = new StConfig();
$st = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
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

            <style>/* Layout geral */
                .recover-wrapper {
                    min-height: 100vh;
                }

                /* Card */
                .recover-card {
                    max-width: 420px;
                    width: 100%;
                    border-radius: 18px;
                    animation: fadeIn 0.5s ease;
                }

                /* Ícone superior */
                .recover-icon {
                    font-size: 55px;
                    color: #0d6efd;
                    margin-bottom: 8px;
                }

                /* Inputs elegantes */
                .recover-input-group .input-group-text {
                    background: #fff;
                    border-right: 0;
                    border-radius: 10px 0 0 10px;
                    color: #0d6efd;
                }

                .recover-input-group .form-control {
                    border-left: 0;
                    border-radius: 0 10px 10px 0;
                    padding: 0.75rem;
                    transition: 0.25s ease-in-out;
                }

                .recover-input-group .form-control:focus {
                    border-color: #0d6efd;
                    box-shadow: 0 0 0 0.15rem rgba(13, 110, 253, 0.25);
                }

                /* Botão de enviar */
                .btn-recover {
                    border-radius: 10px;
                    font-size: 1rem;
                }

                /* Voltar ao login */
                .back-login {
                    font-size: 0.85rem;
                    text-decoration: none;
                    color: #0d6efd;
                }

                .back-login:hover {
                    text-decoration: underline;
                }

                /* Fade in */
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
            <div class="recover-wrapper d-flex justify-content-center align-items-center">

                <div class="recover-card card shadow-lg p-4 border-0">


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
                        <h4 class="fw-bold">Recuperar Senha</h4>
                        <p class="text-muted">
                            Informe seu e-mail e enviaremos instruções para redefinir sua senha.
                        </p>
                    </div>

                    <form action="<?php echo '/' . $config->getUrlPublic(); ?>/Password/SendRecoveryEmail" 
                          method="post" id="form_recovery">

                        <!-- Email -->
                        <div class="input-group mb-3 recover-input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" class="form-control" 
                                   placeholder="Digite seu e-mail" 
                                   name="email" id="email" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold btn-recover">
                            Enviar Instruções
                        </button>

                        <div class="text-center mt-3">
                            <a href="<?php echo '/' . $config->getUrlPublic(); ?>/login" class="back-login">Voltar ao login</a>
                        </div>

                    </form>

                </div>

            </div>
        </div>
        <script src="/assets/vendor/jquery/jquery.min.js"></script>
        <script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="/assets/vendor/login/popper.min.js"></script>
        <script src="/assets/vendor/lte/js/adminlte.min.js?v=3.2.0"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config->getReChaveSiteKey(); ?>"></script>

    </body></html>