<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>送信結果</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/common.css">
</head>

<body>
    <h1>送信しました</h1>

    <?php
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
        $array = array($name, $mail, $age, $sex, $postal_1.'-'.$postal_2, $region, $address, $tel, $rating, $comments, $date);
        //http://php.net/manual/ja/function.implode.php
//        $str = implode(",", $array) . "\n"; //\nは改行
        //File操作、ログ取り等
        //http://php.net/manual/ja/function.fopen.php
        $file = fopen("./data/data.csv", "a"); //ファイルを開く、"a"はなければ勝手に作る？
        flock($file, LOCK_EX); //ファイルをロック
//        fputs($file, $str); //ファイルに書き込み
        fputcsv($file, $array); //ファイルに書き込み
        flock($file, LOCK_UN); //ファイルをロック解除
        fclose($file); //ファイルを閉じる
        ?>
        <main>
            <p id="thanks">ご感想ありがとうございました。</p>
            <button id="back">
                <a href="input_data.php">戻る</a>
            </button>
        </main>
        <!--        ファイルの中を確認してください。-->
</body>

</html>