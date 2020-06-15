<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「「「「');
debug('マイページ画面');
debug('「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

debug('セッション変数の中身：' . print_r($_SESSION, true));

require('auth.php');
$viewChatFlg = 0;
$user = dbGetUserInfo($_SESSION['u_id']);
$user_chat = getUserChat($_SESSION['u_id']);
$livechat = getliveChat($_SESSION['u_id']);
$user_eval = dbGetUserEval($_SESSION['u_id']);

debug('生きてるユーザチャット情報：' . print_r($livechat, true));
debug('ユーザチャット情報：' . print_r($user_chat, true));
debug('ユーザ評価情報：' . print_r($user_eval, true));

$userInfo = $user['u_info'];
$u_syuppin = $user['s_info'];

try {
    // db接続
    $dbh = dbConnect();
    $sql = 'SELECT chat_id, buyer_id, seller_id, i.item_name FROM chat AS c 
                LEFT OUTER JOIN syuppin AS s ON c.syuppin_id = s.syuppin_id
                LEFT OUTER JOIN item AS i ON s.ex_item_id = i.item_id
                WHERE seller_id = :u_id AND delFlg=0';
    $data = array(':u_id' => $_SESSION['u_id']);
    $stmt = queryPost($dbh, $sql, $data);

    $sql = 'SELECT chat_id, buyer_id, seller_id, i.item_name FROM chat AS c 
                LEFT OUTER JOIN syuppin AS s ON c.syuppin_id = s.syuppin_id
                LEFT OUTER JOIN item AS i ON s.ex_item_id = i.item_id
                WHERE buyer_id = :u_id AND delFlg=0';
    $data = array(':u_id' => $_SESSION['u_id']);
    $stmt2 = queryPost($dbh, $sql, $data);

    if ($stmt && $stmt2) {
        //全レコード取得
        $msgInfo['buyer'] = $stmt2->fetchAll();
        $msgInfo['seller'] = $stmt->fetchAll();
    }
} catch (Exception $e) {
    $err_msg['common'] = MSG07;
}
debug('seller' . print_r($msgInfo['seller'], true));
debug('buyer' . print_r($msgInfo['buyer'], true));


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
            <a href="itemlist.php">
                <div class="top-logo">
                    <img src="img/icon.png" alt="">
                </div>
                <div class="top-title">
                    <h1>たぬトレ</h1>
                </div>
            </a>
        </div>
    </header>
    <p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('success'); ?>
    </p>
    <main>
	<div class="all">
        <button class="backbtn-Area btn-left">
                <a href="itemlist.php">一覧に戻る</a>
        </button>
        <div class="edit">
            <a href="editmypage.php">編集</a>
        </div>
        <div class="user-Area">
            <div class="user__icon">
                <img src="<?php echo $userInfo['icon']; ?>" alt="">
            </div>
            <div class="user__info">
                <div class="user__name">
                    <?php echo $userInfo['nickname']; ?>
                </div>
                <div class="user__eval">
                    <p id="star_eval"></p>
                    <h6>取引回数：<?php echo $user_eval['count'];?></h6>
                </div>
            </div>
        </div>

        <div class="myComment">
            <div class="regItemTitle">コメント</div>
            <div class="mycommentArea"><?php echo $userInfo['comment']; ?></div>

            <div class="regItemTitle">出品家具一覧</div>
            <div class="itemArea">
                <?php if (count($u_syuppin) !== 0) { ?>
                        <?php foreach ($u_syuppin as $key => $syuppin) { ?>
                            <p class="itemname"><?php echo $syuppin['ex_item_name']; ?></p>
                            <p class="status">
                                <a href="itemRegDetail.php<?php echo '?s_id=' . $syuppin['syuppin_id']; ?>">編集する</a>
                            </p>
                        <?php } ?>
                <?php } else { ?>
                    <p class="itemname">出品中のアイテムはありません。</p>
                <?php } ?>
            </div>

            <div class="regItemTitle">取引中一覧</div>
            <div class="itemArea">
		<div class="syuppin">
                <?php if ($livechat['seller']['count(chat_id)'] != 0) { ?>
                    <?php foreach ($user_chat['seller_chat'] as $key => $val) { ?>
                        <?php if (empty($val['c_comptime']) && $val['delflg']==0) { ?>
                            <p class="itemname"><?php echo $val['ex_item_name']; ?></p>
                            <p class="status">
                                <?php if(empty($val['s_comptime'])){?>
                                    <a href="msg.php<?php echo '?c_id=' . $val['chat_id']; ?>">取引中</a>
                                <?php }else{?>
                                    <a href="msg.php<?php echo '?c_id=' . $val['chat_id']; ?>">評価待ち</a>
                                <?php }?>
                            </p>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <p class="itemname">取引中のアイテムはありません。</p>
                <?php } ?>
		</div>
            </div>

            <div class="regItemTitle">交渉中一覧</div>
            <div class="itemArea">
		<div class="syuppin">
                <?php if ($livechat['buyer']['count(chat_id)'] != 0) { ?>
                    <?php foreach ($user_chat['buyer_chat'] as $key => $val) { ?>
                        <?php if (empty($val['compflg']) && $val['delflg']==0) { ?>
                            <p class="itemname"><?php echo $val['ex_item_name']; ?></p>
                            <p class="status">
                                <?php if(empty($val['buyer_eval'])){?>
                                    <a href="msg.php<?php echo '?c_id=' . $val['chat_id']; ?>">取引中</a>
                                <?php }else{?>
                                    <a href="msg.php<?php echo '?c_id=' . $val['chat_id']; ?>">あなたの評価待ち</a>
                                <?php }?>
                            </p>
                        <?php } ?>
                    <?php } ?>
                <?php } else { ?>
                    <p class="itemname">取引中のアイテムはありません。</p>
                <?php } ?>
		</div>
            </div>

        </div>
        <div class="archive">
            <a href="archivepage.php">過去取引一覧へ</a>
        </div>
	</div>
    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;? 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script src="js/jquery.raty.js"></script>
    <script>
        $('#star_eval').raty({
            readOnly: true,
            precision: true,
            score: <?php echo $user_eval['eval'];?>
        });
    </script>
</body>

</html>