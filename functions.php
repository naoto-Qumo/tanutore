<?php
// ======================
// ログ取得
// ======================
//ログを取るか
ini_set('log_errors', 'on');
//ログの出力ファイルを指定
ini_set('error_log', 'php.log');

//================================
// デバッグ
//================================
//デバッグフラグ
$debug_flg = false;
//デバッグ関数
function debug($str)
{
  global $debug_flg;
  if (!empty($debug_flg)) {
    error_log('デバッグ：' . $str);
  }
}

// ===============================
// セッション準備
// ===============================
// セッションファイルの置き場所を変更する
//session_save_path('/var/tmp');
// ガーベージコレクションが削除するセッションの有効期限を設定(30日経っているものだけ1/100の確率で削除)
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
// ブラウザを閉じても削除されないようにCookie自体の有効期限を延ばす
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);
// セッションを使う
session_start();
// 現在のセッションIDを新しく生成したものと置き換える(なりすましのセキュリティ対策)
session_regenerate_id();

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart()
{
  debug('>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
  debug('セッションID:' . session_id());
  debug('現在日時タイムスタンプ：' . time());
  if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
    debug('ログイン期限日時タイムスタンプ：' . ($_SESSION['login_date'] + $_SESSION['login_limit']));
  }
}

// ================================
// 定数
// ================================
define('MSG01', '入力必須です。');
define('MSG02', 'メールアドレスの形式で入力してください。');
define('MSG03', '半角英数字のみご利用いただけます。また半角英数字と数字を1文字以上含めてください。');
define('MSG04', '255文字以内で入力してください。');
define('MSG05', '7文字以上で入力してください。');
define('MSG06', 'パスワード(再入力)が一致しません。');
define('MSG07', 'エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのEmailはすでに登録されています。');
define('MSG09', 'メールアドレスまたはパスワードが違います。');
define('MSG10', '選択必須です。');
define('MSG11', '文字で入力してください');
define('MSG12', '半角で入力してください');
define('MSG13', '正しくありません。');
define('MSG14', '有効期限が切れています。');
define('MSG15', '半角で入力してください。');
define('MSG16', '現在のパスワードが違います。');
define('MSG17', '現在のパスワードと同じです。');
define('SUC01', 'ユーザ情報を更新しました。');
define('SUC02', '出品を取り消しました。');
define('SUC03', '出品情報を更新しました。');
define('SUC04', '出品情報を新規登録しました。');
define('SUC05', 'メールを送信しました。');
define('SUC06', 'パスワード再発行メールを送信しました。');
define('SUC07', 'パスワードが更新されました。');
// ================================
// グローバル関数
// ================================
// エラーメッセージ格納用の配列
$err_msg = array();

// ================================
// バリデーション関数
// ================================

