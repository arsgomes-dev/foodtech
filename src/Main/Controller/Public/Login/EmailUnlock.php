<?php

namespace Microfw\Src\Main\Controller\Public\Login;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\Notification;
use Microfw\Src\Main\Common\Entity\Public\CronEmail;

/**
 * Description of EmailUnlock
 *
 * @author ARGomes
 */
class EmailUnlock {

    public static function email_unlock($email, $username) {
        $config = new McClientConfig();
        $notificationSearch = new Notification;
        $notificationSearch->setDescription_type("sys_blocked_account");
        $notifications = $notificationSearch->getQuery();
        $notificationsCount = count($notifications);
        $notification = new Notification;
        $notification = $notifications[0];
        $endereco_http = $config->getDomain() . "/" . $config->getUrlPublic();
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
        $pattern = array('{{{customer.name}}}', '{{{customer.date}}}', '{{{customer.hour}}}', '{{{website.title}}}', '{{{website.http}}}');
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
