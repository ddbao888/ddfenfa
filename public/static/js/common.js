var _this;
var appUrl = window.location.protocol + "//" + window.location.host + "/" + '/?';//微擎路由
var url = window.location.href;
var url_host = window.location.protocol + "//" + window.location.host;
var htttps_url = "https://" + window.location.host;

// var uniacid = '{$_W['uniacid']}';

var url_data = query()
if(query().lecturerId){
    var appUrl = window.location.protocol + "//" + window.location.host + "/" + '/web/eduLecturer.php?c=site&a=entry&m=yzd_edu&';//微擎路由
}



//获取url某变量值
function query(sHref = window.location.href) {
    var obj = {};
    var args = sHref.split('?');
    if (args[0] == sHref) return obj;
    var arr = args[1].split('&');
    for (var i = 0; i < arr.length; i++) {
        var arg = arr[i].split('=');
        obj[arg[0]] = arg[1];
    }
    return obj;
}


function test(a = 111) {
    // console.log(a)
    return '111';
}

//校验密码：只能输入6-20个字母、数字、下划线
function isPasswd(s)
{
var patrn=/^(\w){6,20}$/;
if (!patrn.exec(s)) return false
return true
}


//最多只保留小数点后2位的正数
function isPrice(s)
{
var patrn=/^(\d+|\d+\.\d{1,2})$/;
if (!patrn.exec(s)) return false
return true
}



// 时间戳转时间函数
function timestampToTime(timestamp) {
    const dateObj = new Date(+timestamp * 1000) // ps, 必须是数字类型，不能是字符串, +运算符把字符串转化为数字，更兼容
    const year = dateObj.getFullYear() // 获取年，
    const month = dateObj.getMonth() + 1 // 获取月，必须要加1，因为月份是从0开始计算的
    const date = dateObj.getDate() // 获取日，记得区分getDay()方法是获取星期几的。
    const hours = pad(dateObj.getHours())  // 获取时, pad函数用来补0
    const minutes = pad(dateObj.getMinutes()) // 获取分
    const seconds = pad(dateObj.getSeconds()) // 获取秒
    return year + '-' + month + '-' + date + ' ' + hours + ':' + minutes + ':' + seconds
}


function dateFormat(fmt, date) {
    let ret;
    const opt = {
        "Y+": date.getFullYear().toString(),        // 年
        "m+": (date.getMonth() + 1).toString(),     // 月
        "d+": date.getDate().toString(),            // 日
        "H+": date.getHours().toString(),           // 时
        "M+": date.getMinutes().toString(),         // 分
        "S+": date.getSeconds().toString()          // 秒
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
    };
    for (let k in opt) {
        ret = new RegExp("(" + k + ")").exec(fmt);
        if (ret) {
            fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, "0")))
        };
    };
    return fmt;
}
function fun_date(num) { 
    var date1 = new Date();
    //今天时间
    var time1 = date1.getFullYear() + "-" + (date1.getMonth() + 1) + "-" + date1.getDate()
    // console.log(time1);
    var date2 = new Date(date1);
     date2.setDate(date1.getDate() + num);
     //num是正数表示之后的时间，num负数表示之前的时间，0表示今天
     var time2 = date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate(); 
    // console.log(time2);
    return time2;
}
    

function pad(str) {
    return +str >= 10 ? str : '0' + str
}


function swapArr(arr, index1, index2) {
    arr[index1] = arr.splice(index2, 1, arr[index1])[0];
    return arr;
}


function upGo(fieldData, index) {
    if (index != 0) {
        fieldData[index] = fieldData.splice(index - 1, 1, fieldData[index])[0];
    } else {
        fieldData.push(fieldData.shift());
    }
}





function downGo(fieldData, index) {
    if (index != fieldData.length - 1) {
        fieldData[index] = fieldData.splice(index + 1, 1, fieldData[index])[0];
    } else {
        fieldData.unshift(fieldData.splice(index, 1)[0]);
    }
}

// 获取多少分钟后的时间
function timeMinutes(datetime,minute) {
    // var date=new Date(datetime);     //1. js获取当前时间
    var date= datetime;
    // console.log('js',date)
    var min=date.getMinutes();  //2. 获取当前分钟
    date.setMinutes(min+minute);  //3. 设置当前时间+10分钟：把当前分钟数+10后的值重新设置为date对象的分钟数
    var y = date.getFullYear();
    var m = (date.getMonth() + 1) < 10 ? ("0" + (date.getMonth() + 1)) : (date.getMonth() + 1);
    var d = date.getDate() < 10 ? ("0" + date.getDate()) : date.getDate();
    var h = date.getHours() < 10 ? ('0' + date.getHours()) : date.getHours()
    var f = date.getMinutes() < 10 ? ('0' + date.getMinutes()) : date.getMinutes()
    var s = date.getSeconds() < 10 ? ('0' + date.getseconds()) : date.getSeconds()
    var formatdate = y+'-'+m+'-'+d + " " + h + ":" + f + ":" + s;

    return formatdate;
}

