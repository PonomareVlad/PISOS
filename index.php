<?php
/**
 * Copyright (c) 2019 Ponomarev Studio.
 *
 * https://Ponomarev.Studio
 *
 * @author Vladislav Ponomarev <Git@PonomareVlad.ru>
 */

$order = json_decode(file_get_contents('php://input'), true);

if (empty($order['id'])) exit('Error');

function apiRequest($url, $auth, $method = 'GET', $content = null)
{
    $context = stream_context_create(['http' => [
        'method' => $method,
        'header' => 'Authorization: ' . $auth . " \r\n" .
            'Content-Type: application/json; charset=UTF-8' . "\r\n",
        'content' => $content
    ]]);
    return file_get_contents($url, false, $context);
}

if ($order['financial_status'] == 'paid' && $order['fulfillment_status'] == 'new') {
    error_log(apiRequest($_ENV['API_URL'] . '/admin/orders/' . $order['id'] . '.json', $_ENV['API_AUTH'], 'PUT', '{"order": {"custom_status_permalink": "approved"}}'));
    echo 'OK';
} else exit('Skipped');