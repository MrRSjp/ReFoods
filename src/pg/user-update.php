<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント情報更新処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck() == 1) {
        $buser_id = get_userid();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;

            if(!empty($_FILES['userimg']['name'])){
                $uploadDir = '../userimg/';
                $fileType = explode("/", $_FILES['userimg']['type']);
                $fileName = "user" . $user_id . "." . $fileType[1];
                $uploadFile = $uploadDir . $fileName;
                move_uploaded_file($_FILES['userimg']['tmp_name'], $uploadFile);
                $db = $dbw->prepare('UPDATE users SET user_img="' . $fileName . '" WHERE id=' . $user_id);
                $imgtf = $db -> execute();
            }
            
            if(!empty($_POST['username'])){
                $db = $dbw->prepare('UPDATE users SET name="' . $_POST['username'] . '" WHERE id=' . $user_id);
                $nametf = $db -> execute();
            }
            
            if ($imgtf && $nametf) {
                // データ保存成功後に画面遷移
                header('Location: ../account.php');
                exit;
            } elseif (!$imgtf && $nametf) {
                // データ保存成功後に画面遷移
                header('Location: ../account.php');
                exit;
            } elseif ($imgtf && !$nametf) {
                // データ保存成功後に画面遷移
                header('Location: ../account.php');
                exit;
            } else { ?>
                    <p>データ保存に失敗しました</p>
            <?php }
        }
    } ?>
</body>
</html>