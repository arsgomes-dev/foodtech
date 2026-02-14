<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of MailerNotification
 *
 * @author Ricardo Gomes
 */
class MailerNotificationGroup extends ModelClass {

    protected $table_db = "mailer_notification_group";
    protected $table_columns_like_db = ['title'];
    private $table_db_primaryKey = "id";
    private int $id;
    private int $user_id_created;
    private int $user_id_updated;
    private String $title;

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
}
