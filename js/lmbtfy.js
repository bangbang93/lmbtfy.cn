/**
 * Created by bangbang on 14/10/10.
 */
$(document).ready(function (){
    ZeroClipboard.config({swfPath: '//cdnjscn.b0.upaiyun.com/libs/zeroclipboard/2.1.6/ZeroClipboard.swf'});
    var clip = new ZeroClipboard($('#copy'));
    $('#search').on('click', function (){
        var link = window.location.origin + window.location.pathname + '?' + encodeURIComponent($('#kw').val());
        $.get('http://auth.bangbang93.com/sina/short_url.php?url=' + link, function (data) {
            data = JSON.parse(data);
            link = data[0]['url_short'];
            $('#link').show();
            $('#instructions').text('复制下面的地址');
            $('#lmbtfyLink').val(link).focus().select();
        });
    });
    var $container = $('.container');
    $container.on('click', '#go', function (){
        var link = $('#lmbtfyLink').val();
        if (!!link){
            window.location = link;
        }
    });
    $('#kw').on('keydown', function (e) {
        if (e.keyCode == 13){
            $('#search').trigger('click');
        }
    });
    if (!!window.location.search){
        var kw = decodeURIComponent(window.location.search.substr(1));
        var $kw = $('#kw');
        var $instructions = $('#instructions');
        var $arrow = $('#arrow');
        setTimeout(function (){
            $instructions.text('1、把鼠标放到输入框上');
            $arrow.show().animate({
                left: $kw.offset().left + 10 + 'px',
                top: ($kw.offset().top + $kw.height()/2) + 'px'
            }, 2000, function (){
                $instructions.text('2、输入你的问题');
                $arrow.hide();
                var $kw = $('#kw');
                $kw.focus();
                var i = 0;
                var interval = setInterval(function (){
                    $kw.val(kw.substr(0,i));
                    i++;
                    if (i > kw.length){
                        clearInterval(interval);
                        $instructions.text('3、按下“百度一下”按钮');
                        $arrow.show();
                        var $search = $('#search');
                        $arrow.animate({
                            left: $search.offset().left + $search.width()/2 + 'px',
                            top: $search.offset().top + $search.height()/2 + 'px'
                        }, 1000, function () {
                            $instructions.html('<strong>这对你而言就是这么困难么？</strong>');
                            setTimeout(function (){
                                window.location = 'http://www.baidu.com/s?tn=lmbtfy.cn&ch=3&ie=utf-8&wd=' + encodeURIComponent(kw);
                            }, 2000);
                        })
                    }
                }, 200);
            });
        }, 1000);
    }
});
