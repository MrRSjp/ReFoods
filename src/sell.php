<?php require('db/dbconnect-r.php'); ?>
<?php require('pg/modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/sell.css">
    <title>出品した商品 | ReFoods.</title>
</head>
<header>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <div>
        <form method="get" action="sell.php" class="orderby-form">
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
        <h1>出品した商品</h1>
    </div>
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
                        $posts = $dbr->query('SELECT * FROM posts WHERE poster_id=' . $user_id . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM posts ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                    } elseif(strcmp($_GET['orderby'], "old") == 0) {
                        $posts = $dbr->query('SELECT * FROM posts WHERE poster_id=' . $user_id . ' ORDER BY post_date ASC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM posts ORDER BY post_date ASC LIMIT ' . $start . ',' . $max_post);
                    }
                } else {
                    $posts = $dbr->query('SELECT * FROM posts WHERE poster_id=' . $user_id . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                    // $posts = $dbr->query('SELECT * FROM posts ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
                }

                foreach($posts as $post): ?>
                    <div class="post">
                        <div class="data-box">
                            <div class="postimg-box">
                                <img class="postimg" src="postimg/<?php echo htmlspecialchars($post['img_url'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>" />
                            </div>
                            <div class="postdata-box">
                                <p class="post-title"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?></p>
                                <p class="post-text">投稿日： <?php echo htmlspecialchars($post['post_date'], ENT_QUOTES); ?></p>
                                <p class="post-text">値段：<?php echo htmlspecialchars($post['price'], ENT_QUOTES); ?>円・量：<?php echo htmlspecialchars($post['amount'], ENT_QUOTES); ?>kg・消費期限：<?php echo htmlspecialchars($post['expiration_date'], ENT_QUOTES); ?></p>
                                <?php if((int) $post['is_purchased'] == 1):?>
                                    <?php 
                                    $datadate = new DateTime(substr($post['post_date'], 0, 10));
                                    $nowdate = new DateTime();
                                    $date = date_diff($datadate, $nowdate);

                                    if((int) $date->days <= 7):?>
                                        <!-- <p class="editing-button"><a>売却取消</a></p> -->
                                    <?php endif;?>
                                    <form method="post" action="pg/sell-cancel.php" class="postform"><input type="hidden" name="post_id" value="<?php echo $post['id'] ?>"><input type="submit" value="売却取消" class="editing-button"></form>
                                    <p class="grayout-button"><a>編集</a></p>
                                    <p class="grayout-button"><a>削除</a></p>
                                    <p class="grayout-text">既に購入済みの為、編集や削除はできません。</p>
                                <?php else:?>
                                    <p class="editing-button"><a>編集</a></p>
                                    <form method="post" action="pg/post-delete.php" class="postform"><input type="hidden" name="post_id" value="<?php echo $post['id'] ?>"><input type="submit" value="削除" class="editing-button"></form>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
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
                    <a href="sell.php?page=<?php print($page-1); ?>">前へ</a>
                <?php endif; ?>
                <a><?php echo $page ?>ページ目</a>
                <?php
                $counts = $dbr->query('SELECT COUNT(*) AS cnt FROM posts WHERE poster_id=' . $user_id);
                $count = $counts->fetch();
                $max_page = ceil($count['cnt'] / $max_post);
                if ($page < $max_page): ?>
                    <a href="sell.php?page=<?php print($page+1); ?>">次へ</a>
                <?php endif; ?>
            </div>
        <?php endif;?>
    <?php endif;?>
</footer>
</html>