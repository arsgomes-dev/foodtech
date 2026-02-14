<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace Microfw\Src\Main\Common\Helpers\Public\GetCep;

use Microfw\Src\Main\Common\Helpers\Public\GetJson\GetJson;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

/**
 * Description of GetAddress
 *
 * @author Ricardo Gomes
  ({ "cep": "", "logradouro": "", "complemento": "", "bairro": "", "localidade": "", "uf": "", "unidade": "", "ibge": "", "gia": "" })
 */
class GetAddress {
    /* function getAddressCached($cep) {
      $config = new McClientConfig();
      $dir_base = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml()  . $config->getCache() . "/cep/";
      $cacheFile = $dir_base . $cep . ".json";
      if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 86400)) {
      return json_decode(file_get_contents($cacheFile));
      }
      $getJson = new GetJson();
      $response = file_get_contents("http://viacep.com.br/ws/{$cep}/json/?callback=");
      if ($response) {
      file_put_contents($cacheFile, $response);
      return json_decode($response);
      }
      return null;
      } */

    private string $cacheDir;

    public function __construct() {
        $config = new McClientConfig();
        $this->cacheDir = $_SERVER['DOCUMENT_ROOT'] . $config->getFolderPublicHtml() . $config->getCache() . "/cep/";
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0775, true);
        }
    }

    public function getAddressCached(string $cep): ?array {
        $cep = preg_replace('/\D/', '', $cep);
        if (strlen($cep) !== 8) {
            error_log("CEP inválido: $cep");
            return null;
        }

        $cacheFile = "{$this->cacheDir}/{$cep}.json";

        // Retorna cache se existir e for recente (24h)
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 86400)) {
            $cached = file_get_contents($cacheFile);
            $data = json_decode($cached, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $data;
            }
        }

        $url = "https://viacep.com.br/ws/{$cep}/json/";

        // cURL seguro
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_USERAGENT => 'PHP-cURL',
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err || !$response) {
            error_log("Erro ao consultar ViaCEP: $err");
            return null;
        }

        $data = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE || isset($data['erro'])) {
            error_log("Erro ao decodificar JSON ou CEP inválido: $cep");
            return null;
        }

        // Salva cache
        file_put_contents($cacheFile, json_encode($data, JSON_UNESCAPED_UNICODE));
        if($data){
            echo ($data);
        return $data;
        }else{
            $retorno = ["erro"=>true];
            return $retorno;
        }
    }
}
/*
Exemplo de uso
 * <?php
use Microfw\Src\Main\Common\Helpers\Admin\GetCep\GetAddress;
use Microfw\Src\Main\Common\Entity\Admin\Address;

$getCep = new GetAddress();
$cep = "41213000";
$data = $getCep->getAddressCached($cep);

if ($data) {
  //  print_r($data);
       $address = new Address($data["cep"], $data["logradouro"], $data["bairro"], $data["localidade"],
                    $data["uf"], $data["estado"], $data["regiao"], $data["ibge"], $data["ddd"], $data["siafi"]);

            // Convertendo o objeto em JSON
            $json = json_encode($address);
            echo $json;
} else {
    echo "CEP não encontrado ou inválido.";
}
 * 
 *  */