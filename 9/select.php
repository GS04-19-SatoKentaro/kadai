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
sessionCheck(); //funcの関数

//ログイン時のセッションへの情報セット
$username = loginRollSet();

//ログインメニューの作成
$loginMenuHTML = loginMenuSet();

//アンケートデータの処理
$dataArray = []; //読み込んだデータを保存する配列
//$dataArray = array_reverse($dataArray);
$dataHtmlArray = [];
//男女比用
$sex_male = 0;
$sex_female = 0;
$sex_none = 0;

//評価の結果用
$ratings = [];
$ratings_5 = 0;
$ratings_4 = 0;
$ratings_3 = 0;
$ratings_2 = 0;
$ratings_1 = 0;

//2. DB接続します(エラー処理追加)
require_once './lib/connectdb.php';
$pdo = connectdb();

//２．データ取得SQL作成

$keyword = "";

if (!isset($_GET["keyword"])) {
    //キーワードを受け取ってない場合
    $stmt = $pdo->prepare("SELECT * FROM an_table ORDER BY id DESC");
//    $stmt = $pdo->prepare("SELECT * FROM an_table ORDER BY id DESC LIMIT 5");
} else {
    $keyword = $_GET["keyword"];
    //http://tips.recatnap.info/wiki/Mysql%E3%81%A7%E3%80%81%E8%A4%87%E6%95%B0%E3%82%AB%E3%83%A9%E3%83%A0%E3%81%AB%E5%AF%BE%E3%81%97%E3%81%A6%E3%81%AE%E4%B8%80%E6%8B%AC%E6%A4%9C%E7%B4%A2(concat())
    //http://qiita.com/mpyw/items/b00b72c5c95aac573b71#2-5
    $stmt = $pdo->prepare("SELECT * FROM an_table WHERE concat(id, name, mail, age, sex, postal_1, postal_2, region, address, tel, rating, comments, date, date_updated) LIKE ? ESCAPE '!' ORDER BY id DESC");
    $stmt->bindValue(1, '%' . preg_replace('/(?=[!_%])/', '!', $keyword) . '%', PDO::PARAM_STR);
}

//３．SQL実行
$flag = $stmt->execute();
//４データ処理
$view = "";
if ($flag == false) {
    $view = "SQLエラー";
} else {
    //Selectデータの数だけ自動でループしてくれる
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //$resultに連想配列で入ってる
        //5.表示文字列を作成→変数に追記で代入
        //name, mail, age, sex, postal, region, address, tel, rating, comments, date
//        $array = [$result['id'], $result['name'], $result['mail'], $result['age'], $result['sex'], $result['postal_1'], $result['postal_2'], $result['region'], $result['address'], $result['tel'], $result['rating'], $result['comments'], $result['date'], $result['date_updated']];
//        list($id, $name, $mail, $age, $sex, $postal_1, $postal_2, $region, $address, $tel, $rating, $comments, $date, $date_updated) = $array;
        list($id, $name, $mail, $age, $sex, $postal_1, $postal_2, $region, $address, $tel, $rating, $comments, $date, $date_updated) = array_values($result);

//        $dataArray[] = $array; //現状では再利用なし
        $dataRowHtml_item = '<td class="id"><a class="updateLink" href="detail.php?id=' . $id . '">' . $id . '</a><td class="name">' . $name . '</td><td class="mail">' . $mail . '</td><td class="age">' . $age . '</td><td class="sex">' . $sex . '</td><td class="postal">' . $postal_1 . '-' . $postal_2 . '</td><td class="region">' . $region . '</td><td class="address">' . $address . '</td><td class="tel">' . $tel . '</td><td class="rating">' . $rating . '</td><td class="comments">' . $comments . '</td><td class="date">' . $date . '</td><td class="date_updated">' . $date_updated . '</td>';
        $dataRowHtml = '<tr>' . $dataRowHtml_item . '</tr>';
        $dataHtmlArray[] = $dataRowHtml;
        $ratings[] = $rating;
        switch ($rating) {
            case 5:
                $ratings_5++;
                break;
            case 4:
                $ratings_4++;
                break;
            case 3:
                $ratings_3++;
                break;
            case 2:
                $ratings_2++;
                break;
            case 1:
                $ratings_1++;
                break;
            default:
                break;
        }
        switch ($sex) {
            case "男性":
                $sex_male++;
                break;
            case "女性":
                $sex_female++;
                break;
            default:
                $sex_none++;
                break;
        }
    }
}
$dataHtmlArrayString = implode("\n", $dataHtmlArray);
$ratingsNumber = count($ratings);
$ratings_5_percentage = $ratings_5 / $ratingsNumber * 100;
$ratings_5_percentage = round($ratings_5_percentage);
$ratings_4_percentage = $ratings_4 / $ratingsNumber * 100;
$ratings_4_percentage = round($ratings_4_percentage);
$ratings_3_percentage = $ratings_3 / $ratingsNumber * 100;
$ratings_3_percentage = round($ratings_3_percentage);
$ratings_2_percentage = $ratings_2 / $ratingsNumber * 100;
$ratings_2_percentage = round($ratings_2_percentage);
$ratings_1_percentage = $ratings_1 / $ratingsNumber * 100;
$ratings_1_percentage = round($ratings_1_percentage);

