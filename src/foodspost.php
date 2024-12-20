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
    $expiration_time = $_POST['expiration_time'] ?? '';
    $email = $_POST['email'] ?? '';
    $datetime = new DateTime('now', new DateTimeZone('Asia/Tokyo'));
    $post_date = $datetime->format('Y-m-d H:i:s');
  
    // データベースに保存
     if(!empty($name) && !empty($location) && !empty($amount) && !empty($price) &&!empty($expiration_date) && !empty($expiration_time) && !empty($email)) {// 必須フィールドを確認
        
        $expiration_datetime = $expiration_date . ' ' . $expiration_time;

        $stmt = $dbw->prepare('INSERT INTO posts (name, location, amount, price, expiration_date, email, post_date) VALUES ("'.$name.'","'.$location.'",'.$amount.','.$price.',"'.$expiration_datetime.'","'.$email.'","'.$post_date.'")' );
       echo 'INSERT INTO posts (name, location, amount, price, expiration_date, email, url) VALUES ("'.$name.'","'.$location.'",'.$amount.','.$price.',"'.$expiration_datetime.'","'.$email.'","'.$url.'")'; 

        if ($stmt->execute()) {
            $id = $dbw->lastInsertId();
            $fileType = explode('/',$_FILES['file']['type']);
            $fileName = 'post' .$id . '.' . $fileType[1];
            $imgdb = $dbw->prepare('UPDATE  posts SET img_url = "' .$fileName. '" WHERE id = ' . $id);

            if($imgdb->execute()){
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                        $uploadDir = 'postimg/';
                        $fileType = explode('/',$_FILES['file']['type']);
                        $fileName = 'post' .$id . '.' . $fileType[1];
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

                 // データ保存成功後に画面遷移
            header('Location: ./index.php');
            exit;
            }else{
                echo 'データ保存に失敗しました: ' ;   
            }
        } else {
             echo 'データ保存に失敗しました: ' ; 
        }
    } else {
        echo '全てのフィールドを入力してください。';
    }
    
}

