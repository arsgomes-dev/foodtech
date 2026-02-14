<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Food;
use Microfw\Src\Main\Common\Entity\Admin\FoodTable;
use Microfw\Src\Main\Common\Entity\Admin\FoodGroup;
use Microfw\Src\Main\Common\Entity\Admin\FoodBrand;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_foods_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto; margin-bottom: 20px;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("foods", "foods");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">


                <section class="content col-lg-10 offset-lg-1 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("food_edit", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Alimentos', $_SESSION['user_lang'])] = "foods";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Alimento', $_SESSION['user_lang']), $directory, $translate->translate('Alimento', $_SESSION['user_lang']));
                    ?>  
                    <?php
                    if (in_array("food_view", $privilege_types)) {
                        $foodSearch = new Food;
                        $food = new Food;
                        $food = $foodSearch->getQuery(single: true, customWhere: [["column" => "id", "value" => $gets['code']]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <br>
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <form style="margin: 10px;" role="form" name="form_food" id="form_food">
                                            <input type="hidden" name="code" id="code" value="<?php echo $food->getId(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group to_validation to_validation_description">
                                                        <label for="name"><?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="description" name="description" placeholder="<?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?>"  value="<?php echo $food->getDescription(); ?>">
                                                        <div id="to_validation_blank_description" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Alimento (tabela)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control" id="description_table" name="description_table" placeholder="<?php echo $translate->translate('Alimento (table)', $_SESSION['user_lang']); ?>" value="<?php echo $food->getDescription_table(); ?>">
                                                    </div>
                                                </div>              
                                                <div class="col-lg-5 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Código de Barras', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" data-number class="form-control" id="barcode" name="barcode" placeholder="<?php echo $translate->translate('Código de Barras', $_SESSION['user_lang']); ?>" value="<?php echo $food->getEan(); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group to_validation to_validation_grammage_reference">
                                                        <label for="name"><?php echo $translate->translate('Gr de referência', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations floatTwo" id="grammage_reference" name="grammage_reference" placeholder="<?php echo $translate->translate('Gr de referência', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getGrammage_reference() !== null) ? str_replace(".", ",", $food->getGrammage_reference()) : ""; ?>">
                                                        <div id="to_validation_blank_grammage_reference" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>                                 
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Umidade', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="moisture" name="moisture" placeholder="<?php echo $translate->translate('Umidade', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getMoisture() !== null) ? str_replace(".", ",", $food->getMoisture()) : ""; ?>">
                                                    </div>
                                                </div>                     
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group to_validation to_validation_kcal">
                                                        <label for="name"><?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations floatTwo" id="kcal" name="kcal" placeholder="<?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?>"  value="<?php echo($food->getKcal() !== null) ? str_replace(".", ",", $food->getKcal()) : ""; ?>">
                                                        <div id="to_validation_blank_kcal" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>      
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Kj', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="kj" name="kj" placeholder="<?php echo $translate->translate('Kj', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getKj() !== null) ? str_replace(".", ",", $food->getKj()) : ""; ?>">
                                                    </div>
                                                </div>      
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Proteínas (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="protein" name="protein" placeholder="<?php echo $translate->translate('Proteínas (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getProtein_g() !== null) ? str_replace(".", ",", $food->getProtein_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Lipídios (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="lipid" name="lipid" placeholder="<?php echo $translate->translate('Lipídios (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getLipid_g() !== null) ? str_replace(".", ",", $food->getLipid_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Carboidratos (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="carbohydrate" name="carbohydrate" placeholder="<?php echo $translate->translate('Carboidratos (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getCarbohydrate_g() !== null) ? str_replace(".", ",", $food->getCarbohydrate_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Fibras (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="fiber" name="fiber" placeholder="<?php echo $translate->translate('Fibras (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getFiber_g() !== null) ? str_replace(".", ",", $food->getFiber_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Colesterol (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="cholesterol" name="cholesterol" placeholder="<?php echo $translate->translate('Colesterol (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getCholesterol_mg() !== null) ? str_replace(".", ",", $food->getCholesterol_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cinzas (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="ashes" name="ashes" placeholder="<?php echo $translate->translate('Cinzas (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getAshes_g() !== null) ? str_replace(".", ",", $food->getAshes_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg saturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="acg_saturated" name="acg_saturated" placeholder="<?php echo $translate->translate('Acg saturados (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getAcg_saturated_g() !== null) ? str_replace(".", ",", $food->getAcg_saturated_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg monoinsaturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="acg_monounsaturated" name="acg_monounsaturated" placeholder="<?php echo $translate->translate('Acg monoinsaturados (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getAcg_monounsaturated_g() !== null) ? str_replace(".", ",", $food->getAcg_monounsaturated_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg poliinsaturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="acg_polyunsaturated" name="acg_polyunsaturated" placeholder="<?php echo $translate->translate('Acg poliinsaturados (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getAcg_polyunsaturated_g() !== null) ? str_replace(".", ",", $food->getAcg_polyunsaturated_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg trans (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="acg_trans" name="acg_trans" placeholder="<?php echo $translate->translate('Acg trans (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getAcg_trans_g() !== null) ? str_replace(".", ",", $food->getAcg_trans_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Açúcar total (g)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="total_sugar" name="total_sugar" placeholder="<?php echo $translate->translate('Açúcar total (g)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getTotal_sugar_g() !== null) ? str_replace(".", ",", $food->getTotal_sugar_g()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cálcio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="calcium" name="calcium" placeholder="<?php echo $translate->translate('Cálcio (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getCalcium_mg() !== null) ? str_replace(".", ",", $food->getCalcium_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Magnésio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="magnesium" name="magnesium" placeholder="<?php echo $translate->translate('Magnésio (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getMagnesium_mg() !== null) ? str_replace(".", ",", $food->getMagnesium_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Manganês (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="manganese" name="manganese" placeholder="<?php echo $translate->translate('Manganês (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getManganese_mg() !== null) ? str_replace(".", ",", $food->getManganese_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Fósforo (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="phosphor" name="phosphor" placeholder="<?php echo $translate->translate('Fósforo (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getPhosphor_mg() !== null) ? str_replace(".", ",", $food->getPhosphor_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Ferro (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="iron" name="iron" placeholder="<?php echo $translate->translate('Ferro (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getIron_mg() !== null) ? str_replace(".", ",", $food->getIron_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Sódio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="sodium" name="sodium" placeholder="<?php echo $translate->translate('Sódio (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getSodium_mg() !== null) ? str_replace(".", ",", $food->getSodium_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Potássio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="potassium" name="potassium" placeholder="<?php echo $translate->translate('Potássio (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getPotassium_mg() !== null) ? str_replace(".", ",", $food->getPotassium_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cobre (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="copper" name="copper" placeholder="<?php echo $translate->translate('Cobre (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getCopper_mg() !== null) ? str_replace(".", ",", $food->getCopper_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Zinco (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="zinc" name="zinc" placeholder="<?php echo $translate->translate('Zinco (mg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getZinc_mg() !== null) ? str_replace(".", ",", $food->getZinc_mg()) : ""; ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Selênio (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control floatTwo" id="selenium" name="selenium" placeholder="<?php echo $translate->translate('Selênio (mcg)', $_SESSION['user_lang']); ?>"  value="<?php echo ($food->getSelenium_mcg() !== null) ? str_replace(".", ",", $food->getSelenium_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Retinol (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="retinol" name="retinol" placeholder="<?php echo $translate->translate('Retinol (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getRetinol_mcg() !== null) ? str_replace(".", ",", $food->getRetinol_mcg()) : ""; ?>">
                                                    </div>
                                                </div>                                                
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('RE (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="re" name="re" placeholder="<?php echo $translate->translate('RE (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getRe_mcg() !== null) ? str_replace(".", ",", $food->getRe_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina A (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="rae" name="rae" placeholder="<?php echo $translate->translate('Vitamina A (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getRae_mcg() !== null) ? str_replace(".", ",", $food->getRae_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B1 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="thiamin" name="thiamin" placeholder="<?php echo $translate->translate('Vitamina B1 (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getThiamin_mg() !== null) ? str_replace(".", ",", $food->getThiamin_mg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B2 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="riboflavin" name="riboflavin" placeholder="<?php echo $translate->translate('Vitamina B2 (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getRiboflavin_mg() !== null) ? str_replace(".", ",", $food->getRiboflavin_mg()) : ""; ?>">
                                                    </div>
                                                </div>                           
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B3 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="niacin" name="niacin" placeholder="<?php echo $translate->translate('Vitamina B3 (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getNiacin_mg() !== null) ? str_replace(".", ",", $food->getNiacin_mg()) : ""; ?>">
                                                    </div>
                                                </div>                                              
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B6 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="pyridoxine" name="pyridoxine" placeholder="<?php echo $translate->translate('Vitamina B6 (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getPyridoxine_mg() !== null) ? str_replace(".", ",", $food->getPyridoxine_mg()) : ""; ?>">
                                                    </div>
                                                </div>           
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B9 (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="folate" name="folate" placeholder="<?php echo $translate->translate('Vitamina B9 (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getFolate_mcg() !== null) ? str_replace(".", ",", $food->getFolate_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B12 (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="cobalamin" name="cobalamin" placeholder="<?php echo $translate->translate('Vitamina B12 (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getCobalamin_mcg() !== null) ? str_replace(".", ",", $food->getCobalamin_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina C (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="vitamin_c" name="vitamin_c" placeholder="<?php echo $translate->translate('Vitamina C (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getVitamin_c_mg() !== null) ? str_replace(".", ",", $food->getVitamin_c_mg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina D (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="calciferol" name="calciferol" placeholder="<?php echo $translate->translate('Vitamina D (mcg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getCalciferol_mcg() !== null) ? str_replace(".", ",", $food->getCalciferol_mcg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina E (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="vitamin_e" name="vitamin_e" placeholder="<?php echo $translate->translate('Vitamina E (mg)', $_SESSION['user_lang']); ?>" value="<?php echo ($food->getVitamin_e_mg() !== null) ? str_replace(".", ",", $food->getVitamin_e_mg()) : ""; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                                    <div class="form-group">
                                                        <label for="table_new"><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?> *</label>
                                                        <select <?php echo $edit; ?> class="custom-select to_validations" id="table" name="table">
                                                            <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                            <?php
                                                            $tableSearch = new FoodTable;
                                                            $tables = new FoodTable;
                                                            $tables = $tableSearch->getQuery(order: "description ASC");
                                                            $tablesCount = count($tables);
                                                            if ($tablesCount > 0) {
                                                                $table = new FoodTable;
                                                                for ($i = 0; $i < $tablesCount; $i++) {
                                                                    $table = $tables[$i];
                                                                    $selected = ($table->getId() === $food->getFood_table_id()) ? "selected" : "";
                                                                    ?>
                                                                    <option <?php echo $selected; ?>  value="<?php echo $table->getId(); ?>"><?php echo $table->getDescription(); ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div id="to_validation_blank_table" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                                    <div class="form-group">
                                                        <label for="table_new"><?php echo $translate->translate('Grupo', $_SESSION['user_lang']); ?> *</label>
                                                        <select <?php echo $edit; ?> class="custom-select to_validations" id="group" name="group">
                                                            <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                            <?php
                                                            $groupSearch = new FoodGroup;
                                                            $groups = new FoodGroup;
                                                            $groups = $groupSearch->getQuery(order: "description ASC");
                                                            $groupsCount = count($groups);
                                                            if ($groupsCount > 0) {
                                                                $group = new FoodGroup;
                                                                for ($i = 0; $i < $groupsCount; $i++) {
                                                                    $group = $groups[$i];
                                                                    $selected = ($group->getId() === $food->getFood_group_id()) ? "selected" : "";
                                                                    ?>
                                                                    <option <?php echo $selected; ?>  value="<?php echo $group->getId(); ?>"><?php echo $group->getDescription(); ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>

                                                        <div id="to_validation_blank_group" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">                                                        
                                                    <div class="form-group">
                                                        <label for="table_new"><?php echo $translate->translate('Marca', $_SESSION['user_lang']); ?></label>
                                                        <select <?php echo $edit; ?> class="custom-select" id="brand" name="brand">
                                                            <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                            <?php
                                                            $brandSearch = new FoodBrand;
                                                            $brands = new FoodBrand;
                                                            $brands = $brandSearch->getQuery(order: "description ASC");
                                                            $brandsCount = count($brands);
                                                            if ($brandsCount > 0) {
                                                                $brand = new FoodBrand;
                                                                for ($i = 0; $i < $brandsCount; $i++) {
                                                                    $brand = $brands[$i];
                                                                    $selected = ($brand->getId() === $food->getFood_brand_id()) ? "selected" : "";
                                                                    ?>
                                                                    <option <?php echo $selected; ?>  value="<?php echo $brand->getId(); ?>"><?php echo $brand->getDescription(); ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>     
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top">
                                        <button type="button" class="btn btn-default btn-register" name="save" onclick="updateFood(form_food);"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/foods" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title col-lg-9 col-sm-12"> <?php echo $translate->translate('Medidas Caseiras', $_SESSION['user_lang']); ?></h3>
                                        <div class="card-tools col-lg-3 col-sm-12">                
                                            <div class="input-group input-group-sm float-left">
                                                <div class="input-group-append btn-filter-new">
                                                    <button type="button" class="btn btn-default float-left btn-filter-new-btn" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>"  data-toggle="modal" data-target=".search-modal">
                                                        <i class="fas fa-filter"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-default btn-flat float-left btn-filter-new-btn" title="<?php echo $translate->translate('Novo', $_SESSION['user_lang']); ?>"  data-toggle="modal" data-target=".new-modal">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">                                           
                                        <div id="list"></div>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top" id=pagination></div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div class="modal fade search-modal" id="search-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">         
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Ordenar por', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">

                                                <select class="form-control form-control-md" style="width: 100%;" name="ord" id="ord">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <optgroup label="<?php echo $translate->translate(' medida Caseira', $_SESSION['user_lang']); ?>">
                                                        <option value='1'><?php echo $translate->translate('Crescente', $_SESSION['user_lang']); ?></option>
                                                        <option value='2'><?php echo $translate->translate('Decrescente', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                    <optgroup label="<?php echo $translate->translate('Data', $_SESSION['user_lang']); ?>">
                                                        <option value='3' selected><?php echo $translate->translate('Mais Recente', $_SESSION['user_lang']); ?></option>
                                                        <option value='4'><?php echo $translate->translate('Mais Antigo', $_SESSION['user_lang']); ?></option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>   
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Medida Caseira', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="measure" name="measure" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Medida Caseira', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>                       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadFoodsMeasures();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <?php
                        if (in_array("food_create", $privilege_types) || in_array("food_create", $privilege_types)) {
                            ?>
                            <!-- Modal -->
                            <div class="modal fade new-modal measure_create" id="measure-create" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(form_measure);">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form style="margin: 10px;" role="form" name="form_measure" id="form_measure">
                                                <input type="hidden" name="code" id="code" value="<?php echo $food->getId(); ?>">
                                                <div class="row">
                                                    <div class="col-lg-12 col-sm-12">
                                                        <div class="form-group to_validation to_validation_descriptionMeasure">
                                                            <label for="name"><?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations" id="descriptionMeasure" name="descriptionMeasure" placeholder="<?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_descriptionMeasure" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                        <div class="form-group to_validation to_validation_quantitiesMeasure">
                                                            <label for="name"><?php echo $translate->translate('Quantidade (gr/ml)', $_SESSION['user_lang']); ?> *</label>
                                                            <input type="text" class="form-control to_validations floatTwo" id="quantitiesMeasure" name="quantitiesMeasure" placeholder="<?php echo $translate->translate('Quantidade (g/ml)', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_quantitiesMeasure" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>                  
                                                    </div>
                                                </div>  
                                            </form>
                                            <span style="font-size: 13px;"><b><?php echo $translate->translate('Campo Obrigatório', $_SESSION['user_lang']); ?> *</b></span>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" onclick="saveMeasures(form_measure);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(form_measure);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        if (in_array("food_edit", $privilege_types) || in_array("food_create", $privilege_types)) {
                            ?>
                            <div class="modal fade update-modal measure_update" id="measure-update" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(form_update_measure);">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <form style="margin: 10px;" role="form" name="form_update_measure" id="form_update_measure">
                                                <input type="hidden" name="code" id="code" value="<?php echo $food->getId(); ?>">
                                                <input type="hidden" id="codeMeasure" name="codeMeasure">
                                                <div class="row">
                                                    <div class="col-lg-12 col-sm-12">
                                                        <div class="form-group to_validation to_validation_descriptionMeasure">
                                                            <label for="name"><?php echo $translate->translate('Medida Caseira', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control to_validations" id="descriptionMeasure_edit" name="descriptionMeasure" placeholder="<?php echo $translate->translate('Medida Caseira', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_descriptionMeasure_edit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                        <div class="form-group to_validation to_validation_quantitiesMeasure">
                                                            <label for="name"><?php echo $translate->translate('Quantidade (gr/ml)', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control to_validations floatTwo" id="quantitiesMeasure_edit" name="quantitiesMeasure" placeholder="<?php echo $translate->translate('Quantidade (g/ml)', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_quantitiesMeasure_edit" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div> 
                                                    </div>
                                                </div>  
                                            </form>
                                            <span style="font-size: 13px;"><b><?php echo $translate->translate('Campo Obrigatório', $_SESSION['user_lang']); ?> *</b></span>
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" onclick="saveMeasures(form_update_measure);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(form_update_measure);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END Modal -->
                            <?php
                        }
                        ?>
                        <?php
                    } else {
                        ?>
                        <div class="content-header">
                            <div class="container-fluid">
                                <div class="alert alert-warning alert-dismissible">
                                    <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                                    <?php
                                    echo $translate->translate('Você não tem permissão para visualizar esta página!', $_SESSION['user_lang']);
                                    ?>
                                    </font>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </section>
            </div>
            <!-- footer start -->
            <?php
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
            ?>
            <!-- footer end -->
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <?php
        if (in_array("food_view", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>

            <?php
            if (in_array("food_edit", $privilege_types)) {
                ?>
                <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
                <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
                <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
                <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
                <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
                <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
                <script src="/libs/v1/admin/js/general/foods/update/food.js"></script>
                <?php
            }
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>