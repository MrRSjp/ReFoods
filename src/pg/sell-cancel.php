<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>売却取消処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck_back() == 1) {
        $buser_id = get_userid_back();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;
            $postdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . $_POST['post_id']);
            $postdb -> execute();
            $postdata = $postdb -> fetch();
            if(strcmp($postdata['poster_id'], $user_id) == 0) {
                $db = $dbw->prepare('UPDATE posts SET purchaser_id=NULL, buy_date=NULL, is_purchased=0 WHERE id=' . $_POST['post_id']);
                if ($db->execute()) {
                    $selluserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['poster_id']);
                    $selluserdb -> execute();
                    $selluserdata = $selluserdb -> fetch();

                    $buyuserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['purchaser_id']);
                    $buyuserdb -> execute();
                    $buyuserdata = $buyuserdb -> fetch();

                    //メール送信
                    $to = $buyuserdata['email'];
                    $subject = "【Refoods】出品者様都合による購入取消のお知らせ";
                    $message = "Refoodsをご利用いただき、誠にありがとうございます。\n\n
                    先日購入された下記商品につきまして、出品者様により売却取消処理がされた為、購入が取消された事をお知らせします。本件につきましてご質問等ございましたら、当事者間でメールにてご連絡いただきますようよろしくお願いします。\n\n
                    ------\n
                    -商品情報-\n
                    商品名：" . $postdata['name'] . "\n
                    価格：" . $postdata['price'] . "\n
                    量：" . $postdata['amount'] . "\n
                    場所：" . $postdata['location'] . "\n
                    購入日：" . $postdata['buy_date'] . "\n
                    ------\n
                    -出品者様情報-\n
                    名前：" . $selluserdata['name'] . "\n
                    メールアドレス：" . $selluserdata['email'] . "\n
                    ------\n\n
                    ※このメールは自動送信です。\n
                    ※このメールアドレスは送信専用です。このメールに返信されても、Refoods側からは返信できません。予めご了承ください。";
                    $headers = "From: no-reply@refoods.webpg.jp";
                    mb_send_mail($to, $subject, $message, $headers);

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