<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「');
debug('パスワード変更画面');
debug('「「「「「「「「「「「「「「「「「');
debugLogStart();

// ユーザ認証
require('auth.php');

$u_id = $_SESSION['u_id'];

// DBからユーザ情報取得
$user_pass = dbGetUserPass($u_id);
debug('取得したユーザ情報：'.print_r($user_pass,true));

// POST送信チェック
if(!empty($_POST)){
    debug('POST送信があります');
    debug('POSTの中身：'.print_r($_POST,true));

    $nowPass = $_POST['now_pass'];
    $newPass = $_POST['pass'];
    $reNewPass = $_POST['repass'];

    // 未入力チェック
    validRequired($nowPass,'now_pass');
    validRequired($newPass, 'pass');
    validRequired($reNewPass, 'repass');

    if(empty($err_msg)){
        debug('未入力チェックOK');

        //パスワードチェック
        validPass($nowPass, 'now_pass');
        validPass($newPass, 'pass');

        // 現在のパスワード照合
        if(!password_verify($nowPass, $user_pass['pass'])){
            $err_msg['now_pass'] = MSG16;
        }
        // 新しいパスワードと現在のパスワードが同じかチェック
        if($nowPass === $newPass){
            $err_msg['pass'] = MSG17;
        }

        validMatch($newPass, $reNewPass, 'repass');

        if(empty($err_msg)){
            try {
                // DBへ接続
                $dbh = dbConnect();
                // sql
                $sql = 'UPDATE users SET pass=:new_pass WHERE user_id=:u_id';
                $data = array(':new_pass'=>password_hash($newPass, PASSWORD_DEFAULT), ':u_id'=>$u_id);
                $stmt = queryPost($dbh, $sql, $data);
                // クエリが成功なら
                if($stmt){
                    debug('パスワードを更新しました。');
                    $_SESSION['success'] = SUC07;
                    header('Location:mypage.php');
                } else {
                    debug('パスワードの更新に失敗しました。');
                }
            } catch (Exception $e) {
                error_log('エラーが発生'. $e->getMessage());

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
    <script src="https://kit.fontawesome.com/d5e07a31ed.js" crossorigin="anonymous"></script>
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
        <button class="backbtn-Area btn-left">
                <a href="editmypage.php">キャンセル</a>
        </button>
        <div class="page-title">
            <h1>パスワード変更</h1>
        </div>
        <form class="form-Area" action="" method="post">
            現在のパスワード
            <input class="js-now_pass-check js-pass-validate" type="password" placeholder="7文字以上の英数字" name="now_pass" value="<?php if(!empty($_POST['now_pass'])){ echo $_POST['now_pass'];}?>">
            <div class="now_pass-error-msgArea errorMsgArea"><?php if(!empty($err_msg['now_pass'])){ echo $err_msg['now_pass'];} ?></div>
            新しいパスワード
            <input class="js-pass-check js-pass-validate" type="password" placeholder="7文字以上の英数字" name="pass" value="<?php if(!empty($_POST['pass'])){ echo $_POST['pass'];}?>">
            <div class="pass-error-msgArea errorMsgArea"><?php if(!empty($err_msg['pass'])){ echo $err_msg['pass'];} ?></div>
            新しいパスワード(再入力
            <input class="js-repass-check js-repass-validate" type="password" placeholder="パスワード確認" name="repass" value="<?php if(!empty($_POST['repass'])){ echo $_POST['repass'];}?>">
            <div class="repass-error-msgArea errorMsgArea"><?php if(!empty($err_msg['repass'])){ echo $err_msg['repass'];} ?></div>
            <input type="submit" value="変更する">
        </form>
    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;? 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
</body>

</html>