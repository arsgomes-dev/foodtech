<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

use Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;
use DateTime;

/*Microfw\Src\Main\Common\Helpers\Admin\UniqueCode\GCID;
 * Description of User
 *
 * @author ARGomes
 */

class User extends ModelClass {

    protected $table_db = "users";
    protected $table_columns_like_db = ['email', 'name'];
    private $table_db_primaryKey = "id";
    protected $table_db_join = "employee_id";
    private $id;
    private $department_id;
    private $department_occupation_id;
    private bool $gcid_generation = false;
    private string $gcid;
    private $privilege_id;
    private $administrative;
    private string $email;
    private string $passwd;
    private string $salt;
    private string $token;
    private string $token_date;
    private string $code;
    private $status;
    private $status_agent;
    private string $name;
    private string $cpf;
    private string $photo;
    private string $wallpaper;
    private string $birth;
    private string $contact;
    private string $session_date;
    private string $session_date_last;
    private $language_id;
    private int $currency_id;
    private int $total;

    public function getTable_db_join() {
        if (isset($this->table_db_join)) {
            return $this->table_db_join;
        } else {
            return null;
        }
    }

    /**
     * Get the value of id
     */
    public function getId() {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    public function getDepartment_id() {
        if (isset($this->department_id)) {
            return $this->department_id;
        } else {
            return null;
        }
    }

    public function setDepartment_id($department_id) {
        $this->department_id = $department_id;
    }

    public function getDepartment_occupation_id() {
        if (isset($this->department_occupation_id)) {
            return $this->department_occupation_id;
        } else {
            return null;
        }
    }

    public function setDepartment_occupation_id($department_occupation_id) {
        $this->department_occupation_id = $department_occupation_id;
    }

    /**
     * Get the value of gcid_generation
     */
    public function getGcid_generation() {
        return $this->gcid_generation;
    }

    /**
     * Set the value of gcid_generation
     *
     * @return  self
     */
    public function setGcid_generation($gcid_generation) {
        $this->gcid_generation = $gcid_generation;

        return $this;
    }

    /**
     * Get the value of gcid
     */
    public function getGcid() {
        if (isset($this->gcid)) {
            return $this->gcid;
        } else {
            return null;
        }
    }

    /**
     * Set the value of gcid
     *
     * @return  self
     */
    public function setGcid($gcid = null) {
        ($gcid !== null) ? $this->gcid = $gcid : $this->gcid = (new GCID)->getGuidv4();
        return $this;
    }

    /**
     * Get the value of privilege_id
     */
    public function getPrivilege_id() {
        if (isset($this->privilege_id)) {
            return $this->privilege_id;
        } else {
            return null;
        }
    }

    /**
     * Set the value of privilege_id
     *
     * @return  self
     */
    public function setPrivilege_id($privilege_id) {
        $this->privilege_id = $privilege_id;

        return $this;
    }

    /**
     * Get the value of administrative
     */
    public function getAdministrative() {
        if (isset($this->administrative)) {
            return $this->administrative;
        } else {
            return null;
        }
    }

    /**
     * Set the value of administrative
     *
     * @return  self
     */
    public function setAdministrative($administrative) {
        $this->administrative = $administrative;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail() {
        if (isset($this->email)) {
            return $this->email;
        } else {
            return null;
        }
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of passwd
     */
    public function getPasswd() {
        if (isset($this->passwd)) {
            return $this->passwd;
        } else {
            return null;
        }
    }

    /**
     * Set the value of passwd
     *
     * @return  self
     */
    public function setPasswd($passwd) {
        $this->passwd = $passwd;

        return $this;
    }

    /**
     * Get the value of salt
     */
    public function getSalt() {
        if (isset($this->salt)) {
            return $this->salt;
        } else {
            return null;
        }
    }

    /**
     * Set the value of salt
     *
     * @return  self
     */
    public function setSalt($salt) {
        $this->salt = $salt;

        return $this;
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
     * Get the value of status
     */
    public function getStatus() {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus_agent() {
        if (isset($this->status_agent)) {
            return $this->status_agent;
        } else {
            return null;
        }
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus_agent($status_agent) {
        $this->status_agent = $status_agent;
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

    public function getWallpaper() {
        if (isset($this->wallpaper)) {
            return $this->wallpaper;
        } else {
            return null;
        }
    }

    public function setWallpaper(string $wallpaper) {
        $this->wallpaper = $wallpaper;
    }

    public function getBirth() {
        if (isset($this->birth)) {
            return $this->birth;
        } else {
            return null;
        }
    }

    public function setBirth(string $birth) {
        $this->birth = $this->getFormatDateToMySQL($birth);
    }

    public function getContact() {
        if (isset($this->contact)) {
            return $this->contact;
        } else {
            return null;
        }
    }

    public function setContact(string $contact) {
        $this->contact = $contact;
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

    public function getTotal() {
        if (isset($this->total)) {
            return $this->total;
        } else {
            return null;
        }
    }

    public function setTotal($total) {
        $this->total = $total;
    }
}
