<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入処理中… | ReFoods.</title>
</head>
<body>
    <?php 
    if ((int) logincheck() == 1) {
        $buser_id = get_userid();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;
            date_default_timezone_set('Asia/Tokyo');
            $nowdate = date("Y-m-d H:i:s");
            $db = $dbw->prepare('UPDATE posts SET purchaser_id=' . $user_id . ', buy_date="' . $nowdate . '", is_purchased=1 WHERE id=' . $_POST['post_id']);
            if ($db->execute()) {
                $postdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . $_POST['post_id']);
                $postdb -> execute();
                $postdata = $postdb -> fetch();

                $selluserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['poster_id']);
                $selluserdb -> execute();
                $selluserdata = $selluserdb -> fetch();

                $buyuserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $user_id);
                $buyuserdb -> execute();
                $buyuserdata = $buyuserdb -> fetch();

                //メール送信
                $to = $selluserdata['email'];
                $subject = "【Refoods】「" . $postdata['name'] . "」購入成立のお知らせ";
                $message = "Refoodsをご利用いただき、誠にありがとうございます。\n\n
                " . $selluserdata['name'] . "様が出品された下記商品につきまして、購入者様により購入処理がされた為、購入が成立した事をお知らせします。今後の取引につきましては、当事者間でメールにてご連絡いただきますようよろしくお願いします。\n\n
                ------\n
                -商品情報-\n
                商品名：" . $postdata['name'] . "\n
                価格：" . $postdata['price'] . "\n
                量：" . $postdata['amount'] . "\n
                投稿日：" . $postdata['post_date'] . "\n
                ------\n
                -購入者様情報-\n
                名前：" . $buyuserdata['name'] . "\n
                メールアドレス：" . $buyuserdata['email'] . "\n
                ------\n\n
                ※このメールは自動送信です。\n
                ※このメールアドレスは送信専用です。このメールに返信されても、Refoods側からは返信できません。予めご了承ください。";
                $headers = "From: no-reply@refoods.webpg.jp";
                mb_send_mail($to, $subject, $message, $headers);

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