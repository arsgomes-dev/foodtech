<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of Food
 *
 * @author Ricardo Gomes
 */
class Food extends ModelClass {

    protected $table_db = "food";
    protected $table_columns_like_db = ['description'];
    protected $table_db_primaryKey = "id";
    private $id;
    private $ean;
    private $user_id_created;
    private $user_id_updated;
    private $customer_id;
    private $food_table_number;
    private $food_table_code;
    private $food_group_id;
    private $food_table_id;
    private $food_brand_id;
    private $description;
    private $description_table;
    private $moisture;
    private $kcal;
    private $kj;
    private $protein_g;
    private $lipid_g;
    private $cholesterol_mg;
    private $carbohydrate_g;
    private $fiber_g;
    private $ashes_g;
    private $acg_saturated_g;
    private $acg_monounsaturated_g;
    private $acg_polyunsaturated_g;
    private $grammage_reference;
    private $acg_trans_g;
    private $total_sugar_g;
    private $calcium_mg;
    private $magnesium_mg;
    private $manganese_mg;
    private $phosphor_mg;
    private $iron_mg;
    private $sodium_mg;
    private $potassium_mg;
    private $copper_mg;
    private $zinc_mg;
    private $selenium_mcg;
    private $retinol_mcg;
    //Equivalente de Retinou
    private $re_mcg;
    //Vitamina A
    private $rae_mcg;
    //VItamina B1
    private $thiamin_mg;
    //Vitamina B2
    private $riboflavin_mg;
    //Vitamina B6
    private $pyridoxine_mg;
    //Vitamina B3
    private $niacin_mg;
    private $vitamin_c_mg;
    //Vitamina B9
    private $folate_mcg;
    //Vitamina B12
    private $cobalamin_mcg;
    //vitamina D
    private $calciferol_mcg;
    private $vitamin_e_mg;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getEan() {
        if (isset($this->ean)) {
            return $this->ean;
        } else {
            return null;
        }
    }

