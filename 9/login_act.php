<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');

//connectdb()でPDOを返す。
require_once './lib/connectdb.php';

session_start(); //最初に書く
//include('func.php'); //外部ファイル読み込み（関数群の予定）
//1. 接続します
$pdo = connectdb();

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

    $pdo = connectdb();
    //プリペアドステートメントのエミュレーションの無効化
    //レシピ260, p.663
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //2. 入力されたメールアドレスを持つuserのパスワードを取得するSQLを作成
    $stmt = $pdo->prepare("SELECT password FROM users WHERE mail = :userid");
    $stmt->bindValue(':userid', $useridInputed);
    //3. SQL実行
    $flag = $stmt->execute();

    //4. データ処理
    if ($flag == false) {
        $error .= "SQLエラー";
    } else {
        $result = $stmt->fetchColumn();
        if ($result) {
            $passwordFromDB = $result;
            if (password_verify($passwordInputed, $passwordFromDB)) {
                # セッション固定化攻撃☆レシピ301☆（セッション固定化攻撃を防ぎたい）を防ぐため、セッションIDを変更します。
                session_regenerate_id(true);
                $_SESSION['auth'] = true;
                //ユーザー情報をデータベースから取得
                $stmt = $pdo->prepare("SELECT * FROM users WHERE mail = :userid");
                $stmt->bindValue(':userid', $useridInputed);
                //3. SQL実行
                $flag = $stmt->execute();
                $userInfo = $stmt->fetch();
                $userName = $userInfo['name'];
                $_SESSION['username'] = $userName;
                $_SESSION["chk_ssid"] = session_id();
                $_SESSION["admin_flg"] = $userInfo['admin_flg'];
            }
        }
    }

    if ($_SESSION['auth'] === false) {
        $error .= 'ユーザーIDかパスワードに誤りがあります。';
        if ($error) echo '<div class="error">' . $error . '</div>';
        //logout処理を経由して前画面へ
        header("Location: login_ban.php");
        
    } else {
        header("Location: select.php");
    }
}

exit();

/*
//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM users WHERE lid=:lid AND lpw=:lpw");
$stmt->bindValue(':lid', $_POST["userid"]);
$stmt->bindValue(':lpw', $_POST["password"]);
$res = $stmt->execute();

//SQL実行時にエラーがある場合
if ($res == false) {
    $error = $stmt->errorInfo();
    exit("QueryError:" . $error[2]);
}

//３．抽出データ数を取得
//$count = $stmt->fetchColumn(); //SELECT COUNT(*)で使用可能()
$val = $stmt->fetch(); //1レコードだけ取得する方法
//４. 該当レコードがあればSESSIONに値を代入
if ($val["id"] != "") {
    $_SESSION["chk_ssid"] = session_id();
    $_SESSION["admin_flg"] = $val['admin_flg'];
    $_SESSION["name"] = $val['name'];
    header("Location: select.php");
} else {
    //logout処理を経由して全画面へ
    header("Location: logout.php");
}

exit();
*/
?>

