<?php
namespace MiniERP\Config\Http;

class ClientRequest
{
    public static function get(string $url, array|null $cabecalho = null)
    {
        $curl = curl_init();
        $config = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ];
        if($cabecalho) {
            $config[CURLOPT_HTTPHEADER] = $cabecalho;
        }
        curl_setopt_array($curl, $config);
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}