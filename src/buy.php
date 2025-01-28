<?php require('db/dbconnect-r.php'); ?>
<?php require('pg/modules.php'); ?>
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
    <link rel="stylesheet" href="css/buy.css">
    <title>購入済みの商品 | ReFoods.</title>
</head>
<header>
    <a class="onlySumaho" href="account.php"><img src="icon/chevron-left.svg" width="25vw" height="25vw" alt="アカウントページへ戻る"></a>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <div class="onlyPC">
        <form method="get" action="buy.php" class="orderby-form">
            <select name="orderby" id="post-orderby">
                <option value="" select>表示順</option>
                <option value="new">新しい順</option>
                <option value="old">古い順</option>
            </select>
            <input type="submit" value="更新" class="orderby-update">
            <noscript>
                <input type="submit" value="更新" class="orderby-update">
            </noscript>
        </form>
        <?php 
        $logincheck = logincheck();
        if((int) $logincheck == 1): ?>
            <a href="account.php"><button class="black-button">アカウント管理</button></a>
        <?php else: ?>
            <a href="Authentication.html"><button class="black-button">ログイン</button></a>
        <?php endif; ?>
    </div>
</header>
<body>
    <div class="page-title">
        <h1>購入済みの商品</h1>
    </div>
    <form class="shOrderby onlySumaho" method="get" action="buy.php" class="orderby-form">
        <select name="orderby" id="post-orderby">
            <option value="" select>表示順</option>
            <option value="new">新しい順</option>
            <option value="old">古い順</option>
        </select>
        <input type="submit" value="更新" class="orderby-update">
        <noscript>
            <input type="submit" value="更新" class="orderby-update">
        </noscript>
    </form>
    <div class="contents">
        <!-- JavaScript無効ブラウザ対策でAjax利用見直し -->
        <?php 
        if ((int) $logincheck == 1) {
            $max_post = 10;

            if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
                $page = $_REQUEST['page'];
            } else {
                $page = 1;
            }
            $start = $max_post * ($page - 1);

            $buser_id = get_userid();
            if(strcmp($buser_id, "e") != 0 ) {
                $user_id = $buser_id;

                if(isset($_GET["orderby"])) {
                    if(strcmp($_GET['orderby'], "new") == 0 || strcmp($_GET['orderby'], "") == 0) {
                        $posts = $dbr->query('SELECT * FROM posts WHERE purchaser_id=' . $user_id . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM posts WHERE is_purchased=0 ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                    } elseif(strcmp($_GET['orderby'], "old") == 0) {
                        $posts = $dbr->query('SELECT * FROM posts WHERE purchaser_id=' . $user_id . ' ORDER BY post_date ASC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM posts WHERE is_purchased=0 ORDER BY post_date ASC LIMIT ' . $start . ',' . $max_post);
                    }
                } else {
                    $posts = $dbr->query('SELECT * FROM posts WHERE purchaser_id=' . $user_id . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                    // $posts = $dbr->query('SELECT * FROM posts WHERE is_purchased=0 ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                }

                foreach($posts as $post): ?>
                    <a href="item.php?back=buy&id=<?php echo htmlspecialchars($post['id'], ENT_QUOTES); ?>"><div class="post">
                        <div class="data-box">
                            <div class="postimg-box">
                                <img class="postimg" src="postimg/<?php echo htmlspecialchars($post['img_url'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>" />
                            </div>
                            <div class="postdata-box">
                                <p class="post-title"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?></p>
                                <p class="pb-date onlySumaho">購入日：<?php echo htmlspecialchars($post['buy_date'], ENT_QUOTES); ?></p>
                                <div><form class="onlySumaho" method="post" action="pg/buy-cancel.php"><input type="hidden" name="post_id" value="<?php echo $post['id'] ?>"><input type="submit" value="購入取消" class="black-button"></form></div>
                                <table class="postdata-table onlyPC">
                                    <tbody>
                                        <tr>
                                            <th>場所</th>
                                            <td><?php echo htmlspecialchars($post['location'], ENT_QUOTES); ?></td>
                                            <th rowspan="2" style="vertical-align:bottom;">メールアドレス</th>
                                        </tr>
                                        <tr>
                                            <th>量</th>
                                            <td><?php echo htmlspecialchars($post['amount'], ENT_QUOTES); ?>kg</td>
                                        </tr>
                                        <tr>
                                            <th>値段</th>
                                            <td><?php echo htmlspecialchars($post['price'], ENT_QUOTES); ?>円</td>
                                            <td rowspan="2" style="vertical-align:top;"><?php echo htmlspecialchars($post['email'], ENT_QUOTES); ?></td>
                                        </tr>
                                        <tr>
                                            <th>消費期限</th>
                                            <td><?php echo htmlspecialchars($post['expiration_date'], ENT_QUOTES); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="buy-box onlyPC">
                        <form method="post" action="pg/buy-cancel.php"><input type="hidden" name="post_id" value="<?php echo $post['id'] ?>"><input type="submit" value="購入取消" class="black-button"></form>
                        </div>
                    </div></a>
                <?php endforeach; ?>
            <?php } else { ?>
                <div class="login-urge-box">
                    <p class="login-urge">ログインしてください</p>
                    <a href="Authentication.html"><button class="black-button">ログイン / 新規会員登録</button></a>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="login-urge-box">
                <p class="login-urge">ログインしてください</p>
                <a href="Authentication.html"><button class="black-button">ログイン / 新規会員登録</button></a>
            </div>
        <?php } ?>
    </div>
</body>
<footer>
    <?php if ($logincheck == 1): ?>
        <?php if(strcmp($buser_id, "e") != 0 ): ?>
            <div>
                <?php if ($page >= 2): ?>
                    <a class="pagination-button" href="buy.php?page=<?php print($page-1); ?>">前へ</a>
                <?php endif; ?>
                <a><?php echo $page ?>ページ目</a>
                <?php
                $counts = $dbr->query('SELECT COUNT(*) AS cnt FROM posts WHERE purchaser_id=' . $user_id);
                $count = $counts->fetch();
                $max_page = ceil($count['cnt'] / $max_post);
                if ($page < $max_page): ?>
                    <a class="pagination-button" href="buy.php?page=<?php print($page+1); ?>">次へ</a>
                <?php endif; ?>
            </div>
        <?php endif;?>
    <?php endif;?>
</footer>
</html>