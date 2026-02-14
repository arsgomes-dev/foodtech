<?php

namespace Microfw\Src\Main\Common\Entity\Admin;

/**
 * Description of McConfig
 *
 * @author ARGomes
 */
//TODO: Classe responsável por gerir as configurações do site 
//Não alterar sem ter os conhecimentos necessários

class McConfig{

    //config geral
    private $db = ""; //define o DataBase do sistema (1 = Mysql)    
    private $domain = "";
    private $domainAdmin = "";
    private $urlAdmin = "";
    private $folderAdmin = "";
    private $urlPublic = "";
    private $folderPublic = "";
    private $urlApi = "";
    private $folderApi = "";
    private $urlController = "";
    private $urlHttp = "";
    private $siteTitle = "";
    private $pageHome = "";
    private $pageHomeClient = "";
    private $baseFile = "";
    private $baseFileAdmin = "";
    private $baseFileClient = "";
    private $cache = "";
    private $folderPublicHtml = "";
    private $reChaveSecretKey = "";
    private $reChaveSiteKey = "";
    private $maintenance_admin = "";
    private $maintenance_client = "";
    private $cloudflare_admin = "";
    private $cloudflare_client = "";
    private $theme_custom_admin = "";
    private $theme_custom_client = "";
    private $youtube_search_list;

    public function __construct() {
        $this->db = env('DB_ID');
        $this->domain = env('DOMAIN');
        $this->domainAdmin = env('DOMAIN_ADMIN');
        $this->urlAdmin = env('URL_ADMIN');
        $this->folderAdmin = env('FOLDER_ADMIN');
        $this->urlPublic = env('URL_PUBLIC');
        $this->folderPublic = env('FOLDER_PUBLIC');
        $this->urlApi = env('URL_API');
        $this->folderApi = env('FOLDER_API');
        $this->urlController = env('URL_CONTROLLER');
        $this->urlHttp = env('URL_HTTP');
        $this->pageHome = env('PAGE_HOME_ADMIN');
        $this->pageHomeClient = env('PAGE_HOME_CLIENT');
        $this->siteTitle = env('SITE_TITLE');
        $this->baseFile = env('BASE_FILE');
        $this->baseFileAdmin = env('BASE_FILE_ADMIN');
        $this->baseFileClient = env('BASE_FILE_CLIENT');
        $this->cache = env('CACHE_PATH');
        $this->folderPublicHtml = env('FOLDER_PUBLIC_HTML');
        $this->reChaveSecretKey = env('GOOGLE_RE_SECRET_KEY');
        $this->reChaveSiteKey = env('GOOGLE_RE_SITE_KEY');
        $this->maintenance_admin = env('MAINTENANCE_ADMIN');
        $this->maintenance_client = env('MAINTENANCE_CLIENT');
        $this->cloudflare_admin = env('CLOUDFLARE_ADMIN');
        $this->cloudflare_client = env('CLOUDFLARE_CLIENT');
        $this->theme_custom_admin = env('THEME_CUSTOM_ADMIN');
        $this->theme_custom_client = env('THEME_CUSTOM_CLIENT');
        $this->youtube_search_list = env('YOUTUBE_SEARCH_LIST');
    }

    public function getDb() {
        return $this->db;
    }

    public function getDomain() {
        return $this->domain;
    }

    public function getDomainAdmin() {
        return $this->domainAdmin;
    }

    public function getUrlAdmin() {
        return $this->urlAdmin;
    }

    public function getFolderAdmin() {
        return $this->folderAdmin;
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

    public function getUrlController() {
        return $this->urlController;
    }

    public function getUrlHttp() {
        return $this->urlHttp;
    }

    public function getPageHome() {
        return $this->pageHome;
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

    public function getBaseFileAdmin() {
        return $this->baseFileAdmin;
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

    public function getMaintenance_admin() {
        return $this->maintenance_admin;
    }

    public function getMaintenance_client() {
        return $this->maintenance_client;
    }

    public function getCloudflare_admin() {
        return $this->cloudflare_admin;
    }

    public function getCloudflare_client() {
        return $this->cloudflare_client;
    }

    public function getTheme_custom_admin() {
        return $this->theme_custom_admin;
    }

    public function getTheme_custom_client() {
        return $this->theme_custom_client;
    }

    public function getYoutube_search_list() {
        return $this->youtube_search_list;
    }
}