function getRatingAverage($values) {
    $number = count($values);
    $totalValue = 0;
    foreach ($values as $value) {
        $totalValue += $value;
    }
    return $totalValue / $number;
}

$ratingAverage = getRatingAverage($ratings);
//http://techracho.bpsinc.jp/shibuya/2015_05_18/20313
//http://hp.vector.co.jp/authors/VA056612/fixed_midashi/manual/index.html
?>

<!DOCTYPE HTML>
<html lang="ja">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>アンケート結果（データ表示）</title>
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
        <h1>アンケート結果</h1>
        <form id = "searchForm" name="searchForm" action="./select.php" method="get">
            <p>
                <span class="itemName">キーワード</span>
                <span class="itemBody"><input id="keywordBox" type="text" name="keyword" size="20" value="<?= h($keyword) ?>"></span>
                <input class="active smallSubmit" type="submit" name="search" value="絞り込み検索">
            </p>
        </form>
        <main>
            <ul class="sectionTabs">
                <li class="resultTitle activeTitle">アンケート結果の一覧</li>
                <li class="resultTitle">アンケート結果（個別）</li>
            </ul>
            <section id="rowData" class="resultBody activeResult">
                <!--                http://php5.seesaa.net/category/4135047-1.html-->
                <div id="resultTable" class="scroll_div">
                    <table data-fixedhead="rows:1; cols:1">
                        <thead><tr><th>ID</th><th>名前</th><th>メールアドレス</th><th>年齢</th><th>性別</th><th>郵便番号</th><th>都道府県</th><th>住所</th><th>電話番号</th><th>評価</th><th>コメント</th><th>送信日時</th><th>更新日時</th></tr></thead>
                        <tbody>
<?= $dataHtmlArrayString ?>
                        </tbody>
                    </table>
                </div>
            </section>
            <section class="resultBody">
                <div class="resultSection">
                    <h3 class="resultSectionTitle">評価</h3>
                    <h4 class="resultItemTitle">平均評価</h4>
                    <div id="average" class="resultItem">
                        <span id="ratingResult"><?= round($ratingAverage * 10) / 10 ?></span>
                        <span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span>
                    </div>
                    <!--http://www.moongift.jp/2014/11/cssplot-css%E3%81%A0%E3%81%91%E3%81%A7%E4%BD%9C%E3%82%89%E3%82%8C%E3%81%A6%E3%81%84%E3%82%8B%E3%82%B7%E3%83%B3%E3%83%97%E3%83%AB%E3%81%AA%E3%82%B0%E3%83%A9%E3%83%95/-->
                    <h4 class="resultItemTitle">評価の割合</h4>
                    <div id="ratings" class="resultItem">
                        <ul>
                            <li class="ratingBar"><span class="ratingLabel">星5つ：</span><span class="ratingParcentage"><?= $ratings_5_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?= $ratings_5 ?>／<?= $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星4つ：</span><span class="ratingParcentage"><?= $ratings_4_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?= $ratings_4 ?>／<?= $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星3つ：</span><span class="ratingParcentage"><?= $ratings_3_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?= $ratings_3 ?>／<?= $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星2つ：</span><span class="ratingParcentage"><?= $ratings_2_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?= $ratings_2 ?>／<?= $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星1つ：</span><span class="ratingParcentage"><?= $ratings_1_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?= $ratings_1 ?>／<?= $ratingsNumber ?>票</span></li>
                        </ul>
                    </div>
                </div>
                <div class="resultSection">
                    <h3 class="resultSectionTitle">男女比</h3>
                    <h4 class="resultItemTitle">男女数</h4>
                    <div id="sexNumbers" class="resultItemText">
                        <ul>
                            <li>男：<span class="sexNumber"><?= $sex_male ?></span></li>
                            <li>女：<span class="sexNumber"><?= $sex_female ?></span></li>
                            <li>無回答：<span class="sexNumber"><?= $sex_none ?></span></li>
                        </ul>
                    </div>
                    <h4 class="resultItemTitle">男女比</h4>
                    <div id="chart_div"></div>
                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!--    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
        <script src="./js/fixed_midashi.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="./js/select.js"></script>
    </body>

</html>
