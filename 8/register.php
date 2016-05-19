<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');

//フォームに入力されたユーザー情報用
$usernameInputed = "";
$useridInputed = "";
$passwordInputed = "";

//データベースから取得されたユーザー情報用
$usernameFromDB = "";
$useridFromDB = "";
$passwordFromDB = "";

# エラーメッセージの変数を初期化します。
$error = '';

# 登録がうまくいったかどうかのフラグ。
$registerd = false;

//入力値が入ったかチェックしたかどうか
$ceckedInputData = false;
//入力値が入ったかどうか
$allDataInputed = false;

while ($ceckedInputData === false) {
    if (isset($_POST['name']) == false) {
        $ceckedInputData = true;
        break;
    }
    if (isset($_POST['userid']) == false) {
        $ceckedInputData = true;
        break;
    }
    if (isset($_POST['password']) == false) {
        $ceckedInputData = true;
        break;
    }
    $allDataInputed = true;
    $ceckedInputData = true;
    break;
}

//if (isset($_POST['userid']) && isset($_POST['password'])) {
if ($allDataInputed) {
    $usernameInputed = $_POST['name'];
    $useridInputed = $_POST['userid'];
    $passwordInputed = $_POST['password'];

    try {
        //1. DB接続します
        $pdo = new PDO('mysql:dbname=an;charset=utf8;host=localhost', 'root', ''); //セミコロンは区切り
        //プリペアドステートメントのエミュレーションの無効化
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        //エラーが発生した場合、例外がスローされるようにする。
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //2. IDとして使用するメールアドレスデータ取得SQL作成
        $stmt = $pdo->prepare("SELECT mail FROM users WHERE mail = :userid");
        $stmt->bindValue(':userid', $useridInputed);
        //3. SQL実行
        $flag = $stmt->execute();

        //4. データ処理
        if ($flag == false) {
            $error = "SQLエラー";
        } else {
            $result = $stmt->fetchColumn();
            if ($result) {
                $error = 'このメールアドレスはすでに登録済です。';
            } else {
                
                # ハッシュ処理の計算コストを指定します。ソルトは自動生成とします。
                $options = array('cost' => 10);
                # ハッシュ化方式にPASSWORD_DEFAULTを指定し、パスワードをハッシュ化します。
                $passwordInputedHashed = password_hash($passwordInputed, PASSWORD_DEFAULT, $options);

                //登録情報書き込み用のSQL作成
                $stmt = $pdo->prepare("INSERT INTO users (id, name, mail, password, date_added, date_updated) 
                VALUES( NULL, :name, :userid, :password, sysdate(), sysdate() )");
                $stmt->bindValue(':name', $usernameInputed);
                $stmt->bindValue(':userid', $useridInputed);
                $stmt->bindValue(':password', $passwordInputedHashed);
                //3. SQL実行
                $flag = $stmt->execute();
                if ($flag) {
                    $registerd = true;
                    $error = '登録されました。';
                }
            }
        }
    } catch (PDOException $e) {
        $error = 'データベース処理中にエラーが発生しました。<br>内容：'.h($e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>ユーザー登録</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <header class="headerGlobal">
            <div class="top"><a href="./">My questionnaire</a></div>
            <div class="userMenu">
                管理用：
                <div class="button" id="loginButton"><a href="./select.php">Login</a></div>
            </div>
        </header>
        <h1>ユーザー登録</h1>
        <main>
            <form id="login" class="questionnaire" method="post" action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>">
                <div id="suggestion">
                    お名前、メールアドレス、パスワードを入力してください。
                    <?php if($error) echo '<div class="error">'.$error.'</div>' ?>
                </div>

                <fieldset>
                    <legend>登録情報</legend>
                    <!--phpはnameが変数になる、必ずつける-->
                    <p class="required"><span class="itemName">お名前</span>
                        <span class="itemBody"><input class="answer required" type="text" name="name" size="20" title="あなたの名前を入力してください。" required></span>
                    </p>
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
        <script src="./js/register.js"></script>
    </body>

</html>