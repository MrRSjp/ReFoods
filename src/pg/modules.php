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

/* getIp()関数に関しては下記サイトから拝借しました。ありがとうございます！
URL: https://ja.php.brj.cz/php-deyuzano-ip-adoresuwo-qu-desuru*/

function getIp(): string {
    if (isset($_SERVER['http_cf_connecting_ip'])) { // Cloudflare対応
        $ip = $_SERVER['http_cf_connecting_ip'];
    } elseif (isset($_SERVER['REMOTE_ADDR']) === true) {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (preg_match('/^(?:127|10)\.0\.0\.[12]?\d{1,2}$/', $ip)) {
            if (isset($_SERVER['HTTP_X_REAL_IP'])) {
                $ip = $_SERVER['HTTP_X_REAL_IP'];
            } elseif (isset($_SERVER['http_x_forwarded_for'])) {
                $ip = $_SERVER['http_x_forwarded_for'];
            }
        }
    } else {
        $ip = '127.0.0.1';
    }
    if (in_array($ip, ['::1', '0.0.0.0', 'ローカルホスト'], true)) {
        $ip = '127.0.0.1';
    }
    $filter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    if ($filter === false) {
        $ip = '127.0.0.1';
    }

    return $ip;
}
?>