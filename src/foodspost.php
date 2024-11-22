<?php
require('db/dbconnect-w.php'); // DB接続ファイル
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータを取得
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $price = $_POST['price'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';
    $email = $_POST['email'] ?? '';
    $post_date = date('Y-m-d');

    // データベースに保存
    if ($name && $location && $amount && $place && $expiration_date && $email) { // 必須フィールドを確認
        $stmt = $db->prepare('INSERT INTO posts (name, location, amount, place, expiration_date, email) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('ssssss', $name, $location, $amount, $place, $expiration, $email); // 型指定 s=文字列

        if ($stmt->execute()) {
            // データ保存成功後に画面遷移
            header('Location: index.php');
            exit;
        } else {
            echo 'データ保存に失敗しました: ' . $stmt->error;
        }
    } else {
        echo '全てのフィールドを入力してください。';
    }
    //アップロード先ディレクトリ
    //$uploadDir = 'postimg/';

    //アップロードされたファイル情報
    //$file = $_FILES['file'];

}
?>