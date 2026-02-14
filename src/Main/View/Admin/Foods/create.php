<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;
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


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("food_edit", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Alimentos', $_SESSION['user_lang'])] = "foods";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Novo Alimento', $_SESSION['user_lang']), $directory, $translate->translate('Novo Alimento', $_SESSION['user_lang']));
                    ?>  
                    <?php
                    if (in_array("food_create", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <br>
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <form name="new_food" id="new_food" autocomplete="off">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12">
                                                    <div class="form-group to_validation to_validation_description">
                                                        <label for="name"><?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?> *</label>
                                                        <input type="text" class="form-control to_validations" id="description" name="description" placeholder="<?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_description" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Alimento (tabela)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" class="form-control" id="description_table" name="description_table" placeholder="<?php echo $translate->translate('Alimento (tabela)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>     
                                                <div class="col-lg-5 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Código de Barras', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" data-number class="form-control" id="barcode" name="barcode" placeholder="<?php echo $translate->translate('Código de Barras', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>                                       
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group to_validation to_validation_grammage_reference">
                                                        <label for="name"><?php echo $translate->translate('Gr de referência', $_SESSION['user_lang']); ?> *</label>
                                                        <input type="text" step="0.010" class="form-control to_validations floatTwo" id="grammage_reference" name="grammage_reference" placeholder="<?php echo $translate->translate('Gr de referência', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_grammage_reference" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>                                 
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Umidade', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="moisture" name="moisture" placeholder="<?php echo $translate->translate('Umidade', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>                     
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group to_validation to_validation_kcal">
                                                        <label for="name"><?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?> *</label>
                                                        <input type="text" step="0.010" class="form-control to_validations floatTwo" id="kcal" name="kcal" placeholder="<?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_kcal" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>      
                                                <div class="col-lg-3 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Kj', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="kj" name="kj" placeholder="<?php echo $translate->translate('Kj', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>      
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Proteínas (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="protein" name="protein" placeholder="<?php echo $translate->translate('Proteínas (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Lipídios (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="lipid" name="lipid" placeholder="<?php echo $translate->translate('Lipídios (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Carboidratos (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="carbohydrate" name="carbohydrate" placeholder="<?php echo $translate->translate('Carboidratos (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Fibras (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="fiber" name="fiber" placeholder="<?php echo $translate->translate('Fibras (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Colesterol (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="cholesterol" name="cholesterol" placeholder="<?php echo $translate->translate('Colesterol (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cinzas (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="ashes" name="ashes" placeholder="<?php echo $translate->translate('Cinzas (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg saturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="acg_saturated" name="acg_saturated" placeholder="<?php echo $translate->translate('Acg saturados (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg monoinsaturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="acg_monounsaturated" name="acg_monounsaturated" placeholder="<?php echo $translate->translate('Acg monoinsaturados (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg poliinsaturados (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="acg_polyunsaturated" name="acg_polyunsaturated" placeholder="<?php echo $translate->translate('Acg poliinsaturados (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Acg trans (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="acg_trans" name="acg_trans" placeholder="<?php echo $translate->translate('Acg trans (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Açúcar total (g)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="total_sugar" name="total_sugar" placeholder="<?php echo $translate->translate('Açúcar total (g)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cálcio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="calcium" name="calcium" placeholder="<?php echo $translate->translate('Cálcio (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Magnésio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="magnesium" name="magnesium" placeholder="<?php echo $translate->translate('Magnésio (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Manganês (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="manganese" name="manganese" placeholder="<?php echo $translate->translate('Manganês (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Fósforo (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="phosphor" name="phosphor" placeholder="<?php echo $translate->translate('Fósforo (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Ferro (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="iron" name="iron" placeholder="<?php echo $translate->translate('Ferro (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Sódio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="sodium" name="sodium" placeholder="<?php echo $translate->translate('Sódio (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Potássio (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="potassium" name="potassium" placeholder="<?php echo $translate->translate('Potássio (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Cobre (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="copper" name="copper" placeholder="<?php echo $translate->translate('Cobre (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Zinco (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="zinc" name="zinc" placeholder="<?php echo $translate->translate('Zinco (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div> 
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Selênio (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="selenium" name="selenium" placeholder="<?php echo $translate->translate('Selênio (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Retinou (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="retinou" name="retinou" placeholder="<?php echo $translate->translate('Retinou (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>                                                
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('RE (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="re" name="re" placeholder="<?php echo $translate->translate('RE (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina A (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="rae" name="rae" placeholder="<?php echo $translate->translate('Vitamina A (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B1 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="thiamin" name="thiamin" placeholder="<?php echo $translate->translate('Vitamina B1 (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B2 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="riboflavin" name="riboflavin" placeholder="<?php echo $translate->translate('Vitamina B2 (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>                           
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B3 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="niacin" name="niacin" placeholder="<?php echo $translate->translate('Vitamina B3 (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>                                              
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B6 (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="pyridoxine" name="pyridoxine" placeholder="<?php echo $translate->translate('Vitamina B6 (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>           
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B9 (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="folate" name="folate" placeholder="<?php echo $translate->translate('Vitamina B9 (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina B12 (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="cobalamin" name="cobalamin" placeholder="<?php echo $translate->translate('Vitamina B12 (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina C (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="vitamin_c" name="vitamin_c" placeholder="<?php echo $translate->translate('Vitamina C (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina D (mcg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="calciferol" name="calciferol" placeholder="<?php echo $translate->translate('Vitamina D (mcg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12">
                                                    <div class="form-group">
                                                        <label for="name"><?php echo $translate->translate('Vitamina E (mg)', $_SESSION['user_lang']); ?></label>
                                                        <input type="text" step="0.010" class="form-control floatTwo" id="vitamin_c" name="vitamin_c" placeholder="<?php echo $translate->translate('Vitamina E (mg)', $_SESSION['user_lang']); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                                    <div class="form-group">
                                                        <label for="table_new"><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?> *</label>
                                                        <select class="custom-select to_validations" id="table" name="table">
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
                                                                    ?>
                                                                    <option value="<?php echo $table->getId(); ?>"><?php echo $table->getDescription(); ?></option>
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
                                                        <select class="custom-select to_validations" id="group" name="group">
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
                                                                    ?>
                                                                    <option value="<?php echo $group->getId(); ?>"><?php echo $group->getDescription(); ?></option>
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
                                                        <select class="custom-select" id="brand" name="brand">
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
                                                                    ?>
                                                                    <option value="<?php echo $brand->getId(); ?>"><?php echo $brand->getDescription(); ?></option>
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
                                        <button type="button" class="btn btn-default btn-register" name="save" onclick="createFood(new_food);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/foods" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        if (in_array("food_create", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
            <script src="/libs/v1/admin/plugins/inputmask/jquery.inputmask.min.js"></script>
            <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
            <script src="/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
            <script src="/libs/v1/admin/js/general/foods/create/food.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>