<?php

require('functions.php');

debug('「「「「「「「「「「「「「「「「「「「');
debug('パスワード忘れたか方メール送信画面');
debug('「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// POST送信チェック
if (!empty($_POST)) {

    debug('POST送信チェックOK');
    debug('POST:' . print_r($_POST, true));

    $email = $_POST['email'];

    //バリデーション
    // 未入力チェック
    validRequired($email, 'email');

    if (empty($err_msg)) {
        // メールアドレスの登録チェック
        // Eーmail形式チェック
        validEmail($email, 'email');
        // 最大文字数チェック
        validMaxLen($email, 'email');
        try {
            // DB接続
            $dbh = dbConnect();
            $sql = 'SELECT count(user_id) FROM users WHERE mail=:email';
            $data = array(':email' => $email);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリ結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // 指定されたメールアドレスがDBに登録がある場合
            if ($stmt && array_shift($result)) {
                debug('メールアドレス登録あり');
                $_SESSION['success'] = SUC05;
                // ランダムキー生成
                $auth_key = makeRandKey();

                $from = 'info@tanutore.com';
                $to = $email;
                $subject = '【パスワード再発行認証】| たぬトレ';
                //EOT内の半角空白も全てそのまま半角空白として扱われるのでインデントはしないこと
                $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力いただくとパスワードが再発行されます。

パスワード再発行認証キー入力ページ：
http://localhost:8888/tanutore/passResetReceive.php

【認証キー】
{$auth_key}
※認証キーの有効期限は30分となります。

*****************************
たぬトレカスタマーセンター
Twitter https://twitter.com/tanutore
*****************************
EOT;
                sendMail($from, $to, $subject, $comment);

                // 認証に必要な情報を格納
                $_SESSION['auth_key'] = $auth_key;
                $_SESSION['auth_email'] = $email;
                $_SESSION['auth_key_limit'] = time()+(60*30);

                debug('セッションの中身'.print_r($_SESSION,true));
                header('Location:passResetReceive.php');
            }
        } catch (Exception $e) {
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-169366360-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-169366360-1');
</script>

    <meta charset="utf-8" name="viewport" content="width=device-width">
    <title>たぬトレ</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
</head>

<body id="window-width">
    <header id="header">
        <div id="top-content">
            <a href="">
                <div class="top-logo">
                    <img src="img/icon.png" alt="">
                </div>
                <div class="top-title">
                    <h1>たぬトレ</h1>
                </div>
            </a>
        </div>
    </header>

    <main>
        <div class="page-title">
            <h2>パスワードをお忘れの方</h2>
        </div>
        <div class="commonErrArea">
                <?php 
                if(!empty($err_msg['common'])) echo $err_msg['common'];
                ?>
        </div>
        <form action="" class="form-Area" method="post">
            メールアドレス
            <input type="text" class="js-mail-check js-mail-validate" placeholder="ご登録されたメールアドレス" name="email" value="<?php if(!empty($email)){ echo $email;} ?>">
            <div class="mail-error-msgArea errorMsgArea"><?php if(!empty($err_msg['email'])){ echo $err_msg['email'];} ?></div>
            <p>ご登録されたメールアドレスにパスワード再発行のご案内が配信されます。</p>
            <input type="submit" value="送信する">
        </form>
    </main>

    <footer id="footer">
        <div class="copyright">
            &#169;? 2020 Qumo.inc
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>

</html>