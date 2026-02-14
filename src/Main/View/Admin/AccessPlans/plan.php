<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\AccessPlan;
use Microfw\Src\Main\Common\Entity\Admin\Currency;

$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_access_plans_active = "active";
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;">

    <head>
        <!-- start top base html css -->
        <?php echo $baseHtml->baseCSS(); ?>  
        <link rel="stylesheet" href="/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css">
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>
        <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/css/accessPlan.css'>
        <!-- end top base html css -->
    </head>
    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">
        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("access_plans", "access_plans");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("access_plans_edit", $privilege_types)) ? "" : "disabled";
                    $editDescription = (in_array("access_plans_edit", $privilege_types)) ? "" : false;
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Planos de Acesso', $_SESSION['user_lang'])] = "accessPlans";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Plano de Acesso', $_SESSION['user_lang']), $directory, $translate->translate('Plano de Acesso', $_SESSION['user_lang']));
                    ?>  
                    <?php
                    if (in_array("access_plans_view", $privilege_types)) {
                        $access_planSearch = new AccessPlan;
                        $access_plan = new AccessPlan;
                        $access_plan = $access_planSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $gets['code']]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                        <br>
                        <div class="row" style="margin-bottom: 40px !important;">
                            <div class="col-lg-7 col-sm-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <form style="margin: 10px;" role="form" name="form_access_plan" id="form_access_plan">
                                            <input type="hidden" name="code" id="code" value="<?php echo $access_plan->getId(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="title"><?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?> *</label>
                                                        <input type="text" class="form-control to_validations" name="title" id="title" placeholder="<?php echo $translate->translate('Plano', $_SESSION['user_lang']); ?>" <?php echo $edit; ?> value="<?php echo $access_plan->getTitle(); ?>">
                                                        <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="description">
                                                            <?php echo $translate->translate('Descrição', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <div id="div_description_elements" class="col-lg-12 col-sm-12 description_elements" onclick="mouseClickDescription();">
                                                               <?php
                                                                $desc = explode(";", $access_plan->getDescription());
                                                                $desc_count = count($desc);
                                                                ?>
                                                            <input type="hidden" class = "to_validations" id="description_element_count" name="description_element_count" value="<?php echo $desc_count; ?>">
                                                            <div id="description_elements" class="row col-lg-12">
                                                                <?php
                                                                for ($i = 0; $i < $desc_count; $i++) {
                                                                    echo '<div id="div_description_element_' . $i . '" class = "description_element">';
                                                                    echo '<input type="hidden" name="descriptions_elements[]" id="descriptions_elements[]" value="' . $desc[$i] . '">';
                                                                    echo '<input type="hidden" name="descriptions_elements_count[]" id="descriptions_elements_count[]" value="' . $i . '">';
                                                                    echo '<font>' . $desc[$i] . '&nbsp;&nbsp;</font>';
                                                                    if ($editDescription !== false) {
                                                                        echo '<a title="' . $translate->translate('Remover', $_SESSION['user_lang']) . '" id="div_description_element_' . $i . '" href="javascript:void(0)" onclick="deleteDescriptionElement(this);"><i class="nav-icon fas fa-xmark"></i></a>';
                                                                    }
                                                                    echo '</div>';
                                                                }
                                                                ?>
                                                            </div>          
                                                            <?php
                                                            if ($editDescription !== false) {
                                                                ?>
                                                                <input type="text" class="form-control description_element_input" id="description_element" name="description_element"> 
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                        <span style="font-size: 11px;"><?php echo $translate->translate('Tecle ENTER após cada tag', $_SESSION['user_lang']); ?></span>
                                                        <div id="to_validation_blank_description_element_count" style="display: none;" class="to_blank error invalid-feedback"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>   
                                                </div>
                                                <div class="col-lg-12 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="observ">
                                                            <?php echo $translate->translate('Observações', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <textarea <?php echo $edit; ?> class="form-control to_validations" name="observ" id="observ" placeholder="<?php echo $translate->translate('observações', $_SESSION['user_lang']); ?>"><?php echo $access_plan->getObservation(); ?></textarea>
                                                        <div id="to_validation_blank_observ" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="ribbon"><?php echo $translate->translate('Fita de Promoção', $_SESSION['user_lang']); ?></label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control" id="ribbon" name="ribbon" placeholder="<?php echo $translate->translate('Ex.: Promoção', $_SESSION['user_lang']); ?>" value="<?php echo $access_plan->getRibbon_tag(); ?>">
                                                        <div id="to_validation_blank_ribbon" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nFoods">
                                                            <?php echo $translate->translate('Número de Alimentos/Refeição', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nFoods" name="nFoods" placeholder="<?php echo $translate->translate('Número de Alimentos/Refeição', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off" value="<?php echo $access_plan->getMax_foods(); ?>">
                                                        <div id="to_validation_blank_nFoods" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nMeals">
                                                            <?php echo $translate->translate('Número de Refeições/Dia', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nMeals" name="nMeals" placeholder="<?php echo $translate->translate('Número de Refeições/Dia', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off" value="<?php echo $access_plan->getMax_meals_daily(); ?>">
                                                        <div id="to_validation_blank_nMeals" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nReports">
                                                            <?php echo $translate->translate('Exportar Cardápio', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nReports" name="nReports" placeholder="<?php echo $translate->translate('Exportar Cardápio', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off" value="<?php echo $access_plan->getReports_enabled(); ?>">
                                                        <div id="to_validation_blank_nReports" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="nExport">
                                                            <?php echo $translate->translate('Exportar Dados', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="nExport" name="nExport" placeholder="<?php echo $translate->translate('Exportar Dados', $_SESSION['user_lang']); ?>" inputmode="numeric" autocomplete="off" value="<?php echo $access_plan->getReports_enabled(); ?>">
                                                        <div id="to_validation_blank_nExport" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="price">
                                                            <?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" class="form-control to_validations" id="price" name="price" data-currency="<?php echo $_SESSION['user_currency']; ?>" data-locale="<?php echo str_replace("_", "-", $_SESSION['user_currency_locale']); ?>" placeholder="<?php echo $_SESSION['user_currency_placeholder']; ?>" inputmode="numeric" autocomplete="off" value="<?php echo $translate->translateMonetary($access_plan->getPrice(), $_SESSION['user_currency'], $_SESSION['user_currency_locale']); ?>">
                                                        <div id="to_validation_blank_price" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="durationn"><?php echo $translate->translate('Duração (dias)', $_SESSION['user_lang']); ?> *</label>
                                                        <input <?php echo $edit; ?> type="text" data-number class="form-control to_validations" id="validat" name="validat" placeholder="<?php echo $translate->translate('Duração (dias)', $_SESSION['user_lang']); ?>" value="<?php echo $access_plan->getValidation(); ?>">
                                                        <div id="to_validation_blank_validat" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="start">
                                                            <?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <?php
                                                        $start_date = $translate->translateDate($access_plan->getDate_start(), $_SESSION['user_lang']);
                                                        ?>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="start" name="start" placeholder="<?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?>" value="<?php echo $start_date; ?>">
                                                        <div id="to_validation_blank_start" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="end">
                                                            <?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <?php
                                                        $end_date = $translate->translateDate($access_plan->getDate_end(), $_SESSION['user_lang']);
                                                        ?>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="end" name="end" placeholder="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>" value="<?php echo $end_date; ?>">
                                                        <div id="to_validation_blank_end" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="status"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                        <?php
                                                        $statusInactive = ($access_plan->getStatus() === 0) ? "selected" : "";
                                                        $statusActive = ($access_plan->getStatus() === 1) ? "selected" : "";
                                                        ?>
                                                        <select <?php echo $edit; ?> class="custom-select to_validations" id="sts" name="sts">
                                                            <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                            <option value="1" <?php echo $statusActive; ?>><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                            <option value="0" <?php echo $statusInactive; ?>><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                        </select>
                                                        <div id="to_validation_blank_sts" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campos Obrigatórios', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>

                                    <div class="card-footer card-footer-transparent justify-content-between border-top">
                                        <?php
                                        if (in_array("access_plans_edit", $privilege_types)) {
                                            ?>
                                            <button type="button" class="btn btn-default btn-register" name="save" onclick="updateAccessPlan(form_access_plan);"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></button>
                                        <?php } ?>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/accessPlans" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title col-lg-8 col-sm-12 title-filter-new"> <?php echo $translate->translate('Preços por Promoção', $_SESSION['user_lang']); ?></h3>
                                        <div class="card-tools col-lg-4 col-sm-12 card-filter-new">                
                                            <div class="input-group input-group-sm float-left">
                                                <div class="input-group-append btn-filter-new">
                                                    <button type="button" class="btn btn-block btn-success btn-color" title="<?php echo $translate->translate('Adicionar Plano', $_SESSION['user_lang']); ?>" data-toggle="modal" data-target=".new-modal">
                                                        <i class="fa fa-plus"></i> <?php echo $translate->translate('Adicionar', $_SESSION['user_lang']); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">                                           
                                        <div id="list" style="overflow-x: auto;"></div>
                                    </div>
                                    <div style="overflow-x: auto;" class="card-footer card-footer-transparent justify-content-between border-top" id=pagination></div>
                                </div>
                            </div>
                        </div>
                        <!-- modal -->
                        <div class="modal fade new-modal" id="new-modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">
                                            <div id="h5-save-title"><?php echo $translate->translate('Adicionar Preço', $_SESSION['user_lang']); ?></div>
                                            <div id="h5-update-title" style="display: none;"><?php echo $translate->translate('Atualizar Preço', $_SESSION['user_lang']); ?></div>
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form style="margin: 10px;" role="form" name="form_price_currency" id="form_price_currency">
                                            <input type="hidden" name="plan" id="plan" value="<?php echo $access_plan->getId(); ?>">
                                            <input type="hidden" name="currency" id="currency">
                                            <?php
                                            $currencys = new Currency;
                                            $currencys = $currencys->getQuery();
                                            $currencysCount = count($currencys);
                                            for ($i = 0; $i < $currencysCount; $i++) {
                                                $currency = new Currency;
                                                $currency = $currencys[$i];
                                                echo "<input type='hidden' name='currency_" . $currency->getId() . "' id='currency_" . $currency->getId() . "' value='" . $currency->getCurrency() . "-" . $currency->getLocale() . "-" . $currency->getPlaceholder() . "'>";
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="status"><?php echo $translate->translate('Moeda', $_SESSION['user_lang']); ?> *</label>
                                                        <select class="custom-select to_validations" id="coin_currency" name="coin_currency">
                                                            <?php
                                                            for ($i = 0; $i < $currencysCount; $i++) {
                                                                $currency = new Currency;
                                                                $currency = $currencys[$i];
                                                                echo "<option value='" . $currency->getId() . "'>" . $currency->getCurrency() . " - " . $currency->getTitle() . "</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                        <div id="to_validation_blank_coin_currency" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="price">
                                                            <?php echo $translate->translate('Preço', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input type="text" class="form-control to_validations" id="price_currency" name="price_currency" data-currency="<?php echo $_SESSION['user_currency']; ?>" data-locale="<?php echo str_replace("_", "-", $_SESSION['user_currency_locale']); ?>" placeholder="<?php echo $_SESSION['user_currency_placeholder']; ?>" inputmode="numeric" autocomplete="off">
                                                        <div id="to_validation_blank_price_currency" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="start">
                                                            <?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="start_currency" name="start_currency" placeholder="<?php echo $translate->translate('Data de Início', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_start_currency" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="end">
                                                            <?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?> *
                                                        </label>
                                                        <input <?php echo $edit; ?> type="text" data-role="date" class="data form-control to_validations" id="end_currency" name="end_currency" placeholder="<?php echo $translate->translate('Data de Término', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_end_currency" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="status"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                                        <select class="custom-select to_validations" id="sts_currency" name="sts_currency">
                                                            <option value="1"><?php echo $translate->translate('Ativo', $_SESSION['user_lang']); ?></option>
                                                            <option value="0"><?php echo $translate->translate('Inativo', $_SESSION['user_lang']); ?></option>
                                                        </select>
                                                        <div id="to_validation_blank_sts_currency" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campo Obrigatório', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default btn-register" id="btn-save-title" onclick="updateAccessPlanPrice(form_price_currency);"><?php echo $translate->translate('Cadastrar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-register" id="btn-update-title" style="display: none;" onclick="updateAccessPlanPrice(form_price_currency);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal" onclick="cleanForm(form_price_currency);"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- modal -->
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
            <!-- footer start -->
            <?php
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderAdmin() . "/footer.php");
            ?>
            <!-- footer end -->
            </div>
        </div>        
        <!-- start bottom base html js -->
        <?php echo $baseHtml->baseJS(); ?>  
        <?php
        if (in_array("access_plans_view", $privilege_types)) {
            ?>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/accessPlans/lists/accessPlanPrices.js"></script>
            <?php
            if (in_array("access_plans_edit", $privilege_types)) {
                ?>
                <script>
                                            var language_subscription_validation_input_insert_description = '<?php echo $translate->translate('Essa descrição já consta na lista!', $_SESSION['user_lang']); ?>';
                                            var language_delete_option = "<?php echo $translate->translate('Remover', $_SESSION['user_lang']); ?>";
                </script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/locale.min.js"></script>
                <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
                <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/currency.min.js"></script>
                <script src="<?php echo $config->getDomainAdmin(); ?>//libs/v1/admin/js/general/accessPlans/update/accessPlan.js"></script>
                <?php
            }
        }
        ?>
        <!-- end bottom base html js -->
    </body>
</html>