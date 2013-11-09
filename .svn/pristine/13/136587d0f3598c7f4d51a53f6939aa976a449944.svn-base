<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('views/default/css/login.css') ?>" /> 
        <script type="text/javascript" src="<?php echo base_url('views/javascript/jquery-1.8.0.min.js') ?>"></script>
    </head>
    <body>
        <div class="div_header_png"><span><?php echo $heading_title;?></span></div>
        <div id="content">
            <div class="box" style="width: 400px; min-height: 300px; margin-top: 40px; margin-left: auto; margin-right: auto;">
                <div class="heading">
                    <h1><img src="<?php echo base_url('views/default/image/lockscreen.png') ?>" alt="" /> <?php echo $text_login ?></h1>
                </div>
                <div class="content" style="min-height: 150px; overflow: hidden;">
                    <?php if ($success) { ?>

                        <div class="success"><?php echo $success; ?></div>
                    <?php } ?>

                    <?php if ($error_warning) { ?>
                        <div class="warning"><?php echo $error_warning; ?></div>
                    <?php } ?>
                    <form action="<?php echo site_url('d=common&c=login&m=index'); ?>" method="POST" id="form">
                        <table style="width: 100%;">
                            <tr>
                                <td style="text-align: center;" rowspan="4"><img src="<?php echo base_url('views/default/image/login.png'); ?>" alt="<?php echo $text_login; ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_username; ?><br />
                                    <input type="text" name="username" value="<?php echo $username; ?>" style="margin-top: 4px;" />
                                    <br />
                                    <br />
                                    <?php echo $entry_password; ?><br />
                                    <input type="password" name="password" value="<?php echo $password; ?>" style="margin-top: 4px;" />
                                    <!--  <br />
                                     <a href="{$forgotten}">{$text_forgotten}</a></td> -->
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align: right;"><a id="sbt"  class="button"><?php echo $button_login; ?></a></td>
                            </tr>
                        </table>

                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript"><!--
            $('#form input').keydown(function(e) {
                if (e.keyCode == 13) {
                    $('#form').submit();
                }
            });

            var login={ 
                check:function(){
                    var   username=$.trim($("input[name=username]").val());
                    var   password=$.trim($("input[name=password]").val());
		
                    if(!(/[_a-zA-Z\d\-]+$/.test(username))){ 
                        $('.warning').remove();
                        $('.content').prepend('<div class="warning">请输入有效的用户名！</div>');
                        
                        return false;
                    }
				
                    if(!password){  
                        $('.warning').remove();
                        $('.content').prepend('<div class="warning">请输入密码！</div>');
                        return false;
                    }
		    
                    $('#form').submit();
	           
                }
			
			
            };
	
            $("#sbt").click(login.check);
            //--></script> 
    </body>
</html>