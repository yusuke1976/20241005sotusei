<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$isbn = isset($_GET['isbn']) ? $_GET['isbn'] : '';
if (empty($isbn)) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'ISBN is required']);
    exit;
}

$url = "https://iss.ndl.go.jp/api/sru?operation=searchRetrieve&query=isbn={$isbn}&recordSchema=dcndl&onlyBib=true";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 注意: 本番環境では true にすることを推奨
$response = curl_exec($ch);

if (curl_errno($ch)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} elseif (empty($response)) {
    header('HTTP/1.1 404 Not Found');
    echo json_encode(['error' => 'Empty response from API']);
} else {
    header('Content-Type: application/xml');
    echo $response;
}

curl_close($ch);
?>