<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>本のバーコードスキャナー (NDL Search API対応)</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <style>
        #interactive.viewport {
            width: 640px;
            height: 480px;
        }
        #interactive.viewport canvas, video {
            float: left;
            width: 640px;
            height: 480px;
        }
        #interactive.viewport canvas.drawingBuffer, video.drawingBuffer {
            margin-left: -640px;
        }
        #bookInfo, #debug, #status {
            margin-top: 20px;
        }
        #status {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="interactive" class="viewport"></div>
    <div id="status"></div>
    <div id="result"></div>
    <div id="bookInfo"></div>
    <div id="debug"></div>

    <script>
        Quagga.init({
            inputStream : {
                name : "Live",
                type : "LiveStream",
                target: document.querySelector('#interactive')
            },
            decoder : {
                readers : ["ean_reader"]
            }
        }, function(err) {
            if (err) {
                console.log(err);
                document.getElementById('status').textContent = "カメラの初期化に失敗しました。";
                return;
            }
            console.log("Initialization finished. Ready to start");
            document.getElementById('status').textContent = "カメラが初期化されました。バーコードをスキャンしてください。";
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;

            // Check if the barcode is valid (starts with '9' and has 13 digits)
            if (code.startsWith('9') && code.length === 13) {
                document.getElementById('result').textContent = "バーコード: " + code;
                document.getElementById('status').textContent = "書籍情報を取得中...";
                
                // Amazon.co.jpの書籍ページURLを生成
                var amazonUrl = `https://www.amazon.co.jp/s?k=${code}&i=stripbooks`;
                
                // NDL Search APIにアクセス（プロキシ経由）
                    var apiUrl = `proxy.php?isbn=${code}`;

                    fetch(apiUrl)
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => { throw err; });
                            }
                            return response.text();
                        })
                        .then(str => {
                            console.log("Raw API Response:", str); // Log raw XML response
                            if (str.trim() === '') {
                                throw new Error('Empty response from API');
                            }
                            return (new window.DOMParser()).parseFromString(str, "text/xml");
                        })
                
                
                
                    .then(data => {
                        let bookInfo = {
                            title: "タイトル不明",
                            authors: "不明",
                            publisher: "不明",
                            publishedDate: "不明"
                        };

                        // Helper function to safely query XML elements
                        function getElementText(parent, selector) {
                                const element = parent.querySelector(selector);
                                return element ? element.textContent.trim() : null;
                            }

                        // Helper function to get text from an rdf:Description element
                        function getRdfDescriptionValue(parent, selector) {
                            const element = parent.querySelector(selector + ' rdf\\:Description rdf\\:value');
                            return element ? element.textContent.trim() : null;
                        }


                        const rdf = data.querySelector('rdf\\:RDF');
                                if (rdf) {
                                    const bibResource = rdf.querySelector('dcndl\\:BibResource');
                                    if (bibResource) {
                                        // タイトルの取得
                                        bookInfo.title = getElementText(bibResource, 'dcterms\\:title') || 
                                                        getRdfDescriptionValue(bibResource, 'dc\\:title') ||
                                                        "タイトル不明";

                                        // 著者の取得
                                        const creator = getElementText(bibResource, 'dc\\:creator');
                                        const creatorAgent = getElementText(bibResource, 'dcterms\\:creator foaf\\:Agent foaf\\:name');
                                        bookInfo.authors = creator || creatorAgent || "不明";

                                        // 出版社の取得
                                        const publisher = getElementText(bibResource, 'dcterms\\:publisher foaf\\:Agent foaf\\:name');
                                        bookInfo.publisher = publisher || "不明";

                                        // 出版日の取得
                                        bookInfo.publishedDate = getElementText(bibResource, 'dcterms\\:date') || 
                                                                getElementText(bibResource, 'dcterms\\:issued') ||
                                                                "不明";
                                    }
                                }

                                console.log("Extracted Book Info:", bookInfo);

                                // 書籍情報を表示
                                document.getElementById('bookInfo').innerHTML = `
                                    <h3>書籍情報:</h3>
                                    <p>タイトル: ${bookInfo.title}</p>
                                    <p>著者: ${bookInfo.authors}</p>
                                    <p>出版社: ${bookInfo.publisher}</p>
                                    <p>出版日: ${bookInfo.publishedDate}</p>
                                    <p>ISBN: ${code}</p>
                                    <p>Amazon URL: <a href="${amazonUrl}" target="_blank">${amazonUrl}</a></p>
                                `;
                                
                                // 親ウィンドウのフォームに書籍名とURLを設定
                                if (window.opener && !window.opener.closed) {
                                    try {
                                        window.opener.document.getElementById('book').value = bookInfo.title;
                                        window.opener.document.getElementById('url').value = amazonUrl;
                                        document.getElementById('status').textContent = "書籍情報が自動入力されました。";
                                    } catch (error) {
                                        console.error("親ウィンドウへの書き込み中にエラーが発生しました:", error);
                                        document.getElementById('status').textContent = "親ウィンドウへの書き込みに失敗しました。手動で情報を入力してください。";
                                    }
                                } else {
                                    document.getElementById('status').textContent = "親ウィンドウが見つかりません。手動で情報を入力してください。";
                                }

                                // デバッグ情報を表示
                                document.getElementById('debug').innerHTML = `
                                    <h3>デバッグ情報:</h3>
                                    <pre>${JSON.stringify(bookInfo, null, 2)}</pre>
                                    <p>API Response Structure:</p>
                                    <pre>${new XMLSerializer().serializeToString(data)}</pre>
                                `;
                            })

                            
                        
                        
                    .catch(error => {
                        console.error('Error:', error);
                        document.getElementById('bookInfo').innerHTML = `
                            <p>書籍情報の取得中にエラーが発生しました。</p>
                            <p>エラー詳細: ${error.message || JSON.stringify(error)}</p>
                            <p>ISBN: ${code}</p>
                            <p>Amazon URL: <a href="${amazonUrl}" target="_blank">${amazonUrl}</a></p>
                        `;
                        
                        if (window.opener) {
                            window.opener.document.getElementById('book').value = "タイトル不明";
                            window.opener.document.getElementById('url').value = amazonUrl;
                            document.getElementById('status').textContent = "エラーが発生しました。ISBNとAmazon URLのみ入力されました。";
                        } else {
                            document.getElementById('status').textContent = "エラーが発生しました。親ウィンドウが見つかりません。";
                        }

                        document.getElementById('debug').innerHTML = `
                            <h3>エラー情報:</h3>
                            <pre>${JSON.stringify(error, Object.getOwnPropertyNames(error), 2)}</pre>
                        `;
                    });

                    
            } else {
                // If the barcode is invalid, show an error message
                document.getElementById('result').textContent = "無効なバーコード: " + code;
                document.getElementById('status').textContent = "無効なバーコードです。再度スキャンしてください。";
            }
        });
    </script>
</body>
</html>