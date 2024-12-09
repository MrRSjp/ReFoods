<?php
require('db/dbconnect-w.php'); // DB接続ファイル
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POSTデータを取得
    $url = $_POST['url'] ?? '';
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $price = $_POST['price'] ?? '';
    $expiration_date = $_POST['expiration_date'] ?? '';
    $email = $_POST['email'] ?? '';
    $post_date = date( 'YYYY-MM-DD HH:MM:SS');


    // データベースに保存
    // if(!empty($name) && !empty($location) && !empty($amount) && !empty($price) &&!empty($expiration_date) && !empty($email) && !empty($url)) {// 必須フィールドを確認

    if(!empty($name)){
        $stmt = $dbw->prepare('INSERT INTO posts (name, location, amount, price, expiration_date, email, img_url) VALUES ("'.$name.'","'.$location.'",'.$amount.','.$price.',"'.$expiration_date.'","'.$email.'","'.$url.'")' );
       echo 'INSERT INTO posts (name, location, amount, price, expiration_date, email, url) VALUES ("'.$name.'","'.$location.'",'.$amount.','.$price.',"'.$expiration_date.'","'.$email.'","'.$url.'")'; 

        if ($stmt->execute()) {
            // データ保存成功後に画面遷移
            header('Location: ./index.php');
            exit;
        } else {
             echo 'データ保存に失敗しました: ' ; $stmt->error;
        }
    } else {
        echo '全てのフィールドを入力してください。';

    }

    
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'postimg/';
            $fileName = basename($_FILES['file']['name']);
            $uploadFile = $uploadDir . $fileName;
    
            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
                echo "ファイルがアップロードされました: $uploadFile";
            } else {
                echo "ファイルのアップロードに失敗しました。";
            }
        } else {
            echo "ファイルが選択されていないか、エラーが発生しました。";
        }
    }
}
    ?>

