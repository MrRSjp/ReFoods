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
    <link rel="stylesheet" href="css/imgviewer.css">
    <?php if(empty($postdata)): ?>
        <title>画像が見つかりませんでした | ReFoods.</title>
    <?php else: ?>
        <title>画像ビューアー | ReFoods.</title>
    <?php endif; ?>
</head>
<header>
    <a class="arrow-left" href="index.php"><img src="icon/chevron-left.svg" width="25vw" height="25vw"></a>
    <a href="index.php"><h1>画像ビューアー</h1></a>
</header>
<body>
    <?php if(!empty($postdata)): ?>
        <img class="postimg" src="postimg/<?php echo $postdata['img_url'] ?>" alt="<?php echo $postdata['name'] ?>">
    <?php else: ?>
        <p>画像が見つかりませんでした</p>
    <?php endif; ?>
</body>
</html>