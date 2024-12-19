<?php 
require('../db/dbconnect-w.php');
require('modules.php');
require_once("phpip/vendor/autoload.php");
use GeoIp2\Database\Reader; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck() == 1) {
        /* 新規ユーザーか判定（新規であればユーザーデータベースに新規追加） */
        $existenceCheck = $dbw->query('SELECT EXISTS(SELECT * FROM users WHERE fa_uid="' . $_POST['fa_uid'] . '")');
        $existenceCheck->execute();
        $existenceCheckData = $existenceCheck->fetch();
        if($existenceCheckData == 0) {
            $existenceCheck = $dbw->query('INSERT INTO users (email, fa_uid, name) VALUES ("' . $_POST['email'] . '", "' . $_POST['fa_uid'] . '", "' . $_POST['name'] . '")');
            $existenceCheck->execute();
            $userid = $dbw->lastInsertId();
        } else {
            $userdb = $dbw->query('SELECT * FROM users WHERE fa_uid="' . $_POST['fa_uid'] . '"');
            $userdb->execute();
            $userdbData = $userdb->fetch();
            $userid = $userdbData['id'];
        }

        /* セッション新規追加 */
        $bsid = uniqid('rfs_');
        $sidtf = 0;
        while((int) $sidtf != 1) {
            $sid = $dbw->query('SELECT EXISTS(SELECT * FROM sessions WHERE session_id="' . $bsid . '")');
            $sid->execute();
            $sida = $sid->fetch();
            $sidtf = $sida;
        }
        $sid = $bsid;
        setcookie('sid', $sid);
        date_default_timezone_set('Asia/Tokyo');
        $nowdate = date("Y-m-d H:i:s");

        // ipアドレスから位置判定
        $reader = new Reader('phpip/db/GeoLite2-City.mmdb', array('ja'));
        $ip_address = getIp();
        if(strcmp($ip_address, "127.0.0.1") == 0 ) {
            $location = "";
        } else {
            $record = $reader->city($ip_address);
            $country_name = $record->country->name;
            $pref_name = $record->mostSpecificSubdivision->name;
            $city_name = $record->city->name;
            $location = $country_name . ", " . $pref_name . $city_name;
        }

        $ua = $_SERVER['HTTP_USER_AGENT'];

        if (strstr($ua, 'Windows')) {
            $device = 1;
        } elseif (strstr($ua, 'Macintosh')){
            $device = 2;
        } elseif (strstr($ua, 'iPhone')){
            $device = 5;
        } elseif (strstr($ua, 'iPad')){
            $device = 6;
        } elseif (strstr($ua, 'iPod')){
            $device = 7;
        } elseif (strstr($ua, 'Android')){
            $device = 4;
        } elseif (strstr($ua, 'Linux')){
            $device = 3;
        } else {
            $device = 0;
        }

        if (strstr($ua, 'edge')) {
            $browser = 2;
        } elseif (strstr($ua, 'opera')){
            $browser = 6;
        } elseif (strstr($ua, 'chrome')) {
            $browser = 3;
        } elseif (strstr($ua, 'safari')) {
            $browser = 4;
        } elseif (strstr($ua, 'trident') || strstr($ua, 'msie')) {
            $browser = 1;
        } elseif (strstr($ua, 'firefox')) {
            $browser = 5;
        } else {
            $browser = 0;
        }

        preg_match("/\(([^;]+)/", $ua,$platform_name);
        
        $db = $dbw->prepare('INSERT INTO users (session_id, user_id, login_date, device_num, browser_num, platform_name, ip_address, login_location) VALUES ("' . $sid . '", "' . $userid . '", "' . $nowdate . '", "' . $device . '", "' . $browser . '", "' . $platform_name[1] . '", "' . $ip_address . '", "' . $location . '")');
        if ($db->execute()) {
            setcookie('sid', $sid);
            // データ保存成功後に画面遷移
            header('Location: ../index.php');
            exit;
        } else { ?>
            <p>ログインに失敗しました</p>
        <?php }
    } ?>
</body>
</html>