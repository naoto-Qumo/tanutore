<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「');
debug('出品詳細');
debug('「「「「「「「「「「「「「「「「');
debugLogStart();
debug('セッション変数の中身：'.print_r($_SESSION,true));
debug('get：'.print_r($_GET,true));
debug('POST：'.print_r($_POST,true));

// GETパラメータより出品IDを取得
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
$viewData = dbGetTranOne($s_id);
debug('取引：'.print_r($viewData,true));
if(empty($viewData)){
    header('Location:mypage.php');
}



$user_eval = dbGetUserEval($viewData['user_id']);



// この募集取引に関してすでにチャットページが生成されているかチェック
$chatChk = (!empty($_SESSION['u_id'])) ? getExistChat($_SESSION['u_id'], $s_id) : '';

debug('取引チャット存在チェック：'.print_r($chatChk, true));

if(!empty($_POST)){
    // ログイン認証
    require('auth.php');
    if(!empty($_POST['delFlg'])){
        $delFlg = $_POST['delFlg'];
    } else {
        $delFlg = false;
    }
    $u_id = $_SESSION['u_id'];
    if($delFlg){
        try{
            //DB接続
            $dbh = dbConnect();
            $sql = 'UPDATE syuppin SET delFlg=:delFlg, del_time=:date WHERE syuppin_id=:s_id AND user_id=:u_id';
            $data = array(':delFlg'=>1, ':s_id'=>$s_id, ':u_id'=>$u_id, ':date'=>date('Y-m-d H:i:s'));
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                debug('削除成功');
                $_SESSION['success'] = SUC02;
                header('Location:mypage.php');
            }   
        } catch (Exception $e) {
            $err_msg['common'] = MSG07;
        }
        
    } else {
        // チャット情報を作成
        debug('チャット情報挿入');
        try {
            $dbh = dbConnect();
            $sql = 'INSERT INTO chat (seller_id, buyer_id, syuppin_id) VALUE (:s_uid, :b_uid, :s_id)';
            $data = array(':s_uid'=>$viewData['user_id'], ':b_uid'=>$u_id, ':s_id'=>$s_id);
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                debug('チャットレコード挿入成功');
                header('Location:msg.php?c_id='.$dbh->lastInsertId());
            }
        } catch (Exception $e) {
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
    <!-- twitterカード-->
    <meta name="twitter:card" content="summary" />
    <meta property="og:url" content="https://tanutore.qumo-y2.com/itemRegDetail.php?s_id=<?php echo sanitize($s_id);?>">
    <meta property="og:title" content="たぬトレ | あつまれどうぶつの森 アイテム交換" />
    <meta property="og:description" content="あつ森アイテム物々交換募集サービス！">
    <meta property="og:image" content="http://qumo.php.xdomain.jp/img/tanutoreIcon.png" />
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
	<div class="wide">
        <button class="backbtn-Area">
            <?php if(empty($_SESSION['u_id']) || $viewData['user_id'] !== $_SESSION['u_id']){?>
                <a href="index.php">一覧に戻る</a>
            <?php } else {?>
                <a href="mypage.php">マイページに戻る</a>
            <?php } ?>
        </button>
        <div class="user-Area">
            <div class="user__icon">
                <img src="<?php echo $viewData['icon']?>" alt="">
            </div>
            <div class="user__info">
                <div class="user__name">
                    <a href=""><?php echo $viewData['nickname'];?></a>
                </div>
                <div class="user__eval">
                <div class="user__eval">
                    <p id="star_eval"></p>
                    <h6>取引回数：<?php echo $user_eval['count'];?></h6>
                </div>
                </div>
            </div>
        </div>
        <div class="item-Area">
            <div class="reg__Item__Detail">
                <div class="ItemTitle">出品アイテム</div>
                <div class="ItemImg"><img src="<?php echo $viewData['ex_item_img'];?>" alt=""></div>
                <div class="ItemName"><?php echo $viewData['ex_item_name'];?></div>
            </div>

            <div class="reg__Item__Detail">
                <div class="ItemTitle">欲しいアイテム</div>
                <div class="ItemImg"><img src="<?php echo $viewData['want_item_img'];?>" alt=""></div>
                <div class="ItemName"><?php echo $viewData['want_item_name'];?></div>
            </div>
        </div>
        <div class="detailComment">
            <div class="regItemTitle">コメント</div>
            <div class="commentArea"><?php echo $viewData['comment'];?></div>
        </div>
        <form action="" method="POST">
            <div class="btnArea">
                <?php if(!empty($chatChk)){?>
                    <a class="negobtn" href="msg.php<?php echo '?c_id='.$chatChk['chat_id']; ?>">取引中チャットページへ</a>
                <?php }elseif($viewData['user_id'] !== $_SESSION['u_id']){?>
                    <button class="negobtn" name="nego" value="1">交渉する</button>
                <?php } else {?>
                    <div class="editMenu">
                        <button class="negobtn delTran" name="delFlg" value="1">取りやめる</button>
                        <a class="negobtn" href="transactionReg.php<?php echo '?s_id='.$viewData['s_id'] ?>">編集する</a>
                    </div>
                <?php } ?>
            </div>
        </form>
	</div>
    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;&#65039; 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
    <script src="js/jquery.raty.js"></script>
    <script>
        $('#star_eval').raty({
            readOnly: true,
            precision: true,
            score: <?php
            if(isset($user_eval['eval'])){
               echo $user_eval['eval'];
            } else {
                echo '0';
            }?>
        });
    </script>
</body>

</html>