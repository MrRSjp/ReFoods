<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿削除処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck() == 1) {
        $buser_id = get_userid();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;
            $postdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . $_POST['post_id']);
            $postdb -> execute();
            $postdata = $postdb -> fetch();
            if(strcmp($postdata['poster_id'], $user_id) == 0) {
                $db = $dbw->prepare('DELETE FROM posts WHERE id=' . $_POST['post_id']);
                if ($db->execute()) {
                    // データ保存成功後に画面遷移
                    header('Location: ../sell.php');
                    exit;
                } else { ?>
                     <p>データ保存に失敗しました</p>
                <?php }
            }
        }
    } ?>
</body>
</html>