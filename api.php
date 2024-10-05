<?php
header('Content-Type: application/json');

// DB接続などの共通処理
require_once 'funcs.php';
$pdo = db_conn();

// リクエストの種類を判断
$action = $_POST['action'] ?? '';

switch ($action) {
    case 'submitPrompt':
        handleSubmitPrompt();
        break;
    case 'searchBooks':
        handleSearchBooks();
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

function handleSubmitPrompt() {
    $prompt = $_POST['prompt'] ?? '';
    $genres = json_decode($_POST['genres'] ?? '[]', true);
    
    // OpenAI APIの呼び出し
    $apiKey = '';
    $url = 'https://api.openai.com/v1/chat/completions';
    
    $genreInstruction = '';
    $novelInstruction = '';
    if (!empty($genres)) {
        if (in_array('小説', $genres)) {
            $novelInstruction = "小説のジャンルだけ表示して。";
            // 「小説」を除外した他のジャンルリストを作成
            $otherGenres = array_diff($genres, ['小説']);
            if (!empty($otherGenres)) {
                $genreInstruction = "以下のジャンルの小説のみを紹介してください: " . implode(', ', $otherGenres) . "。";
            } else {
                $genreInstruction = "小説全般を紹介してください。"; // 修正: 小説のみが選択された場合の処理
            }
        } else {
            $genreInstruction = "以下のジャンルの本のみを紹介してください: " . implode(', ', $genres) . "。";
        }
        $genreInstruction .= "各本の紹介の前に、そのジャンルを[ジャンル名]の形式で明示してください。";
    }
        
    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'あなたは悩み解決のための本を優しく紹介するガイドです。
            以下の制約条件を厳密に守ってロールプレイを行ってください。

            制約条件:
            ・ユーザーの悩みや困りごとに対して解決のヒントとなる本を紹介します。
            ・やさしくて安心できる雰囲気を持ち、親しみやすい言葉で対応します。
            ・丁寧で理解しやすい説明を心がけ、ユーザーがリラックスできるよう努めます。
            ・ユーザーの要望に応じて質問し、適切な本を提案します。
            ・専門的な知識を持ちつつ、ユーザーに寄り添う態度を大切にします。
            ・ユーザーの悩みをよく聞き、具体的なアドバイスと関連する本を紹介します。
            ・新入社員が仕事の量に悩む場合、『ゲーデルの不完全性定理』などの間接的なヒントとなる本を紹介します。
            ・ビジネス関連の悩みには、数学書や漫画など他分野の本を紹介します。
            ・少なくとも3冊は本を紹介します。
            ・プログラミングスクールの学生が卒業制作に悩んでいる場合、『ブルーピリオド』などを紹介します。
            ・インターネットで似た悩みを調査し、その悩みを解決した本を紹介します。
            ・紹介する本は、箇条書きで表示して。
            ' . $genreInstruction . $novelInstruction],
            ['role' => 'user', 'content' => $prompt]
        ]
    ];
    
    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => [
                'Content-Type: application/json',
                "Authorization: Bearer $apiKey"
            ],
            'content' => json_encode($data)
        ]
    ];
    
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    if ($result === FALSE) {
        echo json_encode(['error' => 'API request failed']);
    } else {
        $response = json_decode($result, true);
        $content = $response['choices'][0]['message']['content'];
        
        // ジャンルでフィルタリング
        if (!empty($genres)) {
            $filteredContent = filterContentByGenres($content, $genres);
            echo json_encode(['response' => $filteredContent]);
        } else {
            echo json_encode(['response' => $content]);
        }
    }
}

function filterContentByGenres($content, $genres) {
    $lines = explode("\n", $content);
    $filteredLines = [];
    $currentGenre = '';
    $isNovel = in_array('小説', $genres); // 修正: 小説が選択されているかチェック
    
    foreach ($lines as $line) {
        if (preg_match('/\[([^\]]+)\]/', $line, $matches)) {
            $currentGenre = $matches[1];
        }
        if ($isNovel) {
            // 修正: 小説が選択されている場合、全ての行を含める
            $filteredLines[] = $line;
        } elseif (in_array($currentGenre, $genres) || $currentGenre === '') {
            $filteredLines[] = $line;
        }
    }
    
    return implode("\n", $filteredLines);
}

function handleSearchBooks() {
    $query = $_POST['query'] ?? '';
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($query);
    
    $result = file_get_contents($url);
    
    if ($result === FALSE) {
        echo json_encode(['error' => 'Google Books API request failed']);
    } else {
        echo $result; // Google Books APIのレスポンスをそのまま返す
    }
}