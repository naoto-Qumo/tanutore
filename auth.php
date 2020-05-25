<?php
if(!empty($_SESSION)){
    debug('ログイン済ユーザです。');
    // セッション情報がログイン有効期限内か判定
    if(( $_SESSION['limit_time']+$_SESSION['login_date']) < time()){
        debug('ログイン有効期限オーバーです。');
        // セッションを破棄
        session_destroy();
        // ログインページへ遷移
        header('Location:login.php');
    } else {
        debug('ログイン有効期限内です。');
        // 最終ログイン日時を現在日時に更新
        $_SESSION['login_date'] = time();
        // 一覧画面に遷移
        header('Location:msg.html');
    }
} else {
    debug('未ログインユーザです。');
    if(basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header('Location:login.php');
    }
}