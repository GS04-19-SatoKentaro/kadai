<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');

session_start(); //最初に書く

require_once 'func.php';

//2. セッションチェック(前ページのSESSION＿IDと現在のsession_idを比較)
sessionCheck();//funcの関数

//ログイン時のセッションへの情報セット
$username = loginRollSet();

//管理者権限があるかどうかチェック
adminCheck();

//ログインメニューの作成
$loginMenuHTML = '';
$loginMenuHTML .= '<div class="button" id="loginUsersButton"><a href="./users.php">ユーザー一覧</a></div>';
$loginMenuHTML .= '<div class="button" id="loginButton"><a href="./logout.php">Logout</a></div>';

//1.GETでidを取得
if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

if (isset($_POST["id"])) {
    $id = $_POST["id"];
}

//2. DB接続します(エラー処理追加)
require_once './lib/connectdb.php';
$pdo = connectdb();

//３．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=:a1");
$stmt->bindValue(':a1', $id);
$status = $stmt->execute();

//４．データ取得処理後
if($status==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

$result = $stmt->fetch();

$array = [$result['name'], $result['mail'], $result['password'], $result['date_added'], $result['date_updated'], $result['admin_flg'], $result['life_flg']];
list($name, $mail, $password, $date_added, $date_updated, $admin_flg, $life_flg) = $array;

//list($name, $mail, $password, $date_added, $date_updated, $admin_flg, $life_flg) = array_values($result);

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
    if (isset($_POST['id']) == false) {
        $ceckedInputData = true;
        break;
    }
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
    $id = $_POST['id'];
    $usernameInputed = $_POST['name'];
    $useridInputed = $_POST['userid'];
    $passwordInputed = $_POST['password'];

    try {
        //1. DB接続します
        require_once './lib/connectdb.php';

        $pdo = connectdb();

        //プリペアドステートメントのエミュレーションの無効化
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        //エラーが発生した場合、例外がスローされるようにする。
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //2. IDとして使用するメールアドレスデータ取得SQL作成
        $stmt = $pdo->prepare("SELECT * FROM users WHERE mail = :userid");
        $stmt->bindValue(':userid', $useridInputed);
        //3. SQL実行
        $flag = $stmt->execute();

        //4. データ処理
        if ($flag == false) {
            $error = "SQLエラー";
        } else {
            $result = $stmt->fetch();
            if ($result['mail'] == $useridInputed && $result['id'] != $id) {
                $error = 'このメールアドレスはすでに登録済です。'.'mail:'.$result['mail'].'id:'.$result['id'];
            } else {

                # ハッシュ処理の計算コストを指定します。ソルトは自動生成とします。
                $options = array('cost' => 10);
                # ハッシュ化方式にPASSWORD_DEFAULTを指定し、パスワードをハッシュ化します。
                $passwordInputedHashed = password_hash($passwordInputed, PASSWORD_DEFAULT, $options);

                $array = array($usernameInputed, $useridInputed, $passwordInputedHashed);

                //登録情報書き込み用のSQL作成
                
                $stmt = $pdo->prepare("UPDATE users SET name=:newname, mail=:newmail, password=:newpassword, date_updated=sysdate() WHERE id=:id");

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':newname', $array[0]);
                $stmt->bindValue(':newmail', $array[1]);
                $stmt->bindValue(':newpassword', $array[2]);

                $status = $stmt->execute();

                //3. SQL実行
                $flag = $stmt->execute();
                if ($flag) {
                    $registerd = true;
                    $error = '更新されました。';
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
        <title>ユーザー情報（更新、削除）</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <header class="headerGlobal">
            <div class="top"><a href="./">My questionnaire</a></div>
            <div class="userMenu">
                ログインユーザー名：<?= h($username) ?>
                <?= $loginMenuHTML ?>
            </div>
        </header>
        <h1>ユーザー情報（更新、削除）</h1>
        <main>
            <form id="login" class="questionnaire" method="post" action="<?php echo h($_SERVER['SCRIPT_NAME']); ?>">
                <div id="suggestion">
                    お名前、メールアドレス、パスワードを入力してください。
                    <?php if($error) echo '<div class="error">'.h($error).'</div>' ?>
                </div>

                <fieldset>
                    <legend>登録情報</legend>
                    <!--phpはnameが変数になる、必ずつける-->
                    <input type="hidden" name="id" value ="<?=$id?>">

                    <p class="required"><span class="itemName">お名前</span>
                        <span class="itemBody"><input class="answer required" type="text" name="name" placeholder="例）山田　太郎" size="20" title="あなたの名前を入力してください。" value ="<?=$name?>" required></span>
                    </p>
                    <p class="required"><span class="itemName">E-mail</span>
                        <span class="itemBody"><input class="answer required" type="email" name="userid" placeholder="例）●●●●@●●●●.●●●" size="20" title="E-mailアドレスを入力してください。" value ="<?=$mail?>"  required pattern="^\S+@\S+\.\S+$"></span>
                    </p>
                    <p class="required"><span class="itemName">パスワード</span>
                        <span class="itemBody"><input class="answer required" type="password" name="password" placeholder="●●●●●●●●" size="20" title="パスワードを入力してください。" value ="" ></span>
                    </p>
                </fieldset>
                <!--ラジオボタン、セレクトボックスは面倒-->

                <p id="submit">
                    <input class="inactive" type="submit" value="更新">
                </p>

                <button id="reset">入力内容を消去</button>
                <!--nameとmailの値を送る-->
            </form>
            
            <!--削除ボタン-->
            <form id="deleteForm" method="post" action="userdelete.php">
                <p>
                    <input type="hidden" name="id" value="<?=$id?>">
                    <input id="deleteSubmit" class="smallSubmit seriousSubmit active" type="submit" value="このユーザーを無効にする"><!--ここのvalueは表示だけ-->
                </p>
            </form>
        </main>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="./js/userdetail.js"></script>
    </body>
</html>
