<?php
function logincheck() {
    if (isset($_COOKIE["sid"])) {
        require('db/dbconnect-r.php');
        $sid = $dbr->query('SELECT EXISTS(SELECT * FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '")');
        $sid->execute();
        $sida = $sid->fetch();
    } else {
        // $sida = 0;
        //テスト用に一定にログイン状態を返すよう記述しました。
        $sida = 1;
    }
    return $sida;
}

function get_userid() {
    if (isset($_COOKIE["sid"])) {
        require('db/dbconnect-r.php');
        $sid = $dbr->query('SELECT * FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '"');
        $sid->execute();
        $sida = $sid->fetch();
        return $sida["user_id"];
    } else {
        // $sida = "e";
        //テスト用に一定に特定ユーザIDを返すよう記述しました。
        $sida = 2;
        return $sida;
    }
}

function sql_escape($txt) {
    $search = array('%', '_', '\\', "'", '"', ';');
    $replace = array('\%', '\_', '\\\\', "\'", '\"', '\;');
    $word = str_replace($search, $replace, $txt);
    return $word;
}
?>