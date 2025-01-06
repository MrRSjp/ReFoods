<?php require('db/dbconnect-r.php');
require('pg/modules.php');
if(isset($_GET['id'])) {
    if(is_numeric($_GET['id'])) {
        $postdb = $dbr->prepare('SELECT * FROM posts WHERE id=' . sql_escape($_GET['id']));
        $postdb -> execute();
        $postdata = $postdb -> fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="ReFoods" />
    <link rel="manifest" href="/favicon/manifest.json" />
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
        navigator.serviceWorker.register('pg/sw.js').then(registration => {
        console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, err => {
        console.log('ServiceWorker registration failed: ', err);
        }).catch(err => {
        console.log(err)
        });
        });
    }
    </script>
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/item.css">
    <?php if(empty($postdata)): ?>
        <title>お探しの商品が見つかりませんでした | ReFoods.</title>
    <?php else: ?>
        <title>ReFoods. - <?php echo $postdata['name'] ?></title>
    <?php endif; ?>
</head>
<header>
    <a class="onlySumaho arrow-left" href="index.php"><img src="icon/chevron-left.svg" width="25vw" height="25vw"></a>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <?php if((int) logincheck() == 1): ?>
        <a class="onlyPC" href="account.php"><button class="black-button">アカウント管理</button></a>
    <?php else: ?>
        <a class="onlyPC" href="Authentication.html"><button class="black-button">ログイン</button></a>
    <?php endif; ?>
</header>
<body>
    <div class="contents">
        <?php if(!empty($postdata)): ?>
            <div class="img-box">
                <img class="postimg" src="postimg/<?php echo $postdata['img_url'] ?>" alt="<?php echo $postdata['name'] ?>">
                <a class="enlarged-image" href="imgviewer.php?postid=<?php echo $postdata['id'] ?>">画像を拡大して見る</a>
            </div>
            <div class="data-box">
                <h2 class="post-title"><?php echo $postdata['name'] ?></h2>
                <p class="post-data">量：<?php echo $postdata['amount'] ?>kg</p>
                <p class="post-data">価格：<?php echo $postdata['price'] ?>円</p>
                <p class="post-data">消費期限：<?php echo $postdata['expiration_date'] ?></p>
                <p class="post-data">場所：<?php echo $postdata['location'] ?></p>
                <p class="post-data">メールアドレス：<?php echo $postdata['email'] ?></p>
                <form method="post" action="pg/buy-processing.php"><input type="hidden" name="post_id" value="<?php echo $postdata['id'] ?>"><input type="submit" value="購入" class="buy-button"></form>
            </div>
        <?php else: ?>
            <p>お探しの商品が見つかりませんでした</p>
        <?php endif; ?>
    </div>
</body>
</html>