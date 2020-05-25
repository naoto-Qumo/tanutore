<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「「「「「「「');
debug('ログイン画面');
debug('「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// ログイン認証
require('auth.php');

if(!empty($_POST)){
    //フォーム情報変数格納
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;


    //メールアドレス未入力チェック
    validRequired($email, 'email');
    //パスワード未入力チェック
    validRequired($pass, 'pass');

    // メールアドレス形式チェック
    validEmail($email, 'email');
    //メールアドレス重複チェック
    validEmailDup($email);
    // メールアドレス最大文字数チェック
    validMaxLen($email,'email');
    
    // パスワード英数字チェック
    validCharNum($pass, 'pass');
    // パスワード最大文字数チェック
    validMaxLen($pass, 'psss');
    // パスワード最小文字数チェック
    validMinLen($pass, 'pass');

    if(empty($err_msg)){

        try{
            // DB接続
            $dbh = dbConnect();
            // SQL作成
            $sql = 'SELECT user_id, pass FROM users WHERE mail=:email';
            $data = array(':email'=>$email);
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            // クエリの結果の値を取得
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            debug('クエリの中身確認：'.print_r($result,true));

            // パスワード照合
            if(!empty($result) && password_verify($pass, $result['pass'])){
                debug('パスワードが一致しました。');
                // ログイン有効期限セット
                // 60分
                $seslimit = 60*60;
                $_SESSION['login_date'] = time();

                //パスワード保持にチェックがあった場合
                if($pass_save){
                    // ログイン期限を30日とする
                    debug('ログイン保持にチェックがあります。');
                    $_SESSION['login_limit'] = $seslimit * 24 * 30; 
                } else {
                    // チェックがない場合、1時間
                    debug('ログイン保持にチェックがありません。');
                    $_SESSION['login_limit'] = $seslimit;
                }
                // ユーザID取得
                $_SESSION['u_id'] = $result['user_id'];
                debug('セッション変数の中身'.print_r($_SESSION,true));
                header('Location:msg.html');
            } else {
                debug('パスワードが不一致です。');
                $err_msg['common'] = MSG09;
            }
        }catch (Exception $e){
            error_log('エラー発生：'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理完了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.');

?>




<!DOCTYPE html>
<html>

<head>
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
            <h2>ログイン</h2>
        </div>
        <div class="login-no-account">
            <p>アカウントをお持ちでない方はこちら</p>
            <a href="signup.html">新規会員登録</a>
        </div>
        <form class="form-Area" action="" method="POST">
            <div class="mail-error-msgArea errorMsgArea">
                <?php if(!empty($err_msg['common'])){ echo $err_msg['common'];} ?>
            </div>
            メールアドレス
            <input type="text" placeholder="ご登録されたメールアドレス" name="email" value="<?php if(!empty($email)){ echo $email;} ?>">
            <div class="mail-error-msgArea errorMsgArea"><?php if(!empty($err_msg['email'])){ echo $err_msg['email'];} ?></div>
            パスワード
            <input type="password" placeholder="パスワード" name="pass" value="<?php if(!empty($pass)){ echo $pass;} ?>">
            <div class="mail-error-msgArea errorMsgArea"><?php if(!empty($err_msg['pass'])){ echo $err_msg['pass'];} ?></div>
            <div class="login-check-wrap">
                <input type="checkbox" id="login-checkbox">
                <label for="login-checkbox" class="check-label" name="pass_save">次回から自動でログインする</label>
            </div>
            <input type="submit" value="ログインする">
            <a href="passResetSend.html" class="forgot">パスワードを忘れた方はこちら</a>
        </form>
    </main>
    <footer id="footer">
        <div class="copyright">
            ©️ 2020 Qumo.inc
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
</body>

</html>