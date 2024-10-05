<?php
// sotu.txtファイルを読み込んで、クエリと本の推薦を配列に格納する関数
function loadRecommendations($file_path) {
    $recommendations = [];
    
    // ファイルが存在するか確認
    if (file_exists($file_path)) {
        // ファイルを読み込む
        $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // "のおすすめ本は" でクエリと本のタイトルを分ける
            if (strpos($line, 'のおすすめ本は') !== false) {
                list($query, $book) = explode('のおすすめ本は', $line);
                $recommendations[trim($query)] = trim($book); // trim()で余分なスペースや改行を削除
            }
        }
    } else {
        echo "ファイルが見つかりませんでした。";
    }
    
    return $recommendations;
}

// ユーザーからのクエリを受け取る関数
function getBookRecommendation($query, $recommendations) {
    $trimmed_query = trim($query); // ユーザー入力のクエリもtrim()で前後のスペースを削除
    if (array_key_exists($trimmed_query, $recommendations)) {
        return "おすすめの本は「" . $recommendations[$trimmed_query] . "」です。";
    } else {
        return "申し訳ありませんが、そのクエリに対する本の推薦は見つかりませんでした。";
    }
}

// メイン処理
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームからクエリを受け取る
    $user_query = isset($_POST['query']) ? $_POST['query'] : '';
    
    // sotu.txtファイルを読み込む
    $file_path = 'sotu.txt'; // sotu.txtは同じディレクトリにあると仮定
    $recommendations = loadRecommendations($file_path);
    
    // 推薦された本を取得
    $result = getBookRecommendation($user_query, $recommendations);
} else {
    $result = '';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>本の推薦システム</title>
</head>
<body>

<h1>本の推薦システム</h1>

<form method="post" action="">
    <label for="query">悩みを入力してください:</label><br>
    <input type="text" id="query" name="query" value="<?php echo isset($user_query) ? htmlspecialchars($user_query) : ''; ?>"><br><br>
    <input type="submit" value="推薦を取得">
</form>

<p><?php echo $result; ?></p>

</body>
</html>
