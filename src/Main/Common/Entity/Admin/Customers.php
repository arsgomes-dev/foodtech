<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

use Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;

/**
 * Description of Customer
 *
 * @author Ricardo Gomes
 */
class Customers extends ModelClass {

    protected $table_db = "customer";
    protected $table_columns_like_db = ['name', 'email'];
    protected $table_columns_between_db = ['created_at'];
    //menor igual
    protected $table_columns_less_equal_db = ['date_end'];
    //maior igual
    protected $table_columns_greater_equal_db = ['date_start'];
    private $table_db_primaryKey = "id";
    protected $table_db_join = "id";
    private $id;
    private $google_id;
    private bool $gcid_generation = false;
    private string $gcid;
    private $language_id;
    private int $customer_type;
    private string $name;
    private string $cpf;
    private string $photo;
    private string $google_photo;
    private string $birth;
    private string $gender;
    private string $andress_cep;
    private string $andress_city;
    private string $andress_state;
    private string $andress_neighborhood;
    private string $andress_avenue;
    private string $andress_complement;
    private string $andress_number;
    private $contact;
    private string $email;
    private string $passwd;
    private string $salt;
    private string $token;
    private string $token_date;
    private string $code;
    private string $session_date;
    private string $session_date_last;
    private string $auth_token;
    private string $date_start;
    private string $date_end;
    private int $status;
    private string $public_key;
    private string $private_key;
    private string $aes;
    private int $is_premium;
    private int $terms;
    private string $token_ai;

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

    public function getGcid_generation() {
        return $this->gcid_generation;
    }

    public function setGcid_generation($gcid_generation) {
        $this->gcid_generation = $gcid_generation;
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
    }

    public function getGoogle_id() {
        if (isset($this->google_id)) {
            return $this->google_id;
        } else {
            return null;
        }
    }

    public function setGoogle_id($google_id) {
        $this->google_id = $google_id;
    }

    public function getLanguage_id() {
        if (isset($this->language_id)) {
            return $this->language_id;
        } else {
            return null;
        }
    }

    public function setLanguage_id($language_id) {
        $this->language_id = $language_id;
        return $this;
    }

