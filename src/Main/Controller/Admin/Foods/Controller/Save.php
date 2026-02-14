<?php
session_start();

//Função para cadastro e atualização dos DEPARTAMENTOS
//Insere a função que protege o script contra acessos não autorizados, informa que apenas usuários administrativos logados tem essa permissão
use Microfw\Src\Main\Controller\Admin\Login\ProtectedPage;

ProtectedPage::protectedPage();

//Importa as classes que serão utilizadas no script
use Microfw\Src\Main\Common\Helpers\Admin\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Admin\Language;

use Microfw\Src\Main\Common\Entity\Admin\Food;

$language = new Language;
$translate = new Translate();
$privilege_types = $_SESSION['user_type'];

if (in_array("food_edit", $privilege_types) || in_array("food_create", $privilege_types)) {
    if (!empty(array_filter($_POST)) && $_POST &&
            !empty($_POST['description']) && isset($_POST['description']) &&
            !empty($_POST['kcal']) && isset($_POST['kcal']) &&
            !empty($_POST['grammage_reference']) && isset($_POST['grammage_reference']) &&
            !empty($_POST['table']) && isset($_POST['table']) &&
            !empty($_POST['group']) && isset($_POST['group'])) {

        $food = new Food;

        if (!empty($_POST['code']) && isset($_POST['code'])) {
            $food->setId($_POST['code']);
            $food->setUser_id_updated($_SESSION['user_id']);
        } else {
            $food->setUser_id_created($_SESSION['user_id']);
        }

        $food->setDescription($_POST['description']);
        $food->setKcal($_POST['kcal']);
        $food->setGrammage_reference($_POST['grammage_reference']);
        if (!empty($_POST['description_table']) && isset($_POST['description_table'])) {
            $food->setDescription_table($_POST['description_table']);
        }
        if (!empty($_POST['barcode']) && isset($_POST['barcode'])) {
            $food->setEan($_POST['barcode']);
        }
        if (!empty($_POST['moisture']) && isset($_POST['moisture'])) {
            $food->setMoisture(str_replace(['.', ','], ['', '.'], $_POST['moisture']));
        }
        if (!empty($_POST['kj']) && isset($_POST['kj'])) {
            $food->setKj(str_replace(['.', ','], ['', '.'], $_POST['kj']));
        }
        if (!empty($_POST['protein']) && isset($_POST['protein'])) {
            $food->setProtein_g(str_replace(['.', ','], ['', '.'], $_POST['protein']));
        }
        if (!empty($_POST['lipid']) && isset($_POST['lipid'])) {
            $food->setLipid_g(str_replace(['.', ','], ['', '.'], $_POST['lipid']));
        }
        if (!empty($_POST['carbohydrate']) && isset($_POST['carbohydrate'])) {
            $food->setCarbohydrate_g(str_replace(['.', ','], ['', '.'], $_POST['carbohydrate']));
        }
        if (!empty($_POST['fiber']) && isset($_POST['fiber'])) {
            $food->setFiber_g(str_replace(['.', ','], ['', '.'], $_POST['fiber']));
        }
        if (!empty($_POST['cholesterol']) && isset($_POST['cholesterol'])) {
            $food->setCholesterol_mg(str_replace(['.', ','], ['', '.'], $_POST['cholesterol']));
        }
        if (!empty($_POST['ashes']) && isset($_POST['ashes'])) {
            $food->setAshes_g(str_replace(['.', ','], ['', '.'], $_POST['ashes']));
        }
        if (!empty($_POST['acg_saturated']) && isset($_POST['acg_saturated'])) {
            $food->setAcg_saturated_g(str_replace(['.', ','], ['', '.'], $_POST['acg_saturated']));
        }
        if (!empty($_POST['acg_monounsaturated']) && isset($_POST['acg_monounsaturated'])) {
            $food->setAcg_monounsaturated_g(str_replace(['.', ','], ['', '.'], $_POST['acg_monounsaturated']));
        }
        if (!empty($_POST['acg_polyunsaturated']) && isset($_POST['acg_polyunsaturated'])) {
            $food->setAcg_polyunsaturated_g(str_replace(['.', ','], ['', '.'], $_POST['acg_polyunsaturated']));
        }
        if (!empty($_POST['acg_trans']) && isset($_POST['acg_trans'])) {
            $food->setAcg_trans_g(str_replace(['.', ','], ['', '.'], $_POST['acg_trans']));
        }
        if (!empty($_POST['total_sugar']) && isset($_POST['total_sugar'])) {
            $food->setTotal_sugar_g(str_replace(['.', ','], ['', '.'], $_POST['total_sugar']));
        }
        if (!empty($_POST['calcium']) && isset($_POST['calcium'])) {
            $food->setCalcium_mg(str_replace(['.', ','], ['', '.'], $_POST['calcium']));
        }
        if (!empty($_POST['magnesium']) && isset($_POST['magnesium'])) {
            $food->setMagnesium_mg(str_replace(['.', ','], ['', '.'], $_POST['magnesium']));
        }
        if (!empty($_POST['manganese']) && isset($_POST['manganese'])) {
            $food->setManganese_mg(str_replace(['.', ','], ['', '.'], $_POST['manganese']));
        }
        if (!empty($_POST['phosphor']) && isset($_POST['phosphor'])) {
            $food->setPhosphor_mg(str_replace(['.', ','], ['', '.'], $_POST['phosphor']));
        }
        if (!empty($_POST['iron']) && isset($_POST['iron'])) {
            $food->setIron_mg(str_replace(['.', ','], ['', '.'], $_POST['iron']));
        }
        if (!empty($_POST['sodium']) && isset($_POST['sodium'])) {
            $food->setSodium_mg(str_replace(['.', ','], ['', '.'], $_POST['sodium']));
        }
        if (!empty($_POST['potassium']) && isset($_POST['potassium'])) {
            $food->setPotassium_mg(str_replace(['.', ','], ['', '.'], $_POST['potassium']));
        }
        if (!empty($_POST['copper']) && isset($_POST['copper'])) {
            $food->setCopper_mg(str_replace(['.', ','], ['', '.'], $_POST['copper']));
        }
        if (!empty($_POST['zinc']) && isset($_POST['zinc'])) {
            $food->setZinc_mg(str_replace(['.', ','], ['', '.'], $_POST['zinc']));
        }
        if (!empty($_POST['selenium']) && isset($_POST['selenium'])) {
            $food->setSelenium_mcg(str_replace(['.', ','], ['', '.'], $_POST['selenium']));
        }
        if (!empty($_POST['retinol']) && isset($_POST['retinol'])) {
            $food->setRetinol_mcg(str_replace(['.', ','], ['', '.'], $_POST['retinol']));
        }
        if (!empty($_POST['re']) && isset($_POST['re'])) {
            $food->setRe_mcg(str_replace(['.', ','], ['', '.'], $_POST['re']));
        }
        if (!empty($_POST['rae']) && isset($_POST['rae'])) {
            $food->setRae_mcg(str_replace(['.', ','], ['', '.'], $_POST['rae']));
        }
        if (!empty($_POST['thiamin']) && isset($_POST['thiamin'])) {
            $food->setThiamin_mg(str_replace(['.', ','], ['', '.'], $_POST['thiamin']));
        }
        if (!empty($_POST['riboflavin']) && isset($_POST['riboflavin'])) {
            $food->setRiboflavin_mg(str_replace(['.', ','], ['', '.'], $_POST['riboflavin']));
        }
        if (!empty($_POST['niacin']) && isset($_POST['niacin'])) {
            $food->setNiacin_mg(str_replace(['.', ','], ['', '.'], $_POST['niacin']));
        }
        if (!empty($_POST['pyridoxine']) && isset($_POST['pyridoxine'])) {
            $food->setPyridoxine_mg(str_replace(['.', ','], ['', '.'], $_POST['pyridoxine']));
        }
        if (!empty($_POST['folate']) && isset($_POST['folate'])) {
            $food->setFolate_mcg(str_replace(['.', ','], ['', '.'], $_POST['folate']));
        }
        if (!empty($_POST['cobalamin']) && isset($_POST['cobalamin'])) {
            $food->setCobalamin_mcg(str_replace(['.', ','], ['', '.'], $_POST['cobalamin']));
        }
        if (!empty($_POST['vitamin_c']) && isset($_POST['vitamin_c'])) {
            $food->setVitamin_c_mg(str_replace(['.', ','], ['', '.'], $_POST['vitamin_c']));
        }
        if (!empty($_POST['calciferol']) && isset($_POST['calciferol'])) {
            $food->setCalciferol_mcg(str_replace(['.', ','], ['', '.'], $_POST['calciferol']));
        }
        if (!empty($_POST['vitamin_e']) && isset($_POST['vitamin_e'])) {
            $food->setVitamin_e_mg(str_replace(['.', ','], ['', '.'], $_POST['vitamin_e']));
        }

        if (!empty($_POST['table'])) {
            if ($_POST['table'] !== "" && $_POST['table'] !== null && $_POST['table'] !== "") {
                $food->setFood_table_id($_POST['table']);
            }
        }
        if (!empty($_POST['group'])) {
            if ($_POST['group'] !== "" && $_POST['group'] !== null && $_POST['group'] !== "") {
                $food->setFood_group_id($_POST['group']);
            }
        }
        if (!empty($_POST['brand'])) {
            if ($_POST['brand'] !== "" && $_POST['brand'] !== null && $_POST['brand'] !== "") {
                $food->setFood_brand_id($_POST['brand']);
            }
        }
        $return = $food->setSaveQuery();

        if ($return == 1) {
            echo "1->" . $translate->translate('Alteração realizada com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 2) {
            echo "1->" . $translate->translate('Cadastro realizado com sucesso!', $_SESSION['user_lang']);
        } else if ($return == 3) {
            echo "2->" . $translate->translate('Erro ao realizar alteração!', $_SESSION['user_lang']);
        }
    } else {
        echo "2->" . $translate->translate('Não é permitido campos em branco!', $_SESSION['user_lang']);
    }
} else {
    echo "2->" . $translate->translate('Você não possui permissão para esta ação!', $_SESSION['user_lang']);
}