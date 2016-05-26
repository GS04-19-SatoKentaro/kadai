<?php
# セッションを開始します。
session_start();

//SESSIONを初期化（空っぽにする）
$_SESSION = array();

//Cookieに保存してある"SessionIDの保存期間を過去にして破棄
if (isset($_COOKIE[session_name()])) { //session_name()は、セッションID名を返す関数
    setcookie(session_name(), '', time()-42000, '/');
}

//サーバ側での、セッションIDの破棄
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