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
        <title>登録結果</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/common.css">
    </head>

    <body>
        <h1>登録完了</h1>

        <?php
        $result = "";
        
        $name = $_POST["name"];
        $mail = $_POST["mail"];
        $age = $_POST["age"];
        $sex = $_POST["sex"];
        $postal_1 = $_POST["postal_1"];
        $postal_2 = $_POST["postal_2"];
        $region = $_POST["region"];
        $address = $_POST["address"];
        $tel = $_POST["tel"];
        $rating = $_POST["rating"];
        $comments = $_POST["comments"];

        if ($name == "") {
            $name = '未入力';
        }
        if ($mail == "") {
            $mail = '未入力';
        }
        if ($age == "") {
            $age = '未入力';
        }
        if ($sex == "") {
            $sex = '未入力';
        }
        if ($postal_1 == "") {
            $postal_1 = '未入力';
        }
        if ($postal_2 == "") {
            $postal_2 = '未入力';
        }
        if ($region == "") {
            $region = '未選択';
        }
        if ($address == "") {
            $address = '未入力';
        }
        if ($tel == "") {
            $tel = '未入力';
        }
        if ($rating == "") {
            $rating = '未入力';
        }
        if ($comments == "") {
            $comments = '未入力';
        }

        function htmlenc($str) {
//    http://php.net/manual/ja/function.htmlspecialchars.php
            return htmlspecialchars($str, ENT_QUOTES);
        }

        $date = date("Y年m月d日 H:i:s");

        $array = array($name, $mail, $age, $sex, $postal_1 . '-' . $postal_2, $region, $address, $tel, $rating, $comments, $date);


        $pdo = new PDO('mysql:dbname=an;charset=utf8;host=localhost', 'root', '');
        //データ登録SQL作成
        $stmt = $pdo->prepare("INSERT INTO an_table (id, name, mail, age, sex, postal, region, address, tel, rating, comments, date)
        VALUES( NULL, :a1, :a2, :a3, :a4, :a5, :a6, :a7, :a8, :a9, :a10, sysdate() )");

        $stmt->bindValue(':a1', $array[0]);
        $stmt->bindValue(':a2', $array[1]);
        $stmt->bindValue(':a3', $array[2]);
        $stmt->bindValue(':a4', $array[3]);
        $stmt->bindValue(':a5', $array[4]);
        $stmt->bindValue(':a6', $array[5]);
        $stmt->bindValue(':a7', $array[6]);
        $stmt->bindValue(':a8', $array[7]);
        $stmt->bindValue(':a9', $array[8]);
        $stmt->bindValue(':a10', $array[9]);

        $status = $stmt->execute();

        if ($status == false) {
            $result = "申し訳ありません。登録できませんでした。（SQLエラー）";
            //Errorの場合$status=falseとなり、エラー表示
//            echo "SQLエラー";
//            exit;
        } else {
            //５．index.phpへリダイレクト
            //header("Location: index.php"); //Locationは大文字、スペースは必ず入れる。
            $result = "登録は正常に行われました。";
//            exit();
        }
        ?>
        <main>
            <div class="message">ご感想ありがとうございました。<br><?php echo $result ?></div>
            <button id="back">
                <a href="index.php">戻る</a>
            </button>
        </main>
        <!--        ファイルの中を確認してください。-->
    </body>

</html>