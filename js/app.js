$(function(){
    
    // 新規登録フォームバリデーションチェック
    const inputChkMSG = "を入力してください";

    // フォームに入力があるかチェック
    $('.js-mail-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.mail-error-msgArea').show().text('メールアドレスを入力してください。');
        }else{
            $(this).removeClass("input-error");
            $('.mail-error-msgArea').hide();
        }
    });
    $('.js-name-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.name-error-msgArea').show().text('ニックネームを入力してください。');
        }else{
            $(this).removeClass("input-error");
            $('.name-error-msgArea').hide();
        }
    });
    $('.js-pass-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.pass-error-msgArea').show().text('パスワードを入力してください。');
        }else{
            $(this).removeClass("input-error");
            $('.pass-error-msgArea').hide();
        }
    });
    $('.js-repass-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.repass-error-msgArea').show().text('確認のためパスワードを再入力してください。');
        }else{
            $(this).removeClass("input-error");
            $('.repass-error-msgArea').hide();
        }
    });
    
    // フッターがコンテンツが少ない場合でも画面最下部に表示されるようにする
    $footer = $('#footer');
    if(window.innerHeight > $footer.offset().top + $footer.outerHeight()){
       $footer.attr({'style': 'position:fixed; top:' + (window.innerHeight - $footer.outerHeight()) + 'px;'});
    }
});