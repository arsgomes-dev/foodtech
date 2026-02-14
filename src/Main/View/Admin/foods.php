<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\FoodGroup;
use Microfw\Src\Main\Common\Entity\Admin\FoodBrand;
use Microfw\Src\Main\Common\Entity\Admin\FoodTable;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!doctype html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <?php echo $baseHtml->baseCSSICheck(); ?>  
        <?php echo $baseHtml->baseCSSValidate(); ?>  
        <?php echo $baseHtml->baseCSSDate(); ?>          
        <?php echo $baseHtml->baseCSSAlert(); ?>  
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("foods", "foods");
            ?>
            <div class="content-wrapper" style="min-height: auto !important;">


                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory["Home"] = "home";
                    echo $baseHtml->baseBreadcrumb($translate->translate("Alimentos", $_SESSION['user_lang']), $directory, $translate->translate("Alimentos", $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->

                    <?php
                    if (in_array("food_view", $privilege_types)) {
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <button aria-label="Close" type="button" class="btn btn-default btn-i-color btn-filter" title="<?php echo $translate->translate('Filtro', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".search-modal">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <button id="btn-clean-filter" style="display: none;" onclick="cleanSearch();" type="button" class="btn btn-default btn-i-color-danger btn-filter" title="<?php echo $translate->translate('Limpar Filtro', $_SESSION['user_lang']); ?>">
                                    <i class="fas fa-filter-circle-xmark"></i>
                                </button>
                            </div>
                            <div class="col-lg-4 col-sm-12 d-flex flex-column justify-content-center">
                                <?php
                                if (in_array("food_edit", $privilege_types)) {
                                    ?>
                                    <button onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/foods/create"; ?>'" aria-label="Close" type="button" class="btn btn-block btn-success btn-color" title="<?php echo $translate->translate('Adicionar Alimento', $_SESSION['user_lang']); ?>">
                                        <i class="fa fa-plus"></i> <?php echo $translate->translate('Adicionar Alimento', $_SESSION['user_lang']); ?>
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                        <br>
                        <div class="card card-border-radius" style="margin-bottom: 40px !important;">
                            <div class="card-body">           
                                <div id="list" style="overflow-x: auto;"></div>
                            </div>
                            <div class="card-footer card-footer-transparent" id="pagination"></div>
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
                                    <div class="modal-body" style="overflow-y: auto;">     
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
                                                    <optgroup label="<?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?>">
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
                                                <h3 class="card-title"><b><?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <input type="text" id="food_name" name="food_name" class="form-control form-control-md" placeholder="<?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?>">
                                            </div>
                                            <!-- /.card-body -->
                                        </div>     

                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Grupo', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <select class="form-control form-control-md" style="width: 100%;" name="group_search" id="group_search">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $groupSearch = new FoodGroup;
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
                                            </div>
                                            <!-- /.card-body -->
                                        </div>

                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Marca', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <select class="form-control form-control-md" style="width: 100%;" name="brand_search" id="brand_search">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $brandsSearch = new FoodBrand;
                                                    $brands = new FoodBrand;
                                                    $brands = $brandsSearch->getQuery(order: "description ASC");
                                                    $brandsCount = count($brands);
                                                    if ($groupsCount > 0) {
                                                        $brand = new FoodGroup;
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
                                            <!-- /.card-body -->
                                        </div>

                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <select class="form-control form-control-md" style="width: 100%;" name="table_search" id="table_search">
                                                    <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                    <?php
                                                    $tablesSearch = new FoodTable;
                                                    $tables = $tablesSearch->getQuery(order: "description ASC");
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
                                            </div>
                                            <!-- /.card-body -->
                                        </div>
                                        <div class="card card-outline">
                                            <div class="card-header">
                                                <h3 class="card-title"><b><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?></b></h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" style="margin: 0px !important;">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                                <!-- /.card-tools -->
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body" style="display: block;">
                                                <div class="icheck-success">
                                                    <input type="radio" id="status1" name="status" value="1"/>
                                                    <label for="status1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></label>
                                                </div>
                                                <div class="icheck-danger" style="margin-top: 15px !important;">
                                                    <input type="radio" id="status2" name="status" value="2"/>
                                                    <label for="status2"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></label>
                                                </div>
                                                <div class="icheck-default" style="margin-top: 15px !important;">
                                                    <input type="radio" id="status3" name="status" value="" checked/>
                                                    <label for="status3"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?></label>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                        </div>       
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="loadBtnFoods();"><?php echo $translate->translate('Filtrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-light" onclick="cleanSearch();"><?php echo $translate->translate('Limpar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <!-- END Modal -->
                        <?php
                        if (in_array("food_create", $privilege_types)) {
                            ?>
                            <!-- modal new -->
                            <div class="modal fade food-create" id="food-create" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title"><?php echo $translate->translate('Novo Alimento', $_SESSION['user_lang']); ?></h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="cleanForm(new_food);">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form name="new_food" id="new_food" autocomplete="off">
                                                <div class="row">
                                                    <div class="col-lg-6 col-sm-12">
                                                        <div class="form-group to_validation to_validation_description">
                                                            <label for="name"><?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control to_validations" id="description" name="description" placeholder="<?php echo $translate->translate('Alimento', $_SESSION['user_lang']); ?>">
                                                            <div id="to_validation_blank_description" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-sm-12">
                                                        <div class="form-group">
                                                            <label for="name"><?php echo $translate->translate('Alimento (tabela)', $_SESSION['user_lang']); ?></label>
                                                            <input type="text" class="form-control" id="description_table" name="description_table" placeholder="<?php echo $translate->translate('Alimento (table)', $_SESSION['user_lang']); ?>">
                                                        </div>
                                                    </div>                                       
                                                    <div class="col-lg-3 col-sm-12">
                                                        <div class="form-group to_validation to_validation_grammage_reference">
                                                            <label for="name"><?php echo $translate->translate('Gr de referência', $_SESSION['user_lang']); ?></label>
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
                                                            <label for="name"><?php echo $translate->translate('Kcal', $_SESSION['user_lang']); ?></label>
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
                                                    <div class="col-lg-4 col-sm-12 to_validation">                                                        
                                                        <div class="form-group">
                                                            <label for="table_new"><?php echo $translate->translate('Tabela', $_SESSION['user_lang']); ?></label>
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
                                                            <label for="table_new"><?php echo $translate->translate('Grupo', $_SESSION['user_lang']); ?></label>
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
                                        </div>
                                        <div class="modal-footer justify-content-between">
                                            <button type="button" class="btn btn-default btn-register" onclick="createFood(new_food);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                            <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(new_food);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- end modal -->
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
            <script src="/libs/v1/admin/js/general/foods/lists/foods.js"></script>
            <?php
        }
        if (in_array("food_create", $privilege_types) || in_array("food_create", $privilege_types)) {
            ?>
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