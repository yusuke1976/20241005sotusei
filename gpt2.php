<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI選書サービス - 若者の悩み解決</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">AI選書サービス - 若者の悩み解決</h1>
        <form id="bookForm" class="mb-8">
            <div class="mb-4">
                <label class="block mb-2">あなたの悩みを入力してください：</label>
                <textarea id="worry" class="w-full p-2 border rounded" rows="3" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block mb-2">本のジャンルを選択してください（複数選択可）：</label>
                <div id="genreCheckboxes"></div>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">本を探す</button>
        </form>
        <div id="recommendations" class="hidden">
            <h2 class="text-2xl font-bold mb-4">おすすめの本</h2>
            <div id="bookList"></div>
        </div>
    </div>

    <script>
        const genres = ['小説', 'ノンフィクション', '自己啓発', '詩', '漫画', 'その他'];
        const genreCheckboxes = document.getElementById('genreCheckboxes');

        genres.forEach(genre => {
            const label = document.createElement('label');
            label.className = 'inline-flex items-center mr-4';
            label.innerHTML = `
                <input type="checkbox" name="genre" value="${genre}" class="mr-2">
                ${genre}
            `;
            genreCheckboxes.appendChild(label);
        });

        document.getElementById('bookForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const worry = document.getElementById('worry').value;
            const selectedGenres = Array.from(document.querySelectorAll('input[name="genre"]:checked')).map(cb => cb.value);
            
            // ダミーデータを使用（実際のAIロジックの代わり）
            const recommendations = [
                {
                    title: "青春の門",
                    summary: "主人公の成長と自己発見の物語",
                    reason: "若者の悩みと成長のプロセスを描いているため"
                },
                {
                    title: "7つの習慣",
                    summary: "人生の成功に必要な7つの習慣を紹介",
                    reason: "自己改善と目標達成のヒントが得られるため"
                },
                {
                    title: "君の膵臓をたべたい",
                    summary: "生と死、そして人生の意味を考えさせる物語",
                    reason: "若者の悩みを深い視点から考えるきっかけになるため"
                }
            ];

            displayRecommendations(recommendations);
        });

        function displayRecommendations(books) {
            const bookList = document.getElementById('bookList');
            bookList.innerHTML = '';

            books.forEach(book => {
                const bookElement = document.createElement('div');
                bookElement.className = 'bg-white p-6 rounded-lg shadow-md mb-4';
                bookElement.innerHTML = `
                    <h3 class="text-xl font-bold mb-2">${book.title}</h3>
                    <p class="mb-2"><strong>概要：</strong>${book.summary}</p>
                    <p><strong>推薦理由：</strong>${book.reason}</p>
                `;
                bookList.appendChild(bookElement);
            });

            document.getElementById('recommendations').classList.remove('hidden');
        }
    </script>
</body>
</html>