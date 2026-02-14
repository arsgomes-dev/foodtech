<?php

namespace Microfw\Src\Main\Controller\Admin\Login;

use Microfw\Src\Main\Common\Entity\Admin\User;
use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\Notification;
use Microfw\Src\Main\Common\Entity\Admin\CronEmail;

/**
 * Description of EmailUnlock
 *
 * @author ARGomes
 */
class EmailUnlock {

    public static function email_unlock($email, $username) {
        $config = new McConfig();
        $notification = new Notification;
        $notification = $notification->getQuery(single: true, customWhere: [['column' => 'description_type', 'value' => "user_blocked_account"]]);
        $endereco_http = $config->getDomainAdmin() . "/" . $config->getUrlAdmin();
        $title_website = $config->getSiteTitle();
        // {{user.name}} -> nome do usuário
        // {{user.password}} -> senha provisória
        // {{user.date}} -> data
        // {{user.hour}} -> hora
        // {{website.title}} -> titulo do site (configurações)
        // {{website.http}} -> endereço http do site (configurações)
        //email a ser enviado
        date_default_timezone_set('America/Bahia');
        $date = date('d-m-Y');
        $hour = date('H:i', time());
        $pattern = array('{{{user.name}}}', '{{{user.date}}}', '{{{user.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
        $replacement = array($username, $date, $hour, $title_website, $endereco_http);
        $subject = $notification->getTitle();
        $messageSend = $notification->getDescription();
        for ($i = 0; $i < count($pattern); $i++) {
            $subject = preg_replace($pattern[$i], $replacement[$i], $subject);
            $messageSend = preg_replace($pattern[$i], $replacement[$i], $messageSend);
        }
        $cron = new CronEmail();
        $cron->setEmail($email);
        $cron->setNamemailer($username);
        $cron->setSubject($subject);
        $cron->setMessagesend($messageSend);
        $cron->setStatus(1);
        $cron->setSaveQuery();
    }
}
