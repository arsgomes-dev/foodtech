<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Settings\Admin\BaseHtml;
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;
use Microfw\Src\Main\Common\Entity\Admin\Ticket;

$language = new Language;
$translate = new Translate();
$config = new McConfig();
$baseHtml = new BaseHtml();
?>
<!-- start top base html css -->
<?php echo $baseHtml->baseCSS(); ?>  
<!-- end top base html css -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php echo $translate->translate('Novos Tickets', $_SESSION['user_lang']); ?></h3>
    </div>
    <div class="card-body">
        <canvas id="ticketChart"></canvas>
    </div>
</div>
<?php echo $baseHtml->baseJS(); ?>  
<?php
$labelsTickets = [];
$valoresTickets = [];
for ($i = 6; $i >= 0; $i--) {
    $dataCurrentDay = new DateTime(); // data atual
    $ticketsDay = new Ticket;
    if ($i !== 0) {
        $dataCurrentDay->sub(new DateInterval('P' . $i . 'D'));
    }
    $leastDayDB = $dataCurrentDay->format('Y-m-d');
    $leastDay = $dataCurrentDay->format('d/m/Y');
    $count = $ticketsDay->getCountSumQuery(
            customWhere: [['column' => 'created_at', 'value' => $leastDayDB]]
    );
    $labelsTickets[] = $leastDay;
    $valoresTickets[] = $count['total_count'];
}
?>
<script>
    const labelsTickets = <?= json_encode($labelsTickets) ?>;
    const dataTickets = <?= json_encode($valoresTickets) ?>;

    const ctxTickets = document.getElementById('ticketChart').getContext('2d');
    new Chart(ctxTickets, {
        type: 'bar',
        data: {
            labels: labelsTickets,
            datasets: [{
                    label: '<?php echo $translate->translate('Tickets', $_SESSION['user_lang']); ?>',
                    data: dataTickets,
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,1)',
                    borderWidth: 1
                }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>