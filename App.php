<?php

namespace Yapay;

use Exception;

class App
{
    private $urlHomologation = "https://api.intermediador.sandbox.yapay.com.br/api/v3/transactions/payment";
    private $urlProduction = "https://api.intermediador.yapay.com.br/api/v3/transactions/payment";

    private $urlAPI = "";

    public function __construct(string $environment)
    {
        switch ($environment) {
            case Settings::YAPAY_ENVIRONMENT_HOMOLOGATION:
                $this->urlAPI = $this->urlHomologation;
                break;

            default:
                $this->urlAPI = $this->urlProduction;
                break;
        }
    }

    public function callAPI($method, $data)
    {
        // Nothing wrong, begin with creation of request

        $curlRequest = curl_init($this->urlAPI);

        switch ($method) {
            case 'POST':
                curl_setopt($curlRequest, CURLOPT_POST, true);
                if (!empty($data)) {
                    curl_setopt($curlRequest, CURLOPT_POSTFIELDS, json_encode($data));
                }
                break;

            case 'PUT':
                curl_setopt($curlRequest, CURLOPT_PUT, true);
                curl_setopt($curlRequest, CURLOPT_POSTFIELDS, json_encode($data));
                break;

            default:
                if ($data) {
                    $this->urlAPI = sprintf("%s?%s", $this->urlAPI, http_build_query($data));
                }

                break;
        }


        // OPTIONS:
        curl_setopt($curlRequest, CURLOPT_URL, $this->urlAPI);
        curl_setopt($curlRequest, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curlRequest, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlRequest, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlRequest, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($curlRequest, CURLOPT_TIMEOUT, 30);

        $result = curl_exec($curlRequest);

        if (!$result) {
            $curlStatusInfo = curl_getinfo($curlRequest);
            $message = "Failed to connect on Yapay";
            $responseError = sprintf("Response: %s", curl_error($curlRequest));
            $responseErrorCode = sprintf("Error Code: %s", curl_errno($curlRequest));

            $messageExceptionArray = [
                "message" => $message,
                "responseError" => $responseError,
                "responseErrorCode" => $responseErrorCode,
                "curlStatusInfo" => $curlStatusInfo
            ];

            // die(json_encode($messageExceptionArray));
            throw new Exception(sprintf("%s (%s - %s)", $message, $responseErrorCode, $responseError));
        }

        curl_close($curlRequest);

        return $result;
    }
}
