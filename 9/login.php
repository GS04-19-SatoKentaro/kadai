<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');
?>

<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>ログイン</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <header class="headerGlobal">
            <div class="top"><a href="./">My questionnaire</a></div>
            <div class="userMenu">
                ユーザー登録：<div class="button" id="registerButton"><a href="./register.php">Register</a></div>
            </div>
        </header>
        <h1>ログイン</h1>
        <main>
            <form id="login" class="questionnaire" method="post" action="./login_act.php">
                <div id="suggestion">
                    メールアドレスとパスワードを入力してください。
                </div>

                <fieldset>
                    <legend>ログイン情報</legend>
                    <!--phpはnameが変数になる、必ずつける-->
                    <p class="required"><span class="itemName">E-mail</span>
                        <span class="itemBody"><input class="answer required" type="email" name="userid" size="20" title="E-mailアドレスを入力してください。" required pattern="^\S+@\S+\.\S+$"></span>
                    </p>
                    <p class="required"><span class="itemName">パスワード</span>
                        <span class="itemBody"><input class="answer required" type="password" name="password" size="20" title="パスワードを入力してください。"></span>
                    </p>
                </fieldset>
                <!--ラジオボタン、セレクトボックスは面倒-->

                <p id="submit">
                    <input class="inactive" type="submit" value="送信">
                </p>

                <button id="reset">入力内容を消去</button>
                <!--nameとmailの値を送る-->
            </form>
        </main>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="./js/login.js"></script>
    </body>

</html>


