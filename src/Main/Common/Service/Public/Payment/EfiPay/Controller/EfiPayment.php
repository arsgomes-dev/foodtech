<?php

namespace Microfw\Src\Main\Common\Service\Public\Payment\EfiPay\Controller;

use Microfw\Src\Main\Common\Helpers\Public\Translate\Translate;
use Efi\Exception\EfiException;
use Efi\EfiPay;

class EfiPayment {

    function credit_card($type, $payment_token, $payment_method, $product, $price, $custom_id, $notification_url, $customer_name, $customer_cpf, $customer_contact, $customer_email, $customer_birth, $discount = null, $installments = 1) {
        $translate = new Translate();
        if ($type === env('EFI_DB_TYPE')) {
            if ($payment_method === "credit_card" && !empty($payment_token) && isset($payment_token)) {
                $paymentToken = $payment_token;

                $client_id = (env('EFI_SANDBOX')) ? env('EFI_HOMOLOGATION_CLIENT_ID') : env('EFI_PRODUCTION_CLIENT_ID');
                $client_secret = (env('EFI_SANDBOX')) ? env('EFI_HOMOLOGATION_CLIENT_SECRET') : env('EFI_PRODUCTION_CLIENT_SECRET');
                $certificate = (env('EFI_SANDBOX')) ? env('EFI_CERTIFICATE_HOMOLOGATION') : env('EFI_CERTIFICATE');
                $options = [
                    "clientId" => $client_id,
                    "clientSecret" => $client_secret,
                    "certificate" => env('EFI_CERTIFICATE'), // Obrigatório, com exceção da API Cobranças  | Caminho absoluto para o certificado no formato .p12 ou .pem, ou o certificado PEM convertido em base64
                    "pwdCertificate" => env('EFI_PWDCERTIFICATE'), // Opcional | Padrão = "" | Senha de criptografia do certificado
                    "sandbox" => env('EFI_SANDBOX'), // Opcional | Padrão = false | Define o ambiente de desenvolvimento entre Produção e Homologação
                    "debug" => env('EFI_DEBUG'), // Opcional | Padrão = false | Ativa/desativa os logs de requisições do Guzzle
                    "cache" => env('EFI_CACHE'), // Opcional | Padrão = true | Ativa/desativa o cache da autenticação e de certificados base64, otimizando e reduzindo o número de requisições
                    "timeout" => env('EFI_TIMEOUT'), // Opcional | Padrão = 30 | Define o tempo máximo de resposta das requisições
                    "responseHeaders" => env('EFI_RESPONSE_HEADERS'), //  Optional | Default = false || Ativa/desativa o retorno do header das requisições
                ];

                $items = [
                    [
                        "name" => $product,
                        "amount" => 1,
                        "value" => $price
                    ]
                ];
                $metadata = [
                    "custom_id" => $custom_id,
                    "notification_url" => $notification_url
                ];
                $customerDate = [
                    "name" => $customer_name,
                    "cpf" => preg_replace('/[^0-9]/', '', $customer_cpf),
                    "phone_number" => preg_replace('/[^0-9]/', '', $customer_contact),
                    "email" => $customer_email,
                    "birth" => $customer_birth
                ];
                $discountDate = [
                    "type" => "percentage",
                    "value" => $discount
                ];
                $credit_card = null;
                if ($discount !== null && !empty($discount)) {
                    $credit_card = [
                        "customer" => $customerDate,
                        "installments" => $installments,
                        "discount" => $discountDate,
                        "payment_token" => $paymentToken,
                        "message" => "This is a space\n of up to 80 characters\n to tell\n your client something"
                    ];
                } else {
                    $credit_card = [
                        "customer" => $customerDate,
                        "installments" => $installments,
                        "payment_token" => $paymentToken,
                        "message" => "This is a space\n of up to 80 characters\n to tell\n your client something"
                    ];
                }
                $payment = [
                    "credit_card" => $credit_card
                ];

                $body = [
                    "items" => $items,
                    "metadata" => $metadata,
                    "payment" => $payment
                ];
                try {
                    $api = new EfiPay($options);
                    $response = $api->createOneStepCharge($params = [], $body);
                    if ($response['code'] === 200) {
                        $data = $response['data'];
                        $charge_id = $data['charge_id'];
                        $paymentmethod = $data['payment'];
                        $status = $data['status'];
                        return ['allowed' => true, 'message' => $status, 'charge_id' => $charge_id, 'paymentmethod' => $paymentmethod, 'status' => $status];
                    } else {
                        return ['allowed' => false, 'message' => $translate->translate('Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.', $_SESSION['client_lang'])];
                    }
                } catch (EfiException $e) {
                    return ['allowed' => false, 'message' => $translate->translate('Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.', $_SESSION['client_lang'])];
                } catch (Exception $e) {
                    return ['allowed' => false, 'message' => $translate->translate('Ocorreu um erro ao processar o pagamento. Por favor, tente novamente.', $_SESSION['client_lang'])];
                }
            }
        }
    }

    /**
     * Realiza a comunicação direta com a API da Efí para estornar um cartão.
     * * @param string|int $chargeId ID da transação no gateway.
     * @return array Detalhes do sucesso ou erro da API.
     */
    public function executeGatewayRefund($chargeId): array {
        if (empty($chargeId)) {
            return ['allowed' => false, 'message' => 'ID da cobrança inválido.'];
        }

        $isSandbox = env('EFI_SANDBOX', true);
        $apiOptions = [
            "clientId" => $isSandbox ? env('EFI_HOMOLOGATION_CLIENT_ID') : env('EFI_PRODUCTION_CLIENT_ID'),
            "clientSecret" => $isSandbox ? env('EFI_HOMOLOGATION_CLIENT_SECRET') : env('EFI_PRODUCTION_CLIENT_SECRET'),
            "certificate" => $isSandbox ? env('EFI_CERTIFICATE_HOMOLOGATION') : env('EFI_CERTIFICATE'),
            "sandbox" => $isSandbox,
            "debug" => env('EFI_DEBUG', false)
        ];

        try {
            $apiInstance = new \Efi\EfiPay($apiOptions);
            $requestParams = ["id" => (int) $chargeId];

            // Executa o estorno no cartão de crédito via SDK
            $apiResponse = $apiInstance->refundCard($requestParams, []);

            return [
                'allowed' => true,
                'message' => 'Estorno realizado com sucesso!',
                'data' => $apiResponse
            ];
        } catch (\Efi\Exception\EfiException $e) {
            return [
                'allowed' => false,
                'message' => "Erro no Gateway (Efí): {$e->errorDescription}"
            ];
        } catch (\Exception $e) {
            return [
                'allowed' => false,
                'message' => 'Erro interno de processamento: ' . $e->getMessage()
            ];
        }
    }
}
