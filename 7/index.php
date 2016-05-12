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
    <title>アンケート（データ登録）</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/common.css">
</head>

<body>
    <!--        <div id="test">test</div>-->
    <header class="headerGlobal">
        <div class="top"><a href="./">My questionnaire</a></div>
        <div class="userMenu">
            管理用：<div class="button" id="loginButton"><a href="./select.php">Login</a></div>
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
                    <span class="itemBody"><input class="answer required" type="text" name="name" size="20" title="あなたの名前を入力してください。" required></span>
                </p>
                <!--phpはnameが変数になる、必ずつける-->
                <p class="required"><span class="itemName">E-mail</span>
                    <span class="itemBody"><input class="answer required" type="email" name="mail" size="20" title="E-mailアドレスを入力してください。" required pattern="^\S+@\S+\.\S+$"></span>
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
                                <option value="未選択">未選択</option>
                                <option value="北海道">北海道</option>
                                <option value="青森県">青森県</option>
                                <option value="岩手県">岩手県</option>
                                <option value="宮城県">宮城県</option>
                                <option value="秋田県">秋田県</option>
                                <option value="山形県">山形県</option>
                                <option value="福島県">福島県</option>
                                <option value="茨城県">茨城県</option>
                                <option value="栃木県">栃木県</option>
                                <option value="群馬県">群馬県</option>
                                <option value="埼玉県">埼玉県</option>
                                <option value="千葉県">千葉県</option>
                                <option value="東京都">東京都</option>
                                <option value="神奈川県">神奈川県</option>
                                <option value="新潟県">新潟県</option>
                                <option value="富山県">富山県</option>
                                <option value="石川県">石川県</option>
                                <option value="福井県">福井県</option>
                                <option value="山梨県">山梨県</option>
                                <option value="長野県">長野県</option>
                                <option value="岐阜県">岐阜県</option>
                                <option value="静岡県">静岡県</option>
                                <option value="愛知県">愛知県</option>
                                <option value="三重県">三重県</option>
                                <option value="滋賀県">滋賀県</option>
                                <option value="京都府">京都府</option>
                                <option value="大阪府">大阪府</option>
                                <option value="兵庫県">兵庫県</option>
                                <option value="奈良県">奈良県</option>
                                <option value="和歌山県">和歌山県</option>
                                <option value="鳥取県">鳥取県</option>
                                <option value="島根県">島根県</option>
                                <option value="岡山県">岡山県</option>
                                <option value="広島県">広島県</option>
                                <option value="山口県">山口県</option>
                                <option value="徳島県">徳島県</option>
                                <option value="香川県">香川県</option>
                                <option value="愛媛県">愛媛県</option>
                                <option value="高知県">高知県</option>
                                <option value="福岡県">福岡県</option>
                                <option value="佐賀県">佐賀県</option>
                                <option value="長崎県">長崎県</option>
                                <option value="熊本県">熊本県</option>
                                <option value="大分県">大分県</option>
                                <option value="宮崎県">宮崎県</option>
                                <option value="鹿児島県">鹿児島県</option>
                                <option value="沖縄県">沖縄県</option>
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