function dateFormat(fmt, date) {
    let ret;
    const opt = {
        "Y+": date.getFullYear().toString(),        // 年
        "m+": (date.getMonth() + 1).toString(),     // 月
        "d+": date.getDate().toString(),            // 日
        "H+": date.getHours().toString(),           // 时
        "M+": date.getMinutes().toString(),         // 分
        "S+": date.getSeconds().toString()          // 秒
        // 有其他格式化字符需求可以继续添加，必须转化成字符串
    };
    for (let k in opt) {
        ret = new RegExp("(" + k + ")").exec(fmt);
        if (ret) {
            fmt = fmt.replace(ret[1], (ret[1].length == 1) ? (opt[k]) : (opt[k].padStart(ret[1].length, "0")))
        };
    };
    return fmt;
}

// function parseTime(dateTrans) {
//     datdString = datdString.replace("GMT", '').replaceAll("\\(.*\\)", '');
//     //将字符串转化为date类型，格式2016-10-12
//     SimpleDateFormat format = new SimpleDateFormat("EEE MMM dd yyyy HH:mm:ss z", Locale.ENGLISH);
//     Date dateTrans = null;
//     try {
//         dateTrans = format.parse(datdString);
//         return new SimpleDateFormat("yyyy-MM-dd").format(dateTrans).replace("-","/");
//     } catch (ParseException e) {
//         e.printStackTrace();
//     }
//     return datdString;
// }


function strlen(str){
    var len = 0;
    for (var i=0; i<str.length; i++) { 
     var c = str.charCodeAt(i); 
    //单字节加1 
     if ((c >= 0x0001 && c <= 0x007e) || (0xff60<=c && c<=0xff9f)) { 
       len++; 
     } 
     else { 
      len+=2; 
     } 
    } 
    return len;
}


/**
 * URL转base64
 * @param url String 图片链接
 * @callback Function 获取base64格式后的回调函数
 */
function translateImgToBase64(url,callback){
    var canvas = document.createElement('canvas')
    context = canvas.getContext('2d')
    img = new Image  //通过构造函数绘制图片实例
    img.crossOrigin = 'Anonymous'  //处理图片跨域问题，见拓展1
    img.onload = function(){   //该加载过程为异步事件，请先确保获取完整图片
        canvas.width = img.width
        canvas.height = img.height
        context.drawImage(img,0,0)  //将图片绘制在canvas中
        var URLData = canvas.toDataURL('image/png')
        callback(URLData);
        canvas = null
    }
    img.src = url
}

/**
 * 获取base64格式的回调函数
 * @param URLData 获取的base64格式
 */
//  function getBase64(URLData){
//     //  return "base64" + URLData;
//      console.log("base64",URLData)
//  }

 /**
 * Base64转
 * @param base64 String base64格式字符串
 * @param contentType String file对象的文件类型，如："image/png"
 * @param filename String 文件名称或者文件路径
 */
function translateBase64ImgToFile(base64,filename,contentType){
    var arr = base64.split(',')  //去掉base64格式图片的头部
    var bstr = atob(arr[1])   //atob()方法将数据解码
    var leng = bstr.length
    var u8arr = new Uint8Array(leng)
    while(leng--){
       u8arr[leng] =  bstr.charCodeAt(leng) //返回指定位置的字符的 Unicode 编码
    }
    return new File([u8arr],filename,{type:contentType}) 
}


function getBase64(img){
    function getBase64Image(img,width,height) {//width、height调用时传入具体像素值，控制大小 ,不传则默认图像大小
      var canvas = document.createElement("canvas");
      canvas.width = width ? width : img.width;
      canvas.height = height ? height : img.height;

      var ctx = canvas.getContext("2d");
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
      var dataURL = canvas.toDataURL();
      return dataURL;
    }
    var image = new Image();
    image.crossOrigin = '';
    image.src = img;
    var deferred=$.Deferred();
    if(img){
      image.onload =function (){
        deferred.resolve(getBase64Image(image));//将base64传给done上传处理
      }
      return deferred.promise();//问题要让onload完成后再return sessionStorage['imgTest']
    }}

