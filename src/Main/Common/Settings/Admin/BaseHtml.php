<?php

namespace Microfw\Src\Main\Common\Settings\Admin;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use Microfw\Src\Main\Common\Entity\Admin\StConfig;
use Microfw\Src\Main\Common\Entity\Admin\Company;
use Microfw\Src\Main\Common\Helpers\Admin\CloudFlare\CloudFlare;

/**
 * Description of BaseHtml
 *
 * @author Ricardo Gomes
 */
class BaseHtml {

    function baseCSS() {
        $config = new McConfig();
        $stConfig = new StConfig();
        $st = $stConfig->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);
        $website_title = (isset($st) ? $st->getTitle() : "");
        $website_favicon = (isset($st) ? "/ico/" . $st->getFavicon() : "");
        $stCompany = new Company();
        $stCompany = $stCompany->getQuery(single: true, customWhere: [['column' => 'id', 'value' => 1]]);

        $website_title = (isset($stCompany) ? $stCompany->getName_fantasy() : $website_title);
        $website_favicon = (isset($stCompany) ? "/logo/" . $stCompany->getLogo() : $website_favicon);
        
        $dir_favicon = "<link rel='icon' type='image/x-icon' href='" . $config->getBaseFile() . $website_favicon . "'>";
        $favicon = (($website_favicon !== "") ? $dir_favicon : "");
        $css = "";
        $css .= "<meta charset='utf-8'>";
        $css .= $favicon;
        $css .= "<meta name='viewport' content='width=device-width, initial-scale=1'>";
        $css .= "<title>" . $website_title . "</title>";
        $css .= "<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback'>";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/fontawesome-free/css/all.min.css'>";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/bootstrap/css/bootstrap.min.css'>";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/lte/css/adminlte.css?v=3.2.0'>";
        $css .= ($config->getTheme_custom_admin()) ? "<link rel='stylesheet' href='/libs/v1/admin/css/custom.css'>" : "";
        
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/css/doc.css'>";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/css/component.css'>";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/css/color.css'>";
        return $css;
    }

    function baseCSSDate() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/data/css/jquery-ui-1.10.4.custom.min.css'>";
        return $css;
    }

    function baseCSSAlert() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/sweetalert2B/bootstrap-4.min.css'>";
        return $css;
    }

    function baseCSSICheck() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css'>";
        return $css;
    }

    function baseCSSValidate() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/libs/v1/admin/plugins/validation/css/validation.min.css'>";
        return $css;
    }

    function baseJS() {
        $js = "";
        $js .= "<script src='/libs/v1/admin/plugins/jquery/jquery.min.js'></script>";
        $js .= "<script src='/libs/v1/admin/plugins/bootstrap/js/bootstrap.bundle.min.js'></script>";
        $js .= "<script src='/libs/v1/admin/plugins/lte/js/adminlte.min.js?v=3.2.0'></script>";
        return $js;
    }

    function baseMenu($menus_active = null, $submenus_active = null) {

        $menu_active = (isset($menus_active)) ? $menus_active : "";
        $submenu_active = (isset($submenus_active)) ? $submenus_active : "";

        $config = new McConfig();
        //verifica se o sistema esta em modo de liberação de cache do CloudFlare e executa a aliberação do cache
        if ($config->getCloudflare_admin() === true) {
            $cloudFlare = new CloudFlare;
            $cloudFlare->clean();
        }
        // menu topo
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "src/Main/View/" . $config->getFolderAdmin() . "/topbar.php");
        // menu lateral
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "src/Main/View/" . $config->getFolderAdmin() . "/slidebar.php");
    }

    function baseBreadcrumb(String $title, $dir, String $active) {
        //Página -> URL
        //$directory["Home"] = "";
        $config = new McConfig();
        $breadcrumb = "";
        $breadcrumb .= "<br><section class='content-header'>";
        $breadcrumb .= "<div class='container-fluid'>";
        $breadcrumb .= "<div class='row mb-2'>";
        $breadcrumb .= "<div class='col-sm-6'>";
        $breadcrumb .= "<h1>" . $title . "</h1>";
        $breadcrumb .= "</div>";
        $breadcrumb .= "<div class='col-sm-6'>";
        $breadcrumb .= "<ol class='breadcrumb float-sm-right'>";
        foreach ((array) $dir as $key => $value) {
            $breadcrumb .= "<li class='breadcrumb-item breadcrumb-item-color'>";
            $breadcrumb .= ($value !== "") ? "<a href='" . $config->getDomainAdmin() . "/" . $config->getUrlAdmin() . "/" . $value . "'>" : "";
            $breadcrumb .= $key;
            $breadcrumb .= ($value !== "") ? "</a>" : "";
            $breadcrumb .= "</li>";
        }
        $breadcrumb .= "<li class='breadcrumb-item active'>" . $active . "</li>";
        $breadcrumb .= "</ol></div></div></div></section>";
        return $breadcrumb;
    }
}
