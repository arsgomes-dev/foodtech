<?php
session_start();

use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\DepartmentOccupation;

$translate = new Translate();

$config = new McConfig();
$occupationSearch = new DepartmentOccupation();
$occupations = new DepartmentOccupation();
$privilege_types = $_SESSION['user_type'];
if (in_array("department_view", $privilege_types)) {
    if (!empty($_POST['code'])) {
        $occupationSearch->setDepartment_id($_POST['code']);
        $occupations = $occupationSearch->getQuery(limit: 0, offset: 0, order: "title ASC");
        $occupationsCount = count($occupations);
        if ($occupationsCount > 0) {
            $occupationCode = (!empty($_POST['occupation']))? $_POST['occupation'] : null;
            ?>
            <select class="custom-select to_validations" id="occupation" name="occupation">
                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>        
                <?php
                $occupation = new DepartmentOccupation();
                for ($i = 0; $i < $occupationsCount; $i++) {
                    $occupation = $occupations[$i];
                    $selected = ($occupationCode == $occupation->getId())? "selected" : "";
                    echo '<option value="'.$occupation->getId().'" '.$selected.'>'.$occupation->getTitle().'</option>';
                    
                }
                ?>
            </select>
            <?php
        } else {
            ?>
            <select class="custom-select to_validations" id="occupation" name="occupation">
                <option value=""><?php echo $translate->translate('Selecione', $_SESSION['user_lang']); ?>...</option>        
                <?php
                $occupation = new DepartmentOccupation();
                for ($i = 0; $i < $occupationsCount; $i++) {
                    $occupation = $occupations[$i];
                    ?>
                    <option value=""><?php
                    echo $translate->translate('Selecione um departamento!!', $_SESSION['user_lang']);
                    ?></option>
                    <?php
                }
                ?>
            </select> 
            <?php
        }
    } else {
        ?>
        <div class="content-header">
            <div class="container-fluid">
                <div class="alert alert-warning alert-dismissible">
                    <font style="vertical-align: inherit;"><i class="icon fas fa-exclamation-triangle"></i>
                    <?php
                    echo $translate->translate('Selecione um departamento!!', $_SESSION['user_lang']);
                    ?>
                    </font>
                </div>
            </div>
        </div>   
        <?php
    }
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