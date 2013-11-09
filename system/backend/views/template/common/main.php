<!DOCTYPE html>
<html class="off">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
        <title><?php echo $heading_title; ?></title>
        <link rel="shortcut icon" href="http://codeigniter.org.cn/images/design/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/main.css'); ?>" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/icon.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/styles1.css'); ?>" title="styles1" media="screen" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/styles2.css'); ?>" title="styles2" media="screen" >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/styles3.css'); ?>" title="styles3"  media="screen"  >
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/styles4.css'); ?>" title="styles4" media="screen"  >
    </head>
    <body scroll="no" class="objbody">
        <!--<div class="btns btns2" id="btnx"></div>-->
        <div class="header" >

            <div class="logo lf"><a href="http://test.jx.com/jxcms/index.php" target="_blank"></a></div>
            <div class="rt-col">
                <div class="tab_style white cut_line"><a href="javascript:;" class="lockScreen"><img src="<?php echo base_url('views/default/image/lock_screen_main.png'); ?>"/> 锁屏</a><span>|</span><a href="http://forum.grandcloud.cn/index.php" target="_blank">支持论坛</a><span>|</span><a href="http://forum.grandcloud.cn/index.php" target="_blank">帮助？</a>
                    <ul id="Skin">
                        <li class="s1 styleswitch" rel="styles1"></li>
                        <li class="s2 styleswitch" rel="styles2"></li>
                        <li class="s3 styleswitch" rel="styles3"></li>
                        <li class="s4 styleswitch" rel="styles4"></li>
                    </ul>
                </div>
            </div>
            <div class="col-auto">
                <div class="log white cut_line">您好！<?php echo $admin;?> [超级管理员]<span>|</span><a href="javascript:void(0);" class="logout" >[退出]</a><span>|</span>
                    <a href="http://test.jx.com/jxcms/index.php" target="_blank" id="site_homepage">站点首页</a><span>|</span>
                    <a href="?m=member" target="_blank">会员中心</a><span>|</span>
                    <a href="?m=search" target="_blank" id="site_search">搜索</a>
                </div>

                <ul class="nav white" id="top_menu">
                    <?php foreach ($menu as $_value): ?>
                        <?php if ($_value['id'] == 10) { ?>
                            <li id="_M<?php echo $_value['id']; ?>" class="on top_menu"><a href='javascript:_M("<?php echo $_value['id']; ?> ")' > <?php echo $_value['name']; ?></a></li>
                        <?php } else { ?>
                            <li id="_M<?php echo $_value['id']; ?>" class="top_menu"><a href='javascript:_M("<?php echo $_value['id']; ?> ")' ><?php echo $_value['name']; ?></a></li>
                        <?php }; ?>

                    <?php endforeach; ?>
                </ul>

            </div>
        </div>

        <div id="content">
            <div class="col-left left_menu">
                <div id="Scroll" ><div id="leftMain"></div></div>
                <a href="javascript:;" id="openClose"   class="open" title="<?php echo $spread_or_closed; ?>"><span class="hidden"><?php echo $expand; ?></span></a>
            </div>
            <div class="col-1 lf cat-menu" id="display_center_id" style="display:none" height="100%">
                <div class="content">
                    <iframe name="center_frame" id="center_frame" src="" frameborder="false" scrolling="auto" style="border:none" width="100%" height="auto" allowtransparency="true"></iframe>
                </div>
            </div>
            <div class="col-auto mr8">
                <div class="crumbs">
                    <div class="shortcut cu-span"><a href="?m=content&c=create_html&a=public_index&pc_hash=" target="right"><span><?php echo $create_index; ?></span></a><a href="?m=admin&c=cache_all&a=init&pc_hash=" target="right"><span><?php echo $update_backup; ?></span></a><a href="javascript:art.dialog({id:'map',iframe:'?m=admin&c=index&a=public_map', title:'<?php echo $background_map; ?>', width:'700', height:'500', lock:true});void(0);"><span><?php echo $background_map; ?></span></a></div>
                    <?php echo $current_position; ?><span id="current_pos"></span></div>
                <div class="col-1">
                    <div class="content" style="position:relative; overflow:hidden">
                        <iframe name="right" id="rightMain" src="?d=common&c=main&m=public_main" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>

                    </div>
                </div>
            </div>
        </div>

        <div class="scroll"><a href="javascript:;" class="per" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(1);"></a><a href="javascript:;" class="next" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(2);"></a></div>


        <!--锁屏-->
        <div id="dvLockScreen" class="ScreenLock" style="display:<?php if ($lock_screen == 0) echo 'none'; ?>">
            <div id="dvLockScreenWin" class="inputpwd">
                <h5><b class="ico ico-info"></b><span id="lock_tips"><?php echo $lockscreen_status; ?></span></h5>
                <div class="input">
                    <label class="lb"><?php echo $password ?>：</label><input type="password" id="lock_password" class="input-text" size="24">
                    <input type="submit" class="submit" value="&nbsp;" name="dosubmit" >
                </div></div>
        </div>
        
        

        <script type="text/javascript" src="<?php echo base_url('views/javascript/jquery-1.8.0.min.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('views/javascript/main.js'); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url('views/javascript/styleswitch.js'); ?>"></script>
        <script type="text/javascript">
                        

            window.onload = function (){
                if(!+"\v1" && !document.querySelector) { // for IE6 IE7
                    document.body.onresize = resize;
                } else { 
                    window.onresize = resize;
                }
                function resize() {
                    wSize();
                    return false;
                }
            }
            
            var getWindowSize = function(){
                return ["Height","Width"].map(function(name){
                    return window["inner"+name] ||
                        document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
                });
            }
            
            wSize();
            windowW();
            
            (function(){
                var addEvent = (function(){
                    if (window.addEventListener) {
                        return function(el, sType, fn, capture) {
                            el.addEventListener(sType, fn, (capture));
                        };
                    } else if (window.attachEvent) {
                        return function(el, sType, fn, capture) {
                            el.attachEvent("on" + sType, fn);
                        };
                    } else {
                        return function(){};
                    }
                })(),
                Scroll = document.getElementById('Scroll');
                // IE6/IE7/IE8/Opera 10+/Safari5+
                addEvent(Scroll, 'mousewheel', function(event){
                    event = window.event || event ;  
                    if(event.wheelDelta <= 0 || event.detail > 0) {
                        Scroll.scrollTop = Scroll.scrollTop + 29;
                    } else {
                        Scroll.scrollTop = Scroll.scrollTop - 29;
                    }
                }, false);

                // Firefox 3.5+
                addEvent(Scroll, 'DOMMouseScroll',  function(event){
                    event = window.event || event ;
                    if(event.wheelDelta <= 0 || event.detail > 0) {
                        Scroll.scrollTop = Scroll.scrollTop + 29;
                    } else {
                        Scroll.scrollTop = Scroll.scrollTop - 29;
                    }
                }, false);
	
            })();
            
            //initilize load left menu
            $("#leftMain").load("index.php?d=common&c=main&m=public_menu_left&menuid=10");
        </script>
    </body>
</html>