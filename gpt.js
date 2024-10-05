async function submitPrompt() {
    const prompt = document.getElementById("inputText").value;
    const outputElement = document.getElementById("outputText");
    const selectedGenres = Array.from(document.querySelectorAll('input[name="genre"]:checked')).map(el => el.value);
    const excitement = document.querySelector('input[name="excitement"]:checked')?.value || '';

    try {
        // デバッグ用のログ: ユーザーが入力したデータを表示
        console.log("Prompt:", prompt);
        console.log("Selected Genres:", selectedGenres);
        console.log("Excitement Level:", excitement);

        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=submitPrompt&prompt=${encodeURIComponent(prompt)}&genres=${encodeURIComponent(JSON.stringify(selectedGenres))}&excitement=${encodeURIComponent(excitement)}`
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // デバッグ用のログ: APIからのレスポンスを表示
        console.log("API Response:", data);

        // 応答をoutputElementに表示
        outputElement.textContent = data.response;
        outputElement.style.display = 'block';

        // 本の検索結果をクリア
        const bookItemRow = document.querySelector("#bookItem .row");
        bookItemRow.innerHTML = '';

    } catch (e) {
        console.error("Error occurred:", e);
        outputElement.textContent = "Error: " + e.message;
    }
}


const btn = document.getElementById("btn");
const formText = document.getElementById("formText");
const resetBtn = document.getElementById("resetBtn");
const bookItemRow = document.querySelector("#bookItem .row");
const outputText = document.getElementById("outputText");

btn.addEventListener('click', async() => {
    const textValue = formText.value;
    if (!textValue) return;

    try {
        const response = await fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=searchBooks&query=${encodeURIComponent(textValue)}`
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        bookItemRow.innerHTML = '';

        for(let i = 0; i < data.items.length; i++){
            try {
                const bookImg = data.items[i].volumeInfo.imageLinks?.thumbnail || './images/default-book-cover.jpg';
                const bookTitle = data.items[i].volumeInfo.title;
                const bookAuthor = data.items[i].volumeInfo.authors ? data.items[i].volumeInfo.authors.join(', ') : '著者不明';
                const bookContent = data.items[i].volumeInfo.description || '説明なし';
                const bookLink = data.items[i].volumeInfo.infoLink;
                
                const bookCard = `
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow book-card">
                            <div class="card-img-top-wrapper">
                                <img src="${bookImg}" class="card-img-top" alt="${bookTitle}">
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${bookTitle}</h5>
                                <p class="card-text text-muted mb-2">${bookAuthor}</p>
                                <p class="card-text book-description">${bookContent.substring(0, 150)}${bookContent.length > 150 ? '...' : ''}</p>
                                <a href="${bookLink}" target="_blank" class="btn btn-outline-primary mt-auto">詳細を見る</a>
                            </div>
                        </div>
                    </div>
                `;
                
                bookItemRow.insertAdjacentHTML('beforeend', bookCard);
            } catch(e) {
                console.error('Error creating book card:', e);
                continue;
            }
        }

        formText.value = '';
    } catch (e) {
        console.error('Error:', e);
    }
});

resetBtn.addEventListener('click', () => {
    formText.value = '';
    bookItemRow.innerHTML = '';
    outputText.textContent = '';
    outputText.style.display = 'none';  // 回答欄を非表示に
    document.getElementById("inputText").value = "";
    console.log('Reset button clicked. All content cleared.');
});

// スタイルを動的に追加
const style = document.createElement('style');
style.textContent = `
    #bookItem {
        max-width: 1100px;
        margin: 0 auto;
    }
    .book-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        border: none;
        border-radius: 10px;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
    }
    .card-img-top-wrapper {
        height: 200px;
        overflow: hidden;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }
    .card-img-top {
        object-fit: cover;
        height: 100%;
        width: 100%;
        transition: transform 0.3s ease-in-out;
    }
    .book-card:hover .card-img-top {
        transform: scale(1.05);
    }
    .book-description {
        font-size: 0.9rem;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    .card-body {
        background-color: #f8f9fa;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .btn-outline-primary {
        border-radius: 20px;
        transition: all 0.3s ease-in-out;
    }
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(0,123,255,0.2);
    }
`;
document.head.appendChild(style);