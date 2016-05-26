<?php
//DB接続
function db(){
    try {
        return new PDO('mysql:dbname=gs_db;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
        exit('データベースに接続できませんでした。'.$e->getMessage());
    }
}

//認証OK時の初期値セット
function loginSessionSet(){

}

function nameset() {
    return "<p>名前： " . $_SESSION["name"] . "</p>";
}

//セッションチェック用関数
function sessionCheck(){
    if( !isset($_SESSION["chk_ssid"]) || ($_SESSION["chk_ssid"] != session_id()) ){
        echo "LOGIN ERROR";
        exit();
    } else {
        session_regenerate_id(true);
        $_SESSION["chk_ssid"] = session_id(); //サーバ側に持たせるここらへんサーバサイドの難しいところ。
    }
}

//ログイン時のセッションへの情報セット
function loginRollSet() {
    if ($_SESSION["admin_flg"] == 1) {
        $username = $_SESSION['username'] . "（管理者）";
    } else if ($_SESSION["admin_flg"] == 0) {
        $username = $_SESSION['username'] . "（一般）";
    }
    return $username;
}

//管理者権限以外はExit
function adminCheck() {
    if (!$_SESSION["admin_flg"] == 1) {
        exit('権限エラー');
    }
}

//ログインメニューの作成
function loginMenuSet() {
    if ($_SESSION["admin_flg"] == 1) {
        //管理者権限の時
        $loginMenuHTML = '<div class="button" id="loginUsersButton"><a href="./users.php">ユーザー管理</a></div>';
        $loginMenuHTML .= '<div class="button" id="loginButton"><a href="./logout.php">Logout</a></div>';
    } else if ($_SESSION["admin_flg"] == 0) {
        //一般権限の時
        $loginMenuHTML = '<div class="button" id="loginButton"><a href="./logout.php">Logout</a></div>';
    }
    return $loginMenuHTML;
}


//HTML XSS対策
function htmlEnc($value) {
    return htmlspecialchars($value,ENT_QUOTES);
}
?>
