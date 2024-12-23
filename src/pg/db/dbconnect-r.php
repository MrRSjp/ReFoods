<?php
try {
    $dbr = new PDO('mysql:dbname=refoods;host=127.0.0.1;charset=utf8', 'rfread', 'qPjKswxxh2V3');
} catch (PDOException $e) {
    echo 'DB接続エラー：' . $e->getMessage();
}
?>