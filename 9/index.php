<?php
//XSS対策用のhtmlspecialchars()をh($var)で利用
require_once './lib/h.php';
//PHP 5.5以前でpassword_verify()関数を使用する
require_once './lib/password_compat/password.php';
//クリックジャッキング対策、同一生成ページ以外でiframeで表示させない。
header('X-FRAME-OPTIONS: SAMEORIGIN');

//connectdb()でPDOを返す。
require_once './lib/connectdb.php';
//function connectdb(){
//    try {
//        return new PDO('mysql:dbname=an;charset=utf8;host=localhost','root','');
//    } catch (PDOException $e) {
//        exit('DbConnectError:'.$e->getMessage());
//    }
//}

//３．都道府県データ取得用SQL作成
$pdo = connectdb();
$stmtRegion = $pdo->prepare("SELECT * FROM regions");
$statusRegion = $stmtRegion->execute();
$regionOptionHtml ='<option value="未選択">未選択</option>';

//４．都道府県データ取得後
if($statusRegion==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $errorRegion = $stmtRegion->errorInfo();
    exit("QueryError:".$errorRegion[2]);
}else{
    //Selectデータの数だけ自動でループしてくれる
    while( $resultRegion = $stmtRegion->fetch(PDO::FETCH_ASSOC)){
        $regionOptionHtml .= '<option value="'.$resultRegion["region_name"].'">'.$resultRegion["region_name"].'</option>';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>アンケート（データ登録）</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/common.css">
</head>

<body>
    <!--        <div id="test">test</div>-->
    <header class="headerGlobal">
        <div class="top"><a href="./">My questionnaire</a></div>
        <div class="userMenu">
            管理用：<div class="button" id="loginButton"><a href="./login.php">Login</a></div>
        </div>
    </header>
    <h1>アンケート</h1>
    <main>
        <form id="q1" class="questionnaire h-adr" method="post" action="insert.php">
            <div id="suggestion">
                「*」が付いている項目：必須／その他：任意
            </div>
            <fieldset>
                <legend>個人情報</legend>
                <p class="required"><span class="itemName">お名前</span>
                    <span class="itemBody"><input class="answer required" type="text" name="name" placeholder="例）山田　太郎" size="20" title="あなたの名前を入力してください。" required></span>
                </p>
                <!--phpはnameが変数になる、必ずつける-->
                <p class="required"><span class="itemName">E-mail</span>
                    <span class="itemBody"><input class="answer required" type="email" name="mail" placeholder="例）●●●●@●●●●.●●●" size="20" title="E-mailアドレスを入力してください。" required pattern="^\S+@\S+\.\S+$"></span>
                </p>
                <p><span class="itemName">年齢</span>
                    <span class="itemBody"><input class="answer" type="number" name="age" min="0"></span>
                </p>
                <p><span class="itemName">性別</span>
                    <span class="itemBody">
                            <label class="radio">
                                <input type="radio" name="sex" value="男性">男性</label>
                            <label class="radio">
                                <input type="radio" name="sex" value="女性">女性</label>
                            <label class="radio">
                                <input type="radio" name="sex" value="無回答" checked>無回答</label>
                        </span>
                </p>
                <p><span class="itemName">郵便番号</span>
                    <!--https://github.com/yubinbango/yubinbango-->
                    <span class="p-country-name" style="display:none;">Japan</span>
                    <!--https://github.com/yubinbango/yubinbango-->
                    <span class="itemBody"><input class="answer p-postal-code" type="number" name="postal_1">-<input class="answer p-postal-code" type="number" name="postal_2"></span>
                </p>
                <p><span class="itemName">都道府県</span>
                    <!--https://github.com/yubinbango/yubinbango-->
                    <span class="itemBody">
                            <select class="answer p-region" name="region" size="1">
                                <?=$regionOptionHtml?>
                            </select>
                        </span>
                </p>
                <p><span class="itemName">住所</span>
                    <!--https://github.com/yubinbango/yubinbango-->
                    <span class="itemBody"><input class="answer p-locality p-street-address p-extended-address" type="text" name="address" size="20"></span>
                </p>
                <p><span class="itemName">電話</span>
                    <span class="itemBody"><input class="answer" type="tel" name="tel" size="20"></span>
                </p>
            </fieldset>
            <!--ラジオボタン、セレクトボックスは面倒-->

            <fieldset>
                <legend>ご感想</legend>
                <p class="required"><span class="itemName">5段階評価</span>
                    <span class="itemBody"><input id="rating" class="answer required" type="number" name="rating" min=1 max=5 title="評価を5段階で入力してください。" required></span>
                    <span class="ratingImage">★</span><span class="ratingImage">★</span><span class="ratingImage">★</span><span class="ratingImage">★</span><span class="ratingImage">★</span>
                </p>
                <!--phpはnameが変数になる、必ずつける-->
                <p><span class="itemName">その他</span>
                    <span class="itemBody"><textarea class="answer" name="comments" rows="5" cols="25" title="その他ご自由にご記入ください。"></textarea></span>
                </p>
            </fieldset>

            <p id="submit">
                <input class="inactive" type="submit" value="送信">
            </p>

            <button id="reset">入力内容を消去</button>
            <!--nameとmailの値を送る-->
        </form>
    </main>
    <footer class="footer">
       <div id="copyright">
           &copy; Kentaro Sato All Rights Reserved.
       </div>
    </footer>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
    <!--https://github.com/yubinbango/yubinbango-->
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
    <script src="./js/main.js"></script>
</body>

</html>