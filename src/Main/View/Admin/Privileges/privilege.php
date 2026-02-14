<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Privilege;
use Microfw\Src\Main\Common\Entity\Admin\PrivilegeType;
use Microfw\Src\Main\Controller\Admin\Privileges\Search\Privileges;

$config = new McConfig();
$baseHtml = new BaseHtml();
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
        <!-- end top base html css -->
    </head>

    <body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;">

        <div class="wrapper">
            <?php
            $baseHtml->baseMenu("configuration", "privileges");
            ?>
            <div class="content-wrapper" style="min-height: 1004.44px;">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $edit = (in_array("privileges_configuration", $privilege_types)) ? "" : "disabled";
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "home";
                    $directory[$translate->translate('Privilégios', $_SESSION['user_lang'])] = "privileges";
                    echo $baseHtml->baseBreadcrumb($translate->translate('Privilégio', $_SESSION['user_lang']), $directory, $translate->translate('Privilégio', $_SESSION['user_lang']));
                    ?>  
                    <!-- end base html breadcrumb -->
                    <?php
                    if (in_array("privileges_configuration", $privilege_types)) {
                        $privilegeSearch = new Privilege;
                        $privilege = new Privilege;
                        $privilege = $privilegeSearch->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $gets['code']]]);
                        ?>
                        <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                        <br>
                        <div class="row" style="margin-bottom: 40px !important;">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">       
                                        <form style="margin: 10px;" role="form" name="form_privilege" id="form_privilege">
                                            <input type="hidden" name="code" id="code" value="<?php echo $privilege->getId(); ?>">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12 to_validation">
                                                    <div class="form-group">
                                                        <label for="title"><?php echo $translate->translate('Privilégio', $_SESSION['user_lang']); ?> *</label>
                                                        <input type="text" class="form-control to_validations" id="title" name="title" value="<?php echo $privilege->getDescription(); ?>" placeholder="<?php echo $translate->translate('Privilégio', $_SESSION['user_lang']); ?>">
                                                        <div id="to_validation_blank_title" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </form>
                                        <span style="font-size: 13px;"><b><?php echo $translate->translate('Campo Obrigatório', $_SESSION['user_lang']); ?> *</b></span>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top">    
                                        <button type="button" class="btn btn-default btn-register" name="save" onclick="updatePrivilege(form_privilege);"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></button>
                                        <button type="button" class="btn btn-default btn-cancel float-right" name="back" onclick="window.location.href = '<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/privileges" ?>';"><?php echo $translate->translate('Voltar', $_SESSION['user_lang']); ?></button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="nav-icon-color nav-icon fas fa-key"></i> &nbsp; <b><?php echo $translate->translate('Selecione as áreas às quais deseja conceder acesso', $_SESSION['user_lang']); ?></b></h3>
                                    </div>
                                    <div class="card-body">       
                                        <form style="margin: 10px;" role="form" name="form_privilege_types" id="form_privilege_types">
                                            <input type="hidden" name="code" id="code" value="<?php echo $privilege->getId(); ?>">
                                            <style>
                                                /* Borda colorida no topo do card para destacar (padrão AdminLTE/Bootstrap) */
.border-top-purple {
    border-top: 3px solid #6f42c1; /* Roxo */
    border-radius: 0.25rem; /* Ajuste conforme seu tema */
}

/* Espaçamento para o ícone */
.mr-2 {
    margin-right: 0.5rem;
}

/* Ajuste fino na label para alinhar bem com o switch */
.custom-control-label {
    cursor: pointer;
    font-weight: 400;
}

