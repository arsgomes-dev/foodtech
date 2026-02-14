<?php

use Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\EfiSignaturePaymentService;
use Efi\Exception\EfiException;
use Efi\EfiPay;

$client_id = (env('EFI_SANDBOX')) ? env('EFI_HOMOLOGATION_CLIENT_ID') : env('EFI_PRODUCTION_CLIENT_ID');
$client_secret = (env('EFI_SANDBOX')) ? env('EFI_HOMOLOGATION_CLIENT_SECRET') : env('EFI_PRODUCTION_CLIENT_SECRET');

$options = [
    "clientId" => $client_id,
    "clientSecret" => $client_secret,
    "sandbox" => env('EFI_SANDBOX'),
];

$token = $_POST["notification"];
$params = ['token' => $token];

try {
    $api = new EfiPay($options);
    $notification = $api->getNotification($params, []);

    $last = end($notification["data"]); // Pega o último status
    if ($last && !empty($last["identifiers"]["charge_id"]) && !empty($last['custom_id']) && !empty($last["status"]["current"])) {

        $service = new EfiSignaturePaymentService();
        $response = $service->updatePaymentStatus(
                chargeId: $last["identifiers"]["charge_id"],
                customerGcid: $last['custom_id'],
                newStatus: $last["status"]["current"],
                changedBy: "webhook",
                oldStatus: $last["status"]["previous"] ?? null
        );

        print_r($response);
    } else {
        print_r(['allowed' => false, 'message' => 'O webhook não informou os dados corretamente.']);
    }
} catch (EfiException $e) {
    print_r(['allowed' => false, 'message' => $e->errorDescription]);
} catch (Exception $e) {
    print_r(['allowed' => false, 'message' => $e->getMessage()]);
}
