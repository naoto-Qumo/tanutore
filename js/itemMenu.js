$(function(){
    //アコーディオンメニュー
    $('.js-menu__category__link').each(function(){
        $(this).on('click',function(){
            $(".js-open-mark", this).toggleClass('on');
            $("+.subMenu", this).slideToggle(200);
            return false;
        });
    });
    //アイテム選択モーダル表示
    $('.js-show-modal').on('click',function(){
        $('html, body').css('overflow', 'hidden');
        $('.js-cover-modal').show();
        $('.js-selmodal').show();
        var modalButton = $(this);
        //アイテム名がクリックされたらセレクトボックスに選択されたアイテムを入れる
        $('.js-item-click').off('click.sel').on('click.sel',function(){
            modalButton.text($(this).text());
            modalButton.next().find('option').attr("value",$(this).attr("value")).text($(this).text());
            $('html, body').removeAttr('style');
            $('.js-cover-modal').hide();
            $('.js-selmodal').hide();
            $('.js-open-mark').removeClass('on');
            $('.subMenu').hide();
        });
    });

    //ばつボタンが押されたらアイテム選択モーダル閉じる
    $('.js-close-modal').on('click',function(){
        $('html, body').removeAttr('style');
        $('.js-cover-modal').hide();
        $('.js-selmodal').hide();
    });

});