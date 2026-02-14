<?php

namespace Microfw\Src\Main\Common\Helpers\Admin\CloudFlare;

use Microfw\Src\Main\Common\Entity\Admin\CloudflareApi;

class CloudFlare {
    static public function clean() {
        $cloudflare = new CloudflareApi;
        $cloudflare = $cloudflare->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
        $head = [];
        $head[] = 'Content-Type: application/json';
        $head[] = 'X-Auth-Email: ' . $cloudflare->getCust_email();
        $head[] = 'Authorization: Bearer ' . $cloudflare->getCust_xauth();
        $head[] = 'cache-control: no-cache';
        $url = 'https://api.cloudflare.com/client/v4/zones/' . $cloudflare->getCust_zone() . '/purge_cache';
        $purge = ['purge_everything' => true];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($purge));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $json_output = json_decode($response, true);
        $json_output = "";
        curl_close($ch);
    }
}
