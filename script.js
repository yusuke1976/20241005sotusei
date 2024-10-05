function getBookRecommendations() {
    const worry = document.getElementById('worryInput').value;
    const genres = Array.from(document.querySelectorAll('.genre-selection input:checked')).map(input => input.value);

    if (!worry) {
        alert("悩みを入力してください！");
        return;
    }

    if (genres.length === 0) {
        alert("少なくとも一つのジャンルを選択してください！");
        return;
    }

    // ダミーデータで選書を表示（実際にはサーバー側でAI選書を行う想定）
    const books = [
        {
            title: "君の膵臓をたべたい",
            author: "住野よる",
            description: "大病を抱える少女と少年の心温まる友情と成長の物語。",
            reason: "人間関係に悩んでいる場合、この本が感情的な支えになります。"
        },
        {
            title: "7つの習慣",
            author: "スティーブン・R・コヴィー",
            description: "成功と充実感を得るための7つの自己啓発の原則。",
            reason: "自己成長や目標設定に悩む場合に最適な一冊です。"
        },
        {
            title: "ドラゴンボール",
            author: "鳥山明",
            description: "孫悟空の冒険を描いた人気漫画。",
            reason: "夢や目標に向かう勇気をもらえる物語です。"
        }
    ];

    displayResults(books);
}

function displayResults(books) {
    const resultsSection = document.getElementById('resultsSection');
    const bookResults = document.getElementById('bookResults');

    // 前回の結果をクリア
    bookResults.innerHTML = '';

    // 新しい結果を追加
    books.forEach(book => {
        const bookCard = document.createElement('div');
        bookCard.className = 'book-card';

        const title = document.createElement('h3');
        title.textContent = book.title;
        bookCard.appendChild(title);

        const author = document.createElement('p');
        author.textContent = `著者: ${book.author}`;
        bookCard.appendChild(author);

        const description = document.createElement('p');
        description.textContent = `概要: ${book.description}`;
        bookCard.appendChild(description);

        const reason = document.createElement('p');
        reason.textContent = `選んだ理由: ${book.reason}`;
        bookCard.appendChild(reason);

        bookResults.appendChild(bookCard);
    });

    resultsSection.classList.remove('hidden');
}
