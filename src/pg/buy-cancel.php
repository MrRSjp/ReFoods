<?php 
require('../db/dbconnect-w.php');
require('modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>購入取消処理中… | ReFoods.</title>
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
            if(strcmp($postdata['purchaser_id'], $user_id) == 0) {
                if((int) $postdata['poster_id'] == 0) {
                    $db = $dbw->prepare('DELETE FROM posts WHERE id=' . $_POST['post_id']);
                    if ($db->execute()) {
                        $buyuserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['purchaser_id']);
                        $buyuserdb -> execute();
                        $buyuserdata = $buyuserdb -> fetch();

                        //メール送信
                        $to = $postdata['email'];
                        $subject = "【ReFoods】購入者様都合による、お客様の過去出品商品の購入取消のお知らせ（退会済みユーザ向け）";
                        $message = "※本メールは投稿に保存されているメールアドレス宛に送信しております。アカウント情報(メールアドレス含む)はアカウント削除時に既に削除されています。ご安心ください。\n※アカウント削除時の画面でもお伝えした通り、過去売却商品の購入者様による購入キャンセルにつきましてはアカウント削除後でも状況を通知いたします。\n\nReFoodsをご利用いただき、誠にありがとうございます。\n\nアカウント削除前に出品された下記商品につきまして、購入者様により購入取消処理がされた為、購入が取消された事をお知らせします。本件につきましてご質問等ございましたら、当事者間でメールにてご連絡いただきますようよろしくお願いします。\n\n------\n-商品情報-\n商品名：" . $postdata['name'] . "\n価格：" . $postdata['price'] . "\n量：" . $postdata['amount'] . "\n投稿日：" . $postdata['post_date'] . "\n------\n-購入者様情報-\n名前：" . $buyuserdata['name'] . "\nメールアドレス：" . $buyuserdata['email'] . "\n------\n\n※このメールは自動送信です。\n※このメールアドレスは送信専用です。このメールに返信されても、ReFoods側からは返信できません。予めご了承ください。";
                        $headers = "From: ReFoods.<no-reply@refoods.webpg.jp>";
                        mb_send_mail($to, $subject, $message, $headers);

                        // データ保存成功後に画面遷移
                        header('Location: ../buy.php');
                        exit;
                    } else { ?>
                        <p>データ保存に失敗しました</p>
                    <?php }
                } else {
                    $db = $dbw->prepare('UPDATE posts SET purchaser_id=NULL, buy_date=NULL, is_purchased=0 WHERE id=' . $_POST['post_id']);
                    if ($db->execute()) {
                        $selluserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['poster_id']);
                        $selluserdb -> execute();
                        $selluserdata = $selluserdb -> fetch();

                        $buyuserdb = $dbw->prepare('SELECT * FROM users WHERE id=' . $postdata['purchaser_id']);
                        $buyuserdb -> execute();
                        $buyuserdata = $buyuserdb -> fetch();

                        //メール送信
                        $to = $selluserdata['email'];
                        $subject = "【ReFoods】購入者様都合による、" . $selluserdata['name'] . "様出品商品の購入取消のお知らせ";
                        $message = "ReFoodsをご利用いただき、誠にありがとうございます。\n\n" . $selluserdata['name'] . "様が出品された下記商品につきまして、購入者様により購入取消処理がされた為、購入が取消された事をお知らせします。本件につきましてご質問等ございましたら、当事者間でメールにてご連絡いただきますようよろしくお願いします。\n\n------\n-商品情報-\n商品名：" . $postdata['name'] . "\n価格：" . $postdata['price'] . "\n量：" . $postdata['amount'] . "\n投稿日：" . $postdata['post_date'] . "\n------\n-購入者様情報-\n名前：" . $buyuserdata['name'] . "\nメールアドレス：" . $buyuserdata['email'] . "\n------\n\n※このメールは自動送信です。\n※このメールアドレスは送信専用です。このメールに返信されても、ReFoods側からは返信できません。予めご了承ください。";
                        $headers = "From: ReFoods.<no-reply@refoods.webpg.jp>";
                        mb_send_mail($to, $subject, $message, $headers);

                        // データ保存成功後に画面遷移
                        header('Location: ../buy.php');
                        exit;
                    } else { ?>
                        <p>データ保存に失敗しました</p>
                    <?php }
                }
            }
        }
    } ?>
</body>
</html>