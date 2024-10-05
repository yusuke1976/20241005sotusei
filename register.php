<?php
session_start();
include "funcs.php";

function debug_log($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
}

debug_log("register.php started");
debug_log("Session ID: " . session_id());
debug_log("Full session data: " . print_r($_SESSION, true));

// データベース接続
$pdo = db_conn();

// POSTデータを取得
$data = json_decode(file_get_contents('php://input'), true);
debug_log("Received data: " . json_encode($data));

$username = $data['username'];
$credential_id = $data['id'];
$credential_raw_id = $data['rawId'];
$client_data_json = $data['response']['clientDataJSON'];
$attestation_object = $data['response']['attestationObject'];
$fingerprint = $data['fingerprint'];

// 入力チェック
if (empty($username) || empty($credential_id) || empty($credential_raw_id) || empty($client_data_json) || empty($attestation_object) || empty($fingerprint)) {
    debug_log("Missing required information");
    exit(json_encode(['success' => false, 'message' => '必要な情報が不足しています。']));
}

// ユーザー名の重複チェック
$stmt = $pdo->prepare("SELECT * FROM gs_user_table5 WHERE username = :username");
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->execute();
if ($stmt->fetch()) {
    debug_log("Username already exists: $username");
    exit(json_encode(['success' => false, 'message' => 'このユーザー名は既に使用されています。']));
}

// チャレンジの検証
$client_data = json_decode(base64_decode(strtr($client_data_json, '-_', '+/')), true);
$received_challenge = $client_data['challenge'];
$stored_challenge = isset($_SESSION['challenge']) ? $_SESSION['challenge'] : '';

debug_log("Received challenge: " . $received_challenge);
debug_log("Stored challenge: " . $stored_challenge);

// Base64デコードしてバイナリ比較
$received_challenge_binary = base64_decode(strtr($received_challenge, '-_', '+/'));
$stored_challenge_binary = base64_decode($stored_challenge);

if (empty($stored_challenge)) {
    debug_log("No stored challenge found");
    exit(json_encode(['success' => false, 'message' => 'チャレンジが見つかりません。再度お試しください。']));
}

if ($received_challenge_binary !== $stored_challenge_binary) {
    debug_log("Challenge verification failed");
    debug_log("Received challenge (binary) length: " . strlen($received_challenge_binary));
    debug_log("Stored challenge (binary) length: " . strlen($stored_challenge_binary));
    exit(json_encode(['success' => false, 'message' => 'チャレンジの検証に失敗しました。']));
}

debug_log("Challenge verified successfully");

// チャレンジを使用したので、セッションから削除
unset($_SESSION['challenge']);

// ユーザー情報をデータベースに登録
$stmt = $pdo->prepare("INSERT INTO gs_user_table5 (username, credential_id, credential_public_key, fingerprint) VALUES (:username, :credential_id, :credential_public_key, :fingerprint)");
$stmt->bindValue(':username', $username, PDO::PARAM_STR);
$stmt->bindValue(':credential_id', $credential_id, PDO::PARAM_STR);
$stmt->bindValue(':credential_public_key', $attestation_object, PDO::PARAM_STR);
$stmt->bindValue(':fingerprint', $fingerprint, PDO::PARAM_STR);

if ($stmt->execute()) {
    debug_log("User registered successfully: $username");
    exit(json_encode(['success' => true, 'message' => 'ユーザー登録が完了しました。']));
} else {
    debug_log("Database error occurred");
    debug_log("PDO Error: " . print_r($stmt->errorInfo(), true));
    exit(json_encode(['success' => false, 'message' => 'データベースエラーが発生しました。']));
}
?>