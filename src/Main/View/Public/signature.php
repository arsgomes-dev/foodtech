<?php
use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;
use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Settings\Public\BaseHtml;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\Signature;

// Segurança de página
ProtectedPage::protectedPage();

$config = new McClientConfig();
$baseHtml = new BaseHtml();
$translate = new Translate();

/** @var Signature $mySignature */
$signatureRepo = new Signature();
$mySignature = $signatureRepo->getQuery(single: true, customWhere: [
    ['column' => 'customer_id', 'value' => $_SESSION['client_id']],
    ['column' => 'status', 'value' => 1]
]);

$signatureGcid = $mySignature ? $mySignature->getGcid() : null;
?>
<!doctype html>
<html lang="pt-br" style="height: auto;" data-theme="dark">
<head>
    <?php echo $baseHtml->baseCSS(); ?>
    <?php echo $baseHtml->baseCSSICheck(); ?>
    <?php echo $baseHtml->baseCSSAlert(); ?>
    <style>
        /* Estilização Premium para o Dashboard de Assinatura */
        .sub-header-card {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 12px;
            border-left: 6px solid #28a745;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .stat-box {
            padding: 10px;
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        .stat-box:last-child { border-right: none; }
        .stat-label { font-size: 0.75rem; color: #888; text-transform: uppercase; font-weight: bold; }
        .stat-value { font-size: 1.1rem; color: #fff; font-weight: 600; }
        .payment-id { font-family: 'Monaco', 'Consolas', monospace; color: #28a745; background: rgba(40,167,69,0.1); padding: 2px 6px; border-radius: 4px; }
        .table-card { border: none; border-radius: 12px; }
        .btn-filter-custom { background: #343a40; border: 1px solid #454d55; color: #fff; }
    </style>
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed thetec" style="height: auto;">
    <div class="wrapper">
        <?php $baseHtml->baseMenu("subscription", "subscription"); ?>

        <div class="content-wrapper">
            <section class="content col-lg-12 col-md-12">
                <?php
                $directory = ["Home" => "home"];
                echo $baseHtml->baseBreadcrumb($translate->translate("Minha Assinatura", $_SESSION['client_lang']), $directory, $translate->translate("Financeiro", $_SESSION['client_lang']));
                ?>  

                <input type="hidden" id="dir_site" value="<?php echo $config->getUrlPublic(); ?>">
                <input type="hidden" id="signature_gcid" value="<?php echo $signatureGcid; ?>">

                <?php if (!$mySignature): ?>
                    <div class="callout callout-info bg-dark shadow-sm">
                        <h5><i class="fas fa-info-circle text-info"></i> <?php echo $translate->translate("Nenhuma assinatura ativa", $_SESSION['client_lang']); ?></h5>
                        <p><?php echo $translate->translate("Parece que você ainda não faz parte do nosso time premium. Explore os planos para começar!", $_SESSION['client_lang']); ?></p>
                        <a href="/<?php echo $config->getUrlPublic(); ?>/plans" class="btn btn-info btn-sm">Ver Planos Disponíveis</a>
                    </div>
                <?php else: ?>

                    <div class="card sub-header-card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center text-center text-md-left">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <h5 class="mb-1 text-success font-weight-bold">
                                        <i class="fas fa-crown mr-2"></i><?php echo $translate->translate("Assinatura Ativa", $_SESSION['client_lang']); ?>
                                    </h5>
                                    <span class="payment-id small text-uppercase">GCID: <?php echo $signatureGcid; ?></span>
                                </div>
                                <div class="col-6 col-md-3 stat-box">
                                    <div class="stat-label"><?php echo $translate->translate("Investimento", $_SESSION['client_lang']); ?></div>
                                    <div class="stat-value">R$ <?php echo number_format($mySignature->getPrice(), 2, ',', '.'); ?></div>
                                </div>
                                <div class="col-6 col-md-3 stat-box">
                                    <div class="stat-label"><?php echo $translate->translate("Próxima Renovação", $_SESSION['client_lang']); ?></div>
                                    <div class="stat-value"><?php echo date('d/m/Y', strtotime($mySignature->getDate_renovation())); ?></div>
                                </div>
                                <div class="col-md-2 text-md-right mt-3 mt-md-0">
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="confirmCancel();">
                                        <i class="fas fa-times-circle mr-1"></i> <?php echo $translate->translate("Cancelar", $_SESSION['client_lang']); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <div class="col-sm-6">
                            <h5 class="m-0 font-weight-bold"><i class="fas fa-history mr-2 text-muted"></i><?php echo $translate->translate("Histórico de Pagamentos", $_SESSION['client_lang']); ?></h5>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="button" class="btn btn-filter-custom btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#search-modal">
                                <i class="fas fa-filter mr-1"></i> <?php echo $translate->translate("Filtrar", $_SESSION['client_lang']); ?>
                            </button>
                        </div>
                    </div>

                    <div class="card table-card shadow-sm bg-dark">
                        <div class="card-body p-0">           
                            <div id="list" class="table-responsive">
                                </div>
                        </div>
                        <div class="card-footer bg-transparent border-0" id="pagination"></div>
                    </div>

                    <div class="modal fade" id="search-modal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content bg-dark border-secondary shadow-lg">
                                <div class="modal-header border-secondary p-3">
                                    <h6 class="modal-title font-weight-bold"><?php echo $translate->translate('Opções de Filtro', $_SESSION['client_lang']); ?></h6>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-3">
                                    <form id="searchFilter">          
                                        <div class="form-group mb-0">
                                            <label class="small text-muted mb-1"><?php echo $translate->translate('Ordenar por', $_SESSION['client_lang']); ?></label>
                                            <select class="form-control form-control-sm bg-secondary text-white border-0" name="ord_search" id="ord_search">
                                                <option value="1"><?php echo $translate->translate('Mais Recentes', $_SESSION['client_lang']); ?></option>
                                                <option value="2"><?php echo $translate->translate('Mais Antigos', $_SESSION['client_lang']); ?></option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer border-secondary p-2">
                                    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="loadBtnPayments();" data-bs-dismiss="modal">
                                        <?php echo $translate->translate('Aplicar Filtros', $_SESSION['client_lang']); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </section>        
            <?php require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/footer.php"); ?>
        </div>
    </div>        

    <?php echo $baseHtml->baseJS(); ?>  
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/signatures/signatures.js"></script>
    
    <script>
        // Carregamento inicial automático
        document.addEventListener('DOMContentLoaded', function() {
            if(document.getElementById('signature_gcid').value !== "") {
                loadBtnPayments();
            }
        });

        function confirmCancel() {
            Swal.fire({
                title: '<?php echo $translate->translate("Cancelar assinatura?", $_SESSION['client_lang']); ?>',
                text: "<?php echo $translate->translate("Você manterá o acesso até o fim do período atual.", $_SESSION['client_lang']); ?>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<?php echo $translate->translate("Sim, cancelar", $_SESSION['client_lang']); ?>',
                cancelButtonText: '<?php echo $translate->translate("Não, voltar", $_SESSION['client_lang']); ?>'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lógica de cancelamento via Ajax
                }
            })
        }
    </script>
</body>
</html>