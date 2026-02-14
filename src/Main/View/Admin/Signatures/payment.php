<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePayment;
use Microfw\Src\Main\Common\Entity\Admin\SignaturePaymentInvoice;
use Microfw\Src\Main\Common\Entity\Admin\Signature;
use Microfw\Src\Main\Common\Entity\Admin\PaymentStatus;
use Microfw\Src\Main\Common\Entity\Admin\Currency;
use Microfw\Src\Main\Common\Entity\Admin\PaymentMethod;

$config = new McConfig();
$baseHtml = new BaseHtml();
$privilege_types = $_SESSION['user_type'];
$language = new Language;
$translate = new Translate();
if (in_array("customer_signatures", $privilege_types)) {


    $signaturesPayment = new SignaturePayment;
    $signaturesPaymentSearch = new SignaturePayment;
    $signaturesPaymentSearch->setTable_db_primaryKey("gcid");
    $signaturesPayment = $signaturesPaymentSearch->getQuery(single: true, customWhere: [['column' => 'gcid', 'value' => $_POST['code']]]);

    $signature = new Signature;
    $signature = $signature->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturesPayment->getSignature_id()]]);

    $customer = new Customers;
    $customer = $customer->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCustomer_id()]]);

    $currency = new Currency;
    $currency = $currency->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signature->getCurrency_id()]]);

    $signaturesPaymentStatus = new PaymentStatus;
    $signaturesPaymentStatus = $signaturesPaymentStatus->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $signaturesPayment->getPayment_status_id()]]);

    $price = $translate->translateMonetary($signature->getPrice(), $currency->getCurrency(), $currency->getLocale());

    $discount = number_format($signature->getDiscount(), 2, ',', '.');

    $date_billing = "";
    if ($signaturesPayment->getDate_billing() !== null && $signaturesPayment->getDate_billing() !== "") {
        $date_billing = (new DateTime($signaturesPayment->getDate_billing()))->format("d/m/Y");
    }

    $date_due = "";
    if ($signaturesPayment->getDate_due() !== null && $signaturesPayment->getDate_due() !== "") {
        $date_due = (new DateTime($signaturesPayment->getDate_due()))->format("d/m/Y");
    }

    $date_value = "";
    $method_value = "";
    $date_payment = "";
    if ($signaturesPayment->getDate_payment() !== null && $signaturesPayment->getDate_payment() !== "") {
        $date_payment = (new DateTime($signaturesPayment->getDate_payment()))->format("d/m/Y");
        $date_value = $date_payment;
        $method_value = $signaturesPayment->getPayment_method_id();
    }
    $total_price = $signature->getPrice() - ($signature->getPrice() * (number_format($signature->getDiscount(), 2) / 100));
    ?>
    <?php echo $baseHtml->baseCSS(); ?>  
    <link rel='stylesheet' href='<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>

    <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
    <div class="card-tabs">
        <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-three-order-tab" data-toggle="pill" href="#custom-tabs-three-order" role="tab" aria-controls="custom-tabs-three-order" aria-selected="true">
                        <?php echo $translate->translate('Fatura', $_SESSION['user_lang']); ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-invoice-tab" data-toggle="pill" href="#custom-tabs-three-invoice" role="tab" aria-controls="custom-tabs-three-invoice" aria-selected="false">
                        <?php echo $translate->translate('Nota Fiscal', $_SESSION['user_lang']); ?>
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-three-order" role="tabpanel" aria-labelledby="custom-tabs-three-order-tab">
                    <div class="row">
                        <div class="col-lg-12 col-sm-12 invoice-col">
                            <form name="update_payment" id="update_payment" autocomplete="off">
                                <div class="row">
                                    <input type="hidden" name="code" id="code" value="<?php echo $signaturesPayment->getGcid(); ?>">
                                    <div class="col-lg-12 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label><?php echo $translate->translate('Fatura', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control" disabled placeholder="<?php echo $translate->translate('Fatura', $_SESSION['user_lang']); ?>" value="<?php echo $signaturesPayment->getGcid(); ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label><?php echo $translate->translate('Data de Faturamento', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control" disabled placeholder="<?php echo $translate->translate('Data de Faturamento', $_SESSION['user_lang']); ?>" value="<?php echo $date_billing; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label><?php echo $translate->translate('Data de Vencimento', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control" disabled placeholder="<?php echo $translate->translate('Data de Vencimento', $_SESSION['user_lang']); ?>" value="<?php echo $date_due; ?>">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="date_payment"><?php echo $translate->translate('Valor pago em', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="data form-control to_validations" id="date_payment" name="date_payment" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" inputmode="numeric" placeholder="dd/mm/yyyy" data-role="date" value="<?php echo $date_value; ?>">
                                            <div id="to_validation_blank_date_payment" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="method"><?php echo $translate->translate('Método de Pagamento', $_SESSION['user_lang']); ?> *</label>
                                            <select class="form-control form-control-md to_validations" style="width: 100%;" name="method" id="method">
                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                <?php
                                                $paymentMethodsSearch = new PaymentMethod;
                                                $paymentMethods = new PaymentMethod;
                                                $paymentMethodsSearch->setStatus(1);
                                                $paymentMethods = $paymentMethodsSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
                                                $paymentMethodsCount = count($paymentMethods);
                                                if ($paymentMethodsCount > 0) {
                                                    $paymentMethod = new PaymentMethod;
                                                    for ($i = 0; $i < $paymentMethodsCount; $i++) {
                                                        $paymentMethod = $paymentMethods[$i];
                                                        $selected = ($signaturesPayment->getPayment_method_id() === $paymentMethod->getId()) ? "selected" : "";
                                                        ?>
                                                        <option value="<?php echo $paymentMethod->getId(); ?>" <?php echo $selected; ?>><?php echo $paymentMethod->getTitle(); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>  
                                            <div id="to_validation_blank_method" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group to_validation">
                                            <label for="department_search"><?php echo $translate->translate('Status', $_SESSION['user_lang']); ?> *</label>
                                            <select class="form-control form-control-md to_validations" style="width: 100%;" name="sts" id="sts">
                                                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>
                                                <?php
                                                $paymentStatusSearch = new PaymentStatus;
                                                $paymentsStatus = new PaymentStatus;
                                                $paymentsStatus = $paymentStatusSearch->getQuery(limit: 0, offset: 0, order: "title ASC");;
                                                $paymentStatusCount = count($paymentsStatus);
                                                if ($paymentStatusCount > 0) {
                                                    $paymentStatus = new PaymentStatus;
                                                    for ($i = 0; $i < $paymentStatusCount; $i++) {
                                                        $paymentStatus = $paymentsStatus[$i];
                                                        $selected = ($signaturesPayment->getPayment_status_id() === $paymentStatus->getId()) ? "selected" : "";
                                                        ?>
                                                        <option value="<?php echo $paymentStatus->getId(); ?>" <?php echo $selected; ?>><?php echo $paymentStatus->getTitle(); ?></option>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </select>  
                                            <div id="to_validation_blank_sts" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Selecione uma opção', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>    
                                    </div>
                                </div>
                            </form>
                            <br>
                            <br>
                        </div>
                        <div class="col-lg-12 col-sm-12">
                            <div class="table-responsive">

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th><?php echo $translate->translate('Subtotal', $_SESSION['user_lang']); ?></th>
                                            <th><?php echo $translate->translate('Desconto', $_SESSION['user_lang']); ?></th>
                                            <th><?php echo $translate->translate('Total', $_SESSION['user_lang']); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $price; ?></td>
                                            <td><?php echo $discount . '%'; ?></td>
                                            <td><?php echo $translate->translateMonetary($total_price, $currency->getCurrency(), $currency->getLocale()); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default btn-register" onclick="updatePayment(update_payment);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                        <button type="button" class="btn btn-default btn-cancel" data-dismiss="modal"  onclick="cleanPayment();"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                    </div>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-invoice" role="tabpanel" aria-labelledby="custom-tabs-three-invoice-tab">
                    <div class="row">
                        <div class="col-lg-12 invoice-col"><h5><?php echo $translate->translate('Dados da Nota Fiscal', $_SESSION['user_lang']); ?></h5></div>
                        <div class="col-lg-12">
                            <form style="margin: 10px;" role="form" name="update_invoice" id="update_invoice" enctype="multipart/form-data">
                                <?php
                                $invoice = new SignaturePaymentInvoice;
                                $invoice->setTable_db_primaryKey("signature_payment_gcid");
                                $invoice = $invoice->getQuery(single: true, customWhere: [['column' => 'signature_payment_gcid', 'value' => $signaturesPayment->getGcid()]]);
                                ?>
                                <input type="hidden" name="code" id="code" value="<?php echo $signaturesPayment->getGcid(); ?>">
                                <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice->getGcid(); ?>">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="number"><?php echo $translate->translate('Número da Nota Fiscal', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="number" id="number" placeholder="<?php echo $translate->translate('Número da Nota Fiscal', $_SESSION['user_lang']); ?>" value="<?php echo $invoice->getNumber_invoice(); ?>">
                                            <div id="to_validation_blank_number" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="verification"><?php echo $translate->translate('Código de Verificação', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="verification" id="verification" placeholder="<?php echo $translate->translate('Código de Verificação', $_SESSION['user_lang']); ?>" value="<?php echo $invoice->getVerification_code(); ?>">
                                            <div id="to_validation_blank_verification" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="serie"><?php echo $translate->translate('Série', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="serie" id="serie" placeholder="<?php echo $translate->translate('Série', $_SESSION['user_lang']); ?>" value="<?php echo $invoice->getSeries_invoice(); ?>">
                                            <div id="to_validation_blank_serie" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12">
                                        <div class="form-group to_validation">
                                            <label for="issue"><?php echo $translate->translate('Data da Emissão', $_SESSION['user_lang']); ?> *</label>
                                            <?php
                                            $issue = "";
                                            if ($invoice->getDate_issue() !== null && $invoice->getDate_issue() !== "") {
                                                $issue = $translate->translateDate($invoice->getDate_issue(), $_SESSION['user_lang']);
                                            }
                                            ?>
                                            <input type="text" class="form-control to_validations data" name="issue" id="issue" placeholder="<?php echo $translate->translate('Data da Emissão', $_SESSION['user_lang']); ?>" value="<?php echo $issue; ?>">
                                            <div id="to_validation_blank_issue" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="total"><?php echo $translate->translate('Valor Total', $_SESSION['user_lang']); ?> *</label>
                                            <?php
                                            $total = "";
                                            if ($invoice->getTotal_amount() !== null && $invoice->getTotal() !== "") {
                                                $total = $translate->translateMonetary($invoice->getTotal_amount(), $_SESSION['user_currency'], $_SESSION['user_currency_locale']);
                                            }
                                            ?>
                                            <input type="text" class="form-control to_validations" name="total" id="total" placeholder="<?php echo $translate->translate('Valor Total', $_SESSION['user_lang']); ?>" data-currency="<?php echo $_SESSION['user_currency']; ?>" 
                                                   data-locale="<?php echo str_replace("_", "-", $_SESSION['user_currency_locale']); ?>" placeholder="<?php echo $_SESSION['user_currency_placeholder']; ?>" inputmode="numeric" autocomplete="off" 
                                                   value="<?php echo $total; ?>">
                                            <div id="to_validation_blank_total" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="net"><?php echo $translate->translate('Valor Líquido', $_SESSION['user_lang']); ?> *</label>
                                            <?php
                                            $net = "";
                                            if ($invoice->getNet_amount() !== null && $invoice->getNet_amount() !== "") {
                                                $net = $translate->translateMonetary($invoice->getNet_amount(), $_SESSION['user_currency'], $_SESSION['user_currency_locale']);
                                            }
                                            ?>
                                            <input type="text" class="form-control to_validations" name="net" id="net" placeholder="<?php echo $translate->translate('Valor Líquido', $_SESSION['user_lang']); ?>" data-currency="<?php echo $_SESSION['user_currency']; ?>" 
                                                   data-locale="<?php echo str_replace("_", "-", $_SESSION['user_currency_locale']); ?>" placeholder="<?php echo $_SESSION['user_currency_placeholder']; ?>" inputmode="numeric" autocomplete="off" 
                                                   value="<?php echo $net; ?>">
                                            <div id="to_validation_blank_net" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-12 to_validation">
                                        <div class="form-group">
                                            <label for="url"><?php echo $translate->translate('URL de Consulta', $_SESSION['user_lang']); ?> *</label>
                                            <input type="text" class="form-control to_validations" name="url" id="url" placeholder="<?php echo $translate->translate('URL de Consulta', $_SESSION['user_lang']); ?>" value="<?php echo $invoice->getConsultation_url(); ?>">
                                            <div id="to_validation_blank_url" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-sm-12" id="accordion">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title w-100">
                                                    <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                                                        <?php echo $translate->translate('Cancelamento de NFS-e', $_SESSION['user_lang']); ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <!-- caso deseje que fique aberta com tiver nota cancelada inclementar o SHOW na class -->
                                            <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-sm-12">
                                                            <div class="form-group">
                                                                <?php
                                                                $canceled = $translate->translateDate($invoice->getCanceled_at(), $_SESSION['user_lang']);
                                                                ?>
                                                                <label for="canceled"><?php echo $translate->translate('Cancelada em', $_SESSION['user_lang']); ?></label>
                                                                <input type="text" class="form-control" name="canceled" id="canceled" placeholder="<?php echo $translate->translate('Cancelada em', $_SESSION['user_lang']); ?>" value="<?php echo $canceled; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-12 col-sm-12">
                                                            <div class="form-group">
                                                                <label for="cancellation">
                                                                    <?php echo $translate->translate('Motivo do Cancelamento', $_SESSION['user_lang']); ?>
                                                                </label>
                                                                <textarea class="form-control" name="cancellation" id="cancellation" placeholder="<?php echo $translate->translate('Motivo do Cancelamento', $_SESSION['user_lang']); ?>"><?php echo $invoice->getCancel_reason(); ?></textarea>
                                                            </div>
                                                        </div>                              
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php if ($invoice->getInvoice_pdf() === "" || $invoice->getInvoice_pdf() === null) { ?>
                                        <div class="col-lg-12 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="invoice_arq"><?php echo $translate->translate('Arquivo NFS-e', $_SESSION['user_lang']); ?> *</label>
                                                <input type="file" id="invoice_arq" name="invoice_arq" class="form-control border-0 to_validations" accept="pdf/*" >
                                                <div id="to_validation_blank_invoice_arq" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>                                    
                                    <?php } else {
                                        ?>
                                        <div class="col-12">
                                            <hr>
                                            <a target="_blank" download="<?php echo $invoice->getInvoice_pdf(); ?>" href="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $signaturesPayment->getGcid() . "/" . $invoice->getInvoice_pdf(); ?>">
                                                <?php echo $translate->translate('Click para baixar PDF da NFS-e', $_SESSION['user_lang']); ?>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a class="float-right" style="color: red;" title="<?php echo $translate->translate('Remover', $_SESSION['user_lang']); ?>" href="#" onclick="deleteTypeInvoices('<?php echo $invoice->getGcid(); ?>', 'pdf');">
                                                <i class="nav-icon fas fa-xmark"></i>
                                            </a>
                                        </div>
                                    <?php }
                                    ?>
                                    <?php if ($invoice->getInvoice_xml() === "" || $invoice->getInvoice_xml() === null) { ?>
                                        <div class="col-lg-12 col-sm-12 to_validation">
                                            <div class="form-group">
                                                <label for="xml_arq"><?php echo $translate->translate('XML NFS-e', $_SESSION['user_lang']); ?> *</label>
                                                <input type="file" id="xml_arq" name="xml_arq" class="form-control border-0 to_validations" accept="xml/*" >
                                                <div id="to_validation_blank_xml_arq" style="display: none;" class="to_blank"><span><?php echo $translate->translate('Não é permitido campo em branco', $_SESSION['user_lang']); ?>!</span></div>
                                            </div>
                                        </div>                      
                                    <?php } else {
                                        ?>
                                        <div class="col-12">
                                            <hr>
                                            <a target="_blank" download="<?php echo $invoice->getInvoice_xml(); ?>" href="<?php echo $config->getDomainAdmin() . $config->getBaseFile() . "/customers/" . $customer->getGcid() . "/signatures/" . $signature->getGcid() . "/invoices/" . $signaturesPayment->getGcid() . "/" . $invoice->getInvoice_xml(); ?>">
                                                <?php echo $translate->translate('Click para baixar XML da NFS-e', $_SESSION['user_lang']); ?>
                                            </a>
                                            &nbsp;&nbsp;
                                            <a class="float-right" style="color: red;" title="<?php echo $translate->translate('Remover', $_SESSION['user_lang']); ?>" href="#" onclick="deleteTypeInvoices('<?php echo $invoice->getGcid(); ?>', 'xml');">
                                                <i class="nav-icon fas fa-xmark"></i>
                                            </a>
                                        </div>
                                        <br>
                                    <?php } ?>
                                </div>  
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-default btn-register" onclick="updateInvoice(update_invoice);"><?php echo $translate->translate('Salvar', $_SESSION['user_lang']); ?></button>
                        <button type="button" class="btn btn-default btn-tec-infor" onclick="updateInvoiceSend(update_invoice);"><?php echo $translate->translate('Salvar e Enviar', $_SESSION['user_lang']); ?></button>
                        <button type="button" class="btn btn-default btn-tec-success" onclick="sendInvoice(update_invoice);"><?php echo $translate->translate('Enviar', $_SESSION['user_lang']); ?></button>
                        <button type="button" class="btn btn-default btn-cancel float-righ" data-dismiss="modal" onclick="cleanPayment();"><?php echo $translate->translate('Cancelar', $_SESSION['user_lang']); ?></button>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.card -->
    </div>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/validation/js/formValidation.js"></script>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/data/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/inputmask/inputmask.min.js"></script>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/currency.min.js"></script>
    <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/plugins/format/onlyNumbers.min.js"></script>
    <?php echo $translate->translateDatePicker($_SESSION['user_lang']); ?>
<?php } ?>
