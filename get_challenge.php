<?php
session_start();
include "funcs.php";

function debug_log($message) {
    file_put_contents('debug.log', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
}

debug_log("get_challenge.php started");
debug_log("Session ID: " . session_id());
debug_log("Previous session data: " . print_r($_SESSION, true));

// ランダムなチャレンジを生成
$challenge = random_bytes(32);
$challenge_base64 = base64_encode($challenge);

// チャレンジをセッションに保存
$_SESSION['challenge'] = $challenge_base64;

debug_log("New challenge generated: " . substr($challenge_base64, 0, 10) . "...");
debug_log("Updated session data: " . print_r($_SESSION, true));

// セッションを確実に保存
session_write_close();

debug_log("Session closed");

// チャレンジをクライアントに返す
header('Content-Type: application/json');
echo json_encode(['success' => true, 'challenge' => $challenge_base64]);

debug_log("Challenge sent to client");
exit;
?>