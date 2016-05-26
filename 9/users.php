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

//管理者権限があるかどうかチェック
adminCheck();

//ログイン時のセッションへの情報セット
$username = loginRollSet();

//ログインメニューの作成
$loginMenuHTML = '';
$loginMenuHTML .= '<div class="button" id="loginUsersButton"><a href="./select.php">アンケート結果</a></div>';
$loginMenuHTML .= '<div class="button" id="loginButton"><a href="./logout.php">Logout</a></div>';

//ユーザーデータの処理
$dataArray = []; //読み込んだデータを保存する配列
$dataHtmlArray = [];

//2. DB接続します(エラー処理追加)
require_once './lib/connectdb.php';
$pdo = connectdb();

//２．データ取得SQL作成

$keyword = "";

if (!isset($_GET["keyword"])) {
    //キーワードを受け取ってない場合
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
} else {
    $keyword = $_GET["keyword"];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE concat(id, name, mail, password, date_added, date_updated) LIKE ? ESCAPE '!' ORDER BY id DESC");
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
        list($id, $name, $mail, $password, $date_added, $date_updated, $admin_flg, $life_flg) = array_values($result);
        $dataRowHtml_item = '<td class="id"><a class="updateLink" href="userdetail.php?id=' . $id . '">' . $id . '</a><td class="name">' . $name . '</td><td class="mail">' . $mail . '</td><td class="password">' . $password . '</td><td class="date_added">' . $date_added . '</td><td class="date_updated">' . $date_updated . '</td><td class="admin_flg">' . $admin_flg . '</td><td class="life_flg">' . $life_flg . '</td>';
        $dataRowHtml = '<tr>' . $dataRowHtml_item . '</tr>';
        $dataHtmlArray[] = $dataRowHtml;
    }
}
$dataHtmlArrayString = implode("\n", $dataHtmlArray);

?>

<!DOCTYPE HTML>
<html lang="ja">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>ユーザー一覧</title>
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
        <h1>ユーザー一覧</h1>
        <form id = "searchForm" name="searchForm" action="./users.php" method="get">
            <p>
                <span class="itemName">キーワード</span>
                <span class="itemBody"><input id="keywordBox" type="text" name="keyword" size="20" value="<?= h($keyword) ?>"></span>
                <input class="active smallSubmit" type="submit" name="search" value="絞り込み検索">
            </p>
        </form>
        <main>
            <section id="rowData" class="resultBody activeResult">
                <!--                http://php5.seesaa.net/category/4135047-1.html-->
                <div id="resultTable" class="scroll_div">
                    <table data-fixedhead="rows:1; cols:1">
                        <thead><tr><th>ID</th><th>名前</th><th>メールアドレス</th><th>パスワード（ハッシュ）</th><th>追加日時</th><th>更新日時</th><th>管理者：1<br>一般：0</th><th>無効：1<br>有効：0</th></tr></thead>
                        <tbody>
                        <?= $dataHtmlArrayString ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <!--    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
        <script src="./js/fixed_midashi.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="./js/users.js"></script>
    </body>

</html>
