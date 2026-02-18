<?php

session_start();

use Microfw\Src\Main\Controller\Public\Login\ProtectedPage;

ProtectedPage::protectedPage();

use Microfw\Src\Main\Common\Entity\Public\ClientNutritionalProfile;
use Microfw\Src\Main\Common\Entity\Public\McClientConfig;
use Microfw\Src\Main\Common\Entity\Public\Client;

$config = new McClientConfig;

header('Content-Type: application/json; charset=UTF-8');

// lê payload (aceita application/x-www-form-urlencoded ou raw JSON)
$input = $_POST;
if (empty($input)) {
    $raw = file_get_contents('php://input');
    $decoded = json_decode($raw, true);
    if (is_array($decoded))
        $input = $decoded;
}

if (empty($input) || empty($_SESSION['client_id'])) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos']);
    exit;
}

try {
    // atualizar sexo do cliente se for enviado e cliente não tiver
    if (!empty($input['sex']) && !empty($_SESSION['client_id'])) {
        $client = new Client();
        $clientData = $client->getQuery(single: true, customWhere: [['column' => 'id', 'value' => $_SESSION['client_id']]]);
        if ($clientData && empty($clientData->getGender())) {
            $client->setId($_SESSION['client_id']);
            $client->setGender($input['sex']);
            $client->setSaveQuery();
        }
    }

    $profile = new ClientNutritionalProfile();
    if (!isset($input['code'])) {
        $gcid = $profile->getGenerateUniqueGcid(new ClientNutritionalProfile);
        $profile->setGcid($gcid);
    }
    $profile->setCustomer_id((int) $_SESSION['client_id']);
    if (isset($input['height']))
        $profile->setHeight(floatval($input['height']));
    if (isset($input['weight']))
        $profile->setCurrent_weight(floatval($input['weight']));
    // aceitar activity_id ou activity
    if (isset($input['activity_id']))
        $profile->setActivity_level_id((int) $input['activity_id']);
    if (isset($input['factor']))
        $profile->setActivity_level(floatval($input['factor']));
    // aceitar goal_id ou goal ou meta
    if (isset($input['goal_id']))
        $profile->setMeta_id((int) $input['goal_id']);
    elseif (isset($input['goal']))
        $profile->setMeta_id((int) $input['goal']);
    elseif (isset($input['meta']))
        $profile->setMeta_id((int) $input['meta']);

    if (isset($input['imc']))
        $profile->setImc($input['imc']);
    if (isset($input['tmb']))
        $profile->setTmb($input['tmb']);
    if (isset($input['calories']))
        $profile->setNecessary_calories($input['calories']);
    if (isset($input['proteins']))
        $profile->setProteins_g($input['proteins']);
    if (isset($input['carbs']))
        $profile->setCarbohydrates_g($input['carbs']);
    if (isset($input['fats']))
        $profile->setLipids_g($input['fats']);
    if (isset($input['usedWeight']))
        $profile->setUsed_weight($input['usedWeight']);
    if (isset($input['water_ml']))
        $profile->setWater_ml($input['water_ml']);

    $saved = $profile->setSaveQuery();
    if ($saved == 1) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}


