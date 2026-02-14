<?php

namespace Microfw\Src\Main\Common\Helpers\Admin\Translate;

use Microfw\Src\Main\Common\Entity\Admin\McConfig;
use NumberFormatter;

class Translate {

    public function translate($text, $lang) {
        $lang = ucfirst(strtolower($lang));
        $t = $text;
        $language = "";
        $lang_bool = false;
        if (isset($lang)) {
            switch ($lang) {
                case 'Pt_br':
                    $language = "";
                    $lang_bool = false;
                    break;
                case 'En':
                    $language = "en.php";
                    $lang_bool = true;
                    break;
                default:
                    $language = "";
                    $lang_bool = false;
                    break;
            }
            if ($lang_bool) {
                require $_SERVER['DOCUMENT_ROOT'] . '/src/Main/Functions/Api/V1/Translate/Language/' . $language;
                if (array_key_exists($t, $lang_translate)) {
                    $t = $lang_translate[$t];
                }
            }
        }
        return $t;
    }

    public function translateDate($date, $lang) {
        $lang = ucfirst(strtolower($lang));
        $t = $date;
        $language = "";
        $lang_bool = false;
        if (isset($lang)) {
            switch ($lang) {
                case 'Pt_br':
                    $t = ($date) ? date('d/m/Y', strtotime($date)) : "";
                    break;
                case 'En':
                    $t = ($date) ? date('Y-m-d', strtotime($date)) : "";
                    break;
                default:
                    $t = ($date) ? date('d/m/Y', strtotime($date)) : "";
                    break;
            }
        }
        return $t;
    }

    public function translateDatePicker($lang) {
        $lang = strtolower($lang);
        $scriptDate = "";
        if (isset($lang)) {
            switch ($lang) {
                case 'pt_br':
                    $scriptDate = '<script>$(document).ready(function () {$.datepicker.setDefaults($.datepicker.regional[ "pt-BR" ] );$(".data").datepicker({dateFormat: "dd/mm/yyyy"});});</script>';
                    break;
                case 'en_us':
                    $scriptDate = '<script>$(document).ready(function () {$.datepicker.setDefaults($.datepicker.regional[ "en-US" ] );$(".data").datepicker({dateFormat: "mm/dd/yyyy"});});</script>';
                    break;
                default:
                    $scriptDate = '<script>$(document).ready(function () {$.datepicker.setDefaults($.datepicker.regional[ "pt-BR" ] );$(".data").datepicker({dateFormat: "dd/mm/yyyy"});});</script>';
                    break;
            }
            $config = new McConfig;
            return $scriptDate;
        }
    }

    public function translateMonetary($valor, $currency, $locale) {
        if (isset($locale)) {
            switch ($locale) {
                case 'pt_BR':
                    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
                    $t = $formatter->formatCurrency($valor, $currency);
                    break;
                case 'en_US':
                    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
                    $t = $formatter->formatCurrency($valor, $currency);
                    break;
                default:
                    $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
                    $t = $formatter->formatCurrency($valor, $currency);
                    break;
            }
        }
        return $t;
    }

    public function translateMonetaryDoubleLocale($valor, $locale = 'auto') {
        $valor = preg_replace('/[^\d.,-]/', '', $valor);
        $valor = preg_replace('/[\x{00A0}\s]+/u', ' ', $valor);
        $valor = trim($valor);
        if ($locale === 'auto') {
            // Detecta locale automaticamente do sistema (ou configure manualmente)
            $locale = locale_get_default(); // Ex: 'pt_BR', 'en_US'
        }

        $fmt = new NumberFormatter($locale, NumberFormatter::DECIMAL);
        $number = $fmt->parse($valor);

        return $number !== false ? (float) $number : null;
    }

    public function translate_script($lang) {
        $lang = ucfirst(strtolower($lang));
        $language = "";
        if (isset($lang)) {
            switch ($lang) {
                case 'Pt_br':
                    $language = "pt_br.js";
                    break;
                case 'En':
                    $language = "en.js";
                    break;
                default:
                    $language = "pt_br.js";
                    break;
            }
            $config = new McConfig;
            return '<script src="' . $config->domainDir . '/libs/v1/js/plugins/language/' . $language . '"></script>';
        }
    }
}
