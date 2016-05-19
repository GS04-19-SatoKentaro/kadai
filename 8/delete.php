<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');
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

        <?php
        $result = "";

        $id = $_POST["id"];

        $pdo = new PDO('mysql:dbname=an;charset=utf8;host=localhost', 'root', '');
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
        <main>
            <div class="message">削除しました。<br><?php echo $result ?></div>
            <button id="back">
                <a href="select.php">戻る</a>
            </button>
        </main>
        <!--        ファイルの中を確認してください。-->
    </body>

</html>