/* Efeito Hover no item */
.custom-control:hover {
    background-color: #f8f9fa;
    border-radius: 4px;
}
/* 1. Quando o checkbox estiver MARCADO (:checked) */
    /* Pinta o fundo (track) de roxo e a borda de roxo */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #6f42c1 !important;
        border-color: #6f42c1 !important;
    }

    /* 2. Quando o usuário CLICA (Focus) */
    /* Cria aquele brilho ao redor (box-shadow) com um roxo transparente (rgba) */
    .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25) !important; 
    }

    /* 3. (Opcional) Quando estiver DESMARCADO, mas com FOCO */
    /* Se quiser que a borda fique roxa ao clicar, mesmo sem marcar */
    .custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
        border-color: #6f42c1 !important;
    }
                                            </style>
                                            <?php
                                            $privilegesSearch = new Privileges;
                                            $privilege_type_privilege = $privilegesSearch->searchPrivilege($privilege->getId());

                                            $privileges_types_user_temp = new PrivilegeType;
                                            $all_privileges = $privileges_types_user_temp->getQuery(limit: 0, offset: 0, order: "description ASC");

                                            $grouped_privileges = [];

                                            if (count($all_privileges) > 0) {
                                                foreach ($all_privileges as $priv_obj) {
                                                    $full_desc = $priv_obj->getDescription();

                                                    // EXPLODE: Separa "Categoria - Ação"
                                                    // O limite '2' garante que se tiver mais hifens, não quebre a lógica
                                                    $parts = explode(' - ', $full_desc, 2);

                                                    if (count($parts) > 1) {
                                                        $category = trim($parts[0]); // Ex: Ticket
                                                        $action = trim($parts[1]); // Ex: Editar
                                                    } else {
                                                        // Caso não tenha hífen (Ex: "Configurações"), definimos uma categoria padrão
                                                        $category = 'Geral';
                                                        $action = $full_desc;
                                                    }

                                                    // Verifica se está checado
                                                    $is_checked = (in_array($priv_obj->getId(), $privilege_type_privilege)) ? "checked" : "";

                                                    // Agrupa no array
                                                    $grouped_privileges[$category][] = [
                                                        'id' => $priv_obj->getId(),
                                                        'action' => $action,
                                                        'checked' => $is_checked
                                                    ];
                                                }
                                            }
                                            ?>

                                            <div class="row">
                                                <?php foreach ($grouped_privileges as $categoryName => $items): ?>

                                                    <div class="col-md-6 col-lg-4 mb-4">
                                                        <div class="card h-100 shadow-sm border-top-purple">

                                                            <div class="card-header bg-white font-weight-bold d-flex align-items-center">
                                                                <i class="fas fa-folder text-purple mr-2"></i> 
                                                                <?= htmlspecialchars($categoryName) ?>
                                                            </div>

                                                            <div class="card-body">
                                                                <?php foreach ($items as $item): ?>

                                                                    <div class="custom-control custom-switch mb-2">
                                                                        <input type="checkbox" 
                                                                               class="custom-control-input" 
                                                                               name="privileges_type[]" 
                                                                               id="priv_<?= $item['id'] ?>" 
                                                                               value="<?= $item['id'] ?>" 
                                                                               <?= $item['checked'] ?>>

                                                                        <label class="custom-control-label" for="priv_<?= $item['id'] ?>">
                                                                            <?= htmlspecialchars($item['action']) ?>
                                                                        </label>
                                                                    </div>

                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php endforeach; ?>
                                            </div>

                                            <?php if (empty($grouped_privileges)): ?>
                                                <div class="alert alert-info">Nenhum privilégio encontrado.</div>
                                            <?php endif; ?>
                                        </form>
                                    </div>
                                    <div class="card-footer card-footer-transparent justify-content-between border-top">
                                        <button type="button" class="btn btn-default btn-register" name="save" onclick="updatePrivilegeTypes(form_privilege_types);"><?php echo $translate->translate('Atualizar', $_SESSION['user_lang']); ?></button>
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
        if (in_array("privileges_configuration", $privilege_types)) {
            ?>
            <script src="/libs/v1/admin/plugins/sweetalert2/sweetalert2.min.js"></script>
            <script src="/libs/v1/admin/plugins/validation/js/formValidation.min.js"></script>
            <script src="/libs/v1/admin/js/general/privileges/update/privilege.min.js"></script>
            <?php
        }
        ?>
        <!-- end bottom base html js -->
    </body>

</html>