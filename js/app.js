$(function(){

    // 新規登録フォームバリデーションチェック

    $('.js-mail-check').on('blur',function(){
        if($(this).val().trim().length < 1){
            $(this).addClass("input-error");
            $('.mail-error-msgArea').show().text('メールアドレスを入力してください。');
        }else if(!$(this).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)){
            $(this).addClass("input-error");
            $('.mail-error-msgArea').show().text('メールアドレスの形式で入力してください。');
        }else{
            $(this).removeClass("input-error");
            $('.mail-error-msgArea').hide();
        }
    });
    $('.js-name-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.name-error-msgArea').show().text('ニックネームを入力してください。');
        }else if($(this).val().length > 255){
            $(this).addClass("input-error");
            $('.name-error-msgArea').show().text('255文字以内で入力してください');
        }else{
            $(this).removeClass("input-error");
            $('.name-error-msgArea').hide();
        }
    });
    $('.js-pass-check').on('blur',function(){
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.pass-error-msgArea').show().text('パスワードを入力してください。');
        }else if($(this).val().length < 7 || $(this).val().length > 255){
            $(this).addClass("input-error");
            $('.pass-error-msgArea').show().text('8文字以上128文字以内で入力してください');
        }else if(!$(this).val().match(/^[a-zA-Z]+[0-9]+$/)){
            $(this).addClass("input-error");
            $('.pass-error-msgArea').show().text('英字と数字を両方含むパスワードを設定してください。');
        }else{
            $(this).removeClass("input-error");
            $('.pass-error-msgArea').hide();
        }
    });
    $('.js-repass-check').on('blur',function(){
        console.log($('.js-pass-check').val());
        if($(this).val().length < 1){
            $(this).addClass("input-error");
            $('.repass-error-msgArea').show().text('確認のためパスワードを再入力してください。');
        }else if($(this).val() !== $('.js-pass-check').val()){
            $(this).addClass("input-error");
            $('.repass-error-msgArea').show().text('パスワードが一致しません。');
        }else{
            $(this).removeClass("input-error");
            $('.repass-error-msgArea').hide();
        }
    });

    //評価モーダル
    var currentScrollY;
    $('.js-show-evalmodal').on('click',function(){
        currentScrollY = $(window).scrollTop();
        $('html, body').css('overflow', 'hidden');
        $('.js-cover-modal').show();
        $('.js-evalmodal').show();
    });

    //inputフォーム画像プレビュー
    $('#selIcon').on('change', function(e){
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#preview").attr('src', e.target.result);
        }
        reader.readAsDataURL(e.target.files[0]);
    });

    // メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
    }
    
    // フッターがコンテンツが少ない場合でも画面最下部に表示されるようにする
    $footer = $('#footer');
    if(window.innerHeight > $footer.offset().top + $footer.outerHeight()){
       $footer.attr({'style': 'position:fixed; top:' + (window.innerHeight - $footer.outerHeight()) + 'px;'});
    }
});