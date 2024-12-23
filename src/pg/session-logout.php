<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>セッションログアウト処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck() == 1) {
        $buser_id = get_userid();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;
            $sessionsdb = $dbw->prepare('SELECT * FROM sessions WHERE id=' . $_POST['id']);
            $sessionsdb -> execute();
            $sessiondata = $sessionsdb -> fetch();
            echo $user_id;
            if(strcmp($sessiondata['user_id'], $user_id) == 0) {
                $db = $dbw->prepare('DELETE FROM sessions WHERE id=' . $_POST['id']);
                if ($db->execute()) {
                    // データ保存成功後に画面遷移
                    header('Location: ../sessions.php');
                    exit;
                } else { ?>
                     <p>データ保存に失敗しました</p>
                <?php }
            }
        }
    } ?>
</body>
</html>