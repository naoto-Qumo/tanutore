<?php

require('functions.php');

debug('「「「「「「「「「「「「「「「「「「「');
debug('アーカイブ画面');
debug('「「「「「「「「「「「「「「「「「「「');

// ログイン認証
require('auth.php');
$u_id = $_SESSION['u_id'];

// 完了した取引情報の取得
try {
    //DB接続
    $dbh = dbConnect();
    $sql = 'SELECT chat_id, seller_id, buyer_id, s.syuppin_id AS s_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime, seller_eval, buyer_eval, c.comptime AS c_comptime, s.comptime AS s_comptime, compflg, delflg FROM chat AS c
    LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
    LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
    LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
    WHERE seller_id=:u_id AND compflg=:compflg';
    $data = array(':u_id'=>$u_id, ':compflg'=>1);
    $stmt = queryPost($dbh, $sql, $data);

    $sql = 'SELECT chat_id, seller_id, buyer_id, s.syuppin_id AS s_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime, seller_eval, buyer_eval, c.comptime AS c_comptime, s.comptime AS s_comptime, compflg, delflg FROM chat AS c
    LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
    LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
    LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
    WHERE buyer_id=:u_id AND compflg=:compflg';
    $data = array(':u_id'=>$u_id, ':compflg'=>1);
    $stmt2 = queryPost($dbh, $sql, $data);

    if($stmt && $stmt2){
        debug('アーカイブ情報取得成功');
        $archive['seller'] = $stmt->fetchAll();
        $archive['buyer'] = $stmt2->fetchAll();
    }
} catch (Exception $e) {
    error_log('エラー発生：'.$e->getMessage());
    $err_msg['common'] = MSG07;
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
	<div class="all">
        <button class="backbtn-Area">
            <a href="mypage.php">マイページに戻る</a>
        </button>
        <div class="regItemTitle">過去取引一覧</div>
        <div class="archiveArea">
            <h3>出品した取引完了履歴</h3>
            <?php foreach($archive['seller'] as $key => $val){?>
                <p class="itemname"><?php echo $val['ex_item_name'];?></p>
                <p class="status"><?php echo $val['c_comptime'];?> 完了</p>
            <?php }?>
        </div>
        <div class="archiveArea">
            <h3>交渉した取引完了履歴</h3>
            <?php foreach($archive['buyer'] as $key => $val){?>
                <p class="itemname"><?php echo $val['ex_item_name'];?></p>
                <p class="status"><?php echo $val['c_comptime'];?> 完了</p>
            <?php }?>
        </div>
	</div>
    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;? 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/Jquery.selmodal.js"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
</body>

</html>