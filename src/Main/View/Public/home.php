<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$language = new Language;
$translate = new Translate();
$config = new McClientConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$planService = new CheckPlan;
$check = $planService->checkPlan();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-theme="dark">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  

        <!-- end top base html css -->
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
                    <i class="fas fa-chart-line mr-2" style="color: var(--primary-green);"></i>
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
                    <i class="fas fa-history mr-2" style="color: var(--primary-green);"></i>
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
                    <i class="fas fa-chart-pie mr-2" style="color: var(--primary-green);"></i>
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
                    <i class="fas fa-bolt mr-2" style="color: var(--primary-green);"></i>
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

<!-- SCRIPT PARA GRÁFICO (Chart.js) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('caloriesChart');
    
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Calorias Consumidas',
                    data: [1800, 2100, 1950, 1850, 2000, 1900, 1850],
                    borderColor: '#2ecc71',
                    backgroundColor: 'rgba(46, 204, 113, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 5,
                    pointBackgroundColor: '#2ecc71',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }, {
                    label: 'Meta Diária',
                    data: [2000, 2000, 2000, 2000, 2000, 2000, 2000],
                    borderColor: '#95a5a6',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/assets/js/home/home.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>