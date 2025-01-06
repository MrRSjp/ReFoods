<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>投稿更新処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck_back() == 1) {
        $buser_id = get_userid_back();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;

            if(!empty($_FILES['file']['name'])){
                $uploadDir = '../postimg/';
                $fileType = explode("/", $_FILES['file']['type']);
                $fileName = "post" . $_POST['id'] . "." . $fileType[1];
                $uploadFile = $uploadDir . $fileName;
                move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
                $db = $dbw->prepare('UPDATE posts SET img_url="' . $fileName . '" WHERE id=' . $_POST['id']);
                $imgtf = $db -> execute();
            }
            
            if(!empty($_POST['name']) || !empty($_POST['location']) || !empty($_POST['amount']) || !empty($_POST['price']) || !empty($_POST['expiration_date']) || !empty($_POST['expiration_time']) || !empty($_POST['email'])){
                $sqltxt = "a";
                if(!empty($_POST['name'])) {
                    $sqltxt .= ", name='" . sql_escape($_POST['name']) . "'";
                }
                if(!empty($_POST['location'])) {
                    $sqltxt .= ", location='" . sql_escape($_POST['location']) . "'";
                }
                if(!empty($_POST['amount'])) {
                    $sqltxt .= ", amount=" . sql_escape($_POST['amount']);
                }
                if(!empty($_POST['price'])) {
                    $sqltxt .= ", price=" . sql_escape($_POST['price']);
                }
                if (!empty($_POST['expiration_date']) && !empty($_POST['expiration_time'])) {
                    $date = $_POST['expiration_date'] . " " . $_POST['expiration_time'];
                    $sqltxt .= ", expiration_date='" . sql_escape($date) . "'";
                }
                if(!empty($_POST['expiration_date']) && empty($_POST['expiration_time'])) {
                    $postdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . sql_escape($_POST['id']));
                    $postdb -> execute();
                    $postdata = $postdb -> fetch();
                    $ed = explode(" ", $postdata['expiration_date']);
                    $edtxt = $_POST['expiration_date'] . " " . $ed[1];
                    $sqltxt .= ", expiration_date='" . sql_escape($edtxt) . "'";
                }
                if (empty($_POST['expiration_date']) && !empty($_POST['expiration_time'])) {
                    $postdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . sql_escape($_POST['id']));
                    $postdb -> execute();
                    $postdata = $postdb -> fetch();
                    $ed = explode(" ", $postdata['expiration_date']);
                    $edtxt = $ed[0] . " " . $_POST['expiration_time'];
                    $sqltxt .= ", expiration_date='" . sql_escape($edtxt) . "'";
                }
                if(!empty($_POST['email'])) {
                    $sqltxt .= ", email='" . sql_escape($_POST['email']) . "'";
                }

                $sqlbase = "UPDATE posts SET " . substr($sqltxt, 3);
                $db = $dbw->prepare($sqlbase . ' WHERE id=' . $_POST['id']);
                $datatf = $db -> execute();
            }
            
            if ($imgtf && $datatf) {
                // データ保存成功後に画面遷移
                header('Location: ../sell.php');
                exit;
            } elseif (!$imgtf && $datatf) {
                // データ保存成功後に画面遷移
                header('Location: ../sell.php');
                exit;
            } elseif ($imgtf && !$datatf) {
                // データ保存成功後に画面遷移
                header('Location: ../sell.php');
                exit;
            } else { ?>
                    <p>データ保存に失敗しました</p>
            <?php }
        }
    } ?>
</body>
</html>