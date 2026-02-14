<?php

namespace Microfw\Src\Main\Common\Entity\Public;

/**
 * Description of McConfig
 *
 * @author ARGomes
 */
//TODO: Classe responsável por gerir as configurações do site 
//Não alterar sem ter os conhecimentos necessários

class McClientConfig{

    //config geral
    private $db = ""; //define o DataBase do sistema (1 = Mysql)    
    private $domain = "";
    private $urlPublic = "";
    private $folderPublic = "";
    private $urlApi = "";
    private $folderApi = "";
    private $urlHttp = "";
    private $siteTitle = "";
    private $pageHomeClient = "";
    private $baseFile = "";
    private $baseFileClient = "";
    private $cache = "";
    private $folderPublicHtml = "";
    private $reChaveSecretKey = "";
    private $reChaveSiteKey = "";
    private $maintenance_client = "";
    private $cloudflare_client = "";
    private $theme_custom_client = "";
    private $youtube_search_list;
    private $api_free_rate = "";
    private $api_key_client_free = "";
    private $api_key_client_premium_system = "";
    private $api_key_client_premium_byok = "";

    public function __construct() {
        $this->db = env('DB_ID');
        $this->domain = env('DOMAIN');
        $this->urlPublic = env('URL_PUBLIC');
        $this->folderPublic = env('FOLDER_PUBLIC');
        $this->urlApi = env('URL_API');
        $this->folderApi = env('FOLDER_API');
        $this->urlHttp = env('URL_HTTP');
        $this->pageHomeClient = env('PAGE_HOME_CLIENT');
        $this->siteTitle = env('SITE_TITLE');
        $this->baseFile = env('BASE_FILE');
        $this->baseFileClient = env('BASE_FILE_CLIENT');
        $this->cache = env('CACHE_PATH');
        $this->folderPublicHtml = env('FOLDER_PUBLIC_HTML');
        $this->reChaveSecretKey = env('GOOGLE_RE_SECRET_KEY');
        $this->reChaveSiteKey = env('GOOGLE_RE_SITE_KEY');
        $this->maintenance_client = env('MAINTENANCE_CLIENT');
        $this->cloudflare_client = env('CLOUDFLARE_CLIENT');
        $this->theme_custom_client = env('THEME_CUSTOM_CLIENT');
        $this->youtube_search_list = env('YOUTUBE_SEARCH_LIST');
        $this->api_free_rate = env('API_FREE_RATE');
        $this->api_key_client_free = env('API_KEY_CLIENT_FREE');
        $this->api_key_client_premium_system = env('API_KEY_CLIENT_PREMIUM_SYSTEM');
        $this->api_key_client_premium_byok = env('API_KEY_CLIENT_PREMIUM_BYOK');
    }

    public function getDb() {
        return $this->db;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function getUrlPublic() {
        return $this->urlPublic;
    }

    public function getFolderPublic() {
        return $this->folderPublic;
    }

    public function getUrlApi() {
        return $this->urlApi;
    }

    public function getFolderApi() {
        return $this->folderApi;
    }

    public function getUrlHttp() {
        return $this->urlHttp;
    }

    public function getPageHomeClient() {
        return $this->pageHomeClient;
    }

    public function getSiteTitle() {
        return $this->siteTitle;
    }

    public function getBaseFile() {
        return $this->baseFile;
    }

    public function getBaseFileClient() {
        return $this->baseFileClient;
    }

    public function getFolderPublicHtml() {
        return $this->folderPublicHtml;
    }

    public function getCache() {
        return $this->cache;
    }

    public function getReChaveSecretKey() {
        return $this->reChaveSecretKey;
    }

    public function getReChaveSiteKey() {
        return $this->reChaveSiteKey;
    }

    public function getMaintenance_client() {
        return $this->maintenance_client;
    }

    public function getCloudflare_client() {
        return $this->cloudflare_client;
    }

    public function getTheme_custom_client() {
        return $this->theme_custom_client;
    }

    public function getYoutube_search_list() {
        return $this->youtube_search_list;
    }

    public function getApi_free_rate() {
        return $this->api_free_rate;
    }

    public function getApi_key_client_free() {
        return $this->api_key_client_free;
    }

    public function getApi_key_client_premium_system() {
        return $this->api_key_client_premium_system;
    }

    public function getApi_key_client_premium_byok() {
        return $this->api_key_client_premium_byok;
    }
}