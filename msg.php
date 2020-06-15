<?php
require('functions.php');
debug('「「「「「「「「「「「「「「「「「');
debug('チャット画面');
debug('「「「「「「「「「「「「「「「「「');

//ログイン認証
require('auth.php');

//変数初期化
$partnerUserId = '';
$partnerUserInfo = '';
$myUserInfo = '';

//GETパラメータ取得
$c_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
debug('取得したGETパラメータ：'.print_r($_GET,true));
// DBからチャットとメッセージデータを取得
$viewData = getMsgAndChat($c_id);
debug('取得したメッセージデータ：'.print_r($viewData,true));

//パラメータに不正な値が入っているかチェック
if(empty($viewData)){
    error_log('エラー発生：指定したページに不正な値が入りました。');
    header('Location:mypage.php');
}
$msgCount = getMsgCount($c_id);
debug('メッセージカウント：'.print_r($msgCount, true));
$chatInfo = getChat($c_id);
debug('チャット情報：'.print_r($chatInfo, true));
//取引情報取得
$sInfo = dbgetTranOne($viewData[0]['s_id']);
debug('取得した取引情報：'.print_r($sInfo,true));

if(empty($viewData)){
    error_log('エラー発生：商品情報が取得できませんでした。');
    header('Location:mypage.php');
}

// viewDataから相手のユーザIDを取り出す
$dealUserIDs[] = $viewData[0]['seller_id'];
$dealUserIDs[] = $viewData[0]['buyer_id'];
debug('$dealUserIDs：'.print_r($dealUserIDs,true));
if(($key = array_search($_SESSION['u_id'], $dealUserIDs)) !== false){
    unset($dealUserIDs[$key]);
}
$partnerUserId = array_shift($dealUserIDs);
debug('取得した相手ユーザID：'.$partnerUserId);
// 相手のユーザ情報を取得
if(isset($partnerUserId)){
    $partnerUserInfo = dbGetUserInfo($partnerUserId);
    debug('取得した相手のユーザデータ：'.print_r($partnerUserInfo,true));
}
// 相手のユーザ情報が取れたかチェック
if(empty($partnerUserInfo)){
    error_log('エラー発生：相手のユーザ情報を取得できませんでした。');
    header('Location:mypage.php');
}

// DBから自分のユーザ情報を取得
$myUserInfo = dbGetUserInfo($_SESSION['u_id']);
debug('取得した自分のユーザデータ：'.print_r($myUserInfo,true));
// 自分のユーザ情報が取れたかチェック
if(empty($myUserInfo)){
    error_log('エラー発生：自分のユーザ情報が取得できませんでした。');
    header('Location:mypage.php');
}

