<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of FoodGroup
 *
 * @author Ricardo Gomes
 */
class FoodGroup extends ModelClass {

    protected $table_db = "food_group";
    protected $table_columns_like_db = ['description'];
    protected $table_db_primaryKey = "id";
    private $id;
    private $user_id_created;
    private $user_id_updated;
    private string $description;

    public function getId() {
        if (isset($this->id)) {
            return $this->id;
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

    public function getDescription() {
        if (isset($this->description)) {
            return $this->description;
        } else {
            return null;
        }
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUser_id_created($user_id_created) {
        $this->user_id_created = $user_id_created;
    }

    public function setUser_id_updated($user_id_updated) {
        $this->user_id_updated = $user_id_updated;
    }

    public function setDescription(string $description) {
        $this->description = $description;
    }
}
