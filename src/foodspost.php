<?php require('db/dbconnect-r.php');
require('pg/modules.php');

$logincheck = logincheck();
if((int) $logincheck == 1) {
    if(isset($_POST['editpostid'])) {
        if(is_numeric($_POST['editpostid'])) {
            $postdb = $dbr->prepare('SELECT * FROM posts WHERE id=' . sql_escape($_POST['editpostid']));
            $postdb -> execute();
            $postdata = $postdb -> fetch();
            $posted = explode(" ", $postdata['expiration_date']);
        }
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

    function previewFile() {
        var preview = document.getElementById('imgPreview');
        var file    = document.getElementById('fileInput').files[0];
        var reader  = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "";
        }
    }
    </script>
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/foodspost.css">
    <title>投稿フォーム | ReFoods.</title>
</head>
<body>
    <?php if((int) $logincheck == 1): ?>
        <?php if(isset($postdata)):?>
            <form action="pg/post-update.php" method="POST" enctype="multipart/form-data">
                <header>
                    <a href="sell.php"><img height="30vh" src="icon/chevron-left.svg" alt=""></a>
                    <h1>更新</h1>
                    <input class="black-button" type="submit" value="更新">
                </header>
                <main>
                    <div class="contents">
                        <div class="image-box">
                            <h3 class="formTitle">画像</h3>
                            <img id="imgPreview" name="file" src="postimg/<?php echo htmlspecialchars($postdata['img_url'], ENT_QUOTES); ?>">
                            <input type="file"  id="fileInput" name="file" onchange="previewFile()">
                        </div>
                        <div class="data-box">
                            <h3 class="formTitle">商品名</h3>
                            <input class="formitem" type="text" name="name" value="<?php echo htmlspecialchars($postdata['name'], ENT_QUOTES); ?>" required>
                            <h3 class="formTitle">場所</h3>
                            <input class="formitem" type="text" name="location" value="<?php echo htmlspecialchars($postdata['location'], ENT_QUOTES); ?>" required>
                            <h3 class="formTitle">量</h3>
                            <div class="formitem"><input type="number" name="amount" min="0" value="<?php echo htmlspecialchars($postdata['amount'], ENT_QUOTES); ?>" required><a class="support-txt">kg</a></div>
                            <h3 class="formTitle">値段</h3>
                            <div class="formitem"><input type="number" name="price" min="0" value="<?php echo htmlspecialchars($postdata['price'], ENT_QUOTES); ?>" required><a class="support-txt">円</a></div>
                            <h3 class="formTitle">消費期限（日付）</h3>
                            <input class="formitem" type="date" name="expiration_date" value="<?php echo htmlspecialchars($posted[0], ENT_QUOTES); ?>" required>
                            <h3 class="formTitle">消費期限（時間）</h3>
                            <input class="formitem" type="time" name="expiration_time" value="<?php echo htmlspecialchars($posted[1], ENT_QUOTES); ?>"  required>
                            <h3 class="formTitle">メールアドレス</h3>
                            <input class="formitem" type="email" name="email" value="<?php echo htmlspecialchars($postdata['email'], ENT_QUOTES); ?>" required>
                            <input type="hidden" name="id" value="<?php echo $postdata['id']; ?>" required>
                        </div>
                    </div>
                </main>
            </form>
        <?php else:?>
            <form action="pg/post-create.php" method="POST" enctype="multipart/form-data">
                <header>
                    <a href="index.php"><img height="30vh" src="icon/chevron-left.svg" alt=""></a>
                    <h1>投稿</h1>
                    <input class="black-button" type="submit" value="投稿">
                </header>
                <main>
                    <div class="contents">
                        <div class="image-box">
                            <h3 class="formTitle">画像</h3>
                            <img id="imgPreview" name="file" src="icon/noimg.png">
                            <input type="file"  id="fileInput" name="file" onchange="previewFile()">
                        </div>
                        <div class="data-box">
                            <h3 class="formTitle">商品名</h3>
                            <input class="formitem" type="text" name="name" required>
                            <h3 class="formTitle">場所</h3>
                            <input class="formitem" type="text" name="location" required>
                            <h3 class="formTitle">量</h3>
                            <div class="formitem"><input type="number" name="amount" min="0" required><a class="support-txt">kg</a></div>
                            <h3 class="formTitle">値段</h3>
                            <div class="formitem"><input type="number" name="price" min="0" required><a class="support-txt">円</a></div>
                            <h3 class="formTitle">消費期限（日付）</h3>
                            <input class="formitem" type="date" name="expiration_date" required>
                            <h3 class="formTitle">消費期限（時間）</h3>
                            <input class="formitem" type="time" name="expiration_time"  required>
                            <h3 class="formTitle">メールアドレス</h3>
                            <input class="formitem" type="email" name="email" required>
                        </div>
                    </div>
                </main>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <p>ログインしてください</p>
    <?php endif; ?>
</body>
</html>
