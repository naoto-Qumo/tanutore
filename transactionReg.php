<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「「「「「');
debug('取引登録画面');
debug('「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();
debug('セッション変数の中身：'.print_r($_SESSION,true));

// ログイン認証
require('auth.php');
$u_id = $_SESSION['u_id'];
//GETパラメータ取得
$s_id = (!empty($_GET['s_id'])) ? $_GET['s_id'] : '';
debug('$u_id：'.print_r($u_id,true));
debug('$s_id：'.print_r($s_id,true));

//取引情報取得
$tranData = (!empty($s_id)) ? dbGetTranOneU($s_id,$u_id) : '';
$edit_flg = (!empty($tranData)) ? true : false;
debug('$tranData：'.print_r($tranData,true));
debug('$tranData：'.print_r($edit_flg,true));

//パラメータ改竄チェック
//GETパラメータはあるが、取引情報が取れない場合はマイページに遷移させる
if(!empty($s_id) && empty($tranData)){
    debug('GETパラメータの取引IDが違います。マイページへ遷移します。');
    header('Location:mypage.php');
}

// アイテム情報取得
$itemList = dbGetItemList();
debug('アイテム：'.print_r($itemList,true));
//POST送信チェック
if(!empty($_POST)){
    $exibit = $_POST['exhibitionItem'];
    $want = $_POST['wantItem'];
    $comment = $_POST['regComment'];
    //バリデーション
    debug('POSTチェック');
    debug(print_r($exibit,true));
    debug(print_r($want,true));
    debug(print_r($comment,true));

    

    if($comment === ''){
        debug('コメントなし');
        $comment = 'コメントはありません。';
    }

    if(empty($tranData)){
        //新規登録バリデーション
        //アイテム選択チェック
        debug('新規バリデーション');
        validSelectbox($exibit, 'exibit');
        validSelectbox($want, 'want');
        validMaxLen($comment, 'comment');
    } else {
        //更新の場合は内容が違ったらバリデーション
        debug('更新バリデーション');
        if($comment !== $tranData['comment']){
            validMaxLen($comment, 'comment');
        }
    }
    debug('$err_msg'.print_r($err_msg,true));
    if(empty($err_msg)){
        debug('バリデーションOK');
        try {
            // DB接続
            $dbh = dbConnect();
            if($edit_flg){
                //更新
                debug('更新');
                $sql = 'UPDATE syuppin SET ex_item_id=:ex_item_id, want_item_id=:want_item_id, comment=:comment, regtime=:regtime WHERE syuppin_id=:s_id AND user_id=:u_id';
                $data = array(':ex_item_id'=>$exibit, ':want_item_id'=>$want, 
                                ':comment'=>$comment, ':regtime'=>date('Y-m-d H:i:s'),
                                's_id'=>$s_id, 'u_id'=>$u_id);
                $msg = SUC03;
            }else{
                //新規登録
                debug('新規');
                $sql = 'INSERT INTO syuppin (user_id, ex_item_id, want_item_id, comment, regtime) VALUES (:user_id, :ex_item_id, :want_item_id, :comment, :regtime)';
                $data = array(':user_id'=>$u_id, ':ex_item_id'=>$exibit, 
                            ':want_item_id'=>$want, ':comment'=>$comment, ':regtime'=>date('Y-m-d H:i:s'));
                $msg = SUC04;
            }
            
            // クエリ実行
            $stmt = queryPost($dbh, $sql, $data);
            if($stmt){
                if(!$edit_flg){
                    // クエリ成功の場合
                    debug('新規登録です');
                    $_SESSION['s_id'] = $dbh->lastInsertId();
                    header('Location:regComp.php');
                }else{
                    // クエリ成功の場合
                    debug('一覧ページへ遷移します。');
                    $_SESSION['success'] = $msg;
                    header('Location:regComp.php');
                }
            }
        } catch (Exception $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}


debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

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
    <link rel="stylesheet" href="css/itemList.css">
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
        <button class="backbtn-Area">
            <a href="itemlist.php">一覧に戻る</a>
        </button>
        
        <div class="page-title">
            <h1>交換募集登録</h1>
        </div>
        
        <form class="form-Area" action="" method="POST">
            <div class="regItemArea ">
                <div class="regItemTitle">出品するアイテム</div>
                <div class="selModalButton js-show-modal">
                    <?php if(!empty($tranData['ex_item_name'])){
                        echo $tranData['ex_item_name'];
                    } else {
                        echo '選択してください';
                    } ?>
                </div>
                <select class="js-Ajax-itemImg itemSelect" name="exhibitionItem" id="">
                    <option value="<?php if(!empty($tranData['ex_item_id'])){ echo $tranData['ex_item_id'];}else{echo '0';}?>" class="js-transItem"></option>
                </select>
                <div class="itemImg">
                    <img class="ajaxItemImg" src="<?php if(!empty($tranData['ex_item_img'])){ echo $tranData['ex_item_img'];}else{ echo 'img/noimage2.png';}?>" alt="regItem">
                </div>
            </div>

            <div class="regItemArea lastArea">
                <div class="regItemTitle">欲しいアイテム</div>
                <div class="selModalButton js-show-modal">
                    <?php if(!empty($tranData['ex_item_name'])){
                            echo $tranData['ex_item_name'];
                        } else {
                            echo '選択してください';
                        } ?>
                </div>
                <select class="js-Ajax-itemImg itemSelect" name="wantItem" id="">
                    <option value="<?php if(!empty($tranData['want_item_id'])){ echo $tranData['want_item_id'];}else{echo '0';}?>" class="js-transItem"></option>
                </select>
                <div class="itemImg">
                    <img class="ajaxItemImg" src="<?php if(!empty($tranData['want_item_img'])){ echo $tranData['want_item_img'];}else{ echo 'img/noimage2.png';}?>" alt="regItem">
                </div>
            </div>
            <div class="regComment">
                <div class="regItemTitle">コメント</div>
                <textarea name="regComment" id="" cols="30" rows="10" placeholder="コメントを入力"><?php if(!empty($tranData['comment'])){ echo $tranData['comment']; }?></textarea>
            </div>
            <input type="submit" value="登録する">
        </form>

        <div class="modal__cover js-cover-modal">
            <div class="selmodal js-selmodal">
                <ul class="itemList">
                    <li class="category__list top-li">
                        <div class="close-parts js-close-modal"><span></span></div>
                    </li>
                    <li class="category__list">
                        <?php foreach ($itemList['majorDiv'] as $key => $majorDiv) { ?>
                            <div class="menu__category__link js-menu__category__link" href="">
                                <?php echo $majorDiv['major_div']; ?>
                                <div class="plus-parts js-open-mark"><span></span></div>
                            </div>
                            <ul class="subMenu">

                                <?php foreach ($itemList['category'] as $key => $category) { ?>
                                    <?php if ($majorDiv['major_id'] === $category['major_id']) { ?>
                                        <li class="subCategory">
                                            <div class="menu__category__link subMenu__list js-menu__category__link" href="">
                                                <?php echo $category['mid_div']; ?>
                                                <div class="plus-parts js-open-mark"><span></span></div>
                                            </div>
                                            <ul class="subMenu">
                                                <?php foreach ($itemList['list'] as $key => $item) { ?>
                                                    <?php if ($category['mid_id'] === $item['mid_id']) { ?>
                                                        <li class="subMenu__item js-item-click" value="<?php echo $item['item_id']; ?>">
                                                            <?php echo $item['item_name']; ?>
                                                            <div class="arrow-parts">
                                                                <span><i class="fas fa-angle-right"></i></span>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>

    </main>
    <footer id="footer">
        <div class="copyright">
            &#169;&#65039; 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/Jquery.selmodal.js"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
</body>

</html>