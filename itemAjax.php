<?php

require('functions.php');

debug('[[[[[[[[[[[[[[[[[');
debug('Ajax処理');
debug('[[[[[[[[[[[[[[[[[');


if(!empty($_POST)){
    debug(print_r($_POST,true));
    $item_id = $_POST['item_id'];
    try {
        $dbh = dbConnect();
        $sql = 'SELECT img FROM item WHERE item_id = :item_id';
        $data = array(':item_id'=>$item_id);
        $stmt = queryPost($dbh, $sql, $data);

        if($stmt){
            debug('データ返す');
            $itemInfo =  $stmt->fetch(PDO::FETCH_ASSOC);
            debug(print_r($itemInfo,true));
            echo json_encode($itemInfo);
        }
    } catch (Exception $e) {
        error_log('エラー発生：'. $e->getMessage());
    }
}

?>