    public function getUser_id_created() {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function getFood_table_number() {
        if (isset($this->food_table_number)) {
            return $this->food_table_number;
        } else {
            return null;
        }
    }

    public function getFood_table_code() {
        if (isset($this->food_table_code)) {
            return $this->food_table_code;
        } else {
            return null;
        }
    }

    public function getFood_group_id() {
        if (isset($this->food_group_id)) {
            return $this->food_group_id;
        } else {
            return null;
        }
    }

    public function getFood_table_id() {
        if (isset($this->food_table_id)) {
            return $this->food_table_id;
        } else {
            return null;
        }
    }

    public function getFood_brand_id() {
        if (isset($this->food_brand_id)) {
            return $this->food_brand_id;
        } else {
            return null;
        }
    }

    public function getDescription() {
        if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function getDescription_table() {
        if (isset($this->description_table)) {
            return $this->description_table;
        } else {
            return null;
        }
    }

    public function getMoisture() {
        if (isset($this->moisture)) {
            return $this->moisture;
        } else {
            return null;
        }
    }

    public function getKcal() {
        if (isset($this->kcal)) {
            return $this->kcal;
        } else {
            return null;
        }
    }

    public function getKj() {
        if (isset($this->kj)) {
            return $this->kj;
        } else {
            return null;
        }
    }

    public function getProtein_g() {
        if (isset($this->protein_g)) {
            return $this->protein_g;
        } else {
            return null;
        }
    }

    public function getLipid_g() {
        if (isset($this->lipid_g)) {
            return $this->lipid_g;
        } else {
            return null;
        }
    }

    public function getCholesterol_mg() {
        if (isset($this->cholesterol_mg)) {
            return $this->cholesterol_mg;
        } else {
            return null;
        }
    }

    public function getCarbohydrate_g() {
        if (isset($this->carbohydrate_g)) {
            return $this->carbohydrate_g;
        } else {
            return null;
        }
    }

    public function getFiber_g() {
        if (isset($this->fiber_g)) {
            return $this->fiber_g;
        } else {
            return null;
        }
    }

    public function getAshes_g() {
        if (isset($this->ashes_g)) {
            return $this->ashes_g;
        } else {
            return null;
        }
    }

    public function getAcg_saturated_g() {
        if (isset($this->acg_saturated_g)) {
            return $this->acg_saturated_g;
        } else {
            return null;
        }
    }

    public function getAcg_monounsaturated_g() {
        if (isset($this->acg_monounsaturated_g)) {
            return $this->acg_monounsaturated_g;
        } else {
            return null;
        }
    }

    public function getAcg_polyunsaturated_g() {
        if (isset($this->acg_polyunsaturated_g)) {
            return $this->acg_polyunsaturated_g;
        } else {
            return null;
        }
    }

    public function getGrammage_reference() {
        if (isset($this->grammage_reference)) {
            return $this->grammage_reference;
        } else {
            return null;
        }
    }

    public function getAcg_trans_g() {
        if (isset($this->acg_trans_g)) {
            return $this->acg_trans_g;
        } else {
            return null;
        }
    }

    public function getTotal_sugar_g() {
        if (isset($this->total_sugar_g)) {
            return $this->total_sugar_g;
        } else {
            return null;
        }
    }

    public function getCalcium_mg() {
        if (isset($this->calcium_mg)) {
            return $this->calcium_mg;
        } else {
            return null;
        }
    }

    public function getMagnesium_mg() {
        if (isset($this->magnesium_mg)) {
            return $this->magnesium_mg;
        } else {
            return null;
        }
    }

    public function getManganese_mg() {
        if (isset($this->manganese_mg)) {
            return $this->manganese_mg;
        } else {
            return null;
        }
    }

    public function getPhosphor_mg() {
        if (isset($this->phosphor_mg)) {
            return $this->phosphor_mg;
        } else {
            return null;
        }
    }

    public function getIron_mg() {
        if (isset($this->iron_mg)) {
            return $this->iron_mg;
        } else {
            return null;
        }
    }

    public function getSodium_mg() {
        if (isset($this->sodium_mg)) {
            return $this->sodium_mg;
        } else {
            return null;
        }
    }

    public function getPotassium_mg() {
        if (isset($this->potassium_mg)) {
            return $this->potassium_mg;
        } else {
            return null;
        }
    }

    public function getCopper_mg() {
        if (isset($this->copper_mg)) {
            return $this->copper_mg;
        } else {
            return null;
        }
    }

    public function getZinc_mg() {
        if (isset($this->zinc_mg)) {
            return $this->zinc_mg;
        } else {
            return null;
        }
    }

    public function getSelenium_mcg() {
        if (isset($this->selenium_mcg)) {
            return $this->selenium_mcg;
        } else {
            return null;
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setEan($ean) {
        $this->ean = $ean;
    }

    public function setUser_id_created($user_id_created) {
        $this->user_id_created = $user_id_created;
    }

    public function setUser_id_updated($user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function setCustomer_id($customer_id) {
        $this->customer_id = $customer_id;
    }

    public function setFood_table_number($food_table_number) {
        $this->food_table_number = $food_table_number;
    }

    public function setFood_table_code($food_table_code) {
        $this->food_table_code = $food_table_code;
    }

    public function setFood_group_id($food_group_id) {
        $this->food_group_id = $food_group_id;
    }

    public function setFood_table_id($food_table_id) {
        $this->food_table_id = $food_table_id;
    }

    public function setFood_brand_id($food_brand_id) {
        $this->food_brand_id = $food_brand_id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDescription_table($description_table) {
        $this->description_table = $description_table;
    }

    public function setMoisture($moisture) {
        $this->moisture = str_replace(",", ".", $moisture);
    }

    public function setKcal($kcal) {
        $this->kcal = str_replace(",", ".", $kcal);
    }

    public function setKj($kj) {
        $this->kj = str_replace(",", ".", $kj);
    }

    public function setProtein_g($protein_g) {
        $this->protein_g = str_replace(",", ".", $protein_g);
    }

    public function setLipid_g($lipid_g) {
        $this->lipid_g = str_replace(",", ".", $lipid_g);
    }

    public function setCholesterol_mg($cholesterol_mg) {
        $this->cholesterol_mg = str_replace(",", ".", $cholesterol_mg);
    }

    public function setCarbohydrate_g($carbohydrate_g) {
        $this->carbohydrate_g = str_replace(",", ".", $carbohydrate_g);
    }

    public function setFiber_g($fiber_g) {
        $this->fiber_g = str_replace(",", ".", $fiber_g);
    }

    public function setAshes_g($ashes_g) {
        $this->ashes_g = str_replace(",", ".", $ashes_g);
    }

    public function setAcg_saturated_g($acg_saturated_g) {
        $this->acg_saturated_g = str_replace(",", ".", $acg_saturated_g);
    }

    public function setAcg_monounsaturated_g($acg_monounsaturated_g) {
        $this->acg_monounsaturated_g = str_replace(",", ".", $acg_monounsaturated_g);
    }

    public function setAcg_polyunsaturated_g($acg_polyunsaturated_g) {
        $this->acg_polyunsaturated_g = str_replace(",", ".", $acg_polyunsaturated_g);
    }

    public function setGrammage_reference($grammage_reference) {
        $this->grammage_reference = str_replace(",", ".", $grammage_reference);
    }

    public function setAcg_trans_g($acg_trans_g) {
        $this->acg_trans_g = str_replace(",", ".", $acg_trans_g);
    }

    public function setTotal_sugar_g($total_sugar_g) {
        $this->total_sugar_g = str_replace(",", ".", $total_sugar_g);
    }

    public function setCalcium_mg($calcium_mg) {
        $this->calcium_mg = str_replace(",", ".", $calcium_mg);
    }

    public function setMagnesium_mg($magnesium_mg) {
        $this->magnesium_mg = str_replace(",", ".", $magnesium_mg);
    }

    public function setManganese_mg($manganese_mg) {
        $this->manganese_mg = str_replace(",", ".", $manganese_mg);
    }

    public function setPhosphor_mg($phosphor_mg) {
        $this->phosphor_mg = str_replace(",", ".", $phosphor_mg);
    }

    public function setIron_mg($iron_mg) {
        $this->iron_mg = str_replace(",", ".", $iron_mg);
    }

    public function setSodium_mg($sodium_mg) {
        $this->sodium_mg = str_replace(",", ".", $sodium_mg);
    }

    public function setPotassium_mg($potassium_mg) {
        $this->potassium_mg = str_replace(",", ".", $potassium_mg);
    }

    public function setCopper_mg($copper_mg) {
        $this->copper_mg = str_replace(",", ".", $copper_mg);
    }

    public function setZinc_mg($zinc_mg) {
        $this->zinc_mg = str_replace(",", ".", $zinc_mg);
    }

    public function setSelenium_mcg($selenium_mcg) {
        $this->selenium_mcg = str_replace(",", ".", $selenium_mcg);
    }

    public function getRetinol_mcg() {
        if (isset($this->retinol_mcg)) {
            return $this->retinol_mcg;
        } else {
            return null;
        }
    }

    public function setRetinol_mcg($retinol_mcg) {
        $this->retinol_mcg = $retinol_mcg;
    }

    public function getRe_mcg() {
        if (isset($this->re_mcg)) {
            return $this->re_mcg;
        } else {
            return null;
        }
    }

    public function setRe_mcg($re_mcg) {
        $this->re_mcg = $re_mcg;
    }

    public function getRae_mcg() {
        if (isset($this->rae_mcg)) {
            return $this->rae_mcg;
        } else {
            return null;
        }
    }

    public function setRae_mcg($rae_mcg) {
        $this->rae_mcg = $rae_mcg;
    }

    public function getThiamin_mg() {
        if (isset($this->thiamin_mg)) {
            return $this->thiamin_mg;
        } else {
            return null;
        }
    }

    public function setThiamin_mg($thiamin_mg) {
        $this->thiamin_mg = $thiamin_mg;
    }

    public function getRiboflavin_mg() {
        if (isset($this->riboflavin_mg)) {
            return $this->riboflavin_mg;
        } else {
            return null;
        }
    }

    public function setRiboflavin_mg($riboflavin_mg) {
        $this->riboflavin_mg = $riboflavin_mg;
    }

    public function getPyridoxine_mg() {
        if (isset($this->pyridoxine_mg)) {
            return $this->pyridoxine_mg;
        } else {
            return null;
        }
    }

    public function setPyridoxine_mg($pyridoxine_mg) {
        $this->pyridoxine_mg = $pyridoxine_mg;
    }

    public function getNiacin_mg() {
        if (isset($this->niacin_mg)) {
            return $this->niacin_mg;
        } else {
            return null;
        }
    }

    public function setNiacin_mg($niacin_mg) {
        $this->niacin_mg = $niacin_mg;
    }

    public function getVitamin_c_mg() {
        if (isset($this->vitamin_c_mg)) {
            return $this->vitamin_c_mg;
        } else {
            return null;
        }
    }

    public function setVitamin_c_mg($vitamin_c_mg) {
        $this->vitamin_c_mg = $vitamin_c_mg;
    }

    public function getFolate_mcg() {
        if (isset($this->folate_mcg)) {
            return $this->folate_mcg;
        } else {
            return null;
        }
    }

    public function setFolate_mcg($folate_mcg) {
        $this->folate_mcg = $folate_mcg;
    }

    public function getCobalamin_mcg() {
        if (isset($this->cobalamin_mcg)) {
            return $this->cobalamin_mcg;
        } else {
            return null;
        }
    }

    public function setCobalamin_mcg($cobalamin_mcg) {
        $this->cobalamin_mcg = $cobalamin_mcg;
    }

    public function getCalciferol_mcg() {
        if (isset($this->calciferol_mcg)) {
            return $this->calciferol_mcg;
        } else {
            return null;
        }
    }

    public function setCalciferol_mcg($calciferol_mcg) {
        $this->calciferol_mcg = $calciferol_mcg;
    }

    public function getVitamin_e_mg() {
        if (isset($this->vitamin_e_mg)) {
            return $this->vitamin_e_mg;
        } else {
            return null;
        }
    }

    public function setVitamin_e_mg($vitamin_e_mg) {
        $this->vitamin_e_mg = $vitamin_e_mg;
    }
}
