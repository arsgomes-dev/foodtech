<?php

use Microfw\Src\Main\Common\Entity\Public\StConfig;

$stConfig = new StConfig();
$st = $stConfig->getQuery(single: true,
        customWhere: [['column' => 'id', 'value' => 1]]);
?>
<footer style="margin-top: 10px !important; " class="main-footer"><?php echo $st->getFooter(); ?></footer>