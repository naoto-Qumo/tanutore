<?php 
    require('functions.php');
    debug('「「「「「「「「「「「「「「「「「「「「');
    debug('出品一覧画面');
    debug('「「「「「「「「「「「「「「「「「「「「');
    debugLogStart();
    debug('セッション変数の中身：'.print_r($_SESSION,true));
    
    $tranAll = dbGetTranList();
    
    if($tranAll){
        debug('出品一覧取得成功');
        debug(print_r($tranAll,true));
    } else {
        $err_msg['common'] = MSG07;
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
        <link rel="styleshee" href="css/selmodal.css">
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
            <div class="listpage-title">
            	<h1>TOPページ</h1>
            	<a href="mypage.php">
            	<i class="fas fa-home fa-3x"></i>
            	</a>
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
        	 <a href="transactionReg.php" class="serch">検索する</a>
        	 <a href="transactionReg.php" class="touroku">出品する</a>
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
        <script src="js/Jquery.selmodal.js"></script>
        <script src="js/app.js"></script>
        <script type="text/javascript">
            $(function(){
                $('select').selModal();
            });
        </script>
    </body>
</html>