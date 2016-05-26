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

$result = "";

$id = $_POST["id"];

//データベース接続
require_once './lib/connectdb.php';
$pdo = connectdb();
    
//データ登録SQL作成
$stmt = $pdo->prepare("DELETE FROM an_table WHERE id=:id");
$stmt->bindValue(':id', $id);
$status = $stmt->execute();

if ($status == false) {
    $result = "申し訳ありません。削除できませんでした。（SQLエラー）";
    //Errorの場合$status=falseとなり、エラー表示
    //            echo "SQLエラー";
    //            exit;
} else {
    //５．index.phpへリダイレクト
    //header("Location: index.php"); //Locationは大文字、スペースは必ず入れる。
    $result = "削除は正常に行われました。";
    //            exit();
}

?>

<!DOCTYPE HTML>
<html lang="ja">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>削除結果</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <h1>削除結果</h1>

        <main>
            <div class="message">削除しました。<br><?php echo $result ?></div>
            <button id="back">
                <a href="select.php">戻る</a>
            </button>
        </main>
    </body>
</html>
