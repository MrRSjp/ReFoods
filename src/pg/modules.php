<?php
function logincheck() {
    if (isset($_COOKIE["sid"])) {
        require('db/dbconnect-r.php');
        $sid = $dbr->query('SELECT COUNT(id) FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '" LIMIT 1');
        $sid->execute();
        $sida = $sid->fetchColumn();
        if(isset($sida)) {
            if(is_null($sida) || strcmp($sida, "") == 0) {
                $sida = 0;
            } else {
                if((int) $sida == 0){
                    setcookie('sid', '', time() - 3600, "/");
                }
            }
        } else {
            $sida = 0;
        }
    } else {
        $sida = 0;
    }
    return $sida;
}

function get_userid() {
    if (isset($_COOKIE["sid"])) {
        require('db/dbconnect-r.php');
        $sid = $dbr->query('SELECT * FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '"');
        $sid->execute();
        $sida = $sid->fetch();
        if(isset($sida)) {
            if(is_null($sida["user_id"]) || strcmp($sida["user_id"], "") == 0){
                return "e";
            } else {
                return $sida["user_id"];
            }
        } else {
            return "e";
        }
    } else {
        $sida = "e";
        return $sida;
    }
}

function logincheck_back() {
    if (isset($_COOKIE["sid"])) {
        require('../db/dbconnect-r.php');
        $sid = $dbr->query('SELECT COUNT(id) FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '" LIMIT 1');
        $sid->execute();
        $sida = $sid->fetchColumn();
        if(isset($sida)) {
            if(is_null($sida) || strcmp($sida, "") == 0) {
                $sida = 0;
            } else {
                if((int) $sida == 0){
                    setcookie('sid', '', time() - 3600, "/");
                }
            }
        } else {
            $sida = 0;
        }
    } else {
        $sida = 0;
    }
    return $sida;
}

function get_userid_back() {
    if (isset($_COOKIE["sid"])) {
        require('../db/dbconnect-r.php');
        $sid = $dbr->query('SELECT * FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '"');
        $sid->execute();
        $sida = $sid->fetch();
        if(isset($sida)) {
            if(is_null($sida["user_id"]) || strcmp($sida["user_id"], "") == 0){
                return "e";
            } else {
                return $sida["user_id"];
            }
        } else {
            return "e";
        }
    } else {
        $sida = "e";
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
URL: https://ja.php.brj.cz/php-deyuzano-ip-adoresuwo-qu-desuru */

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
    if (in_array($ip, ['::1', '0.0.0.0', 'ローカルホスト', 'localhost'], true)) {
        $ip = '127.0.0.1';
    }
    $filter = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    if ($filter === false) {
        $ip = '127.0.0.1';
    }

    return $ip;
}
?>