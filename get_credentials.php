<?php
session_start();
include "funcs.php";

function debug_log($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
}

debug_log("get_credentials.php started");
debug_log("Session ID: " . session_id());

// データベース接続
$pdo = db_conn();

// 登録済みのパスキー情報を取得
$stmt = $pdo->prepare("SELECT credential_id FROM gs_user_table5");
$status = $stmt->execute();

if ($status == false) {
    debug_log("Database query failed: " . print_r($stmt->errorInfo(), true));
    exit(json_encode(['success' => false, 'message' => 'データベースエラーが発生しました。']));
}

$credentials = $stmt->fetchAll(PDO::FETCH_ASSOC);

debug_log("Retrieved credentials: " . print_r($credentials, true));

// クライアントに返すデータを整形
$response = [
    'success' => true,
    'credentials' => array_map(function($cred) {
        return ['id' => $cred['credential_id']];
    }, $credentials)
];

debug_log("Response data: " . json_encode($response));

header('Content-Type: application/json');
echo json_encode($response);
exit;
?>