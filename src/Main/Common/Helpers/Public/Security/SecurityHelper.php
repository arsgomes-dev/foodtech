<?php

namespace Microfw\Src\Main\Common\Helpers\Public\Security;

class SecurityHelper {

    // Esta chave deve ficar no seu .env ou config, NUNCA no código fonte público
    // Deve ter 32 caracteres para AES-256-CBC
    private static $encryption_key = 'LS0tLS1CRUdJTiBQUklWQVRFIEtFWS0tLS0tCk1JSUpRZ0lCQURBTkJna3Foa2lHOXcwQkFRRUZBQVNDQ1N3d2dna29BZ0VBQW9JQ0FRQ1pGcE5mR2dDVk5kSHgKM2YzN1'
            . 'F1V1ZCSmZRMFZIUnNIMFhhSHd0amI1bktpZU0wZGdCWHVwYjdGa2RsTG1Vd1pPMFRLM2NkMys3MCtvWAo3VnNEVHhVL2tmVkx2bDV2NWN2QTR3enFaVjRnSlBrKzVEMkRnSlRzeGJSNlJqQUt4cjJ5ekJFanU'
            . '2S0padzNVCmlvMGE0S1ZncEVnZmlXSjNDNE5pcVUzdVo2LzRkSlNIUW5qdmtCaW85OHVpa2o2cjJHUyswby9mOURnK0xNRGUKOVkzcGVHM1FnS2RIVXVSZHU3SFNtdHVFL21tY1NYWTltazBaM1oyVUFMRGZ0OH'
            . 'llMzlEK3MxNzhaYnBYek9ObQpkNVJlWXUvSkM2ZkRteHZDWUpBamd2eDRXVkRseXJkbHRHTkFtMnRLNkswZU1ZMkwvNjRpZkZZa3RlR1hiTTFyCnBFdTZ5NEtIT1AwVWFDT3FOZkF6bks4U0lGU25RcVUrL1hBdEw5'
            . 'bW9qcGFPVkVZR09JTjNnL3BDcHJKZXAxZGYKLzhiSlNSRDdlSnk2eXNaOXRRMzFZTS9QdHVmQkxEUThZVXk4cU9nZjdFRy9CWVZ5VU0rQTQwV0gzYmVCQXQyNgpnZlo0VW9BK2JxTXFaU3BXakliUnUybmI4eldMWDh'
            . 'XT0VtazVNcGtCYVhEMDhpYy9oQ3lGa3psU3NpVkNmUlZMCkphK3czRTQzYjkyWWZqcko5WG9icGtXYUJtYnFHRDhOZVU2aU9FWGUvR0ZoM1Y5T3RxN1Z2SkFHa2cyMmRSZVMKakR0ZS92OXpFaGF4UUdrZkhJVjV2bFNkQW'
            . '4vVjJ2eDVoTDBJMUMwTzkrYitZR0hmRWMxbVFvSzBuc3VaL0RFcApvUWYxZ2dUSXlndWdXcGR3eFo0RStVb3h4MDFheHdJREFRQUJBb0lDQUVSRTV3K2dIdVpyaSthYW91cnNHRW51Ck5HMnhDeFhCNk9jSmQyY3hNTm44M'
            . 'klwYUFrUUtPZVVvYjAxYng2N283SitaR21lWSt1T2VTMlRFT3JRdERrSzkKS25ET3ducVFOZDhjNGVPZHRPNE16d0lXOHIrMEZiWEpMUVRpVEFaaVBySi9ncDAyemZNTWZBUnVqU0tSVCs3YgpGRGJNSTVjSEVWNXNOZzY5T'
            . '3FKSUN2eU96ak8zUk9nRktWQ2tlMEpUVEFvMUNHaE5Gcy9UVVdlY2hkNjZEKzdOCmtNdWowYWRqVHBlbTY0SlJtbk5SNTJMdGJyaThOY3VNeTFQWk9XMFlUckZtK0ZNQ1lxbEkrNWYwZDd2bnp4c1AKRVkrUG1qK0NwSVRSb3h'
            . 'sZ2EwMHVxNzYxUlJYSXVYNDhhUWR2L2JtVEdlclRHKzlmRk8xY1hmZEUyMmkvM01NVQpNOUZ4a09QWEtKTGV5WEl0RTA3aUVhWXdnbWZNSTRhbzgvbGxWSWVNZzRBMjU0d1RtbTk5SkJqVTJveGltSjk2CmVmK0IycWdFNTlOQW'
            . 'RzV2hFWmdYZmFzZlhDR09nUE5pdy9JVGdJdWJwR3VOVTk5RW9RTkpHQWl6ZmVkUkFhKzEKS1pDZ3lSWXQ3M2l4YlRraEpFWks1a2dRRUtUdmxaUUpEN1JIbDI5UVBGTjFRWDhWWlRwVFJRem1ZOUxqSlBROApjZ1VHU1BoeDdBR'
            . 'U5KZlRaYjZ2U3RFQy93WDd5VVBudU1Rd0JoVU9rYlp5Z2cvUXZQbFBPZVdlZkgxR2UyV0g4ClVHYTNKak43ZTZ3L3hJYUYvaFlkS0dxRXA2Y1VPdjVsYTFhZmtZeHFKd3JqMHhzN2hYc0xHQjhMOEJ5cmp3QzMKMHFRa0phOG9GUz'
            . 'RnUVQzOUxSSWhBb0lCQVFESnRUaEdBanoxNVZiMnJncFV6ZHE5MjJFMjZGelFZVXlxZHUwaQoyYitHTXN6SFl3T0RrSUEwaG11YkJRTTQ0UVk4MUlvU0NGVXBvK3V0bGVoMnhnV0ZyUm9zdnJOdVc1TElLYVVXCmVCNHQyeXdNZFFzb'
            . 'GlMalFJYmhoajVWUE4yZ2szVnliMVdSTFA4MUpPeXFWR3dKR0JLUjM5TlVuZS9od28vZHcKai9OdUdBUXVCK1RKUnZTK21tREp5Mmd2Wk13cFhhZWdMRkVkbjdMMG1DZG13aW5NbVVtVC9lUHlvN2wwbFpvWQpmMUJBUEtSWXdTNC8we'
            . 'VVHNkpUNGJzN0hrb1hFcDdNWVU5SUJVQTQ1UXh0WUR0TENCZ0xjdDg5ZTA1aVNlS0NwCk0zR3JkVGVtc1pTVWlVcklCMGh0RXJOM0xuY212ZWdRc0xDc1pFcGFoaUVMWXRmZkFvSUJBUURDU3pFSXVjR3cKaXlhVkx2QTZWaVpsRXp0dHh'
            . 'wMlpZQTIzL1EvenVSSDVTcDNiWDRkYlJtdTQrMmNQOUYvTC92VW5ZSnBLVWtNYgpVRkd5LzR4Q2t0dTN0endFempkaWdTN0ZRUWoyaTJzTW5pNkhMZjhiT3hBM1pudnJuT2crTU9JQUx1WnFJMU0yCk5XeFM0SmIvczJ6OWl5T3NzL0NPK'
            . '01aWHhZaDRaVzVrQVphR3hKbTd4Y3dyK2lia0NsSzlmRjd5a05Ea3FUNHUKMUUzUGMwMnFJZmFpa3dybUZZWXBVdXpVY3ZXRjFPMzJlcGUzRmloRURUejNTcGxkdGZVeFNRR2s3NUlXaFlDcQptekFyNk1GdDIwdjdVUW1wMTBJQ0xtVnF3b'
            . 'FVrRitHSWxLVzEyWUhnVUNsa3U0dklFV3d4OFJGRVpLYndOUm1SCmZQSTZ0SFJBK25vWkFvSUJBRzdoT1kveWh3UTlEL01HZFJOdEhjT2tKdXFDRFJOWGlVZGpuTE85c3pUWUZBMisKOWgyS2Y0OWdIU0xZUEk4MTA3SDR1L1Z4c3k3eXR3b'
            . 'HpFSmpKL2hzZnJ2WE4xdURoWWV5NlI4LzBNOUxOV29kMgpoNndZWGsrN1dabjN6Z0gvMlRYNm9YL2diQU9aalFXbWlwL3dldTEyZTlxZE1kZEVwS3QyMXZ4L2hUZU42QzVOCmxJeTRmcTJRTzRoeVVsRkxQWmUzcmYrMG5OcUdBVi9IakZGR2hx'
            . 'ZTcwK0NRZm8vUlJJODc5YnRsc1AyKzJERVoKOEl5UlN4ZGpIeEQ2Q0oxWWhFUTRVNUVaOHFWYUZwZVB0aVpQNzdkTWlxSStRTFpGNXVjTmZIUEduY084NmR5dQpYWmpSWjlSUmZKVEk1UEt0RGo2endpLzZrUVFURlhSeHF4U1JQMDBDZ2dFQkFM'
            . 'ZTF2d3hnRDVzdFIzTUJxZFdQCkJjakdVWWZ2cDY2Ukd3ZWYrVWhhOG5yRHFkVDJVNWJqVkJIWmJFNnlveTNReWQ3TXdiYUtaN2RZejVjdmVHQ3UKV2FBeFdrZTA4THRzS0Z3TXJUdnBBWFF4MFE2eVdDZFlSbklMcmhwUEIyMWViU0w5TlpLZ0Noc'
            . '1Vrbk1ldHNmWgowTEUvc2FDbmwwcW9RV3BXZFQ1WnNmSlBhaFBOcXdyWDhNQ1lTOU9OUzBTdFoxMTF2bjZtNUF3RlkvbEdMZVl6CkxPLzFsdldNM29rT1JxNXVjR1oxdWZjM1hXS1pTY05tdlFHYUFMK1J2K0ExQnAvOGdpWlhYeHh4bGkrK2FiN0'
            . 'UKL3VnSGJOcXhsVkZZcXo0eHQ2MWtBelZRVUF0Tk9UZHV0R1R4ekM2RkFzZUtCK2lpUHhLYk1xelU5bmk1amI2dQoxMmtDZ2dFQURnK2hiTHFBa3NOZHZuWllUVkFKTEUwSVFSQ0o4VU5aajVqMXdaVjc1WkpmaHhmMTZnbE5yMExLClplZVBrQ1YzL3V'
            . 'PSmFsVVA0ZTQ3K04wQlRWcXJEMU1tRFVHaVp4ZTEvUXRjMDUyTjhzUjJ2cjR6Snp6RUs1NVMKK0lBSUlJaWNhS2F0cXNQMEFFRFJTOEtlb2g5enVISm1OYVNLdU5iYmk0MTlNYktDclNaZmZCaXd2U2Z5ZmQrcApNMDJqMXVKbVB0SmFFZDdRM2VCNGliQ'
            . 'WxtNDBUMElRNVQ2bmhWZGtrMzNFS2lORGZBRzk1aEZGZzlWT3dCM2xiCnpMMFNrbFlwSlVrM'
            . 'FcydkV5MTdodU1KVG01YUxpYUFEL1dpNGFkVDE5MzdvVDlzMjFsMHBvL0tabUlSM2hwYUYKVUJ3MDl6aWxWbzBRUzZsb0RkSU5jb3d6RGUyaFVnPT0KLS0tLS1FTkQgUFJJVkFURSBLRVktLS0tLQo=';
    private static $cipher = "AES-256-CBC";

    public static function encryptKey($apiKey) {
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($apiKey, self::$cipher, self::$encryption_key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, self::$encryption_key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    public static function decryptKey($encryptedKey) {
        $c = base64_decode($encryptedKey);
        $ivlen = openssl_cipher_iv_length(self::$cipher);
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, self::$cipher, self::$encryption_key, $options = OPENSSL_RAW_DATA, $iv);
        return $original_plaintext;
    }
}
