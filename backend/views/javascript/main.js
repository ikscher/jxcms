/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/*锁屏*/
$('.lockScreen').click(function(){
    $.get("?d=common&c=main&m=public_lock_screen");
    $('#dvLockScreen').css('display','');
});
            
            

            
$('#lock_password').bind('keydown', function(e) {
    if (e.keyCode == 13) {
        $("input[name=dosubmit]").trigger('click');
    }
});
            
/*退出*/
$('.logout').click(function(){
    if(confirm("你确定退去后台吗？")){
        location.href="?d=common&c=logout&m=index";
    }else{
        return false;
    }
});

            
//load left menu when clicking top menu
function _M(menuid) {
    menuid=parseInt(menuid);
    $("#leftMain").load("index.php?d=common&c=main&m=public_menu_left&menuid="+menuid, {
        limit: 25
    }, function(){
        windowW();
    });

    $('.top_menu').removeClass("on");
    $('#_M'+menuid).addClass("on");
    $.get("index.php?d=common&c=main&m=public_current_pos",{
        menuid:menuid
    } ,function(data){
        $("#current_pos").html(data);
    });
    //当点击顶部菜单后，隐藏中间的框架
    $('#display_center_id').css('display','none');
    //显示左侧菜单，当点击顶部时，展开左侧
    $(".left_menu").removeClass("left_menu_on");
    $("#openClose").removeClass("close");
    $("html").removeClass("on");
    $("#openClose").data('clicknum', 0);
    $("#current_pos").data('clicknum', 1);
}
            
//initilize the window
if(!Array.prototype.map)
    Array.prototype.map = function(fn,scope) {
        var result = [],ri = 0;
        for (var i = 0,n = this.length; i < n; i++){
            if(i in this){
                result[ri++]  = fn.call(scope ,this[i],i,this);
            }
        }
        return result;
    };
            
          
            
function windowW(){
    if($('#Scroll').height()<$("#leftMain").height()){
        $(".scroll").show();
    }else{
        $(".scroll").hide();
    }
}
            

            

            
function wSize(){
    //这是一字符串
    var str=getWindowSize();
    var strs= new Array(); //定义一数组
    strs=str.toString().split(","); //字符分割
    var heights = strs[0]-125,Body = $('body');
    $('#rightMain').height(heights);   
    if(strs[1]<980){
        $('.header').css('width',980+'px');
        $('#content').css('width',980+'px');
        Body.attr('scroll','');
        Body.removeClass('objbody');
    }else{
        $('.header').css('width','auto');
        $('#content').css('width','auto');
        Body.attr('scroll','no');
        Body.addClass('objbody');
    }
	
    var openClose = $("#rightMain").height()+39;
    $('#center_frame').height(openClose+9);
    $("#openClose").height(openClose-10);	
    $("#Scroll").height(openClose-20);
    windowW();
}

            

//左侧导航菜单隐藏
$("#openClose").click(function(){
    if($(this).data('clicknum')==1) {
        $("html").removeClass("on");
        $(".left_menu").removeClass("left_menu_on");
        $(this).removeClass("close");
        $(this).data('clicknum', 0);
        $(".scroll").show();
    } else {
        $(".left_menu").addClass("left_menu_on");
        $(this).addClass("close");
        $("html").addClass("on");
        $(this).data('clicknum', 1);
        $(".scroll").hide();
    }
    return false;
});

//使用鼠标滚动scrollbar
function menuScroll(num){
    var Scroll = document.getElementById('Scroll');
    if(num==1){
        Scroll.scrollTop = Scroll.scrollTop - 60;
    }else{
        Scroll.scrollTop = Scroll.scrollTop + 60;
    }
}


