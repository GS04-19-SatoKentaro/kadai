<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');

//セッションを開始
session_start();

//フォームに入力されたユーザー情報用
$useridInputed = "";
$passwordInputed = "";

//データベースから取得されたユーザー情報用
$useridFromDB = "";
$passwordFromDB = "";

# エラーメッセージの変数を初期化します。
$error = '';

# 認証済みかどうかのセッション変数を初期化します。
if (!isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
}

if (isset($_POST['userid']) && isset($_POST['password'])) {
    $useridInputed = $_POST['userid'];
    $passwordInputed = $_POST['password'];

    //1. DB接続します
    require_once './lib/connectdb.php';
    //try {
    //  $pdo = new PDO('mysql:dbname=an;charset=utf8;host=localhost','root','');
    //} catch (PDOException $e) {
    //  exit('DbConnectError:'.$e->getMessage());
    //}

    //プリペアドステートメントのエミュレーションの無効化
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //2. データ取得SQL作成
    $stmt = $pdo->prepare("SELECT password FROM users WHERE mail = :userid");
    $stmt->bindValue(':userid', $useridInputed);
    //3. SQL実行
    $flag = $stmt->execute();

    //4. データ処理
    $view = "";
    if ($flag == false) {
        $view = "SQLエラー";
    } else {
        $result = $stmt->fetchColumn();
        if ($result) {
            $passwordFromDB = $result;
            if (password_verify($passwordInputed, $passwordFromDB)) {
                # セッション固定化攻撃☆レシピ301☆（セッション固定化攻撃を防ぎたい）を防ぐため、セッションIDを変更します。
                session_regenerate_id(true);
                $_SESSION['auth'] = true;
                //ユーザー名をデータベースから取得
                $stmt = $pdo->prepare("SELECT name FROM users WHERE mail = :userid");
                $stmt->bindValue(':userid', $useridInputed);
                //3. SQL実行
                $flag = $stmt->execute();
                $userName = $stmt->fetchColumn();
                $_SESSION['username'] = $userName;
            }
        }
    }

    if ($_SESSION['auth'] === false) {
        $error = 'ユーザーIDかパスワードに誤りがあります。';
    }
}

if ($_SESSION['auth'] !== true) {
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
                <form id="login" class="questionnaire" method="post" action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>">
                    <div id="suggestion">
                        メールアドレスとパスワードを入力してください。<?php if($error) echo '<div class="error">'.$error.'</div>' ?>
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


    <?php
    # スクリプトを終了し、認証が必要なページが表示されないようにします。
    exit();
}
/* ?>終了タグ省略 ☆レシピ001☆（サーバーのPHP情報を知りたい） */
