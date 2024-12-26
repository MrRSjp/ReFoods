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
    <link rel="stylesheet" href="css/sessions.css">
    <title>有効なログイン済端末の管理 | ReFoods.</title>
</head>
<header>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <div>
        <form method="get" action="sessions.php" class="orderby-form">
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
        <h1>有効なログイン済端末の管理</h1>
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
                        $posts = $dbr->query('SELECT * FROM sessions WHERE user_id=' . $user_id . ' ORDER BY login_date DESC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM sessions ORDER BY login_date DESC LIMIT ' . $start . ',' . $max_post);
                    } elseif(strcmp($_GET['orderby'], "old") == 0) {
                        $posts = $dbr->query('SELECT * FROM sessions WHERE user_id=' . $user_id . ' ORDER BY login_date ASC LIMIT ' . $start . ',' . $max_post);
                        // $posts = $dbr->query('SELECT * FROM sessions ORDER BY login_date ASC LIMIT ' . $start . ',' . $max_post);
                    }
                } else {
                    $posts = $dbr->query('SELECT * FROM sessions WHERE user_id=' . $user_id . ' ORDER BY login_date DESC LIMIT ' . $start . ',' . $max_post);
                    // $posts = $dbr->query('SELECT * FROM sessions ORDER BY login_date DESC LIMIT ' . $start . ',' . $max_post);
                }

                foreach($posts as $post): ?>
                    <?php 
                    $osname = "端末名不明";
                    $browsername = "不明";
                    if((int) $post['device_num'] == 0) {
                        if(isset($post['platform_name'])) {
                            $osname = $post['platform_name'];
                        } else {
                            $osname = "端末名不明";
                        }
                    } elseif ((int) $post['device_num'] == 1) {
                        $osname = "Windows";
                    } elseif ((int) $post['device_num'] == 2) {
                        $osname = "MacOS";
                    } elseif ((int) $post['device_num'] == 3) {
                        $osname = "Linux";
                    } elseif ((int) $post['device_num'] == 4) {
                        $osname = "Android";
                    } elseif ((int) $post['device_num'] == 5) {
                        $osname = "iPhone";
                    } elseif ((int) $post['device_num'] == 6) {
                        $osname = "iPad";
                    } elseif ((int) $post['device_num'] == 7) {
                        $osname = "iPod";
                    }

                    if((int) $post['browser_num'] == 0) {
                        $browsername = "不明";
                    } elseif ((int) $post['browser_num'] == 1) {
                        $browsername = "Internet Explorer";
                    } elseif ((int) $post['browser_num'] == 2) {
                        $browsername = "Edge";
                    } elseif ((int) $post['browser_num'] == 3) {
                        $browsername = "Chrome";
                    } elseif ((int) $post['browser_num'] == 4) {
                        $browsername = "Safari";
                    } elseif ((int) $post['browser_num'] == 5) {
                        $browsername = "Firefox";
                    } elseif ((int) $post['browser_num'] == 6) {
                        $browsername = "Opera";
                    }
                    if(strcmp($post['session_id'], $_COOKIE["sid"]) == 0): ?>
                        <div class="post">
                            <div class="data-box">
                                <div class="postimg-box">
                                    <img class="postimg" src="icon/session/device<?php echo htmlspecialchars($post['device_num'], ENT_QUOTES); ?>.png" alt="<?php echo $osname; ?>" />
                                </div>
                                <div class="postdata-box">
                                    <p class="post-title"><?php echo $osname ?></p>
                                    <p class="post-text">ログイン場所：<?php echo htmlspecialchars($post['login_location'], ENT_QUOTES); ?></p>
                                    <p class="post-text">ログイン時間：<?php echo htmlspecialchars($post['login_date'], ENT_QUOTES); ?></p>
                                    <?php if((int) $post['browser_num'] == 0): ?>
                                        <p class="browser-name"><p class="browser-name-item">ブラウザ名：</p><p class="browser-name-item"><?php echo $browsername; ?></p></p>
                                    <?php else: ?>
                                        <p class="browser-name"><p class="browser-name-item">ブラウザ名：</p><img class="browser-name-item" src="icon/session/browser<?php echo htmlspecialchars($post['browser_num'], ENT_QUOTES); ?>.png" alt="<?php echo $browsername; ?>" width="20vw" height="20vw" /><p class="browser-name-item"><?php echo $browsername; ?></p></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="buy-box">
                                <div class="blackout-button">現在のセッション</div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="post">
                            <div class="data-box">
                                <div class="postimg-box">
                                    <img class="postimg" src="icon/session/device<?php echo htmlspecialchars($post['device_num'], ENT_QUOTES); ?>.png" alt="<?php echo $osname; ?>" />
                                </div>
                                <div class="postdata-box">
                                    <p class="post-title"><?php echo $osname ?></p>
                                    <p class="post-text">ログイン場所：<?php echo htmlspecialchars($post['login_location'], ENT_QUOTES); ?></p>
                                    <p class="post-text">ログイン時間：<?php echo htmlspecialchars($post['login_date'], ENT_QUOTES); ?></p>
                                    <?php if((int) $post['browser_num'] == 0): ?>
                                        <p class="browser-name"><p class="browser-name-item">ブラウザ名：</p><p class="browser-name-item"><?php echo $browsername; ?></p></p>
                                    <?php else: ?>
                                        <p class="browser-name"><p class="browser-name-item">ブラウザ名：</p><img class="browser-name-item" src="icon/session/browser<?php echo htmlspecialchars($post['browser_num'], ENT_QUOTES); ?>.png" alt="<?php echo $browsername; ?>" width="20vw" height="20vw" /><p class="browser-name-item"><?php echo $browsername; ?></p></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="buy-box">
                                <form method="post" action="pg/session-logout.php"><input type="hidden" name="id" value="<?php echo $post['id'] ?>"><input type="submit" value="このセッションをログアウトする" class="black-button"></form>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <a class="pagination-button" href="sessions.php?page=<?php print($page-1); ?>">前へ</a>
                <?php endif; ?>
                <a><?php echo $page ?>ページ目</a>
                <?php
                $counts = $dbr->query('SELECT COUNT(*) AS cnt FROM sessions WHERE user_id=' . $user_id);
                $count = $counts->fetch();
                $max_page = ceil($count['cnt'] / $max_post);
                if ($page < $max_page): ?>
                    <a class="pagination-button" href="sessions.php?page=<?php print($page+1); ?>">次へ</a>
                <?php endif; ?>
            </div>
        <?php endif;?>
    <?php endif;?>
</footer>
</html>