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
    if ((int) logincheck_back() == 1) {
        $buser_id = get_userid_back();
        if(strcmp($buser_id, "e") != 0 ) {
            $user_id = $buser_id;
            $pcdb = $dbw->prepare('SELECT * FROM posts WHERE id=' . $_POST['post_id']);
            $pcdb -> execute();
            $pcdata = $pcdb -> fetch();
            if(strcmp($pcdata['is_purchased'], "0") == 0) {
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
                    $subject = "【ReFoods】「" . $postdata['name'] . "」購入成立のお知らせ";
                    $message = "ReFoodsをご利用いただき、誠にありがとうございます。\n\n" . $selluserdata['name'] . "様が出品された下記商品につきまして、購入者様により購入処理がされた為、購入が成立した事をお知らせします。今後の取引につきましては、当事者間でメールにてご連絡いただきますようよろしくお願いします。\n\n------\n-商品情報-\n商品名：" . $postdata['name'] . "\n価格：" . $postdata['price'] . "\n量：" . $postdata['amount'] . "\n投稿日：" . $postdata['post_date'] . "\n------\n-購入者様情報-\n名前：" . $buyuserdata['name'] . "\nメールアドレス：" . $buyuserdata['email'] . "\n------\n\n※このメールは自動送信です。\n※このメールアドレスは送信専用です。このメールに返信されても、ReFoods側からは返信できません。予めご了承ください。";
                    $headers = "From: ReFoods.<no-reply@refoods.webpg.jp>";
                    mb_send_mail($to, $subject, $message, $headers);

                    // データ保存成功後に画面遷移
                    header('Location: ../index.php');
                    exit;
                } else { ?>
                        <p>データ保存に失敗しました</p>
                <?php }
            }
        }
    } ?>
</body>
</html>