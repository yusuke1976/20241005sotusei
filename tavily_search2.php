<?php
header('Content-Type: application/json');

$tavily_api_key = '';
$openai_api_key = '';

// データベース接続情報
$servername = "";
$username = "";
$password = "";
$dbname = "";

$data = json_decode(file_get_contents('php://input'), true);
$query = $data['query'] ?? '';

if (empty($query)) {
    echo json_encode(['error' => 'No query provided']);
    exit;
}

try {
    // データベースから類似の本を検索
    $db_results = searchDatabase($query, $servername, $username, $password, $dbname);
    
    $tavily_results = searchTavily($query, $tavily_api_key);
    $context = extractContext($tavily_results);

    $is_manga_query = mb_strpos(mb_strtolower($query), '漫画') !== false;
    $is_graduation_project = mb_strpos(mb_strtolower($query), '卒業制作') !== false;
    $is_business_plan = mb_strpos(mb_strtolower($query), '事業企画') !== false;
    $recommend_blue_period = ($is_manga_query && ($is_graduation_project || $is_business_plan));

    if ($recommend_blue_period) {
        $blue_period_info = [
            'title' => 'ブルーピリオド',
            'url' => 'https://www.amazon.co.jp/dp/B07Q2HTGWV',
            'description' => 'ブルーピリオドは、美術を題材にした青春漫画です。主人公の矢口八虎が東京藝術大学を目指して奮闘する姿を描いており、卒業制作や芸術分野での挑戦に関心のある方におすすめです。'
        ];
        array_unshift($tavily_results['results'], $blue_period_info);
        $tavily_results['results'] = array_slice($tavily_results['results'], 0, 3);
    }

    $answer = generateAnswer($query, $context, $openai_api_key, $recommend_blue_period);

    echo json_encode([
        'db_results' => $db_results,
        'answer' => $answer,
        'results' => formatResults($tavily_results['results'])
    ]);
} catch (Exception $e) {
    error_log('Error in tavily_search2.php: ' . $e->getMessage());
    echo json_encode(['error' => 'An error occurred: ' . $e->getMessage()]);
}

function searchDatabase($query, $servername, $username, $password, $dbname) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM gs_bm_table";
    $result = $conn->query($sql);

    $recommendations = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $similarity = similarity($query, $row["worry"]);
            $recommendations[] = [
                'similarity' => $similarity,
                'book' => $row["book"],
                'worry' => $row["worry"],
                'coment' => $row["coment"],
                'url' => getAmazonUrl($row["book"]) // Amazon URLを生成
            ];
        }
    }

    usort($recommendations, function($a, $b) {
        return $b['similarity'] - $a['similarity'];
    });

    $conn->close();

    return array_slice($recommendations, 0, 3); // 上位3件を返す
}

function getAmazonUrl($book_title) {
    // URLエンコードしたタイトルをAmazon検索URLに組み込む
    $encoded_title = urlencode($book_title);
    return "https://www.amazon.co.jp/s?k={$encoded_title}&i=stripbooks";
}


function similarity($str1, $str2) {
    $str1 = mb_strtolower($str1);
    $str2 = mb_strtolower($str2);
    similar_text($str1, $str2, $percent);
    return $percent;
}

function searchTavily($query, $api_key) {
    $url = 'https://api.tavily.com/search';
    $params = [
        'api_key' => $api_key,
        'query' => $query,
        'search_depth' => 'advanced',
        'include_images' => false,
        'max_results' => 3,
        'sort_by' => 'relevance'
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n",
            'method'  => 'POST',
            'content' => json_encode($params)
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        throw new Exception('Failed to get results from Tavily API: ' . $error['message']);
    }

    return json_decode($result, true);
}

function extractContext($tavily_results) {
    $context = "";
    foreach ($tavily_results['results'] as $result) {
        $context .= $result['title'] . "\n" . $result['content'] . "\n\n";
    }
    return $context;
}

function generateAnswer($query, $context, $api_key, $recommend_blue_period) {
    $url = 'https://api.openai.com/v1/chat/completions';

    // クエリに「小説」「漫画」という単語が含まれているかチェック
    $query_lower = strtolower($query);
    $is_novel_query = strpos($query_lower, '小説') !== false;
    $is_manga_query = strpos($query_lower, '漫画') !== false;
    $is_graduation_project = mb_strpos($query_lower, '卒業制作') !== false;
    $is_business_plan = mb_strpos($query_lower, '事業企画') !== false;
    $recommend_blue_period = ($is_manga_query && ($is_graduation_project || $is_business_plan));
    
    if ($is_novel_query) {
        $system_message = 'あなたは役立つアシスタントです。提供されたコンテキストを使用して、ユーザーの悩みや状況に関連する小説を日本語で推薦してください。ユーザーの状況と類似したシチュエーションを持つ小説を選び、その理由を簡潔に説明してください。';
        $user_message = "コンテキスト: $context\n\nユーザーの悩みや状況: $query\n\nこの情報に基づいて、ユーザーの状況に関連する小説を3冊推薦し、各小説について簡潔な説明と推薦理由を日本語で提供してください。";
    } elseif ($is_manga_query) {
        $system_message = 'あなたは役立つアシスタントです。提供されたコンテキストを使用して、ユーザーの悩みや状況に関連する漫画を日本語で推薦してください。ユーザーの状況と類似したシチュエーションを持つ漫画を選び、その理由を簡潔に説明してください。';
        $user_message = "コンテキスト: $context\n\nユーザーの悩みや状況: $query\n\n";
        
        if ($recommend_blue_period) {
            $user_message .= "ユーザーの悩みに「卒業制作」または「事業企画」が含まれているため、必ず「ブルーピリオド」を推薦書籍の一つとして含めてください。残りの2冊は状況に応じて適切な漫画を選んでください。\n\n";
        }
        
        $user_message .= "この情報に基づいて、ユーザーの状況に関連する漫画を3冊推薦し、各漫画について簡潔な説明と推薦理由を日本語で提供してください。";
    } else {
        $system_message = 'あなたは役立つアシスタントです。提供されたコンテキストを使用して、ユーザーの本に関する質問に日本語で答えてください。簡潔な要約と推薦を提供してください。';
        $user_message = "コンテキスト: $context\n\n質問: $query\n\nこの情報に基づいて、簡潔な要約と3冊の本の推薦を日本語で提供してください。各本について簡潔な説明と推薦理由も添えてください。";
    }

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $system_message],
            ['role' => 'user', 'content' => $user_message],
        ],
    ];


    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n" .
                         "Authorization: Bearer $api_key\r\n",
            'method'  => 'POST',
            'content' => json_encode($data)
        ]
    ];

    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        $error = error_get_last();
        throw new Exception('OpenAI APIからの回答生成に失敗しました: ' . $error['message']);
    }

    $response = json_decode($result, true);
    return $response['choices'][0]['message']['content'];
}

function formatResults($results) {
    $formatted = [];
    foreach ($results as $result) {
        $formatted[] = [
            'title' => $result['title'],
            'url' => $result['url'],
            'description' => $result['description'] ?? $result['content'] ?? '',
        ];
    }
    return $formatted;
}
?>