<?php

namespace Microfw\Src\Main\Common\Entity\Public;

use Microfw\Src\Main\Common\Entity\Public\ModelClass;
use Microfw\Src\Main\Common\Helpers\General\UniqueCode\GCID;
use DateTime;

class Signature extends ModelClass {

    protected $table_db = "signatures";
    protected $table_db_primaryKey = "id";
    //menor igual
    protected $table_columns_less_equal_db = ['date_end'];
    //maior igual
    protected $table_columns_greater_equal_db = ['date_start'];
    protected $table_columns_between_db = ['created_at'];
    protected $table_db_join = "customer_id";
    private $id;
    private string $gcid;
    private int $customer_id;
    private int $access_plan_id;
    private int $currency_id;
    private $price;
    private $discount;
    private string $date_start;
    private string $date_end;
    private int $auto_renew;
    private $auto_renew_accepted_at;
    private string $renewal_cycle;
    private string $date_renovation;
    private int $access_plan_coupon_id;
    private int $status;
    private int $user_id_updated;

    public function getTable_db_join() {
        if (isset($this->table_db_join)) {
            return $this->table_db_join;
        } else {
            return null;
        }
    }

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getGcid() {
        if (isset($this->gcid)) {
            return $this->gcid;
        } else {
            return null;
        }
    }

    public function setGcid($gcid = null) {
        ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();
        return $this;
    }

    public function getCustomer_id() {
        if (isset($this->customer_id)) {
            return $this->customer_id;
        } else {
            return null;
        }
    }

    public function setCustomer_id(int $customer_id) {
        $this->customer_id = $customer_id;
    }

    public function getAccess_plan_id() {
        if (isset($this->access_plan_id)) {
            return $this->access_plan_id;
        } else {
            return null;
        }
    }

    public function setAccess_plan_id(int $access_plan_id) {
        $this->access_plan_id = $access_plan_id;
    }

    public function getCurrency_id() {
        if (isset($this->currency_id)) {
            return $this->currency_id;
        } else {
            return null;
        }
    }

    public function setCurrency_id(int $currency_id) {
        $this->currency_id = $currency_id;
    }

    public function getPrice() {
        if (isset($this->price)) {
            return $this->price;
        } else {
            return null;
        }
    }

    public function setPrice(string $price) {
        $this->price = $price;
    }

    public function getDiscount() {
        if (isset($this->discount)) {
            return $this->discount;
        } else {
            return null;
        }
    }

    public function setDiscount(string $discount) {
        $this->discount = $discount;
    }

    public function getDate_start() {
        if (isset($this->date_start)) {
            return $this->date_start;
        } else {
            return null;
        }
    }

    public function setDate_start(string $date_start) {
        $this->date_start = $date_start;
    }

    public function getDate_end() {
        if (isset($this->date_end)) {
            return $this->date_end;
        } else {
            return null;
        }
    }

    public function setDate_end(string $date_end) {
        $this->date_end = $date_end;
    }

    public function getAuto_renew() {
        if (isset($this->auto_renew)) {
            return $this->auto_renew;
        } else {
            return null;
        }
    }

    public function setAuto_renew(int $auto_renew) {
        $this->auto_renew = $auto_renew;
    }

    public function getAuto_renew_accepted_at() {
        if (isset($this->auto_renew_accepted_at)) {
            return $this->auto_renew_accepted_at;
        } else {
            return null;
        }
    }

    public function setAuto_renew_accepted_at($auto_renew_accepted_at) {
        $this->auto_renew_accepted_at = $auto_renew_accepted_at;
    }

    public function getRenewal_cycle() {
        if (isset($this->renewal_cycle)) {
            return $this->renewal_cycle;
        } else {
            return null;
        }
    }

    public function setRenewal_cycle($renewal_cycle) {
        $this->renewal_cycle = $renewal_cycle;
    }

    public function getDate_renovation() {
        if (isset($this->date_renovation)) {
            return $this->date_renovation;
        } else {
            return null;
        }
    }

    public function setDate_renovation(string $date_renovation) {
        $this->date_renovation = $date_renovation;
    }

    public function getAccess_plan_coupon_id() {
        if (isset($this->access_plan_coupon_id)) {
            return $this->access_plan_coupon_id;
        } else {
            return null;
        }
    }

    public function setAccess_plan_coupon_id(int $access_plan_coupon_id) {
        $this->access_plan_coupon_id = $access_plan_coupon_id;
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setStatus(int $status) {
        $this->status = $status;
    }

    public function getUser_id_updated() {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function setUser_id_updated(int $user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    /**
     * Retorna o preço total e a data de renovação com base no ciclo.
     */
    public function calculateCycleDetails(float $basePrice, string $cycle): array {
        if ($cycle === "anual") {
            return [
                'price' => $basePrice * (int) env('PAG_CYCLE_ANUAL_X_PRICE'),
                'date' => date('Y-m-d H:i:s', strtotime('+1 year'))
            ];
        }
        // Padrão Mensal
        return [
            'price' => $basePrice,
            'date' => date('Y-m-d H:i:s', strtotime('+31 days'))
        ];
    }

    /**
     * Garante que a infraestrutura de pastas do cliente exista.
     */
    public function prepareSignatureDirectory(string $clientGcid, string $signatureGcid, $config): void {
        $basePath = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getBaseFileClient();
        $targetDir = $basePath . "/client/{$clientGcid}/signatures/{$signatureGcid}";

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
            mkdir($targetDir . "/invoices", 0777, true);
        }
    }
}

?>