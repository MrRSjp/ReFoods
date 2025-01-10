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
    <link rel="stylesheet" href="css/useredit.css">
    <title>アカウント情報編集 | ReFoods.</title>
</head>
<header>
    <a class="onlySumaho" href="account.php"><img src="icon/chevron-left.svg" width="25vw" height="25vw" alt="アカウントページへ戻る"></a>
    <a href="index.php"><p class="logo logosize">ReFoods.</p></a>
    <div class="onlyPC">
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
        <h1>アカウント情報編集</h1>
    </div>
    <div class="contents">
        <!-- JavaScript無効ブラウザ対策でAjax利用見直し -->
        <?php if ((int) $logincheck == 1):?>
            <form class="useredit-form onlyPC" action="pg/user-update.php" method="post" enctype="multipart/form-data">
                <table class="useredit-table">
                    <tbody>
                        <tr>
                            <th class="useredit-table-th">名前</th>
                            <td class="useredit-table-td"><input type="text" name="username" class="textform"></td>
                        </tr>
                        <tr>
                            <th class="useredit-table-th">ユーザー画像</th>
                            <td class="useredit-table-td"><input type="file" name="userimg" accept="image/jpeg, image/png"></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" value="更新する" class="black-button">
            </form>
            <form class="form onlySumaho" action="pg/user-update.php" method="post" enctype="multipart/form-data">
                <h3 class="title">名前</h3>
                <input class="data textform" type="text" name="username"></p>
                <h3 class="title">ユーザー画像</h3>
                <input class="data" type="file" name="userimg" accept="image/jpeg, image/png"></p>
                <input type="submit" value="更新する" class="black-button">
            </form>
        <?php else: ?>
            <div class="login-urge-box">
                <p class="login-urge">ログインしてください</p>
                <a href="Authentication.html"><button class="black-button">ログイン / 新規会員登録</button></a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>