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
    var scrollPosition;
    $('.js-show-modal').off('click.sel').on('click.sel',function(){
        scrollPosition = $(window).scrollTop();
        console.log(scrollPosition);
        $('body').addClass('fixed').css({
            top: -1*scrollPosition
        });
        $('.js-cover-modal').show().css({ top: scrollPosition });
        $('.js-selmodal').show();
        var modalButton = $(this);
        //アイテム名がクリックされたらセレクトボックスに選択されたアイテムを入れる
        $('.js-item-click').off('click.sel').on('click.sel',function(){
            var item_id = $(this).attr('value');
            console.log(item_id);
            modalButton.text($(this).text());
            modalButton.next().find('option').attr("value",$(this).attr("value")).text($(this).text());
            //Ajax通信を使った画像切替
            $.ajax({
                type: 'POST',
                url: 'itemAjax.php',
                dataType: 'JSON',
                data: {
                    item_id: item_id
                }
            }).done(function(data,status){
                console.log(status);
                console.log(data);
                modalButton.nextAll('.itemImg').find('.ajaxItemImg').attr("src", data['img']);
            });
            
            $('body').removeClass('fixed').css({'top':0});
            window.scrollTo(0, scrollPosition);
            $('.js-cover-modal').hide();
            $('.js-selmodal').hide();
            $('.js-open-mark').removeClass('on');
            $('.subMenu').hide();
        });
    });
    //アイテム検索
    $('.js-serch-btn').off('click.sel').on('click.sel',function(){
        scrollPosition = $(window).scrollTop();
        console.log(scrollPosition);
        $('body').addClass('fixed').css({
            top: -1*scrollPosition
        });
        $('.js-cover-modal').show().css({ top: scrollPosition });
        $('.js-selmodal').show();
        var modalButton = $(this);
        //アイテム名がクリックされたらモーダル閉じる
        $('.js-item-click').off('click.sel').on('click.sel',function(){
            
            $('body').removeClass('fixed').css({'top':0});
            window.scrollTo(0, scrollPosition);
            $('.js-cover-modal').hide();
            $('.js-selmodal').hide();
            $('.js-open-mark').removeClass('on');
            $('.subMenu').hide();
        });
    });
    //ばつボタンが押されたらアイテム選択モーダル閉じる
    $('.js-close-modal').on('click',function(){
        $('body').removeClass('fixed').css({'top':0});
        window.scrollTo(0, scrollPosition);
        $('.js-cover-modal').hide();
        $('.js-selmodal').hide();
        $('.js-open-mark').removeClass('on');
        $('.subMenu').hide();
    });

    // 取引登録画面Ajax
    $('.js-item-click').off('click.item').on('click.item',function(){
        console.log($(this).attr('value'));
    });
});