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

if(!empty($_POST)){
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
    <main>
        <button class="backbtn-Area">
            <?php if($viewData['user_id'] !== $_SESSION['u_id']){?>
                <a href="itemlist.php">一覧に戻る</a>
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
                    <div class="star__wrap">
                        <span class="rate rate3-5"></span>
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
                <?php if($viewData['user_id'] !== $_SESSION['u_id']){?>
                    <button class="negobtn" name="nego" value="1">交渉する</button>
                <?php } else {?>
                    <div class="editMenu">
                        <button class="negobtn delTran" name="delFlg" value="1">取りやめる</button>
                        <a class="negobtn" href="transactionReg.php<?php echo '?s_id='.$viewData['s_id'] ?>">編集する</a>
                    </div>
                <?php } ?>
            </div>
        </form>
    </main>
    <footer id="footer">
        <div class="copyright">
            ©️ 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js"
        integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/Jquery.selmodal.js"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
</body>

</html>