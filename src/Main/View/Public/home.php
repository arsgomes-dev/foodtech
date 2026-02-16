<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;
use Microfw\Src\Main\Common\Entity\Public\ClientNutritionalProfile;
use Microfw\Src\Main\Common\Entity\Public\Client;
use Microfw\Src\Main\Common\Entity\Public\ClientPhysicalActivityLevel;
use Microfw\Src\Main\Common\Entity\Public\ClientNutritionalGoal;

$language = new Language;
$translate = new Translate();
$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$planService = new CheckPlan;
$check = $planService->checkPlan();

// Busca cliente logado (para birth/gender)
$client = new Client();
$client = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);

// Verifica se cliente possui perfil nutricional cadastrado
$clientNutritionalProfile = new ClientNutritionalProfile();
$clientProfile = $clientNutritionalProfile->getQuery(single: true, customWhere: [['column' => 'customer_id', 'value' => $_SESSION['client_id']]]);

// Carrega níveis de atividade física e metas apenas se as classes existirem no autoload
$activityLevels = [];
$goals = [];
if (class_exists('\Microfw\\Src\\Main\\Common\\Entity\\Public\\ClientPhysicalActivityLevel')) {
    $activityRepo = new ClientPhysicalActivityLevel();
    $activityLevels = $activityRepo->getQuery(customWhere: [['column' => 'is_active', 'value' => 1]], order: 'display_order ASC');
}
if (class_exists('\Microfw\\Src\\Main\\Common\\Entity\\Public\\ClientNutritionalGoal')) {
    $goalRepo = new ClientNutritionalGoal();
    $goals = $goalRepo->getQuery(customWhere: [['column' => 'is_active', 'value' => 1]], order: 'display_order ASC');
}
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  

        <!-- end top base html css -->
        <!-- Font Awesome CDN (fallback caso não esteja carregado pelo tema) -->
        <title>Dashboard - Perfil Nutricional</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-p2c6XoVxj1Z+Yh4Z1nXkK2Qe5sZl7Yf2KqG1nZl6JQm2h1Q9j1b5h2KQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/assets/css/custom/nutrition-wizard.css">
     </head>

     <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

         <div class="wrapper">
            <?php
            $baseHtml->baseMenu("home");
            ?>
            <div class="content-wrapper" style="min-height: auto !important; margin-bottom: 20px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $workspaceDash = $translate->translate('Dashboard', $_SESSION['client_lang']);
                    echo $baseHtml->baseBreadcrumb($workspaceDash, $directory, "Dashboard");
                    ?>  
                    <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['client_lang_locale']; ?>">
                    <!-- end base html breadcrumb -->
                    <?php
                    if (!$check['allowed']) {
                        ?>
                        <div class="alert alert-info text-start" style="padding: 0.50rem 1.25rem; margin-bottom: 10px;">
                            <strong><?php echo $check['message']; ?></strong><br>
                        </div> 
                        <br>
                        <?php
                    } else {
                        ?>  
                        <!-- cards -->
                        <!-- ================================================
     EXEMPLO DE PÁGINA HOME COM TEMA DE NUTRIÇÃO
     ================================================ -->

<!-- CARDS DE ESTATÍSTICAS NUTRICIONAIS -->
<div class="row mb-4 mt-4">
    <!-- Card: Calorias Diárias -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="nutrition-card calories fade-in">
            <div class="card-icon">
                <i class="fas fa-fire"></i>
            </div>
            <div class="card-value">1,850</div>
            <div class="card-label">Calorias Consumidas</div>
            <div class="card-trend positive">
                <i class="fas fa-arrow-up"></i>
                <span>250 kcal acima da meta</span>
            </div>
            <div class="progress mt-3">
                <div class="progress-bar calories" role="progressbar" style="width: 92%" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Card: Proteínas -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="nutrition-card protein fade-in" style="animation-delay: 0.1s">
            <div class="card-icon">
                <i class="fas fa-drumstick-bite"></i>
            </div>
            <div class="card-value">85g</div>
            <div class="card-label">Proteínas</div>
            <div class="card-trend positive">
                <i class="fas fa-check-circle"></i>
                <span>Meta atingida</span>
            </div>
            <div class="progress mt-3">
                <div class="progress-bar protein" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Card: Hidratação -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="nutrition-card water fade-in" style="animation-delay: 0.2s">
            <div class="card-icon">
                <i class="fas fa-tint"></i>
            </div>
            <div class="card-value">1.8L</div>
            <div class="card-label">Água Consumida</div>
            <div class="card-trend negative">
                <i class="fas fa-exclamation-circle"></i>
                <span>Faltam 700ml</span>
            </div>
            <div class="progress mt-3">
                <div class="progress-bar water" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <!-- Card: Refeições -->
    <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="nutrition-card fade-in" style="animation-delay: 0.3s">
            <div class="card-icon">
                <i class="fas fa-utensils"></i>
            </div>
            <div class="card-value">4/6</div>
            <div class="card-label">Refeições Realizadas</div>
            <div class="card-trend positive">
                <i class="fas fa-clock"></i>
                <span>Próxima: 19:00</span>
            </div>
            <div class="progress mt-3">
                <div class="progress-bar" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<!-- CARDS DE ATIVIDADES E GRÁFICOS -->
<div class="row mb-4">
    <!-- Resumo Semanal -->
    <div class="col-lg-8 col-md-12 mb-3">
        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-2" style="color: var(--primary-color,#6b5bff);"></i>
                    Consumo Calórico - Semana
                </h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-calendar-week"></i> Esta Semana
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="caloriesChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Atividades Recentes -->
    <div class="col-lg-4 col-md-12 mb-3">
        <div class="card fade-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-2" style="color: var(--primary-color,#6b5bff);"></i>
                    Atividades Recentes
                </h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div style="width: 40px; height: 40px; background: var(--gradient-success); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-apple-alt text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Café da Manhã</strong>
                                <p class="mb-0 text-muted small">08:30 - 420 kcal</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div style="width: 40px; height: 40px; background: var(--gradient-hydration); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-glass-water text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Hidratação</strong>
                                <p class="mb-0 text-muted small">10:15 - 500ml</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-3" style="border-bottom: 1px solid var(--border-color);">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div style="width: 40px; height: 40px; background: var(--gradient-energy); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-running text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Exercício</strong>
                                <p class="mb-0 text-muted small">11:00 - 320 kcal queimadas</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-0">
                        <div class="d-flex align-items-center">
                            <div class="mr-3">
                                <div style="width: 40px; height: 40px; background: var(--gradient-success); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-utensils text-white"></i>
                                </div>
                            </div>
                            <div>
                                <strong>Almoço</strong>
                                <p class="mb-0 text-muted small">12:30 - 680 kcal</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- MACRONUTRIENTES E AÇÕES RÁPIDAS -->
<div class="row mb-4">
    <!-- Distribuição de Macronutrientes -->
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card fade-in">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-2" style="color: var(--primary-color,#6b5bff);"></i>
                    Macronutrientes (Hoje)
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-drumstick-bite mr-2" style="color: #3498db;"></i> Proteínas</span>
                            <strong>85g / 90g</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar protein" role="progressbar" style="width: 94%" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-bread-slice mr-2" style="color: #f39c12;"></i> Carboidratos</span>
                            <strong>210g / 250g</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar calories" role="progressbar" style="width: 84%" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-seedling mr-2" style="color: #2ecc71;"></i> Gorduras</span>
                            <strong>45g / 60g</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-carrot mr-2" style="color: #e67e22;"></i> Fibras</span>
                            <strong>18g / 25g</strong>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 72%; background: linear-gradient(135deg, #e67e22, #d35400);" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="col-lg-6 col-md-12 mb-3">
        <div class="card fade-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt mr-2" style="color: var(--primary-color,#6b5bff);"></i>
                    Ações Rápidas
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <button class="btn btn-outline-primary w-100" style="padding: 20px; border-radius: 12px;">
                            <i class="fas fa-plus-circle fa-2x mb-2"></i>
                            <br>
                            <strong>Nova Refeição</strong>
                        </button>
                    </div>
                    <div class="col-6 mb-3">
                        <button class="btn btn-outline-primary w-100" style="padding: 20px; border-radius: 12px;">
                            <i class="fas fa-tint fa-2x mb-2"></i>
                            <br>
                            <strong>Adicionar Água</strong>
                        </button>
                    </div>
                    <div class="col-6 mb-3">
                        <button class="btn btn-outline-primary w-100" style="padding: 20px; border-radius: 12px;">
                            <i class="fas fa-running fa-2x mb-2"></i>
                            <br>
                            <strong>Registrar Exercício</strong>
                        </button>
                    </div>
                    <div class="col-6 mb-3">
                        <button class="btn btn-outline-primary w-100" style="padding: 20px; border-radius: 12px;">
                            <i class="fas fa-chart-line fa-2x mb-2"></i>
                            <br>
                            <strong>Ver Progresso</strong>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!$clientProfile) : ?>
<!-- Modal Perfil Nutricional - aparece se não houver perfil -->
<div id="nutriModal" class="modal show-backdrop" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="nutriModalLabel" data-client-birth="<?php echo $client ? $client->getBirth() : ''; ?>" data-customer-id="<?php echo (int)($_SESSION['client_id'] ?? 0); ?>">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content nutri-modal">
      <div class="modal-header d-flex align-items-center justify-content-between">
        <div>
          <h5 class="modal-title" id="nutriModalLabel">Configurar Perfil Nutricional</h5>
          <div class="modal-subtitle text-muted" style="font-size:13px;">Complete seu perfil em 4 passos rápidos</div>
        </div>
        <button type="button" class="btn btn-sm btn-light" id="nutriClose" aria-label="Fechar janela">×</button>
      </div>
      <div class="modal-body">
        <div class="nutri-stepper" id="nutriStepper" role="tablist" aria-label="Progresso do fluxo">
            <div class="step-dot" data-step="1" role="tab" aria-selected="true"><div class="dot active" data-step="1"><span class="step-num">1</span></div><div class="step-title">Dados<br><small>Básicos</small></div></div>
            <div class="connector" aria-hidden="true"></div>
            <div class="step-dot" data-step="2" role="tab" aria-selected="false"><div class="dot" data-step="2"><span class="step-num">2</span></div><div class="step-title">Atividade<br><small>Física</small></div></div>
            <div class="connector" aria-hidden="true"></div>
            <div class="step-dot" data-step="3" role="tab" aria-selected="false"><div class="dot" data-step="3"><span class="step-num">3</span></div><div class="step-title">Metas</div></div>
            <div class="connector" aria-hidden="true"></div>
            <div class="step-dot" data-step="4" role="tab" aria-selected="false"><div class="dot" data-step="4"><span class="step-num">4</span></div><div class="step-title">Resultados</div></div>
        </div>

        <!-- Step 1: Dados Básicos -->
        <div class="step active" data-step="1">
            <!-- heading shown in stepper; content area contains form fields only -->
            <div class="row">
                <div class="col-md-4">
                    <label>Sexo biológico <span class="nutri-tooltip" title="Usamos sexo biológico porque as fórmulas de cálculo de taxa metabólica consideram diferenças fisiológicas." tabindex="0">?</span></label>
                    <div class="d-flex gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" id="sex_m" value="M" <?php echo (($client && $client->getGender() === 'M') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="sex_m">Masculino</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="sex" id="sex_f" value="F" <?php echo (($client && $client->getGender() === 'F') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="sex_f">Feminino</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>Altura (cm)</label>
                    <input type="number" id="height" class="form-control" min="50" max="300" placeholder="Ex: 170">
                </div>
                <div class="col-md-4">
                    <label>Peso atual (kg)</label>
                    <input type="number" id="weight" class="form-control" step="0.1" min="20" max="500" placeholder="Ex: 78.5">
                </div>
            </div>
        </div>

        <!-- Step 2: Atividade Física -->
        <div class="step" data-step="2">
            <!-- heading shown in stepper; content area contains options only -->
            <div class="nutri-options">
                <?php
                if (!empty($activityLevels)) {
                    foreach ($activityLevels as $lvl) {
                        // garante prefixo tipo 'fas ' caso não exista (fas/far/fab/fal)
                        $iconClass = $lvl->getIcon() ? $lvl->getIcon() : 'fa-running';
                        if (!preg_match('/\b(fas|far|fal|fab)\b/', $iconClass)) { $iconClass = 'fas ' . $iconClass; }
                        $title = htmlspecialchars($lvl->getTitle() ?? '');
                        $desc = htmlspecialchars($lvl->getDescription() ?? '');
                        $mult = $lvl->getMultiplier_factor() ?? '';
                        echo "<div class=\"nutri-option\" tabindex=\"0\" role=\"button\" aria-pressed=\"false\" data-value=\"{$lvl->getId()}\" data-multiplier=\"{$mult}\">";
                        echo "<div class=\"icon-box\"><i class=\"{$iconClass} fa-2x\" aria-hidden=\"true\"></i></div>";
                        echo "<div class=\"content\"><h5>{$title}</h5><small>{$desc}</small></div>";
                        echo "</div>";
                    }
                } else {
                    // fallback options (fixed HTML + icons with prefix)
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="1" data-multiplier="1.2"><div class="icon-box"><i class="fas fa-couch fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Sedentário</h5><small>Pouco ou nenhum exercício</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="2" data-multiplier="1.375"><div class="icon-box"><i class="fas fa-walking fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Levemente Ativo</h5><small>Exercício leve 1-3 dias/semana</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="3" data-multiplier="1.55"><div class="icon-box"><i class="fas fa-running fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Moderadamente Ativo</h5><small>Exercício moderado 3-5 dias/semana</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="4" data-multiplier="1.725"><div class="icon-box"><i class="fas fa-dumbbell fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Muito Ativo</h5><small>Exercício intenso 6-7 dias/semana</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="5" data-multiplier="1.9"><div class="icon-box"><i class="fas fa-fire fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Extremamente Ativo</h5><small>Exercício + trabalho físico</small></div></div>';
                }
                ?>
            </div>
        </div>

        <!-- Step 3: Metas -->
        <div class="step" data-step="3">
            <!-- heading shown in stepper; content area contains options only -->
            <div class="nutri-options">
                <?php
                if (!empty($goals)) {
                    foreach ($goals as $g) {
                        $icon = $g->getIcon() ? $g->getIcon() : 'fa-bullseye';
                        if (!preg_match('/\b(fas|far|fal|fab)\b/', $icon)) { $icon = 'fas ' . $icon; }
                        $title = htmlspecialchars($g->getTitle() ?? '');
                        $desc = htmlspecialchars($g->getDescription() ?? '');
                        $calAdj = $g->getCaloric_adjustment() ?? 0;
                        $prot = $g->getProtein_percentage() ?? null;
                        $carb = $g->getCarbohydrate_percentage() ?? null;
                        $fat = $g->getFat_percentage() ?? null;
                        echo "<div class=\"nutri-option\" tabindex=\"0\" role=\"button\" aria-pressed=\"false\" data-value=\"{$g->getId()}\" data-caloric-adjustment=\"{$calAdj}\" data-protein=\"{$prot}\" data-carbs=\"{$carb}\" data-fat=\"{$fat}\">";
                        echo "<div class=\"icon-box\"><i class=\"{$icon} fa-2x\" aria-hidden=\"true\"></i></div>";
                        echo "<div class=\"content\"><h5>{$title}</h5><small>{$desc}</small></div>";
                        echo "</div>";
                    }
                } else {
                    // fallback
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="1" data-caloric-adjustment="-500" data-protein="30" data-carbs="50" data-fat="20"><div class="icon-box"><i class="fas fa-arrow-down fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Emagrecer</h5><small>Déficit calórico de -500 kcal/dia</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="2" data-caloric-adjustment="0" data-protein="30" data-carbs="50" data-fat="20"><div class="icon-box"><i class="fas fa-balance-scale fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Manter Peso</h5><small>Calorias de manutenção</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="3" data-caloric-adjustment="500" data-protein="25" data-carbs="55" data-fat="20"><div class="icon-box"><i class="fas fa-arrow-up fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Ganhar Peso</h5><small>Superávit calórico de +500 kcal/dia</small></div></div>';
                    echo '<div class="nutri-option" tabindex="0" role="button" aria-pressed="false" data-value="4" data-caloric-adjustment="350" data-protein="35" data-carbs="45" data-fat="20"><div class="icon-box"><i class="fas fa-dumbbell fa-2x" aria-hidden="true"></i></div><div class="content"><h5>Ganhar Massa Muscular</h5><small>Superávit calórico + alto teor proteico</small></div></div>';
                }
                ?>
            </div>
        </div>

        <!-- Step 4: Resultados (visualização antes de salvar) -->
        <div class="step" data-step="4">
            <!-- heading shown in stepper; content area contains calculated results only -->
            <div id="nutriResults">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5>Seu IMC</h5>
                            <div id="resultImc" style="font-size:32px;font-weight:700">-</div>
                            <div id="resultImcLabel" class="text-muted">-</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card p-3">
                            <h5>Calorias Diárias</h5>
                            <div id="resultCalories" style="font-size:32px;font-weight:700">- kcal</div>
                            <div id="resultTmb" class="text-muted">TMB: - kcal</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card p-3 text-center">
                            <div class="text-muted">Proteínas</div>
                            <div id="resultProteins" style="font-size:24px;font-weight:700">- g</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 text-center">
                            <div class="text-muted">Carboidratos</div>
                            <div id="resultCarbs" style="font-size:24px;font-weight:700">- g</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card p-3 text-center">
                            <div class="text-muted">Lipídios</div>
                            <div id="resultFats" style="font-size:24px;font-weight:700">- g</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      </div>
      <div class="modal-footer" style="border-top:0;">
        <button type="button" class="btn btn-secondary" id="nutriBack">← Voltar</button>
        <button type="button" class="btn btn-primary" id="nutriNext">Próximo →</button>
        <button type="button" class="btn btn-success" id="nutriSave" style="display:none;">Salvar Perfil</button>
      </div>
    </div>
  </div>
</div>

<!-- end modal -->

<?php endif; ?>

                        <!-- cards -->
<?php } ?>
                </section>
                <!-- footer start -->
                <?php
                require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php");
                ?>
                <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/assets/js/profile/nutrition-wizard.js"></script>
        <script src="/assets/js/profile/nutrition-wizard-init.js"></script>
        <script src="/assets/js/home/home.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
         <!-- end bottom base html js -->
     </body>
 </html>
