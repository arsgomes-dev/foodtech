<?php

namespace Microfw\Src\Main\Controller\Admin\Login;

use Microfw\Src\Main\Common\Entity\Admin\LoginAttempts;

/**
 * Description of CheckBrute
 *
 * @author ARGomes
 */
class CheckBrute {

    public static function checkbrute($user) {
        $now = time();
        $valid_attempts = $now - (2 * 60 * 60);
        $attempts = new LoginAttempts();
        $count = $attempts->getCountSumQuery(
                customWhere: [['column' => 'user_id', 'value' => $user], ['column' => 'time', 'value' => $valid_attempts]]
        );
        if ($count['total_count'] >= 5) {
            return true;
        } else {
            return false;
        }
    }
}