// POST送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。'.print_r($_POST,true));
    
    $msg = (isset($_POST['msg'])) ? $_POST['msg'] : '';
    $eval = (isset($_POST['evalVal'])) ? $_POST['evalVal'] : '';
    // バリデーションチェック
    // 未入力チェック
    validRequired($msg, 'msg');
    // 最大文字数チェック
    validMaxLen($msg, 'msg', 500);
    

    if(empty($err_msg)){
        debug('バリデーションOK');

        //例外
        try {
            // DBへ接続
            $dbh = dbConnect();
            $sql = 'INSERT INTO message (chat_id, sender_id, receiver_id, message) VALUES (:c_id, :my_id, :partner_id, :msg)';
            $data = array(':c_id'=>$c_id, ':partner_id'=>$partnerUserId, ':my_id'=>$myUserInfo['u_info']['user_id'], ':msg'=>$msg);
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                // POSTをクリア
                $_POST = array();
                debug('メッセージ登録成功');
                header('Location:'.$_SERVER['PHP_SELF'].'?c_id='.$c_id);
            } 
        } catch (Exception $e) {
            debug('メッセージ登録失敗'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }

    if(!empty($eval)){
        debug('評価をつけます');
        if($chatInfo[0]['seller_id'] == $myUserInfo['u_info']['user_id']){
            debug('buyerの評価をつけます。');
            try {
                // DBへ接続
                $dbh = dbConnect();
                $sql = 'UPDATE chat AS c
                        LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
                        SET c.buyer_eval=:eval
                        WHERE chat_id=:c_id';
                $data = array(':eval'=>$eval, ':c_id'=>$c_id);
                $stmt = queryPost($dbh, $sql, $data);

                $sql = 'UPDATE syuppin AS s
                        SET s.comptime=:now
                        WHERE syuppin_id=:s_id';
                $data = array(':now'=>date('Y-m-d H:i:s'), ':s_id'=>$chatInfo[0]['s_id']);
                $stmt2 = queryPost($dbh, $sql, $data);

                if($stmt && $stmt2){
                    // POSTをクリア
                    $_POST = array();
                    debug('購入者評価成功');
                    header('Location:mypage.php');
                } 
            } catch (Exception $e) {
                debug('メッセージ登録失敗'.$e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }else{
            debug('sellerの評価をつけます。');
            try {
                // DBへ接続
                $dbh = dbConnect();
                $sql = 'UPDATE chat AS c
                        LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
                        SET c.seller_eval=:eval
                        WHERE chat_id=:c_id';
                $data = array(':eval'=>$eval, ':c_id'=>$c_id);
                $stmt = queryPost($dbh, $sql, $data);

                $sql = 'UPDATE chat AS c
                        LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
                        SET c.compflg=1, c.comptime=:date
                        WHERE c.syuppin_id=:s_id';
                $data = array(':s_id'=>$chatInfo[0]['s_id'], ':date'=>date('Y-m-d H:i:s'));
                $stmt2 = queryPost($dbh, $sql, $data);

                if($stmt && $stmt2){
                    // POSTをクリア
                    $_POST = array();
                    debug('出品者評価成功');
                    header('Location:mypage.php');
                } 
            } catch (Exception $e) {
                debug('メッセージ登録失敗'.$e->getMessage());
                $err_msg['common'] = MSG07;
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
    <link rel="stylesheet" href="css/itemList.css">
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
    <main>
	<div class="all">
        <div class="top__btn__Area">
            <button class="backbtn-Area btn-left">
                <a href="mypage.php">マイページに戻る</a>
            </button>
            <?php if(empty($chatInfo[0]['compflg']) && empty($chatInfo[0]['s_comptime']) && ($chatInfo[0]['seller_id'] == $myUserInfo['u_info']['user_id'])) {?>
                <button class="trancomp-btn btn-right js-show-evalmodal">
                    取引完了
                </button>
            <?php }elseif(empty($chatInfo[0]['compflg']) && (($chatInfo[0]['buyer_id'] == $myUserInfo['u_info']['user_id']) && !empty($chatInfo[0]['buyer_eval']))){ ?>
                <button class="trancomp-btn btn-right js-show-evalmodal">
                    評価を送る
                </button>
            <?php } ?>
        </div>
        <div class="tranInfo__Area">
            <div class="userInfo">
                <div class="userIcon">
                    <img src="<?php echo $sInfo['icon'];?>" alt="">
                </div>
                <div class="userName">
                    <?php echo $sInfo['nickname'];?>
                </div>
            </div>
            <div class="tranInfo">
                <div class="itemInfo">
                    <span class="tranItem-color">出品アイテム</span>
                    <div class="itemName">
                        <?php echo sanitize($sInfo['ex_item_name']);?>
                    </div>
                </div>
                <div class="itemInfo">
                    <span class="wantItem-color">欲しいアイテム</span>
                    <div class="itemName">
                    <?php echo sanitize($sInfo['want_item_name']);?>
                    </div>
                </div>
            </div>
        </div>

        <div class="msg__Area">
            <?php if(!empty($viewData)){
                if($msgCount[0]['count(message)'] != 0){
                    foreach($viewData as $key => $val){
                        if(!empty($val['sender_id']) && $val['sender_id'] == $partnerUserId){ ?>

                            <div class="msg__contents msg-left">
                                <div class="msgUser">
                                    <img src="<?php echo sanitize($partnerUserInfo['u_info']['icon']);?>" alt="">
                                </div>
                                <div class="msg__wrap">
                                    <p class="msg__text">
                                        <span class="triangle"></span>
                                        <span class="hukidashi"><?php if(!empty($val['message'])) echo sanitize($val['message']);?></span>
                                    </p>
                                    <div class="send__time"><?php if(!empty($val['datetime'])) echo sanitize($val['datetime']);?></div>
                                </div>
                            </div>

                    <?php } else { ?>
                            <div class="msg__contents msg-right">
                                <p class="msg__text">
                                    <span class="triangle"></span>
                                    <span class="hukidashi"><?php if(!empty($val['message'])) echo sanitize($val['message']);?></span>
                                </p>
                                <div class="send__time"><?php if(!empty($val['datetime'])) echo sanitize($val['datetime']);?></div>
                            </div>
                    <?php }
                    }
                } else {
                    echo 'まだメッセージはありません。';
                } ?>
        <?php    }?>
        </div>
        <?php if(empty($chatInfo[0]['s_comptime'])){?>
            <div class="msgSend__Area">
                <form action="" method="POST">
                    <textarea name="msg" id="" placeholder="メッセージを入力" cols="50" rows="10"></textarea>
                    <input type="submit" value="送信">
                </form>
            </div>
        <?php } ?>
        <div class="modal__cover js-cover-modal"></div>
        <div class="eval__modal js-evalmodal">
            <div class="eval__modal__wrap">
                <div class="modalTitle">
                    <?php if($chatInfo[0]['seller_id'] == $myUserInfo['u_info']['user_id']) {?>
                        <h3>このユーザはどうでしたか？</h3>
                        <h6 class="modalSubTitle">評価を送って取引を完了しましょう！</br>この出品に関して他のユーザとも取引をしていた場合は、</br>すべての取引が中止されます。</h6>
                    <?php }else{ ?>
                        <h3>このユーザはどうでしたか？</h3>
                        <h6 class="modalSubTitle">評価を送って取引を完了しましょう！</h6>
                    <?php } ?>
                </div>
                <div class="evalSel">
                    <form class="eval-form__Area" action="" method="post">
                        <div class="evalbtn__wrap">
                            <label for="eval0">
                                <input type="radio" class="evalbtn" id="eval0" name="evalVal" value="1">
                                <img class="radio-img" src="img/eval0.png" alt="">
                                <p>悪かった</p>
                            </label>
                            <label for="eval3">
                                <input type="radio" class="evalbtn" id="eval3" name="evalVal" value="3">
                                <img class="radio-img" src="img/eval3.png" alt="">
                                <p>普通</p>
                            </label>
                            <label for="eval5">
                                <input type="radio" class="evalbtn" id="eval5" name="evalVal" value="5">
                                <img class="radio-img" src="img/eval5.png" alt="">
                                <p>良かった</p>
                            </label>
                        </div>
                        <input class="tranCompBtn" type="submit" value="取引完了">
                    </form>
                </div>
            </div>
        </div>
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
</body>

</html>