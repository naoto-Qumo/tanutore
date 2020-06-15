<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「「');
debug('パスワードリセット認証キー入力画面');
debug('「「「「「「「「「「「「「「「「「「');
debugLogStart();

// SESSIONに認証キーがあるか確認、ない場合は送信画面にリダイレクト
if(empty($_SESSION['auth_key'])){
    header('Location:passResetSend.php');
}

// POST送信チェック
if(!empty($_POST)){
    debug('POST送信チェックOK');
    debug('POST情報：'.print_r($_POST,true));

    $auth_key = $_POST['token'];

    // バリデーション
    // 未入力チェック
    validRequired($auth_key, 'token');

    if(empty($err_msg)){
        debug('未入力チェックOK');
        // 固定長チェック
        validLength($auth_key, 'token');
        // 半角チェック
        validHalf($auth_key, 'token');

        if(empty($err_msg)){
            debug('バリデーションOK');

            if($auth_key !== $_SESSION['auth_key']){
                $err_msg['common'] = MSG13;
            }
            if(time() > $_SESSION['auth_key_limit']){
                $err_msg['common'] = MSG14;
            }

            if(empty($err_msg)){
                debug('認証OK');

                $pass = makeRandKey();

                try {
                    $dbh = dbConnect();
                    $sql = 'UPDATE users SET pass=:pass WHERE mail=:email';
                    $data = array(':pass'=>password_hash($pass, PASSWORD_DEFAULT), ':email'=>$_SESSION['auth_email']);
                    // クエリ実行
                    $stmt = queryPost($dbh, $sql, $data);
                    // クエリ成功の場合
                    if($stmt){
                        debug('クエリ成功');
                        // メールを送信
                        $from = 'info@tanutore.com';
                        $to = $_SESSION['auth_email'];
                        $subject = '【パスワード再発行完了】｜たぬトレ';
                                        //EOT内の半角空白も全てそのまま半角空白として扱われるのでインデントはしないこと
                        $comment = <<<EOT
本メールアドレス宛にパスワードの再発行をいたしました。
下記のURLにて再発行パスワードをご入力いただき、ログインください。

ログインページ：
http://localhost:8888/tanutore/login.php

【再発行パスワード】
{$pass}
※ログイン後、パスワードのご変更をお願いいたします。

*****************************
たぬトレカスタマーセンター
Twitter https://twitter.com/tanutore
*****************************
EOT;
                        sendMail($from, $to, $subject, $comment);

                        // セッション削除
                        session_unset();
                        $_SESSION['success2'] = SUC05;
                        debug('あああセッション変数の中身'.print_r($_SESSION, true));
                        header('Location:login.php');

                    }
                } catch (Exception $e) {
                    error_log('エラー発生：'.$e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
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
	<div class="all">
        <p id="js-show-msg" style="display:none;" class="msg-slide">
            <?php echo getSessionFlash('success'); ?>
        </p>
        <div class="page-title">
            <h2>パスワードリセット</h2>
        </div>
        <form action="" class="form-Area" method="post">
            <div class="commonErrArea">
                <?php 
                if(!empty($err_msg['common'])) echo $err_msg['common'];
                ?>
            </div>
            <p class="formMsg">ご指定のメールアドレスにお送りした【パスワード再発行】メール内の「再発行キー」を入力してください。</p>
            <input type="text" placeholder="再発行キー" name="token">
            <div class="errorMsgArea"><?php if(!empty($err_msg['token'])){ echo $err_msg['token'];} ?></div>
            <input type="submit" value="再発行する">
            <p class="forgot">再発行メールが届かない場合は再送信してください。</p>
            <a href="passResetSend.php" class="forgot">再送信する</a>
        </form>
	</div>
    </main>

    <footer id="footer">
        <div class="copyright">
            &#169;? 2020 Qumo.inc
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>

</html>