<?php
session_start();
?>
    <head>
        <meta charset = "UTF-8">
    </head>

    <body>
        <form action="b.php" method="POST">
            画像  <input type="file" name="file"><br/>
            名前 <br/> <input type="text" name="id"><br/>
            場所 <br/> <input type="text" name="id"><br/>
            量　 <br/> <input type="text" name="id"><br/>
            メールアドレス <br/> <input type="email" name="mail"><br/>
            <input type="submit" value="投稿">
        </form>
    </body>