<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <meta id="i18n_pagename" content="index-common">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">

    <title>{{$app->in_name}}</title>
    <link href="/fenfa/static/app/download.css" rel="stylesheet">
    <link href="/fenfa/static/guide/swiper-3.3.1.min.css" rel="stylesheet">
    <link href="/fenfa/static/guide/ab.css" rel="stylesheet">
    <style type="text/css">.wechat_tip,.wechat_tip>i{position:absolute;right:10px}.wechat_tip{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-align:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;background:#3ab2a7;color:#fff;font-size:14px;font-weight:500;width:135px;height:60px;border-radius:10px;top:15px}.wechat_tip>i{top:-10px;width:0;height:0;border-left:6px solid transparent;border-right:6px solid transparent;border-bottom:12px solid #3ab2a7}.mask img{max-width:100%;height:auto}</style>
    <script src="/fenfa/static/guide/zepto.min.js" type="text/javascript"></script>
    <script src="/fenfa/static/guide/swiper.jquery.min.js" type="text/javascript"></script>


</head>
<body>
@if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger'))

<div class="wechat_tip_content"><div class="wechat_tip"><i class="triangle-up"></i><span class="i18n" name="qdjysj"></span>请点击右上角<br><span class="i18n" name="zai">{{strpos($_SERVER['HTTP_USER_AGENT'], 'Android') ? '<span class="i18n" name="llq"></span>' : 'Safari'}}<span class="i18n" name="zdk"></span></div></div>
@else
{{--<span class="pattern left"><img src="/fenfa/static/app/left.png"></span>
<span class="pattern right"><img src="/fenfa/static/app/right.png"></span>--}}
@endif
<div class="out-container">
    <div class="main">
        <header>
            <div class="table-container">
                <div class="cell-container">
                    <div class="app-brief">
                        <div class="icon-container wrapper">
                            <i class="icon-icon_path bg-path"></i>
                            <span class="icon"><img src="{{$app->in_icon}}" onerror="this.src='/fenfa/static/app/{{$app->in_form}}.png'"></span>
                            <span class="qrcode">{{$qr}}</span>
                        </div>
                        <h1 class="name wrapper"><span class="icon-warp"><i class="icon-{{strtolower($app->in_form)}}"></i>{{$app->in_name}}</span></h1>
                        <p class="scan-tips"><span class="i18n" name="smewmxz"></span><br /><span class="i18n" name="ysjllqsrwz"></span>：<span class="text-black">{{env("APP_URL")}}/{{$app->id}}</span></p>
                        <div class="release-info">
                            <p>{{$app->in_bsvs}}（Build {{$app->in_bvs}}）- {{$app->in_size}}</p>
                            <p><span class="i18n" name="gxy"></span>{{$app->in_addtime}}</p>
                        </div>
                            @if(checkmobile() || strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger'))
                        <div id="actions" class="actions">
                            @if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger'))
                            <button type="button" class="i18n" name="bzczwxxaz"></button>
                           @else
                            <button onclick="install_app('/app/install/{{$app->id}}')"><span class="i18n" name="xzaz"></span></button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>
        @if($app->in_kid)
        <div class="per-type-info section">
            <div class="type">
                <div class="info">
                    <p class="type-icon">
                        <i class="icon-{{strtolower($app->in_form)}}"></i>
                    </p>
                    <p class="version">
                        <span class="i18n" name="dqbb"></span>{{$app->in_bsvs}}（Build {{$app->in_bvs}}）
                        <span class="i18n" name="wjdx"></span>{{$app->in_size}}><br>
                        <span class="i18n" name="gxy"></span>{{$app->in_addtime}}
                    </p>
                </div>
            </div>
            <div class="type">
                <div class="info">
                    <p class="type-icon">
                        <i class="icon-{{strtolower($app->in_form)}}"></i>
                    </p>
                    <p class="version">
                        <span class="i18n" name="dqbb"></span>{{$app->in_bsvs}}（Build {{$app->in_bvs}}）
                        <span class="i18n" name="wjdx"></span>{{$app->in_size}}><br>
                        <span class="i18n" name="gxy"></span>{{$app->in_addtime}}
                    </p>
                </div>
            </div>
        </div>
        @endif
        <div class="footer">{{$_SERVER['HTTP_HOST']}}<span class="i18n" name="msg_ncptqzxzbyyfx">是应用内测平台，请自行甄别应用风险！如有问题可通过邮件反馈。</span> {{--<a class="one-key-report i18n" href="mailto:" name="lxwm"></a>--}}</div>
    </div>
</div>
<div class="mask" style="display:none"></div>
<script type="text/javascript" src="/fenfa//static/i18n/jquery.min.js"></script>
<script type="text/javascript" src="/fenfa//static/i18n/jquery.i18n.properties.js"></script>
<script type="text/javascript" src="/fenfa//static/i18n/language.js"></script>
<script type="text/javascript">
    function mobile_provision(){
        document.getElementById('actions').innerHTML = '<p>'+ $.i18n.prop('msg_zzazqahjck') +'</p><button onclick="location.href=\'{{env("APP_URL")}}/{{$app->in_mobilevision}}\'">'+ $.i18n.prop('msg_ljxr') +'</button>';
    }

    @if(IN_MOBILEPROVISION==0)


    function install_app(_link){
        if(!/android/.test(navigator.userAgent.toLowerCase())){
            document.getElementById('actions').innerHTML = '<button style="min-width:43px;width:43px;padding:12px 0;border-top-color:transparent;border-left-color:transparent" class="loading">&nbsp;</button>';
            setTimeout("mobile_provision()", 6000);
        }
        location.href = _link;
    }
   @else
    function install_app(_link){
        if(/android/.test(navigator.userAgent.toLowerCase())){
            location.href = _link;
        }else{
            $('.mask').show();
            $('.mask').html('<div class="alert-box"><div class="size-pic"><img id="mq1" src="{{env("APP_URL")}}/fenfa/static/guide/mq1.jpg"><div class="device"><div class="swiper-container1"><div class="swiper-wrapper"><div class="swiper-slide"><img src="{{env("APP_URL")}}/fenfen/static/guide/mq1.jpg"><div class="next_btn"></div></div><div class="swiper-slide"><img src="{{env("APP_URL")}}/fenffa/static/guide/mq2.jpg"><div class="next_btn"></div></div><div class="swiper-slide"><img src="{{env("APP_URL")}}/fenfa/static/guide/mq3.jpg"><div class="next_btn"></div></div><div class="swiper-slide"><img src="{{env("APP_URL")}}/fenfa/static/guide/mq4.jpg"></div></div></div></div></div><div class="alert-btn"><div class="color-bar change top-bar"></div><div class="color-bar change buttom-bar"></div><a onclick="install_ing(\'' + _link + '\')" class="color-bar change text-bar">'+ $.i18n.prop('msg_ljaz') +'</a></div></div>');
        }
    }
  @endif
    function install_ing(_link){
        location.href = _link;
        $(".text-bar")[0].innerHTML = $.i18n.prop('msg_azz');
        $(".change").show();
        $(".text-bar").attr("disabled", "true");
        $(".top-bar").css("width", "0.1%");
        timer = setTimeout(function() {
            $(".top-bar").css("width", "0.1%").animate({
                width:"20%"
            }, 1e3, function() {
                $("#mq1").hide();
                $(".device").show();
                var mySwiper = new Swiper(".swiper-container1", {
                    nextButton:".next_btn",
                    autoplay:3e3,
                    autoplayStopOnLast:true
                });
                $(".top-bar").css("width", "20%").animate({
                    width:"100%"
                }, 15e3, function() {
                    $(".text-bar")[0].innerHTML = $.i18n.prop('msg_ljxr');
                    $(".text-bar").removeAttr("disabled");
                    $(".text-bar").attr("href", "{{env("APP_URL")}}/{{$app->in_mobilevision}}");
                });
            });
        }, 1e3);
    }
</script>
</body>
</html>