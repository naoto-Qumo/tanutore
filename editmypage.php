<?php

require('functions.php');
debug('「「「「「「「「「「「「「「「「「「「');
debug('マイページ編集画面');
debug('「「「「「「「「「「「「「「「「「「「');
debugLogStart();

//ログインチェック
require('auth.php');

//ユーザ情報取得
$user = dbGetUserInfo($_SESSION['u_id']);
debug(print_r($user['u_info'], true));
$userInfo = $user['u_info'];

//POST送信チェック
if (!empty($_POST)) {
    debug(print_r($_POST, true));
    //POSTの中身格納
    $name = $_POST['name'];
    $myComment = $_POST['myComment'];
    $u_id = $_SESSION['u_id'];

    debug('画像：'.print_r($_FILES, true));
    //画像をアップロードし、パスを格納
    $pic = ( !empty($_FILES['pic']['name']) ) ? uploadImg($_FILES['pic'],'pic') : '';
    //画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $pic = ( empty($pic) && !empty($userInfo['icon']) ) ? $userInfo['icon'] : $pic;

    //DB接続
    $dbh = dbConnect();
    $sql = 'UPDATE users SET nickname=:name, comment=:myComment, icon=:pic WHERE user_id=:u_id';
    $data = array(':name' => $name, 'myComment' => $myComment,':pic'=>$pic, ':u_id' => $u_id);

    //クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if ($stmt) {
        debug('ユーザ情報編集成功');
        $_SESSION['success'] = SUC01;
        header('Location:mypage.php');
    } else {
        $err_msg['common'] = MSG07;
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
    <script type="text/javascript">
        function disp() {

            // 「OK」時の処理開始 ＋ 確認ダイアログの表示
            if (window.confirm('完了しますか？')) {

                location.href = "mypage.php";

            }

            // 「キャンセル」時の処理開始
            else {

                window.alert('キャンセルされました');
            }
        }
    </script>
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
        <form action="" method="post" enctype="multipart/form-data">
            <button class="backbtn-Area">
                <a href="mypage.php">キャンセル</a>
            </button>

            <input type="submit" class="editcomplete" onclick="disp()" value="完了"></input>
            <div class="user-Area">

                <div class="user__icon">
                    <label class="editImg" for="selIcon">
                        <i class="fas fa-camera fa-2x"></i>
                        <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                        <input id="selIcon" name="pic" type="file" accept="image/*">
                        <img id="preview" src="<?php echo $userInfo['icon'];?>" alt="">
                    </label>
                </div>

                <div class="user__info">
                    <div class="user__name edit">
                        ニックネーム
                        <input type="text" name="name" value="<?php echo $userInfo['nickname']; ?>"></input>
                    </div>
                </div>
            </div>
            <div class="myComment">
                <div class="regItemTitle">コメント</div>
                <textarea name="myComment" id="" cols="" rows="5"><?php echo $userInfo['comment']; ?></textarea>
            </div>
        </form>

    </main>
    <footer id="footer">
        <div class="copyright">
            ©️ 2020 Qumo.inc
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
    <script src="js/Jquery.selmodal.js"></script>
    <script src="js/app.js"></script>
    <script src="js/itemMenu.js"></script>
</body>

</html>