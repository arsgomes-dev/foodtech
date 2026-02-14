<?php

namespace Microfw\Src\Main\Common\Entity\Public;

class Mailer {

    private string $host;
    private string $username;
    private string $passwd;
    private int $port;
    private string $name;

    public function __construct() {
        $this->host = env('EMAIL_HOST');
        $this->username = env('EMAIL_USERNAME');
        $this->passwd = env('EMAIL_PASSWD');
        $this->port = env('EMAIL_PORT');
        $this->name = env('EMAIL_NAME');
    }

    /**
     * Get the value of host
     */
    public function getHost() {
        if (isset($this->host)) {
            return $this->host;
        } else {
            return null;
        }
    }

    /**
     * Set the value of host
     *
     * @return  self
     */
    public function setHost($host) {
        $this->host = $host;

        return $this;
    }

    /**
     * Get the value of username
     */
    public function getUsername() {
        if (isset($this->username)) {
            return $this->username;
        } else {
            return null;
        }
    }

    /**
     * Set the value of username
     *
     * @return  self
     */
    public function setUsername($username) {
        $this->username = $username;

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
     * Get the value of port
     */
    public function getPort() {
        if (isset($this->port)) {
            return $this->port;
        } else {
            return null;
        }
    }

    /**
     * Set the value of port
     *
     * @return  self
     */
    public function setPort($port) {
        $this->port = $port;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName() {
        if (isset($this->name)) {
            return $this->name;
        } else {
            return null;
        }
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }
}
