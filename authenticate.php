<?php
session_start();
include "funcs.php";

// データベース接続
$pdo = db_conn();

// POSTデータを取得
$data = json_decode(file_get_contents('php://input'), true);
$credential_id = $data['id'];
$client_data_json = $data['response']['clientDataJSON'];
$authenticator_data = $data['response']['authenticatorData'];
$signature = $data['response']['signature'];

// チャレンジの検証
$client_data = json_decode(base64_decode($client_data_json), true);
if ($client_data['challenge'] !== $_SESSION['challenge']) {
    exit(json_encode(['success' => false, 'message' => 'チャレンジの検証に失敗しました。']));
}

// データベースでcredential_idを検索
$stmt = $pdo->prepare("SELECT * FROM gs_user_table5 WHERE credential_id = :credential_id");
$stmt->bindValue(':credential_id', $data['rawId'], PDO::PARAM_STR);
$status = $stmt->execute();

if ($status == false) {
    $error = $stmt->errorInfo();
    exit(json_encode(['success' => false, 'message' => "QueryError:".$error[2]]));
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // ここで signature と authenticator_data の検証を行うべきですが、
    // 簡略化のため省略しています。実際の実装では必ず検証してください。

    // 認証成功
    $_SESSION['chk_ssid'] = session_id();
    $_SESSION['username'] = $user['username'];
    exit(json_encode(['success' => true]));
} else {
    // 認証失敗
    exit(json_encode(['success' => false, 'message' => 'ユーザーが見つかりません。']));
}
?>