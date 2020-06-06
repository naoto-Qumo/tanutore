<?php 
    require('functions.php');
    debug('「「「「「「「「「「「「「「「「「「「「');
    debug('出品一覧画面');
    debug('「「「「「「「「「「「「「「「「「「「「');
    debugLogStart();
    debug('セッション変数の中身：'.print_r($_SESSION,true));

    $i_id = (!empty($_GET['i_id'])) ? $_GET['i_id'] : '';

    if(empty($i_id)){
        // 全出品情報取得
        $tranAll = dbGetTranList();
        debug('アイテム：'.print_r($itemList,true));
        if($tranAll){
            debug('出品一覧取得成功');
            debug(print_r($tranAll,true));
        } else {
            $err_msg['common'] = MSG07;
        }
    } else {
        // 指定された情報のみ取得
        $tranAll = dbSerchItemList($i_id);
        $itemInfo = dbGetItem($i_id);
        if($tranAll && $itemInfo){
            debug('検索一覧取得成功');
            debug(print_r($tranAll,true));
            debug('検索されたアイテム情報');
            debug(print_r($itemInfo,true));
        } else {
            $err_msg['common'] = MSG07;
        }
    }

    // アイテム情報取得
    $itemList = dbGetItemList();
    debug('アイテムリスト：'.print_r($itemList,true));
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" name="viewport" content="width=device-width">
        <title>たぬトレ</title>
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/style.css">
        <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/itemList.css">
        <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    </head>
    
    <body>
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
            <p id="js-show-msg" style="display:none;" class="msg-slide">
                <?php echo getSessionFlash('success'); ?>
            </p>
            <div class="listpage-title">
            	<h1>TOPページ</h1>
            	<a href="mypage.php">
            	<i class="fas fa-home fa-3x"></i>
            	</a>
            </div>
            <div class="serch-result__Area">
                <p class="serch-bar">検索：<?php 
                    if(!empty($i_id)){ 
                        echo $itemInfo[0]['item_name'];
                    }else {
                        echo '全アイテム';
                    }?>
                <?php echo count($tranAll)?>件</p>
                <button class="editbtn">
                    <a href="itemlist.php">検索解除</a>
                </button>
            </div>
            <form action="" class="form-Area">
                <div class="itemmain">
                    <?php foreach($tranAll as $key => $val){?>
        			<div class="sub">
                        <a class="list" href="itemRegDetail.php?<?php echo 's_id='.$val['s_id']?>">
                            <img src="<?php if(!empty($val['ex_item_img'])){echo $val['ex_item_img'];}else{ echo 'img/noimage2.png';}?>" alt="" class="listimg">
            			    <h2 class="title">出：<?php if(!empty($val['ex_item_name'])) echo $val['ex_item_name'];?></h2>
            			    <h2 class="title">求：<?php if(!empty($val['want_item_name'])) echo $val['want_item_name'];?></h2>
                            <h2 class="title">出品者：<?php if(!empty($val['nickname'])) echo $val['nickname'];?></h2>
                        </a>
                    </div>
                    <?php }?>                        
        		</div>	
            </form>
        <div class="botan">
        	 <button class="js-serch-btn serch">検索する</button>
        	 <a href="transactionReg.php" class="touroku">出品する</a>
        </div>

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
                                                        <a href="itemList.php?i_id=<?php echo $item['item_id']; ?>">
                                                            <li class="subMenu__item js-item-click" value="<?php echo $item['item_id']; ?>">
                                                                <?php echo $item['item_name']; ?>
                                                                <div class="arrow-parts">
                                                                    <span><i class="fas fa-angle-right"></i></span>
                                                                </div>
                                                            </li>
                                                        </a>
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

        <footer id="footer">
           <div class="copyright">
                ©️ 2020 Qumo.inc
           </div>
        </footer>

        </main>
        <script
  src="https://code.jquery.com/jquery-3.5.0.min.js"
  integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ="
  crossorigin="anonymous"></script>
        <script src="js/app.js"></script>
        <script src="js/itemMenu.js"></script>
    </body>
</html>