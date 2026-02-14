<?php

namespace Microfw\Src\Main\Common\Settings\Public;

use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\StConfig;
use Microfw\Src\Main\Common\Entity\Public\Company;
use Microfw\Src\Main\Common\Helpers\Public\CloudFlare\CloudFlare;
use Microfw\Src\Main\Controller\Public\AccessPlans\CheckPlan;

/**
 * Description of BaseHtml
 *
 * @author Ricardo Gomes
 */
class BaseHtml {

    function baseCSS() {
        $check = new CheckPlan;
        $check->checkPlan();
        $config = new McClientConfig();
        $stConfig = new StConfig();
        $st = $stConfig->getQuery(single: true,
                customWhere: [['column' => 'id', 'value' => 1]]);
        $website_title = (isset($st) ? $st->getTitle() : "");
        $website_favicon = (isset($st) ? "/ico/" . $st->getFavicon() : "");
        $stCompany = new Company();
        $stCompany = $stCompany->getQuery(single: true,
                customWhere: [['column' => 'id', 'value' => 1]]);

        $website_title = (isset($stCompany) ? $stCompany->getName_fantasy() : $website_title);
        $website_favicon = (isset($stCompany) ? "/logo/" . $stCompany->getLogo() : $website_favicon);

        $dir_favicon = "<link rel='icon' type='image/x-icon' href='" . $config->getBaseFile() . $website_favicon . "'>";
        $favicon = (($website_favicon !== "") ? $dir_favicon : "");
        $css = "";
        $css .= "<meta charset='utf-8'>";
        $css .= $favicon;
        $css .= "<meta name='viewport' content='width=device-width, initial-scale=1'>";
        $css .= "<title>" . $website_title . "</title>";
        $css .= "<script>!function(){var t=localStorage.getItem('theme')||'light';document.documentElement.setAttribute('data-theme',t)}();</script>";
        $css .= "<link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700&display=swap'>";
        $css .= "<link rel='stylesheet' href='/assets/fonts/css/all.min.css'>";
        $css .= "<link rel='stylesheet' href='/assets/vendor/bootstrap/css/bootstrap.min.css'>";
        $css .= "<link rel='stylesheet' href='/assets/vendor/lte/css/adminlte.css?v=3.2.0'>"; 
        $css .= "<link rel='stylesheet' href='/assets/css/custom/nutrition-theme-light.css'>";
        $css .= "<link rel='stylesheet' href='/assets/css/custom/nutrition-theme-dark.css'>";
        $css .= "<link rel='stylesheet' href='/assets/css/custom/nutrition-components.css'>";
       // $css .= "<link rel='stylesheet' href='/assets/css/custom2/additional_components.css'>";
       // $css .= "<link rel='stylesheet' href='/assets/css/custom2/custom_theme.css'>";
        return $css;
    }

    function baseCSSDate() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/assets/vendor/data/css/jquery-ui-1.10.4.custom.min.css'>";
        return $css;
    }

    function baseCSSAlert() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/assets/vendor/sweetalert2B/bootstrap-4.min.css'>";
        return $css;
    }

    function baseCSSICheck() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/assets/vendor/icheck-bootstrap/icheck-bootstrap.min.css'>";
        return $css;
    }

    function baseCSSValidate() {
        $css = "";
        $css .= "<link rel='stylesheet' href='/assets/vendor/validation/css/validation.min.css'>";
        return $css;
    }

    function baseJS() {
        $js = "";
        $js .= "<script src='/assets/vendor/jquery/jquery.min.js'></script>";
        $js .= "<script src='/assets/vendor/bootstrap/js/bootstrap.bundle.min.js'></script>";
        $js .= "<script src='/assets/vendor/lte/js/adminlte.min.js?v=3.2.0'></script>";
        $js .= "<script src='/assets/js/custom/theme-switcher.js'></script>";
        return $js;
    }

    function baseMenu($menus_active = null, $submenus_active = null) {

        $menu_active = (isset($menus_active)) ? $menus_active : "";
        $submenu_active = (isset($submenus_active)) ? $submenus_active : "";

        $config = new McClientConfig();
        //verifica se o sistema esta em modo de liberação de cache do CloudFlare e executa a aliberação do cache
        if ($config->getCloudflare_client() === true) {
            $cloudFlare = new CloudFlare;
            $cloudFlare->clean();
        }
        // menu topo
        require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/topbar.php");
        // menu lateral
            require_once trim($_SERVER['DOCUMENT_ROOT'] . "/src/Main/View/" . $config->getFolderPublic() . "/slidebar_base.php");
       
    }

    function baseBreadcrumb(String $title, $dir, String $active) {
        //Página -> URL
        //$directory["Home"] = "";
        $config = new McClientConfig();
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
            $breadcrumb .= ($value !== "") ? "<a href='" . $config->getDomain() . "/" . $config->getUrlPublic() . "/" . $value . "'>" : "";
            $breadcrumb .= $key;
            $breadcrumb .= ($value !== "") ? "</a>" : "";
            $breadcrumb .= "</li>";
        }
        $breadcrumb .= "<li class='breadcrumb-item active'>" . $active . "</li>";
        $breadcrumb .= "</ol></div></div></div></section>";
        return $breadcrumb;
    }
}
