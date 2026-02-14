<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of StConfig
 *
 * @author Ricardo Gomes
 */
class StConfig extends ModelClass {

    protected $table_db = "stconfig";
    private $table_db_primaryKey = "id";
    private $id;
    private string $title;
    private string $ico;
    private string $favicon;
    private string $footer;
    private string $logo;
    private string $tag_key_words;
    private string $tag_description;
    private string $tag_title;
    private string $whatsapp;
    private int $maintenance;

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

    /**
     * Get the value of title
     */
    public function getTitle() {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of ico
     */
    public function getIco() {
        if (isset($this->ico)) {
            return $this->ico;
        } else {
            return null;
        }
    }

    /**
     * Set the value of ico
     *
     * @return  self
     */
    public function setIco($ico) {
        $this->ico = $ico;

        return $this;
    }

    public function getFavicon() {
        if (isset($this->favicon)) {
            return $this->favicon;
        } else {
            return null;
        }
    }

    public function setFavicon(string $favicon) {
        $this->favicon = $favicon;

        return $this;
    }

    /**
     * Get the value of footer
     */
    public function getFooter() {
        if (isset($this->footer)) {
            return $this->footer;
        } else {
            return null;
        }
    }

    /**
     * Set the value of footer
     *
     * @return  self
     */
    public function setFooter($footer) {
        $this->footer = $footer;

        return $this;
    }

    /**
     * Get the value of logo
     */
    public function getLogo() {
        if (isset($this->logo)) {
            return $this->logo;
        } else {
            return null;
        }
    }

    /**
     * Set the value of logo
     *
     * @return  self
     */
    public function setLogo($logo) {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get the value of tag_key_words
     */
    public function getTag_key_words() {
        if (isset($this->tag_key_words)) {
            return $this->tag_key_words;
        } else {
            return null;
        }
    }

    /**
     * Set the value of tag_key_words
     *
     * @return  self
     */
    public function setTag_key_words($tag_key_words) {
        $this->tag_key_words = $tag_key_words;

        return $this;
    }

    /**
     * Get the value of tag_description
     */
    public function getTag_description() {
        if (isset($this->tag_description)) {
            return $this->tag_description;
        } else {
            return null;
        }
    }

    /**
     * Set the value of tag_description
     *
     * @return  self
     */
    public function setTag_description($tag_description) {
        $this->tag_description = $tag_description;

        return $this;
    }

    /**
     * Get the value of tag_title
     */
    public function getTag_title() {
        if (isset($this->tag_title)) {
            return $this->tag_title;
        } else {
            return null;
        }
    }

    /**
     * Set the value of tag_title
     *
     * @return  self
     */
    public function setTag_title($tag_title) {
        $this->tag_title = $tag_title;

        return $this;
    }

    /**
     * Get the value of whatsapp
     */
    public function getWhatsapp() {
        if (isset($this->whatsapp)) {
            return $this->whatsapp;
        } else {
            return null;
        }
    }

    /**
     * Set the value of whatsapp
     *
     * @return  self
     */
    public function setWhatsapp($whatsapp) {
        $this->whatsapp = $whatsapp;

        return $this;
    }

    /**
     * Get the value of maintenance
     */
    public function getMaintenance() {
        if (isset($this->maintenance)) {
            return $this->maintenance;
        } else {
            return null;
        }
    }

    /**
     * Set the value of maintenance
     *
     * @return  self
     */
    public function setMaintenance($maintenance) {
        $this->maintenance = $maintenance;

        return $this;
    }
}
