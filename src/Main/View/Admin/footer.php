<?php
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
$stConfig = new StConfig();
$st = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
?>
<footer style="margin-top: 60px !important; " class="main-footer"><?php echo $st->getFooter(); ?></footer>