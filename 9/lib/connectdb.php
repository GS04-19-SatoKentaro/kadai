<?php
//DB接続
function connectdb(){
    try {
        return new PDO('mysql:dbname=an;charset=utf8;host=localhost','root','');
    } catch (PDOException $e) {
        exit('DbConnectError:'.$e->getMessage());
    }
}
