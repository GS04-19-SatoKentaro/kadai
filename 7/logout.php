<?php
# セッションを開始します。
session_start();
# セッションを破棄☆レシピ232☆（セッションを破棄したい）します。
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>ログアウト</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <header class="headerGlobal">
            <div class="top"><a href="./">My questionnaire</a></div>
        </header>
        <h1>ログアウト</h1>
        <main>
            <div class="message">ログアウトしました。</div>
            <button id="back">
                <a href="index.php">戻る</a>
            </button>
        </main>
    </body>

</html>