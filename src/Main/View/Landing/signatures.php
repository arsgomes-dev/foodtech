<?php

// --- INTEGRAÇÃO BACKEND ---
use Microfw\Src\Main\Common\Entity\Public\AccessPlan;

// Busca os planos do banco
$planEntity = new AccessPlan;
$plans = $planEntity->getQuery(
        customWhere: [['column' => 'status', 'value' => 1]],
        order: 'price ASC'
);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Assine o YouTubeOS</title>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="/assets/public/css/style.css">
        <link rel="stylesheet" href="/assets/public/css/register.css">
    </head>
    <body>
        <input type="hidden" id="x_y" value="<?php echo (env('PAG_CYCLE_ANUAL_X_PRICE') ?? 12); ?>">

        <div class="container-fluid p-0">
            <div class="row g-0 split-screen">

                <div class="col-lg-5 d-none d-lg-flex left-panel">
                    <div style="z-index: 1;">
                        <a class="navbar-brand text-white fs-3 fw-bold mb-5 d-block" href="index.php">
                            <i class="fab fa-youtube me-2" style="color: var(--primary-color);"></i>YouTube<span style="color: var(--primary-color);">OS</span>
                        </a>

                        <h1 class="text-white fw-bold display-5 mb-4">
                            Seu canal,<br>no próximo nível.
                        </h1>
                        <p class="text-secondary lead mb-5">
                            Junte-se à plataforma que usa Inteligência Artificial para transformar visualizações em inscritos fiéis.
                        </p>

                        <div class="testimonial-box">
                            <div class="d-flex mb-3 text-warning small">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-light fst-italic mb-3">
                                "A análise de thumbnail do YouTubeOS é inacreditável. O cadastro foi super rápido e já comecei a usar no mesmo dia."
                            </p>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">RC</div>
                                <div class="ms-3">
                                    <h6 class="text-white mb-0">Ricardo Cruz</h6>
                                    <small class="text-muted">Canal de Reviews</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7 right-panel">
                    <div class="auth-container">

                        <div class="d-block d-lg-none text-center mb-4">
                            <span class="fs-3 fw-bold text-white">YouTube<span class="text-primary">OS</span></span>
                        </div>

                        <div class="step-indicator">
                            <div class="step-item active" id="ind-0">
                                <div class="step-circle">1</div>
                                <div class="step-label">Plano</div>
                            </div>
                            <div class="step-item" id="ind-1">
                                <div class="step-circle">2</div>
                                <div class="step-label">Pessoal</div>
                            </div>
                            <div class="step-item" id="ind-2">
                                <div class="step-circle">3</div>
                                <div class="step-label">Endereço</div>
                            </div>
                            <div class="step-item" id="ind-3">
                                <div class="step-circle">4</div>
                                <div class="step-label">Acesso</div>
                            </div>
                        </div>

                        <form id="wizardForm" action="processar_assinatura.php" method="POST">

                            <div class="step-content active">
                                <h3 class="fw-bold text-white mb-4">Escolha o melhor plano</h3>
                                <?php
                                $isPeriod = isset($_POST['period_plan']) ? $_POST['period_plan'] : "";
                                ?>
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check" name="ciclo" id="ciclo_mensal" value="monthly" <?php
                                        if ($isPeriod !== 'yearly') {
                                            echo'checked';
                                        }
                                        ?> >
                                        <label class="btn btn-outline-light" for="ciclo_mensal">Mensal</label>
                                        <input type="radio" class="btn-check" name="ciclo" id="ciclo_anual" value="yearly" <?php
                                        if ($isPeriod === 'yearly') {
                                            echo'checked';
                                        }
                                        ?>>
                                        <label class="btn btn-outline-light" for="ciclo_anual">Anual 
                                            <?php
                                            if (env('PAG_CYCLE_ANUAL_X_PRICE') < 12) {
                                                    if (env('PAG_CYCLE_ANUAL_X_PRICE') === 1) {
                                                        echo "(1 mês grátis)";
                                                    } else {
                                                        echo "(" . (12 - env('PAG_CYCLE_ANUAL_X_PRICE')) . " meses grátis)";
                                                    }
                                                }
                                            ?>                                                                                       
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <?php
                                    $isPlan = isset($_POST['plan']) ? $_POST['plan'] : "";

                                    if (!empty($plans)):
                                        ?>
                                        <?php foreach ($plans as $index => $plan): ?>
                                            <?php
                                            // Ignora planos sem preço ou zerados (opcional)
                                            if ($plan->getPrice() <= 0)
                                                continue;
                                            // Lógica para marcar o "recomendado" (ex: o do meio ou por tag)
                                            $isRecommended = ($plan->getRecommended() === 1);

                                            $isChecked = '';
                                            if ($plan->getGcid() === $isPlan) {
                                                $isChecked = 'checked';
                                            }

                                            // Processa features (pega só as 2 primeiras para o card ficar limpo)
                                            $features = array_filter(array_map('trim', explode(';', $plan->getDescription())));
                                            $topFeatures = array_slice($features, 0, 2);
                                            ?>

                                            <div class="col-md-4 d-flex"> <input type="radio" class="btn-check plan-radio" 
                                                                                 name="plano" 
                                                                                 id="plano_<?= $plan->getGcid() ?>" 
                                                                                 value="<?= $plan->getGcid() ?>" 
                                                                                 data-price="<?= number_format($plan->getPrice(), 2, '.', '') ?>" 
                                                                                 data-name="<?= htmlspecialchars($plan->getTitle()) ?>"
        <?= $isChecked ?>>

                                                <label class="selectable-plan d-block w-100" for="plano_<?= $plan->getGcid() ?>">

                                                    <div class="plan-header-wrapper">
                                                        <div class="badge bg-primary mb-2 <?= $isRecommended ? '' : 'invisible' ?>">
                                                            Recomendado
                                                        </div>

                                                        <h6 class="fw-bold text-white"><?= htmlspecialchars($plan->getTitle()) ?></h6>

                                                        <h4 class="fw-bold text-white my-2 price-display">
                                                            R$ <?= number_format($plan->getPrice(), 2, ',', '.') ?>
                                                        </h4>
                                                    </div>

                                                    <hr class="border-secondary opacity-25 my-3">

                                                    <ul class="list-unstyled text-secondary small mb-0 plan-features">
                                                        <?php foreach ($topFeatures as $feature): ?>
                                                            <li class="mb-2"><i class="fas fa-check me-2 text-primary"></i> <?= htmlspecialchars($feature) ?></li>
        <?php endforeach; ?>
                                                    </ul>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="text-white">Nenhum plano disponível.</div>
