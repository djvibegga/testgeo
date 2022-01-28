<?php

function requestCadastrCenter(string $number) {
    $params = [
        'clientId' => 'LMRZYHROM1o94YQQXMs42-P3s-n6thKQVu9EjS17SCX3nnqT151Ile035wvCi7hC1E33fzxUSJuYnHgB',
        'cadastralNumber' => $number
    ];
    $ch = curl_init(
        'https://soft.farm/api/open/cadastral/find-center-by-cadastral-number'
        . '?' . http_build_query($params)
        );
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $response = json_decode($response, true);
    curl_close($ch);
    if (! empty($response['status']) && isset($response['data']['lat']) && $response['data']['lng']) {
        return [$response['data']['lat'], $response['data']['lng']];
    }
    return false;
}