function errorMessage(resp)
{
    if(resp.status == 422) {
        this.$message({
            message: '失败了!',
            type: 'success',
            duration: 500,
            onClose: () => {
                _this.cardLoading = false
                location.href = "{php echo $this->createWebUrl('lec_course')}"
            },
        })
    }
}

function ajaxLoadPage(url, data={})
{
    loading();
    $.ajax({
        url: url,
        data: data,
        dataType: 'html',
        beforeSubmit:function(){
            loading();
        },
        success: function (data) {
            closeLoading();
            $('#app_page').empty();
            $('#app_page').html(data);
        }
    }).done().fail(function (data) {
        closeLoading();
        //toastr.error('系统错误!');
    });
        //add the new page to the window.history
      // window.history.pushState({path: '/web/index'},'','/web/index');
}

$(window).on('popstate', function() {
    var newPageArray = location.pathname.split('/'),
        //this is the url of the page to be loaded
        newPage = newPageArray[newPageArray.length];
    console.log(newPage);
    ajaxLoadPage(newPage);
});

function loading() {
    console.log('loading...');
    $("button[type=submit]").attr("disabled",　true);
    $('body').append('<div class="spiner-example" style="position:fixed;z-index:1000000;top:1px;top:20%;height:100%;width:100%;display:block">\n' +
        '    <div class="sk-spinner sk-spinner-three-bounce" style="opacity:1">\n' +
        '        <div class="sk-bounce1"></div>\n' +
        '        <div class="sk-bounce2"></div>\n' +
        '        <div class="sk-bounce3"></div>\n' +
        '    </div>\n' +
        '</div>')

}
function closeLoading() {
    $("button[type=submit]").attr("disabled",　false);
    $('.ibox-content').css('opacity', 1);
    $('.spiner-example').hide();
}

$(document).on('click', '.J_menuItem', function() {
    if($(this).is('.navactive')) {
        return false;
    }
    $('.navtitle').each(function() {
        console.log(1);
        //修改选中样式
        if($(this).is('.navactive')) {
            var oldMenu = $(this).data('id');
            var oldIconVal = $(this).data('icon-val');
            var oldIconVal2 = $(this).data('icon-val2');
            var oldIcon = $(this).data('icon');
            if(oldIcon) {
                console.log('移除样式')
                $('#icon-'+oldMenu).removeClass('nav-icon-active');
                $('#icon-'+oldMenu).addClass('nav-icon');
                console.log('增加样式');
                $('#icon-'+oldMenu).removeClass(oldIconVal2);
                $('#icon-'+oldMenu).addClass(oldIconVal);
            } else {
                console.log('oldMenu='+oldMenu);
                console.log(oldIconVal2);
                $('#icon-'+oldMenu).attr('src', oldIconVal);
            }
        }
    })
    //移除选中样式
    $('.navtitle').removeClass('navactive');
    $(this).addClass('navactive');

    var menuId = $(this).data('id');
    var icon = $(this).data('icon');
    var iconVal = $(this).data('icon-val2');
    console.log(iconVal);
    var erjiMenu = $('#therji-'+menuId);
    $('.therji').hide();
    $('#therji-'+menuId).show();
    erjiMenu.show();
    console.log('menuID='+menuId);
    //修改选中图标
    $('#icon-'+menuId).addClass('nav-icon-active');
    if(icon) {
      $('#icon-'+menuId).addClass(iconVal);
    } else {
        $('#icon-'+menuId).attr('src', iconVal);
    }

    var title = $(this).data('title');
    $('.navback_title').html(title);

    var href = $(this).data('href');
    console.log(href);
    if(href) {
        $('#navback').hide();
        ajaxLoadPage(href);
        $('#div_page').removeClass('yzd_back');
        $('#div_page').addClass('yzd_back_no2menu')
    } else {
        $('#div_page').addClass('yzd_back')
        $('#div_page').removeClass('yzd_back_no2menu');
        $('#navback').show();

        var childDiv = $('#therji-'+menuId).children("a").get(1);
        $(childDiv).addClass('nav2itemactive');
        var pageUrl = $(childDiv).data('href');

        ajaxLoadPage(pageUrl);
    }

});

$(document).on('click','.J_menuItem2', function() {
    console.log('hello');
    $('.J_menuItem2').each(function() {
        $(this).removeClass('nav2itemactive');
    })
    $(this).addClass('nav2itemactive');
    var url = $(this).data('href');
    ajaxLoadPage(url);
})



