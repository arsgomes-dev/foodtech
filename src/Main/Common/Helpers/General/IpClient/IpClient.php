<?php

namespace Microfw\Src\Main\Common\Helpers\General\IpClient;

class IpClient {

    function getClientIp(): string {
        $keys = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_REAL_IP',
            'HTTP_X_FORWARDED_FOR',
            'REMOTE_ADDR'
        ];

        foreach ($keys as $key) {
            if (!empty($_SERVER[$key])) {

                // Pode vir uma lista de IPs (X-Forwarded-For)
                $ipList = explode(',', $_SERVER[$key]);

                foreach ($ipList as $ip) {
                    $ip = trim($ip);

                    // Valida IPv4 e IPv6
                    if (filter_var($ip, FILTER_VALIDATE_IP)) {
                        return $ip;
                    }
                }
            }
        }

        return '0.0.0.0';
    }
}
