<?php require('db/dbconnect-r.php'); ?>
<?php require('pg/modules.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

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
    <link rel="stylesheet" href="css/top.css">
    <?php if(isset($_GET["search"])): ?>
        <title>「<?php echo htmlspecialchars($_GET["search"], ENT_QUOTES) ?>」の検索結果 | ReFoods.</title>
    <?php else: ?>
        <title>ReFoods.</title>
    <?php endif; ?>
</head>
<header>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <form method="get" action="index.php" class="search_container">
        <?php if(isset($_GET["search"])): ?>
            <input type="text" name="search" placeholder="　キーワード検索" value="<?php echo htmlspecialchars($_GET["search"], ENT_QUOTES) ?>">
        <?php else: ?>
            <input type="text" name="search" placeholder="　キーワード検索">
        <?php endif; ?>
        <?php if(isset($_GET["orderby"])): ?>
            <input type="hidden" name="orderby" value="<?php echo $_GET["orderby"] ?>">
        <?php endif; ?>
        <button type="submit" class="search-button"><img src="icon/search.svg" width="20vw" height="20vw" alt="送信"/></button>
    </form>
    <div>
        <form method="get" action="index.php" class="orderby-form">
            <select name="orderby" id="post-orderby">
                <option value="" select>表示順</option>
                <option value="new">新しい順</option>
                <option value="old">古い順</option>
                <option value="lowprice">価格が安い順</option>
                <option value="highprice">価格が高い順</option>
                <option value="near">近いところ順</option>
                <option value="distant">遠いところ順</option>
            </select>
            <?php if(isset($_GET["search"])): ?>
                <input type="hidden" name="search" value="<?php echo $_GET["search"] ?>">
            <?php endif; ?>
            <input type="submit" value="更新" class="orderby-update">
            <noscript>
                <input type="submit" value="更新" class="orderby-update">
            </noscript>
        </form>
        <?php $logincheck = logincheck();
        if((int) $logincheck == 1): ?>
            <a class="onlyPC" href="account.php"><button class="black-button">アカウント管理</button></a>
        <?php else: ?>
            <a class="onlyPC" href="Authentication.html"><button class="black-button">ログイン</button></a>
        <?php endif; ?>
    </div>
</header>
<body>
    <?php if(isset($_GET["search"])): ?>
        <h1 class="search-title">「<?php echo htmlspecialchars($_GET["search"], ENT_QUOTES) ?>」の検索結果</h1>
    <?php endif; ?>
    <div class="contents">
        <?php if((int) $logincheck == 1): ?>
            <div class="onlyPC" id="post-page-button"><a href="foodspost.php">投稿する</a></div>
        <?php endif; ?>
        <ul class="shMenu onlySumaho">
            <li>
                <a href="index.php"><img src="icon/home.svg" width="30px" height="30px"/><br>
                <span class="iconname">ホーム</span></a>
            </li>
            <li>
                <a href="foodspost.php"><img src="icon/edit.svg" width="30px" height="30px"/><br>
                <span class="iconname">投稿</span></a>
            </li>
            <li>
                <a href="account.php"><img src="icon/account.svg" width="30px" height="30px"/><br>
                <span class="iconname">アカウント管理</span></a>
            </li>
        </ul>
        <!-- JavaScript無効ブラウザ対策でAjax利用見直し -->
        <?php 
        $max_post = 10;

        if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
            $page = $_REQUEST['page'];
        } else {
            $page = 1;
        }
        $start = $max_post * ($page - 1);

        $sql = "SELECT * FROM posts WHERE is_purchased=0";
        $sqla = "";

        if(isset($_GET["search"])) {
            if(strpos($_GET['search'], ' ') !== false && strpos($_GET['search'], '　') !== false) {
                $hsp = mb_substr_count($_GET['search'], " ");
                $zsp = mb_substr_count($_GET['search'], "　");
                if($hsp < $zsp) {
                    $split = explode(" ", $_GET['search']);
                    for($i = 0; $i < count($split); $i++) {
                        if(strpos($split[$i], '　') !== false) {
                            $split2 = explode("　", $split[$i]);
                            for($i = 0; $i < count($split2); $i++){
                                $sqla = $sqla . " AND name LIKE '%" . sql_escape($split2[$i]) . "%'";
                            }
                        } else {
                            $sqla = $sqla . " AND name LIKE '%" . sql_escape($split[$i]) . "%'";
                        }
                    }
                } else {
                    $split = explode("　", $_GET['search']);
                    for($i = 0; $i < count($split); $i++) {
                        if(strpos($split[$i], ' ') !== false) {
                            $split2 = explode(" ", $split[$i]);
                            for($i = 0; $i < count($split2); $i++){
                                $sqla = $sqla . " AND name LIKE '%" . sql_escape($split2[$i]) . "%'";
                            }
                        } else {
                            $sqla = $sqla . " AND name LIKE '%" . sql_escape($split[$i]) . "%'";
                        }
                    }
                }
            } elseif(strpos($_GET['search'], ' ') !== false) {
                $split = explode(" ", $_GET['search']);
                for($i = 0; $i < count($split); $i++){
                    $sqla = $sqla . " AND name LIKE '%" . sql_escape($split[$i]) . "%'";
                }
            } elseif(strpos($_GET['search'], '　') !== false) {
                $split = explode("　", $_GET['search']);
                for($i = 0; $i < count($split); $i++){
                    $sqla = $sqla . " AND name LIKE '%" . sql_escape($split[$i]) . "%'";
                }
            } else {
                $sqla = $sqla . " AND name LIKE '%" . sql_escape($_GET['search']) . "%'";
            }
        }

        if(isset($_GET["orderby"])) {
            if(strcmp($_GET['orderby'], "new") == 0  || strcmp($_GET['orderby'], "") == 0) {
                $posts = $dbr->query($sql . $sqla . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
            } elseif(strcmp($_GET['orderby'], "old") == 0) {
                $posts = $dbr->query($sql . $sqla . ' ORDER BY post_date ASC LIMIT ' . $start . ',' . $max_post);
            } elseif(strcmp($_GET['orderby'], "lowprice") == 0) {
                $posts = $dbr->query($sql . $sqla . ' ORDER BY price ASC LIMIT ' . $start . ',' . $max_post);
            } elseif(strcmp($_GET['orderby'], "highprice") == 0) {
                $posts = $dbr->query($sql . $sqla . ' ORDER BY price DESC LIMIT ' . $start . ',' . $max_post);
            } elseif(strcmp($_GET['orderby'], "near") == 0) {
                $posts = $dbr->query('');
            } elseif(strcmp($_GET['orderby'], "distant") == 0) {
                $posts = $dbr->query('');
            }
        } else {
            $posts = $dbr->query($sql . $sqla . ' ORDER BY post_date DESC LIMIT ' . $start . ',' . $max_post);
        }

        foreach($posts as $post): ?>
        <div class="post">
            <a href="item.php?id=<?php echo $post['id'] ?>"><div class="data-box">
                <div class="postimg-box">
                    <img class="postimg" src="postimg/<?php echo htmlspecialchars($post['img_url'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?>" />
                </div>
                <div class="postdata-box">
                    <p class="post-title"><?php echo htmlspecialchars($post['name'], ENT_QUOTES); ?></p>
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
                    <div class="onlySumaho">
                        <p class="bold">場所</p>
                        <p class="shPostTxt"><?php echo htmlspecialchars($post['location'], ENT_QUOTES); ?></p>
                        <p class="bold">量</p>
                        <p class="shPostTxt"><?php echo htmlspecialchars($post['amount'], ENT_QUOTES); ?>kg</p>
                        <p class="bold">値段</p>
                        <p class="shPostTxt"><?php echo htmlspecialchars($post['price'], ENT_QUOTES); ?>円</p>
                        <p class="bold">消費期限</p>
                        <p><?php echo htmlspecialchars($post['expiration_date'], ENT_QUOTES); ?></p>
                    </div>
                </div>
            </div></a>
            <div class="buy-box onlyPC">
                <form method="post" action="pg/buy-processing.php"><input type="hidden" name="post_id" value="<?php echo $post['id'] ?>"><input type="submit" value="購入" class="black-button"></form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</body>
<footer>
    <div>
        <?php if ($page >= 2): ?>
            <a class="pagination-button" href="index.php?page=<?php print($page-1); ?>">前へ</a>
        <?php endif; ?>
        <a><?php echo $page ?>ページ目</a>
        <?php
        $sqlpage = 'SELECT COUNT(*) AS cnt FROM posts WHERE is_purchased=0' . $sqla;
        $counts = $dbr->query($sqlpage);
        $count = $counts->fetch();
        $max_page = ceil($count['cnt'] / $max_post);
        if ($page < $max_page): ?>
            <a class="pagination-button" href="index.php?page=<?php print($page+1); ?>">次へ</a>
        <?php endif; ?>
    </div>
</footer>
</html>