<?php endif; ?>
                                </div>
                            </div>

                            <div class="step-content">
                                <h3 class="fw-bold text-white mb-4">Dados Pessoais</h3>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nome Completo</label>
                                        <input type="text" class="form-control" name="nome" placeholder="Nome completo" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">CPF</label>
                                        <input type="text" class="form-control" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required>
                                        <div class="invalid-feedback">CPF inválido.</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Data de Nascimento</label>
                                        <input type="date" class="form-control" name="nascimento" id="nascimento" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Celular / WhatsApp</label>
                                        <input type="text" class="form-control" name="celular" id="celular" placeholder="(00) 00000-0000" required>
                                    </div>
                                </div>
                            </div>

                            <div class="step-content">
                                <h3 class="fw-bold text-white mb-4">Endereço</h3>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">CEP</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" required>
                                            <span class="input-group-text bg-dark border-secondary text-secondary" id="cep-loading" style="display:none;">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </span>
                                        </div>
                                        <small id="cep-error" class="text-danger d-none">CEP não encontrado</small>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Rua / Logradouro</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" readonly required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Complemento</label>
                                        <input type="text" class="form-control" name="complemento" placeholder="Apto, Bloco...">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" readonly required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" readonly required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">UF</label>
                                        <input type="text" class="form-control" id="uf" name="uf" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="step-content">
                                <h3 class="fw-bold text-white mb-4">Finalizar Cadastro</h3>

                                <div class="p-3 mb-4 rounded-3 border border-secondary border-opacity-25 bg-dark">
                                    <h6 class="text-white fw-bold mb-3">Resumo da Assinatura</h6>
                                    <div class="d-flex justify-content-between text-secondary mb-1">
                                        <span id="resumo-plano">--</span>
                                        <span id="resumo-ciclo">Mensal</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-25 mt-2">
                                        <span class="text-white">Total:</span>
                                        <span class="text-primary fw-bold fs-5" id="resumo-valor">R$ --</span>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Seu melhor E-mail</label>
                                        <div class="input-group has-validation">
                                            <input type="email" class="form-control" name="email" id="email" required>
                                            <div id="email-feedback" class="invalid-feedback">
                                                Este e-mail já está cadastrado. <a href="/app/login">Faça login</a>.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Senha</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="senha" id="senha" placeholder="Min. 8 caracteres" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('senha', 'eye-icon-senha')"><i id="eye-icon-senha" class="far fa-eye"></i></button>
                                            <div class="invalid-feedback">A senha é obrigatória.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Confirmar Senha</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" name="conf_senha" id="conf_senha" required>
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePass('conf_senha', 'eye-icon-conf-senha')"><i id="eye-icon-conf-senha" class="far fa-eye"></i></button>
                                            <div class="invalid-feedback" id="conf-senha-feedback">As senhas não coincidem.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="termos" name="termos" required>
                                    <label class="form-check-label text-secondary" for="termos">
                                        Li e concordo com os <a href="/termos" target="_blank" class="text-primary text-decoration-none">Termos de Uso</a> e <a href="/privacidade" target="_blank" class="text-primary text-decoration-none">Política de Privacidade</a>.
                                    </label>
                                    <div class="invalid-feedback">
                                        Você precisa aceitar os termos para continuar.
                                    </div>
                                </div>
                            </div>

                            <div class="wizard-buttons">
                                <button type="button" class="btn btn-outline-light rounded-pill px-4" id="prevBtn" onclick="nextPrev(-1)" style="display:none;">
                                    <i class="fas fa-arrow-left me-2"></i> Voltar
                                </button>
                                <button type="button" class="btn btn-primary-gradient rounded-pill px-4 fw-bold ms-auto" id="nextBtn" onclick="nextPrev(1)">
                                    Próximo <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
        <script src="/assets/vendor/login/md5.js"></script>
        <script src="/assets/public/js/register.js"></script>
    </body>
</html>