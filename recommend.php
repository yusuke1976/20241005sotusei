<?php
header('Content-Type: application/json');

// OpenAI APIキーを設定
$api_key = '';

// POSTリクエストからジャンルと悩みを取得
$input = json_decode(file_get_contents('php://input'), true);
$genres = $input['genres'] ?? [];
$concern = $input['concern'] ?? '';

if (empty($genres)) {
    http_response_code(400);
    echo json_encode(['error' => 'ジャンルが選択されていません。']);
    exit;
}

if (empty($concern)) {
    http_response_code(400);
    echo json_encode(['error' => '悩みが入力されていません。']);
    exit;
}

// OpenAI APIリクエストの準備
$url = 'https://api.openai.com/v1/chat/completions';
$data = [
    'model' => 'gpt-4o-mini',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'あなたは悩み解決のための本を優しく紹介するガイドです。
            以下の制約条件を厳密に守ってロールプレイを行ってください。

            制約条件:
            ・ユーザーの悩みや困りごとに対して解決のヒントとなる本を紹介します。
            ・amazonで販売している本から選んでください。
            ・やさしくて安心できる雰囲気を持ち、親しみやすい言葉で対応します。
            ・丁寧で理解しやすい説明を心がけ、ユーザーがリラックスできるよう努めます。
            ・ユーザーの要望に応じて質問し、適切な本を提案します。
            ・ユーザーの悩みをよく聞き、具体的なアドバイスと関連する本を紹介します。
            ・少なくとも3冊は本を紹介します。紹介する本は、新しい段落で表示します。
            ・指定されたジャンルの本のみを紹介してください。他のジャンルの本は紹介しないでください。
            ・インターネットで似た悩みを調査し、その悩みを解決した本を紹介します。'
        ],
        [
            'role' => 'user',
            'content' => "次の悩みに対して、指定されたジャンルから本を3冊以上推薦してください。必ず指定されたジャンルの本のみを推薦してください。

悩み: {$concern}

ジャンル: " . implode(', ', $genres)
        ]
    ],
    // 'max_tokens' => 500
];

// cURLを使用してAPIリクエストを送信
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
]);

$response = curl_exec($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch) || $http_status !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'APIリクエストに失敗しました。']);
    exit;
}

curl_close($ch);

// レスポンスを解析
$result = json_decode($response, true);
$recommendation = $result['choices'][0]['message']['content'] ?? '推薦を生成できませんでした。';

// 結果を返す
echo json_encode(['recommendation' => $recommendation]);