    public function getName() {
        if (isset($this->name)) {
            return $this->name;
        } else {
            return null;
        }
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function getCpf() {
        if (isset($this->cpf)) {
            return $this->cpf;
        } else {
            return null;
        }
    }

    public function setCpf(string $cpf) {
        $tempCpf = str_replace(array('.', '-', '/'), "", $cpf);
        $this->cpf = $tempCpf;
    }

    public function getPhoto() {
        if (isset($this->photo)) {
            return $this->photo;
        } else {
            return null;
        }
    }

    public function setPhoto(string $photo) {
        $this->photo = $photo;
    }

    public function getGoogle_photo() {
        if (isset($this->google_photo)) {
            return $this->google_photo;
        } else {
            return null;
        }
    }

    public function setGoogle_photo(string $google_photo) {
        $this->google_photo = $google_photo;
    }

    public function getBirth() {
        if (isset($this->birth)) {
            return $this->birth;
        } else {
            return null;
        }
    }

    public function setBirth(string $birth) {
        $this->birth = $birth;
    }

    public function getGender() {
        if (isset($this->gender)) {
            return $this->gender;
        } else {
            return null;
        }
    }

    public function setGender(string $gender) {
        $this->gender = $gender;
    }

    public function getAndress_cep() {
        if (isset($this->andress_cep)) {
            return $this->andress_cep;
        } else {
            return null;
        }
    }

    public function setAndress_cep(string $andress_cep) {
        $this->andress_cep = $andress_cep;
    }

    public function getAndress_city() {
        if (isset($this->andress_city)) {
            return $this->andress_city;
        } else {
            return null;
        }
    }

    public function setAndress_city(string $andress_city) {
        $this->andress_city = $andress_city;
    }

    public function getAndress_state() {
        if (isset($this->andress_state)) {
            return $this->andress_state;
        } else {
            return null;
        }
    }

    public function setAndress_state(string $andress_state) {
        $this->andress_state = $andress_state;
    }

    public function getAndress_neighborhood() {
        if (isset($this->andress_neighborhood)) {
            return $this->andress_neighborhood;
        } else {
            return null;
        }
    }

    public function setAndress_neighborhood(string $andress_neighborhood) {
        $this->andress_neighborhood = $andress_neighborhood;
    }

    public function getAndress_avenue() {
        if (isset($this->andress_avenue)) {
            return $this->andress_avenue;
        } else {
            return null;
        }
    }

    public function setAndress_avenue(string $andress_avenue) {
        $this->andress_avenue = $andress_avenue;
    }

    public function getAndress_complement() {
        if (isset($this->andress_complement)) {
            return $this->andress_complement;
        } else {
            return null;
        }
    }

    public function setAndress_complement(string $andress_complement) {
        $this->andress_complement = $andress_complement;
    }

    public function getAndress_number() {
        if (isset($this->andress_number)) {
            return $this->andress_number;
        } else {
            return null;
        }
    }

    public function setAndress_number(string $andress_number) {
        $this->andress_number = $andress_number;
    }

    public function getContact() {
        if (isset($this->contact)) {
            return $this->contact;
        } else {
            return null;
        }
    }

    public function setContact($contact) {
        $this->contact = $contact;
    }

    public function getEmail() {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return null;
        }
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getPasswd() {
        if (isset($this->passwd)) {
            return $this->passwd;
        } else {
            return null;
        }
    }

    public function setPasswd($passwd) {
        $this->passwd = $passwd;
    }

    public function getSalt() {
        if (isset($this->salt)) {
            return $this->salt;
        } else {
            return null;
        }
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    /**
     * Get the value of token
     */
    public function getToken() {
        if (isset($this->token)) {
            return $this->token;
        } else {
            return null;
        }
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token) {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of token_date
     */
    public function getToken_date() {
        if (isset($this->token_date)) {
            return $this->token_date;
        } else {
            return null;
        }
    }

    /**
     * Set the value of token_date
     *
     * @return  self
     */
    public function setToken_date($token_date) {
        $this->token_date = $token_date;

        return $this;
    }

    /**
     * Get the value of code
     */
    public function getCode() {
        if (isset($this->code)) {
            return $this->code;
        } else {
            return null;
        }
    }

    /**
     * Set the value of code
     *
     * @return  self
     */
    public function setCode($code) {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of session_date
     */
    public function getSession_date() {
        if (isset($this->session_date)) {
            return $this->session_date;
        } else {
            return null;
        }
    }

    /**
     * Set the value of session_date
     *
     * @return  self
     */
    public function setSession_date($session_date) {
        $this->session_date = $session_date;

        return $this;
    }

    /**
     * Get the value of session_date_last
     */
    public function getSession_date_last() {
        if (isset($this->session_date_last)) {
            return $this->session_date_last;
        } else {
            return null;
        }
    }

    /**
     * Set the value of session_date_last
     *
     * @return  self
     */
    public function setSession_date_last($session_date_last) {
        $this->session_date_last = $session_date_last;
        return $this;
    }

    public function getAuth_token() {
        if (isset($this->auth_token)) {
            return $this->auth_token;
        } else {
            return null;
        }
    }

    public function setAuth_token($auth_token) {
        $this->auth_token = $auth_token;
    }

    public function getDate_start() {
        if (isset($this->date_start)) {
            return $this->date_start;
        } else {
            return null;
        }
    }

    public function setDate_start($date_start) {
        $this->date_start = $date_start;
    }

    public function getDate_end() {
        if (isset($this->date_end)) {
            return $this->date_end;
        } else {
            return null;
        }
    }

    public function setDate_end($date_end) {
        $this->date_end = $date_end;
    }

    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getIs_premium() {
        if (isset($this->is_premium)) {
            return $this->is_premium;
        } else {
            return null;
        }
    }

    public function setIs_premium($is_premium) {
        $this->is_premium = $is_premium;
    }

    public function setTerms($terms) {
        $this->terms = $terms;
    }

    public function getTerms() {
        if (isset($this->terms)) {
            return $this->terms;
        } else {
            return null;
        }
    }

    public function getPublic_key() {
        if (isset($this->public_key)) {
            return $this->public_key;
        } else {
            return null;
        }
    }

    public function setPublic_key($public_key) {
        $this->public_key = $public_key;
    }

    public function getPrivate_key() {
        if (isset($this->private_key)) {
            return $this->private_key;
        } else {
            return null;
        }
    }

    public function setPrivate_key($private_key) {
        $this->private_key = $private_key;
    }

    public function getAES() {
        if (isset($this->aes)) {
            return $this->aes;
        } else {
            return null;
        }
    }

    public function setAES($aes) {
        $this->aes = $aes;
    }

    public function getToken_ai() {
        if (isset($this->token_ai)) {
            return $this->token_ai;
        } else {
            return null;
        }
    }

    public function setToken_ai($token_ai) {
        $this->token_ai = $token_ai;
    }
}
