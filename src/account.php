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
    <link rel="stylesheet" href="css/account.css">
    <title>アカウント管理 | ReFoods.</title>
</head>
<header>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <?php
    $logincheck = logincheck();
    if((int) $logincheck == 1): ?>
        <a href="account.php"><button class="black-button">アカウント管理</button></a>
    <?php else: ?>
        <a href="Authentication.html"><button class="black-button">ログイン</button></a>
    <?php endif; ?>
</header>
<body>
    <div class="contents">
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
        <?php if ((int) $logincheck == 1) {
            $buser_id = get_userid();
            if(strcmp($buser_id, "e") != 0 ) {
                $user_id = $buser_id;
                $userdb = $dbr->prepare('SELECT * FROM users WHERE id=' . $user_id);
                $userdb -> execute();
                $userdata = $userdb -> fetch(); ?>
                <div class="section">
                    <div style="display: inline-block;">
                        <table class="user-table">
                            <tbody>
                                <tr>
                                    <td><img class="userimg" src="userimg/<?php echo htmlspecialchars($userdata['user_img'], ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($userdata['name'], ENT_QUOTES); ?>"></td>
                                    <td class="username"><p><?php echo htmlspecialchars($userdata['name'], ENT_QUOTES); ?> さん</p></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><a href="pg/logout.php"><button class="black-button">ログアウト</button></a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="section">
                    <div class="inner-box">
                        <h2 class="section-title">商品管理</h2>
                        <ul class="section-list">
                            <a href="sell.php"><li class="section-item"><p>出品した商品</p><img src="icon/chevron-right.svg" width="20vw" height="20vw"></li></a>
                            <a href="buy.php"><li class="section-item"><p>購入済みの商品</p><img src="icon/chevron-right.svg" width="20vw" height="20vw"></li></a>
                            <!-- 取引中の商品の項目の実装はプラスアルファ -->
                            <!-- <li>売却取引中の商品</li> -->
                            <!-- <li>購入取引中の商品</li> -->
                        </ul>
                    </div>
                </div>
                <div class="section">
                    <div class="inner-box">
                        <h2 class="section-title">アカウント管理</h2>
                        <ul class="section-list">
                            <a href="sessions.php"><li class="section-item"><p>有効なログイン済端末の管理</p><img src="icon/chevron-right.svg" width="20vw" height="20vw"></li></a>
                            <a href="useredit.php"><li class="section-item"><p>アカウント情報の編集</p><img src="icon/chevron-right.svg" width="20vw" height="20vw"></li></a>
                        </ul>
                    </div>
                </div>
            <?php } else { ?>
                <p class="login-urge">ログインしてください</p>
                <a href="Authentication.html"><button class="black-button">ログイン / 新規会員登録</button></a>
            <?php } ?>
        <?php } else { ?>
            <p class="login-urge">ログインしてください</p>
            <a href="Authentication.html"><button class="black-button">ログイン / 新規会員登録</button></a>
        <?php } ?>
    </div>
</body>
</html>