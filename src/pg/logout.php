<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck_back() == 1) {
        if (isset($_COOKIE["sid"])) {
            $db = $dbw->prepare('DELETE FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '"');
            if ($db->execute()) {
                setcookie('sid', '', time() - 3600, "/");
                // データ保存成功後に画面遷移
                header('Location: ../index.php');
                exit;
            } else { ?>
                 <p>データ保存に失敗しました</p>
            <?php }
        }
    } ?>
</body>
</html>