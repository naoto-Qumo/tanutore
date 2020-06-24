<?php
    require('functions.php');

    debug('「「「「「「「「「「「「「「「「「');
    debug('サインアップ画面');
    debug('「「「「「「「「「「「「「「「「「');
    debugLogStart();

    // POST送信チェック
    if(!empty($_POST)){
        // 変数にフォーム情報を格納
        $email = $_POST['email'];
        $name = $_POST['name'];
        $pass = $_POST['pass'];
        $repass = $_POST['repass'];

        // 未入力チェック
        validRequired($email, 'email');
        validRequired($name, 'name');
        validRequired($pass, 'pass');
        validRequired($repass, 'repass');

        if(empty($err_msg)){
            // メールアドレス形式チェック
            validEmail($email, 'email');
            //メールアドレス重複チェック
            validEmailDup($email);
            // メールアドレス最大文字数チェック
            validMaxLen($email,'email');

            // ニックネーム最大文字数チェック
            validMaxLen($name,'name');

            // パスワード英数字チェック
            validCharNum($pass, 'pass');
            // パスワード最大文字数チェック
            validMaxLen($pass, 'pass');
            // パスワード最小文字数チェック
            validMinLen($pass, 'pass');
            


            // 再入力パスワード最大文字数チェック
            validMaxLen($repass, 'repass');
            // 再入力パスワード最小文字数チェック
            validMinLen($pass, 'repass');

            if(empty($err_msg)){

                // パスワードと再入力パスワードが一致しているかチェック
                validMatch($pass, $repass, 'repass');

                if(empty($err_msg)){
                    try {
                        $dbh = dbConnect();
                        $sql = 'INSERT INTO users (mail, pass, nickname, login_time) VALUES (:email, :pass, :name, :login_time)';
                        $data = array(':email'=>$email, ':pass'=>password_hash($pass, PASSWORD_DEFAULT),
                                        ':name'=>$name, 'login_time'=>date('Y-m-d H:i:s'));
               
                        // クエリ実行
                        $stmt = queryPost($dbh, $sql, $data);
                        // クエリ成功の場合
                        if($stmt){
                            //ログイン有効期限（デフォルト1時間）
                            $sesLimit = 60*60;
                            // 最終ログイン日時を現在日時にする
                            $_SESSION['login_date'] = time();
                            $_SESSION['login_limit'] = $sesLimit;
                            // ユーザIDを格納
                            $_SESSION['u_id'] = $dbh->lastInsertId();
                            debug('セッション変数の中身：'.print_r($_SESSION,true));

                            header('Location:index.php');
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

<body id="signupBackImg">
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
        <div class="page-title">
            <h1>新規登録</h1>
        </div>
        <form class="form-Area" action="" method="post">
            メールアドレス
            <input class="js-mail-check js-mail-validate" type="text" placeholder="PC・携帯どちらでも可" name="email" value="<?php if(!empty($_POST['email'])){ echo $_POST['email'];}?>">
            <div class="mail-error-msgArea errorMsgArea"><?php if(!empty($err_msg['email'])){ echo $err_msg['email'];} ?></div>
            ニックネーム
            <input class="js-name-check" type="text" placeholder="例)たぬ子" name="name" value="<?php if(!empty($_POST['name'])){ echo $_POST['name'];}?>">
            <div class="name-error-msgArea errorMsgArea"><?php if(!empty($err_msg['name'])){ echo $err_msg['name'];} ?></div>
            パスワード(半角英字と数字を1つ以上含む8文字以上)
            <input class="js-pass-check js-pass-validate" type="password" placeholder="8文字以上の英数字" name="pass" value="<?php if(!empty($_POST['pass'])){ echo $_POST['pass'];}?>">
            <div class="pass-error-msgArea errorMsgArea"><?php if(!empty($err_msg['pass'])){ echo $err_msg['pass'];} ?></div>
            パスワード(再入力)
            <input class="js-repass-check js-repass-validate" type="password" placeholder="パスワード確認" name="repass" value="<?php if(!empty($_POST['repass'])){ echo $_POST['repass'];}?>">
            <div class="repass-error-msgArea errorMsgArea"><?php if(!empty($err_msg['repass'])){ echo $err_msg['repass'];} ?></div>
            <input type="submit" value="登録する">
        </form>
	</div>
    </main>
    <footer id="footer">
        <div class="copyright">
            &copy; 2020 Qumo
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>

</html>