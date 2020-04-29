$(function(){
    //フッターがコンテンツが少ない場合でも画面最下部に表示されるようにする
    $footer = $('#footer');
    if(window.innerHeight > $footer.offset().top + $footer.outerHeight()){
       $footer.attr({'style': 'position:fixed; top:' + (window.innerHeight - $footer.outerHeight()) + 'px;'});
    }
});