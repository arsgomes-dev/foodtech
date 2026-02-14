<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Customers;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;
use Microfw\Src\Main\Common\Entity\Admin\Signature;

$language = new Language;
$translate = new Translate();
$config = new McConfig();
$baseHtml = new BaseHtml();
$bar_home_active = "active";
?>
<!DOCTYPE html>
<html lang="pt-br" style="height: auto;" data-bs-theme="light">

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
                <input type="hidden" name="dir_site" id="dir_site" value="<?php echo $config->getUrlAdmin(); ?>">
                <input type="hidden" name="site_locale" id="site_locale" value="<?php echo $_SESSION['user_lang_locale']; ?>">
                <section class="content col-lg-8 offset-lg-2 col-md-12 offset-md-0">
                    <!-- start base html breadcrumb -->
                    <?php
                    $directory = [];
                    $directory[$translate->translate('Home', $_SESSION['user_lang'])] = "";
                    echo $baseHtml->baseBreadcrumb("Dashboard", $directory, "Dashboard");
                    ?>  
                    <!-- end base html breadcrumb -->

                    <div class="container-fluid">
                        <!-- start boxes -->
                        <div class="row">
                            <div class="col-lg-3 col-6">
                                <?php
                                $customers = new Customers;
                                $customers = $customers->getCountSumQuery();
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3><?php echo $customers['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Clientes', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/customer" class="small-box-footer"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $start = [];
                                $end = [];
                                $customers = new Customers;
                                $dataCurrentDayTemp = new DateTime(); // data atual
                                $dataLeastCurrentDayTemp = new DateTime(); // data atual
                                $dataCurrentDay = $dataCurrentDayTemp;
                                $currentDayDB = $dataCurrentDay->format('Y-m-d');
                                $end['created_at'] = $currentDayDB;
                                $dataLeastCurrentDayTemp->sub(new DateInterval('P5D'));
                                $leastDayDB = $dataLeastCurrentDayTemp->format('Y-m-d');
                                $start['created_at'] = $leastDayDB;
                                $customers_count = $customers->getCountSumQuery(and_or: false, less_equal: $end, greater_equal: $start);
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?php echo $customers_count['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Novos Clientes', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/customer" class="small-box-footer"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $signatures = new Signature;
                                $signatures_count = $signatures->getCountSumQuery(customWhere: [['column' => 'status', 'value' => 1]]);
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><?php echo $signatures_count['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Assinaturas', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <a href="<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/signatures" class="small-box-footer"><?php echo $translate->translate('Todos', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12"><h3><?php echo $translate->translate('Tickets', $_SESSION['user_lang']); ?></h3></div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $tickets = new Ticket;
                                $tickets->setResponse(1);
                                $tickets_new_count = $tickets->getCountSumQuery(customWhere: [['column' => 'response', 'value' => 1]]);
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-gradient-purple">
                                    <div class="inner">
                                        <h3><?php echo $tickets_new_count['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Novos', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-ticket"></i>
                                    </div>
                                    <a href="#" onclick="redirectPost('<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets', {response: '1'})" class="small-box-footer"><?php echo $translate->translate('Visualizar', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $tickets = new Ticket;
                                $tickets_active_count = $tickets->getCountSumQuery(customWhere: [['column' => 'status', 'value' => 1], ['column' => 'response', 'value' => 1]]);
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-gray">
                                    <div class="inner">
                                        <h3><?php echo $tickets_active_count['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Ativos', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-ticket"></i>
                                    </div>
                                    <a href="#" onclick="redirectPost('<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets', {response: '2'})" class="small-box-footer"><?php echo $translate->translate('Visualizar', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $tickets = new Ticket;
                                $tickets->setMessage_reading_status(1);
                                $tickets_new_message = $tickets->getCountSumQuery(customWhere: [['column' => 'message_reading_status', 'value' => 1]]);
                                ;
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="inner">
                                        <h3><?php echo $tickets_new_message['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Mensagens', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-ticket"></i>
                                    </div>
                                    <a href="#" onclick="redirectPost('<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets', {messageReading: '1'})" class="small-box-footer"><?php echo $translate->translate('Novas', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-6">
                                <?php
                                $tickets = new Ticket;
                                $tickets_new_level_five = $tickets->getCountSumQuery(customWhere: [['column' => 'status', 'value' => 1], ['column' => 'level', 'value' => 5]]);
                                ?>
                                <!-- small box -->
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?php echo $tickets_new_level_five['total_count']; ?></h3>

                                        <p><?php echo $translate->translate('Emergência', $_SESSION['user_lang']); ?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-ticket"></i>
                                    </div>
                                    <a href="#" onclick="redirectPost('<?php echo $config->getDomainAdmin() . "/" . $config->getUrlAdmin(); ?>/tickets', {response: '3'})" class="small-box-footer"><?php echo $translate->translate('Visualizar', $_SESSION['user_lang']); ?> <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <!-- end boxes -->
                        <div class="row">                            
                            <div class="col-md-6" id="signatureChartDiv"></div>
                            <div class="col-md-6" id="ticketsChartDiv"></div>
                        </div>
                    </div>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="<?php echo $config->getDomainAdmin(); ?>/libs/v1/admin/js/general/home/home.js"></script>
        <!-- end bottom base html js -->
    </body>
</html>