<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOW (Bookshelf Of Worries)</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>BOW (Bookshelf Of Worries)</h1>
        <p>あなたの悩み、本が導く答え</p>
    </header>

    <main>
        <section class="input-section">
            <h2>あなたの悩みを教えてください</h2>
            <textarea id="worryInput" placeholder="ここに悩みを入力してください"></textarea>

            <h3>本のジャンルを選んでください</h3>
            <div class="genre-selection">
                <label><input type="checkbox" value="小説"> 小説</label>
                <label><input type="checkbox" value="ノンフィクション"> ノンフィクション</label>
                <label><input type="checkbox" value="自己啓発"> 自己啓発</label>
                <label><input type="checkbox" value="詩"> 詩</label>
                <label><input type="checkbox" value="漫画"> 漫画</label>
                <label><input type="checkbox" value="その他"> その他</label>
            </div>

            <h3>わくわく度を選んでください</h3>
            <div class="excitement-selection">
                <label><input type="radio" name="excitement" value="1"> 1 (落ち着いた)</label>
                <label><input type="radio" name="excitement" value="2"> 2</label>
                <label><input type="radio" name="excitement" value="3"> 3 (バランス)</label>
                <label><input type="radio" name="excitement" value="4"> 4</label>
                <label><input type="radio" name="excitement" value="5"> 5 (わくわく)</label>
            </div>

            <button onclick="getBookRecommendations()">本を探す</button>
        </section>

        <section id="resultsSection" class="hidden">
            <h2>あなたの悩みにおすすめの本</h2>
            <div id="bookResults"></div>
        </section>
    </main>

    <footer>
        <p>© 2024 BOW (Bookshelf Of Worries). All rights reserved.</p>
    </footer>

    <script src="script2.js"></script>
</body>
</html>