<?php
# 認証を要求したいページの先頭に以下を記述します。
require_once './login.php';
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
                ログインユーザー名：
                <?php echo h($_SESSION['username']); ?>
                <div class="button" id="loginButton"><a href="./logout.php">Logout</a></div>
            </div>
        </header>
        <h1>アンケート結果</h1>
        <main>
            <ul class="sectionTabs">
                <li class="resultTitle activeTitle">アンケート結果の一覧</li>
                <li class="resultTitle">アンケート結果（個別）</li>
            </ul>
            <section id="rowData" class="resultBody activeResult">
                <div id="resultTable" class="scroll_div">
                    <?php
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
                    //1.  DB接続します
                    $pdo = new PDO('mysql:dbname=an;charset=utf8;host=localhost', 'root', ''); //セミコロンは区切り
                    //２．データ登録SQL作成
                    $stmt = $pdo->prepare("SELECT * FROM an_table");
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
                            $array = [$result['id'], $result['name'], $result['mail'], $result['age'], $result['sex'], $result['postal'], $result['region'], $result['address'], $result['tel'], $result['rating'], $result['comments'], $result['date']];
                            list($id, $name, $mail, $age, $sex, $postal, $region, $address, $tel, $rating, $comments, $date) = $array;
                            $dataArray[] = $array; //現状では再利用なし
                            $dataRowHtml_item = '<td class="id">' . $id . '<td class="name">' . $name . '</td><td class="mail">' . $mail . '</td><td class="age">' . $age . '</td><td class="sex">' . $sex . '</td><td class="$postal">' . $postal . '</td><td class="region">' . $region . '</td><td class="address">' . $address . '</td><td class="tel">' . $tel . '</td><td class="rating">' . $rating . '</td><td class="comments">' . $comments . '</td><td class="date">' . $date . '</td>';
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
                    echo '<table data-fixedhead="rows:1; cols:1">';
                    echo '<thead><tr><th>ID</th><th>名前</th><th>メールアドレス</th><th>年齢</th><th>性別</th><th>郵便番号</th><th>都道府県</th><th>住所</th><th>電話番号</th><th>評価</th><th>コメント</th><th>送信日時</th></tr></thead>';
                    echo '<tbody>';
                    echo $dataHtmlArrayString;
                    echo '</tbody>';
                    echo '</table>';
                    ?>
                </div>
            </section>
            <section class="resultBody">
                <div class="resultSection">
                    <h3 class="resultSectionTitle">評価</h3>
                    <h4 class="resultItemTitle">平均評価</h4>
                    <div id="average" class="resultItem">
                        <span id="ratingResult"><?php echo round($ratingAverage * 10) / 10 ?></span>
                        <span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span><span class="ratingImageResult">★</span>
                    </div>
                    <!--http://www.moongift.jp/2014/11/cssplot-css%E3%81%A0%E3%81%91%E3%81%A7%E4%BD%9C%E3%82%89%E3%82%8C%E3%81%A6%E3%81%84%E3%82%8B%E3%82%B7%E3%83%B3%E3%83%97%E3%83%AB%E3%81%AA%E3%82%B0%E3%83%A9%E3%83%95/-->
                    <h4 class="resultItemTitle">評価の割合</h4>
                    <div id="ratings" class="resultItem">
                        <ul>
                            <li class="ratingBar"><span class="ratingLabel">星5つ：</span><span class="ratingParcentage"><?php echo $ratings_5_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?php echo $ratings_5 ?>／<?php echo $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星4つ：</span><span class="ratingParcentage"><?php echo $ratings_4_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?php echo $ratings_4 ?>／<?php echo $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星3つ：</span><span class="ratingParcentage"><?php echo $ratings_3_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?php echo $ratings_3 ?>／<?php echo $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星2つ：</span><span class="ratingParcentage"><?php echo $ratings_2_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?php echo $ratings_2 ?>／<?php echo $ratingsNumber ?>票</span></li>
                            <li class="ratingBar"><span class="ratingLabel">星1つ：</span><span class="ratingParcentage"><?php echo $ratings_1_percentage ?>%</span><span class="ratingParcentageBar"><span class="bar"></span></span><span class="ratingNumber"><?php echo $ratings_1 ?>／<?php echo $ratingsNumber ?>票</span></li>
                        </ul>
                    </div>
                </div>
                <div class="resultSection">
                    <h3 class="resultSectionTitle">男女比</h3>
                    <h4 class="resultItemTitle">男女数</h4>
                    <div id="sexNumbers" class="resultItemText">
                       <ul>
                           <li>男：<span class="sexNumber"><?php echo $sex_male ?></span></li>
                           <li>女：<span class="sexNumber"><?php echo $sex_female ?></span></li>
                           <li>無回答：<span class="sexNumber"><?php echo $sex_none ?></span></li>
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