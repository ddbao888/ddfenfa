var ot=new Date().getTime();var oloaded=0;function set_start(){parent.$(".redirect-tips").hide();parent.$(".progress-container").show();parent.$(".turbo-upload").html('<a class="ng-binding" href="javascript:$(\'iframe\')[0].contentWindow.set_cancle()">'+ $.i18n.prop('msg_qx') +'</a>');}
function set_cancle(){parent.$("#speed-uploadify").fadeOut(1e3,function(){$(this).show().text($.i18n.prop('msg_yqxsc'));parent.$(".growing").css("width","0%");parent.$(".turbo-upload").hide();parent.$("iframe").remove();});}
function set_progress(u_size,u_length){var nt=new Date().getTime();var pertime=nt-ot;var per=Math.round(u_size/u_length*100);if(pertime>=1e3){var perload=u_size-oloaded;var speed=perload/(pertime/1e3);var units="b/s";if(speed/1024>1){speed=speed/1024;units="k/s";}
if(speed/1024>1){speed=speed/1024;units="M/s";}
speed=speed.toFixed(1);parent.$(".growing").css("width",per+"%");parent.$("#percentage").text(" - "+per+"% - "+speed+units);ot=new Date().getTime();oloaded=u_size;}
if(per>99){parent.$("#percentage").text($.i18n.prop('msg_zzbcqsd'));}}
function set_error(_text){parent.$(".progress-container").hide();parent.$(".redirect-tips").show().text(_text);}
function set_reload(){parent.location.reload();}