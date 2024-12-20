<?php
try {
    $dbw = new PDO('mysql:dbname=refoods;host=127.0.0.1;charset=utf8', 'rfwrite', '7aZKFU6Bekr4');
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}
?>