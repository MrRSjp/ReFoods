<?php require('db/dbconnect-r.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/top.css">
    <title>ReFoods.</title>
</head>
<header>
    <p class="logo logosize">ReFoods.</p>
    <form action="#">
        <input type="text">
        <input type="submit" value="検索">
    </form>
    <select name="post-order" id="post-order-id">
        <option value="new" selected>新しい順</option>
        <option value="old">古い順</option>
        <option value="lowprice">価格が安い順</option>
        <option value="highprice">価格が高い順</option>
        <option value="near">近いところ順</option>
        <option value="distant">遠いところ順</option>
    </select>
    <?php 
    $sid = $dbr->query('SELECT EXISTS(SELECT * FROM sessions WHERE session_id="' . $_COOKIE["sid"] . '")');
    $sid->execute();
    $sida = $sid->fetch();

    if((int) $sida == 1): ?>
    <button>アカウント管理</button>
    <?php else: ?>
    <button>ログイン</button>
    <?php endif; ?>
</header>
<body>
    <!-- JavaScript無効ブラウザ対策でAjax利用見直し -->
    <?php 
    if(isset($_GET["order"])) {
        if(strcmp($_GET['order'], "new") == 0) {
            $posts = $dbr->query('');
        } elseif(strcmp($_GET['order'], "old") == 0) {
            $posts = $dbr->query('');
        } elseif(strcmp($_GET['order'], "lowprice") == 0) {
            $posts = $dbr->query('');
        } elseif(strcmp($_GET['order'], "highprice") == 0) {
            $posts = $dbr->query('');
        } elseif(strcmp($_GET['order'], "near") == 0) {
            $posts = $dbr->query('');
        } elseif(strcmp($_GET['order'], "distant") == 0) {
            $posts = $dbr->query('');
        }
    } else {
        $posts = $dbr->query('');
    }

    foreach($posts as $post): ?>
    <div class="postOuter"></div>
    <?php endforeach; ?>
</body>
</html>