// 未入力チェック
function validRequired($str, $key)
{
  if ($str === '') {
    global $err_msg;
    $err_msg[$key] = MSG01;
  }
}
// メールアドレス形式チェック
function validEmail($str, $key)
{
  if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $str)) {
    global $err_msg;
    $err_msg[$key] = MSG02;
  }
}
// Email重複チェック
function validEmailDup($email)
{
  global $err_msg;

  try {
    // DB接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT count(*) FROM users WHERE mail = :email';
    $data = array(':email' => $email);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    //クエリ結果の値を取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //array_shift関数は配列の先頭を取り出す関数。クエリ結果は配列形式で入っているため、array_shiftで１つ目だけ取り出して判定する。
    if (!empty(array_shift($result))) {
      $err_msg['email'] = MSG08;
    }
  } catch (Exception $e) {
    error_log('エラー発生：' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}
// 英数字チェック
function validCharNum($str, $key)
{
  if (!preg_match('/^(?=.*?[a-z])(?=.*?\d)[a-zA-Z\d]+/i', $str)) {
    global $err_msg;
    $err_msg[$key] = MSG03;
  }
}
// 最大文字数チェック
function validMaxLen($str, $key, $MAX_LEN = 255)
{
  if (mb_strlen($str) > $MAX_LEN) {
    global $err_msg;
    $err_msg[$key] = MSG04;
  }
}
// 最小文字数チェック
function validMinLen($str, $key, $MIN_LEN = 8)
{
  if (mb_strlen($str) < $MIN_LEN) {
    global $err_msg;
    $err_msg[$key] = MSG05;
  }
}
// 同値チェック
function validMatch($str1, $str2, $key)
{
  if ($str1 !== $str2) {
    global $err_msg;
    $err_msg[$key] = MSG06;
  }
}
// セレクトボックスチェック
function validSelectbox($str, $key)
{
  if ($str == 0) {
    global $err_msg;
    $err_msg[$key] = MSG10;
  }
}
// 固定長チェック
function validLength($str, $key, $length = 8){
  if(mb_strlen($str) !== $length){
    global $err_msg;
    $err_msg[$key] = $length.MSG11;
  }
}
// 半角チェック
function validHalf($str, $key)
{
  if (!preg_match('/^[a-zA-Z0-9]+$/', $str)) {
    global $err_msg;
    $err_msg[$key] = MSG15;
  }
}
// パスワードチェック
function validPass($str, $key){
  validCharNum($str, $key);
  validMinLen($str, $key);
  validMaxLen($str, $key);
}
// =============================
// データベース
// =============================
// DB接続関数
function dbConnect()
{
  // DBへの接続準備
  $dsn = 'mysql:dbname=xs094943_tanutore;host=mysql10044.xserver.jp;charset=utf8';
  $user = 'xs094943_qumo';
  $password = 'tqaunmuotore';
  $options = array(
    // SQL実行失敗時にはエラ&#12316;コードのみ設定
    PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT,
    // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // バッファクエリを使う(一度に結果セットをすべて取得し、サーバ負荷を軽減)
    // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
  // PDOオブジェクト生成(DBへ接続)
  $dbh = new PDO($dsn, $user, $password, $options);
  return $dbh;
}
// SQL実行関数
function queryPost($dbh, $sql, $data)
{
  // クエリー作成
  $stmt = $dbh->prepare($sql);
  // プレースホルダに値をセットし、SQL文を実行
  if (!$stmt->execute($data)) {
    debug('クエリに失敗しました。');
    debug('失敗したSQL：' . print_r($stmt, true));
    $err_msg['common'] = MSG07;
    return 0;
  }
  debug('クエリ成功。');
  return $stmt;
}
// ログイン時間更新
function dbUpdateLogin($u_id)
{
  // DB接続
  $dbh = dbConnect();
  $sql = 'UPDATE users SET login_tim=:login_time';
  $data = array(':login_time' => date('Y-m-d H:i:s'));
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $itemList['majorDiv'] = $stmt->fetchAll();
  } else {
    return false;
  }
}

// カテゴリ情報取得
function dbGetItemList()
{
  // DB接続
  $dbh = dbConnect();
  $sql = 'SELECT * FROM majorDiv';
  $data = array();
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $itemList['majorDiv'] = $stmt->fetchAll();
  } else {
    return false;
  }

  $sql = 'SELECT * FROM category RIGHT OUTER JOIN majorDiv AS major ON category.major_id = major.major_id RIGHT OUTER JOIN midDiv AS mid ON category.mid_id = mid.mid_id';
  $data = array();
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $itemList['category'] = $stmt->fetchAll();
  } else {
    return false;
  }

  $sql = 'SELECT * FROM item INNER JOIN majorDiv AS major ON item.major_id = major.major_id INNER JOIN midDiv AS mid ON item.mid_id = mid.mid_id';
  // クエリ実行
  $data = array();
  $stmt = queryPost($dbh, $sql, $data);
  if ($stmt) {
    $itemList['list'] = $stmt->fetchAll();
    return $itemList;
  } else {
    return false;
  }
}
// 出品情報取得
function dbGetTranList()
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT syuppin_id AS s_id,s.user_id,u.nickname,s.ex_item_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime FROM syuppin AS s 
          LEFT OUTER JOIN users AS u ON s.user_id = u.user_id
          LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
          LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
          WHERE delFlg=0 AND comptime IS NULL
          ORDER BY regtime DESC';
  $data = array();
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $tranAll = $stmt->fetchAll();
    return $tranAll;
  } else {
    return false;
  }
}
// 検索情報取得
function dbSerchItemList($i_id)
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT syuppin_id AS s_id,s.user_id,u.nickname,s.ex_item_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime FROM syuppin AS s 
          LEFT OUTER JOIN users AS u ON s.user_id = u.user_id
          LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
          LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
          WHERE s.ex_item_id=:i_id AND delFlg=0 AND comptime IS NULL
          ORDER BY regtime DESC';
  $data = array(':i_id'=>$i_id);
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $tranAll = $stmt->fetchAll();
    return $tranAll;
  } else {
    return false;
  }
}
function dbGetItem($i_id)
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT item_id, item_name FROM item
          WHERE item_id=:i_id';
  $data = array(':i_id'=>$i_id);
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $tranAll = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tranAll;
  } else {
    return false;
  }
}
// 取引情報取得
function dbGetTranOne($s_id)
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT syuppin_id AS s_id,s.user_id,u.nickname,u.icon,s.ex_item_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime FROM syuppin AS s 
          LEFT OUTER JOIN users AS u ON s.user_id = u.user_id
          LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
          LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
          WHERE syuppin_id=:s_id
          ORDER BY regtime DESC';
  $data = array(':s_id' => $s_id);
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    //1レコード返却
    $tranAll = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tranAll;
  } else {
    return false;
  }
}
function dbGetTranOneU($s_id, $u_id)
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT syuppin_id AS s_id,s.user_id,u.nickname,u.icon,s.ex_item_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime FROM syuppin AS s 
          LEFT OUTER JOIN users AS u ON s.user_id = u.user_id
          LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
          LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
          WHERE syuppin_id=:s_id AND s.user_id=:u_id
          ORDER BY regtime DESC';
  $data = array(':s_id' => $s_id, ':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);

  if ($stmt) {
    //1レコード返却
    $tranAll = $stmt->fetch(PDO::FETCH_ASSOC);
    return $tranAll;
  } else {
    return false;
  }
}
// ユーザ情報取得関数
function dbGetUserInfo($u_id)
{
  //DB接続
  //出品一覧取得
  $dbh = dbConnect();
  $sql = 'SELECT user_id, nickname, icon, user_evaluation, comment FROM users WHERE user_id = :u_id';
  $data = array(':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);

  $sql = 'SELECT syuppin_id, i.item_name AS ex_item_name, comptime FROM syuppin AS s 
          LEFT OUTER JOIN item AS i ON s.ex_item_id = i.item_id 
          WHERE user_id = :u_id AND comptime IS NULL AND delFlg=0';
  $data = array(':u_id' => $u_id);
  $stmt2 = queryPost($dbh, $sql, $data);

  if ($stmt && $stmt2) {
    //1レコード返却
    $user['u_info'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $user['s_info'] = $stmt2->fetchAll();
    return $user;
  } else {
    return false;
  }
}
// msg情報取得
function getMsgAndChat($c_id)
{
  debug('msg情報を取得');
  debug('チャットID：' . $c_id);
  // 例外
  // DB接続
  $dbh = dbConnect();
  // SQL
  $sql = 'SELECT c.chat_id AS c_id, seller_id, buyer_id, syuppin_id AS s_id, sender_id, receiver_id, message, datetime FROM message AS m
            RIGHT OUTER JOIN chat AS c ON m.chat_id=c.chat_id
            WHERE c.chat_id=:c_id
            ORDER BY datetime ASC';
  $data = array(':c_id' => $c_id);
  $stmt = queryPost($dbh, $sql, $data);
  if ($stmt) {
    $result = $stmt->fetchAll();
    return $result;
  } else {
    return false;
  }
}
function getChat($c_id)
{
  debug('チャット情報取得');
  debug('チャットID：' . $c_id);
  // 例外
  // DB接続
  $dbh = dbConnect();
  // SQL
  $sql = 'SELECT chat_id, seller_id, buyer_id, s.syuppin_id AS s_id, seller_eval, buyer_eval, c.comptime AS c_comptime, s.comptime AS s_comptime, compflg FROM chat AS c
            LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
            WHERE c.chat_id=:c_id';
  $data = array(':c_id' => $c_id);
  $stmt = queryPost($dbh, $sql, $data);
  if ($stmt) {
    $result = $stmt->fetchAll();
    return $result;
  } else {
    return false;
  }
}

function getUserChat($u_id)
{
  debug('ユーザごとの取引中チャット情報取得');
  debug('ユーザID：' . $u_id);
  // 例外
  // DB接続
  $dbh = dbConnect();
  // SQL
  $sql = 'SELECT chat_id, seller_id, buyer_id, s.syuppin_id AS s_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime, seller_eval, buyer_eval, c.comptime AS c_comptime, s.comptime AS s_comptime, compflg, delflg FROM chat AS c
            LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
            LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
            LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
            WHERE seller_id=:u_id';
  $data = array(':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);

  $sql = 'SELECT chat_id, seller_id, buyer_id, s.syuppin_id AS s_id, ex.item_name AS ex_item_name,ex.img AS ex_item_img ,s.want_item_id, want.item_name AS want_item_name, want.img AS want_item_img,s.comment,s.regtime, seller_eval, buyer_eval, c.comptime AS c_comptime, s.comptime AS s_comptime, compflg, delflg FROM chat AS c
            LEFT OUTER JOIN syuppin AS s ON c.syuppin_id=s.syuppin_id
            LEFT OUTER JOIN item AS ex ON s.ex_item_id = ex.item_id
            LEFT OUTER JOIN item AS want ON s.want_item_id = want.item_id
            WHERE buyer_id=:u_id';
  $data = array(':u_id' => $u_id);
  $stmt2 = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $result['seller_chat'] = $stmt->fetchAll();
    $result['buyer_chat'] = $stmt2->fetchAll();
    return $result;
  } else {
    return false;
  }
}
function getliveChat($u_id)
{
  // DB接続
  $dbh = dbConnect();
  // SQL
  $sql = 'SELECT count(chat_id) FROM chat AS c
          WHERE seller_id=:u_id AND compflg=0 AND comptime IS NULL';
  $data = array(':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);

  $sql = 'SELECT count(chat_id) FROM chat AS c
          WHERE buyer_id=:u_id AND compflg=0';

  $data = array(':u_id' => $u_id);
  $stmt2 = queryPost($dbh, $sql, $data);

  if ($stmt) {
    $result['seller'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $result['buyer'] = $stmt2->fetch(PDO::FETCH_ASSOC);
    return $result;
  } else {
    return false;
  }
}
// 募集登録に対してチャットページが存在するかチェック
function getExistChat($u_id, $s_id){
  // DB接続
  $dbh = dbConnect();
  // SQL
  $sql = 'SELECT chat_id FROM chat AS c
          WHERE buyer_id=:u_id AND syuppin_id=:s_id AND compflg=0 AND comptime IS NULL';
  $data = array(':u_id' => $u_id, ':s_id'=>$s_id);
  $stmt = queryPost($dbh, $sql, $data);
  if($stmt){
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return  $result;
  } else {
    return false;
  }

}
// メッセージ件数取得
function getMsgCount($c_id)
{
  $dbh = dbConnect();
  $sql = 'SELECT count(message) FROM message AS m
            RIGHT OUTER JOIN chat AS c ON m.chat_id=c.chat_id
            WHERE c.chat_id=:c_id
            ORDER BY datetime ASC';
  $data = array(':c_id' => $c_id);
  $stmt = queryPost($dbh, $sql, $data);
  if ($stmt) {
    $result = $stmt->fetchAll();
    return $result;
  } else {
    return false;
  }
}
// パスワード取得
function dbGetUserPass($u_id){
  $dbh = dbConnect();
  $sql = 'SELECT user_id, pass FROM users AS u
            WHERE user_id=:u_id';
  $data = array(':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);
  if ($stmt) {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
  } else {
    return false;
  }
}
// ユーザ評価情報を取得
function dbGetUserEval($u_id)
{
  $dbh = dbConnect();
  $sql = 'SELECT sum(seller_eval) AS s_eval, count(chat_id) AS count FROM chat WHERE seller_id=:u_id AND seller_eval IS NOT NULL';
  $sql2 = 'SELECT sum(buyer_eval) AS b_eval, count(chat_id) AS count FROM chat WHERE buyer_id=:u_id AND buyer_eval IS NOT NULL';
  $data = array(':u_id' => $u_id);
  $stmt = queryPost($dbh, $sql, $data);
  $stmt2 = queryPost($dbh, $sql2, $data);
  if ($stmt && $stmt2) {
    $result['seller_eval'] = $stmt->fetch(PDO::FETCH_ASSOC);
    $result['buyer_eval'] = $stmt2->fetch(PDO::FETCH_ASSOC);
    //評価の平均を求める
    $deno = ($result['seller_eval']['count'] + $result['buyer_eval']['count']);
    if((int)$deno !== 0){
      $result['eval'] =  ($result['seller_eval']['s_eval'] + $result['buyer_eval']['b_eval'])/$deno;
      $result['count'] = ($result['seller_eval']['count'] + $result['buyer_eval']['count']);
    } else {
      $result['eval'] =  0;
      $result['count'] = 0;
    }
    return $result;
    
  } else {
    return false;
  }
}

// =============================
// その他処理
// =============================

//sessionを１回だけ取得で
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }
}

// ランダムキー生成
function makeRandKey($length = 8){
  static $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
  $str = '';
  for ($i = 0; $i < $length; ++$i) {
      $str .= $chars[mt_rand(0, 35)];
  }
  return $str;
}

// メール送信
function sendMail($from, $to, $subject, $comment){
  if(!empty($from) && !empty($to) && !empty($subject) && !empty($comment)){
    // 文字化けしないように設定(お決まりのパターン)
    // 使っている言語の設定
    mb_language("Japanese");
    // 内部の言語をどうエンコーディングするか設定
    mb_internal_encoding("UTF-8");

    // メール送信(結果はTrue or False)
    $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
    // 送信結果チェック
    if($result){
      debug('メール送信に成功しました');
    } else {
      debug('メール送信に失敗しました');
    }
  }
}
// サニタイズ
function sanitize($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

// =============================
// 画像処理
// =============================
function uploadImg($file, $key)
{
  debug('画像アップロード処理開始');
  debug('FILE情報：' . print_r($file, true));

  if (isset($file['error']) && is_int($file['error'])) {
    try {
      // バリデーション
      // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
      //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
      switch ($file['error']) {
        case UPLOAD_ERR_OK: // OK
          break;
        case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
          throw new RuntimeException('ファイルが選択されていません');
        case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
        case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
          throw new RuntimeException('ファイルサイズが大きすぎます');
        default: // その他の場合
          throw new RuntimeException('その他のエラーが発生しました');
      }

      // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
      // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
      $type = @exif_imagetype($file['tmp_name']);
      if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
        throw new RuntimeException('画像形式が未対応です');
      }

      // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
      // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
      // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
      // image_type_to_extension関数はファイルの拡張子を取得するもの
      $path = 'uploads/' . sha1_file($file['tmp_name']) . image_type_to_extension($type);
      if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
        throw new RuntimeException('ファイル保存時にエラーが発生しました');
      }
      // 保存したファイルパスのパーミッション（権限）を変更する
      chmod($path, 0644);

      debug('ファイルは正常にアップロードされました');
      debug('ファイルパス：' . $path);
      return $path;
    } catch (RuntimeException $e) {

      debug($e->getMessage());
      global $err_msg;
      $err_msg[$key] = $e->getMessage();
    }
  }
}