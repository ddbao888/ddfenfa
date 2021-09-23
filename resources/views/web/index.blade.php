<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>控制台</title>
    <link href="/static/css/bootstrap.min.css?v=201903090001" rel="stylesheet">
    <link href="/static/css/common.css" rel="stylesheet">
    <link href="/static/css/style.css" rel="stylesheet">
    <link href="/static/css/element_yzd.css" rel="stylesheet">
    <link href="/static/css/iconfont/iconfont.css" rel="stylesheet">
    <link href="/static/css/color_ui/iconfont.css" rel="stylesheet">
    <link href="/static/css/flex.css" rel="stylesheet">
    <link href="/lib/elementui/theme/index.css" rel="stylesheet">
    <link href="/static/css/diy.css" rel="stylesheet">
    <script src="/lib/jquery/jquery.min.js"></script>
    <script src="/lib/vue/vue.js"></script>
    <script src="/lib/elementui/lib/index.js"></script>
    <script src="/lib/vue-dragging-master/vue-dragging.es5.js"></script>
    <script src="/lib/axios/dist/axios.min.js"></script>
    <script src="/lib/qs/dist/qs.js"></script>
    <script src="https://gosspublic.alicdn.com/aliyun-oss-sdk-6.5.0.min.js"></script>
    <script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key=CMZBZ-Z5H6D-HE743-P4XQK-YRJ7H-M5FZS"></script>
    <script src="/lib/js/txyun/dist/cos-js-sdk-v5.min.js"></script>
    <!-- 自定义全局封装 -->
    <script type="text/javascript">
        if(navigator.appName == 'Microsoft Internet Explorer'){
            if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
                alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
            }
        }
    </script>
    <script type="text/javascript" src="/static/js/echarts.js"></script>
    <script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/static/js/util.js"></script>
    <script type="text/javascript" src="/static/js/common.min.js"></script>
    <script type="text/javascript" src="/static/js/require.js"></script>

    <script src="/lib/ueditor/ueditor.config.js"></script>
    <!-- <script src="/addons/yzd_edu/lib/ueditor/ueditor.all.min.js"></script> -->
    <script src="/lib/ueditor/ueditor.all.js"></script>
    <script src="/lib/ueditor/ueditor.parse.js"></script>
    <script src="/lib/ueditor/ueditor.parse.min.js"></script>
</head>
<body>
<div class="header">
    <div  id="header" class="header_right">
        <el-dropdown style="cursor:pointer;line-height:40px">
            <span class="el-dropdown-link">
                分发系统<i class="el-icon-arrrow-down el-icon-right"></i>
            </span>
            <el-dropdown-menu slot="dropdown">
                <span><el-dropwon-item></el-dropwon-item></span>
                <a><el-dropdown-item>修改密码</el-dropdown-item></a>
                <a><el-dropdown-item>退出系统</el-dropdown-item></a>
            </el-dropdown-menu>
        </el-dropdown>
    </div>

</div>
<div class="container-fluid col-md-12 col-xs-12 col-sm-12">
    <div class="row yzd_top">
        <div class="big-menu">
            <div class="top">
                <div class="userImgWrapper">
                    <img class="userImg" src="/static/img/logo.jpg"/>
                </div>
            </div>
            <div class="navback_title">
                概述
            </div>
            <div class="navback" id="navback"></div>

            @foreach($menus as $key => $menu)
            <div class="panel panel-default nav_item">
                <div class="panel-heading navtitle nav_item_info">
                    <a data-id="{{$menu->id}}" data-title="{{$menu->title}}" data-href="{{$menu->href}}" data-icon="@if($menu->is_icon) true @endif" data-icon-val="{{$menu->icon}}" data-icon-val2="{{$menu->icon_active}}" class="J_menuItem panel-title navtitle @if($menu->id == 1) navactive @endif">
                        @if($menu->is_icon)
                            <i id="icon-{{$menu->id}}" class="@if($menu->id == 1) nav-icon-active @else nav-icon @endif @if($menu->id == 1){{$menu->icon_active}} @else {{$menu->icon}} @endif"></i>
                        @else
                            <img id="icon-{{$menu->id}}" class="@if($menu->id == 1) nav_icon-active @else nav_icon @endif " src="{{$menu->icon}}"/>
                        @endif
                        {{$menu->title}}
                    </a>
                    @if(isset($menu->childs))
                    <ul class="therji" id="therji-{{$menu->id}}" style="display: @if($menu->id == 1) block @else none @endif">
                        @foreach($menu->childs as $key => $child)
                        <a data-href="{{$child->href}}" class="J_menuItem2 nav2active list-group-item @if(!$child->href) nav2header  @endif">{{$child->title}}</a>
                        @endforeach
                    </ul>
                   @endif
                </div>
            </div>
            @if($key ==7)
                    <div class=" panel panel-default nav_item">
                        <div class="panel-heading navtitle nav_item_info">
                            <a class="panel-title navtitle " style="background-color: #393836;"></a>
                        </div>
                    </div>
            @endif
            @endforeach
        </div>
    </div>
    <div class="yzd_back_no2menu" id="div_page" style="  padding-right: 10px ">
        <main>
            <div class="cd-index cd-main-content">
                <div>
                    <div class="content" id="app_page">

                    </div>
                    <!-- your content here -->
                </div>
            </div>
        </main>



    </div>
</div>
</body>
<script src="/static/js/common.js"></script>
<script>
    new Vue({
        el:'#header',
        methods:{
            __init()
            {
            }
        }
    })
</script>
<script>
    ajaxLoadPage("{{route('web.dashboard')}}");
    $('.navback').hide();



</script>



<style>
    .nav-icon{
        font-size:18px;
        display:inline-block;
        margin-right:5px;
        margin-top:-4px;
        vertical-align:middle;
        color:#aeaeae;
    }
    .nav-icon-active{
        font-size:18px;
        display:inline-block;
        margin-right:5px;
        margin-top:-4px;
        vertical-align:middle;
        color:#fff;
    }
    .nav2header{
        background-color:#eee;
        font-weight:600;
        line-height:40px
    }
    .nav2itemactive{
        color:#04ba8f!important;
    }

</style>
</html>