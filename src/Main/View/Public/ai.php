<?php

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Microfw\Src\Main\Common\Entity\Public\AIPreference;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;

$config = new McClientConfig();
//importa o arquivo de tradução para os retornos da página
$translate = new Translate();

$intelligence = new AIPreference();
$intelligence = $intelligence->getQuery(customWhere: [['column' => 'customer_gcid', 'value' => $_SESSION['client_gcid']], ['column' => 'channel_gcid', 'value' => $_SESSION['active_workspace_gcid']]]);
header("Content-Type: application/json; charset=UTF-8");
    $dados[] = [
        'model_channel' => $intelligence->getChannel_gcid() ?? '',
        'model_code' => $intelligence->getGcid() ?? '',
        'model_gcid' => $intelligence->getGcid() ?? '',
        'model_title' => $intelligence->getTitle() ?? '',
        'model_style' => $intelligence->getStyle() ?? '',
        'model_tone' => $intelligence->getTone() ?? '',
        'model_voice_rules' => $intelligence->getVoice_rules() ?? '',
        'model_reference_channels' => $intelligence->getReference_channels() ?? '',
        'model_target_audience' => $intelligence->getTarget_audience() ?? '',
        'model_niche' => $intelligence->getNiche() ?? '',
        'model_video_goal' => $intelligence->getVideo_goal() ?? '',
        'model_unique_value' => $intelligence->getUnique_value() ?? '',
        'model_brand_guidelines' => $intelligence->getBrand_guidelines() ?? '',
        'model_video_length' => $intelligence->getVideo_length() ?? '',
        'model_video_style' => $intelligence->getVideo_style() ?? '',
        'model_editing_style' => $intelligence->getEditing_style() ?? '',
        'model_hook_type' => $intelligence->getHook_type() ?? '',
        'model_cta_type' => $intelligence->getCta_type() ?? '',
        'model_analysis_type' => $intelligence->getAnalysis_type() ?? '',
        'model_seo_focus' => $intelligence->getSeo_focus() ?? '',
        'model_retention_focus' => $intelligence->getRetention_focus() ?? '',
        'model_structure_rules' => $intelligence->getStructure_rules() ?? '',
        'model_forbidden_words' => $intelligence->getForbidden_words() ?? '',
        'model_priority_points' => $intelligence->getPriority_points() ?? '',
        'model_temperature' => $intelligence->getTemperature() ?? '',
        'model_max_length' => $intelligence->getMax_length() ?? '',
        'model_language_level' => $intelligence->getLanguage_level() ?? '',
        'model_additional_instructions' => $intelligence->getAdditional_instructions() ?? ''
    ];


if (!$dados) {
    echo json_encode(['success' => false, 'message' => $translate->translate('Modelos não localizados!', $_SESSION['client_lang'])]);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => $dados
        ], JSON_UNESCAPED_UNICODE);

exit;
