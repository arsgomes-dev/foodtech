<?php
if (!isset($_SESSION)) {
    session_cache_expire(1);
    session_start();
}

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;

$tradutor = new Translate();
$config = new McConfig();
$stConfig = new StConfig();
$st = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' =>1]]);
$website_title = (isset($st) ? $st->getTitle() : "");
$website_logo = (isset($st) ? $st->getLogo() : "");
?>
<!doctype html>
<html lang="pt-br"><head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $website_title; ?></title>

        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">

        <link rel="stylesheet" href="/libs/v1/admin/plugins/fontawesome-free/css/all.min.css">

        <link rel="stylesheet" href="/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

        <link rel="stylesheet" href="/libs/v1/admin/plugins/lte/css/adminlte.min.css?v=3.2.0">
    </head>
    <body class="login-page" style="min-height: 496.781px;">
        <div class="login-box">
            <div class="login-logo">
                <a href="../../index2.html">Thetec<b>inf</b>or<b> ®</b></a>
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
            <div class="card">

                <div class="card-body login-card-body">
                    <p class="login-box-msg">Realize o login para iniciar uma sessão</p>
                    <form name="form_login" id="form_login" action="<?php echo "/" . $config->getUrlAdmin(); ?>/login" method="post" >
                        <input type="hidden" name="language" id="language" value="1">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Email" name="email" id="email">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Senha" name="pass" id="pass">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4">
                                <button type="button" class="btn btn-primary btn-block" onclick="login();">Login</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>


        <script src="/libs/v1/admin/plugins/jquery/jquery.min.js"></script>
        <script src="/libs/v1/admin/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="/libs/v1/admin/plugins/login/popper.min.js"></script>
        <script src="/libs/v1/admin/plugins/lte/js/adminlte.min.js?v=3.2.0"></script>
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $config->getReChaveSiteKey(); ?>"></script>
        <script src="/libs/v1/admin/plugins/login/md5.js"></script>
        <script src="/libs/v1/admin/plugins/login/login.js"></script>
        <script>
                                            function login() {
                                                grecaptcha.execute("<?php echo $config->getReChaveSiteKey(); ?>", {
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

    </body></html>