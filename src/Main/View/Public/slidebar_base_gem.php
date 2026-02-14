<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\Language;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\StConfig;
use Microfw\Src\Main\Common\Entity\Public\Company;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

$config = new McClientConfig();
$stConfig = new StConfig;
$language = new Language;
$translate = new Translate();
$stConfig = $stConfig->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);
$website_logo = (isset($stConfig) ? $stConfig->getLogo() : "");
$website_ico = (isset($stConfig) ? "/ico/" . $stConfig->getIco() : "");
$website_title = (isset($stConfig) ? $stConfig->getTitle() : "");

$planService = new CheckPlan;
$check = $planService->checkPlan();

$stCompany = new Company();
$stCompany = $stCompany->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);

$website_title = (isset($stCompany) ? $stCompany->getName_fantasy() : $website_title);
$website_ico = (isset($stCompany) ? "/logo/" . $stCompany->getLogo() : $website_favicon);
?>
<aside class="main-sidebar elevation-4 custom-sidebar">
    <a href="/home" class="brand-link">
        <img src="/assets/img/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">NutriSystem</span>
    </a>

    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="/home" class="nav-link <?php echo ($menu_active === 'home') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Início</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/refeicoes" class="nav-link <?php echo ($menu_active === 'refeicoes') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-utensils"></i>
                        <p>Minhas Refeições</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/consumo" class="nav-link <?php echo ($menu_active === 'consumo') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-apple-alt"></i>
                        <p>Consumos</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="/gastos" class="nav-link <?php echo ($menu_active === 'gastos') ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-running"></i>
                        <p>Gasto Energético</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>