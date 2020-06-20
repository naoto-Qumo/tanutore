<?php

require('functions.php');

debug('「「「「「「「「「「「「「「「');
debug('登録完了シェアページ');
debug('「「「「「「「「「「「「「「「');
debugLogStart();

//ログイン認証
require('auth.php');

$s_id = (!empty($_SESSION['s_id'])) ? $_SESSION['s_id'] : '';
$tranInfo = (!empty($s_id)) ? dbGetTranOne($s_id) : '';

if(empty($tranInfo)){
    //募集取引情報が取得できなかった場合は、パラメータ改竄されている可能性があるため、マイページに遷移
    debug('取得したパラメータで募集取引情報が取得できませんでした。');
    //header('Location:mypage.php');
} else {
    debug('シェア情報取得完了');
    $url = 'https://tanutore.qumo-y2.com/itemRegDetail.php?s_id='.sanitize($s_id);
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
    <meta name="twitter:card" content="summary">
    <title>たぬトレ</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/d5e07a31ed.js" crossorigin="anonymous"></script>
</head>

<body id="signupBackImg">
    <header id="header">
        <div id="top-content">
            <a href="index.php">
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
        <button class="backbtn-Area">
            <a href="index.php">一覧に戻る</a>
        </button>
        <div class="page-title">
                <h1>募集登録完了</h1>
        </div>
        <div class="pageMsg">
            <p>募集情報の登録が完了しました。<br>シェアしてみんなに知らせましょう！</p>
            <a class="twitter-share-button" data-size="large" href="http://twitter.com/share?&text=たぬトレでアイテム交換募集を登録しました！%0a出)<?php echo sanitize($tranInfo['ex_item_name']);?>%0a求)<?php echo sanitize($tranInfo['want_item_name']);?>%0a#たぬトレ%0a#あつまれどうぶつの森%0a#あつ森%0a&url=<?php echo urlencode($url);?>" target="_blank">ツイート</a>
        </div>
    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;&#65039; 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
    <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    </body>
</html>