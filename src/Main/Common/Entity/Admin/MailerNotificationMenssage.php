<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of MailerNotification
 *
 * @author Ricardo Gomes
 */
class MailerNotificationMenssage extends ModelClass {

    protected $table_db = "mailer_notification_menssage";
    protected $table_columns_like_db = ['title'];
    private $table_db_primaryKey = "id";
    private int $id;
    private int $user_id_created;
    private int $user_id_updated;
    private String $title;
    private String $type;
    private String $title_email;
    private String $description_email;
    private int $status;

    public function getId(): int {
        if (isset($this->id)) {
            return $this->id;
        } else {
            return null;
        }
    }

    public function getUser_id_created(): int {
        if (isset($this->user_id_created)) {
            return $this->user_id_created;
        } else {
            return null;
        }
    }

    public function getUser_id_updated(): int {
        if (isset($this->user_id_updated)) {
            return $this->user_id_updated;
        } else {
            return null;
        }
    }

    public function getTitle(): String {
        if (isset($this->title)) {
            return $this->title;
        } else {
            return null;
        }
    }

    public function getType(): String {
        if (isset($this->type)) {
            return $this->type;
        } else {
            return null;
        }
    }

    public function getTitle_email(): String {
        if (isset($this->title_email)) {
            return $this->title_email;
        } else {
            return null;
        }
    }

    public function getDescription_email(): String {
        if (isset($this->description_email)) {
            return $this->description_email;
        } else {
            return null;
        }
    }

    public function getStatus(): int {
        if (isset($this->status)) {
            return $this->status;
        } else {
            return null;
        }
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUser_id_created(int $user_id_created): void {
        $this->user_id_created = $user_id_created;
    }

    public function setUser_id_updated(int $user_id_updated): void {
        $this->user_id_updated = $user_id_updated;
    }

    public function setTitle(String $title): void {
        $this->title = $title;
    }

    public function setType(String $type): void {
        $this->type = $type;
    }

    public function setTitle_email(String $title_email): void {
        $this->title_email = $title_email;
    }

    public function setDescription_email(String $description_email): void {
        $this->description_email = $description_email;
    }

    public function setStatus(int $status): void {
        $this->status = $status;
    }
}
