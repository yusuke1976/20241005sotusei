<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>本のバーコードスキャナー</title>
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
    </style>
</head>
<body>
    <div id="interactive" class="viewport"></div>
    <div id="result"></div>

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
                return;
            }
            console.log("Initialization finished. Ready to start");
            Quagga.start();
        });

        Quagga.onDetected(function(result) {
            var code = result.codeResult.code;

            // Check if the barcode starts with '9'
            if (code.startsWith('9')) {
                document.getElementById('result').textContent = "Barcode: " + code;
                
                // Amazon.co.jpの書籍ページURLを生成
                var amazonUrl = `https://www.amazon.co.jp/s?k=${code}&i=stripbooks`;
                
                // Google Books APIを使用して書籍情報を取得
                fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${code}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.items && data.items.length > 0) {
                            var bookTitle = data.items[0].volumeInfo.title;
                            
                            // 親ウィンドウのフォームに書籍名とURLを設定
                            window.opener.document.getElementById('book').value = bookTitle;
                            window.opener.document.getElementById('url').value = amazonUrl;
                            
                            // このウィンドウを閉じる
                            window.close();
                        } else {
                            // 書籍情報が見つからない場合は、AmazonのURLのみを設定
                            window.opener.document.getElementById('url').value = amazonUrl;
                            window.close();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // エラーが発生した場合は、AmazonのURLのみを設定
                        window.opener.document.getElementById('url').value = amazonUrl;
                        window.close();
                    });
            } else {
                // If the barcode does not start with '9', ignore it.
                document.getElementById('result').textContent = "Invalid barcode: " + code;
            }
        });
    </script>
</body>
</html>