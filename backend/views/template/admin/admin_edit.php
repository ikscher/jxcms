<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css">
<div class="pad_10">
    <!--导航开始-->
    <div class="nav_">
        
        <ul>   
            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>
    </div>
    <!--导航结束-->

    <input type="hidden" name="info[userid]" value="<?php echo $info['userid']; ?>" />
    <input type="hidden" name="info[encrypt]" value="<?php echo $info['encrypt']; ?>" />
    <table  class="table">
        <tr>
            <td width="80"><?php echo $this->lang->line('username') ?></td> 
            <td><?php echo $info['username']; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('password') ?></td> 
            <td>
                <div class="col-xs-3"><input type="password" name="info[password]" id="password" class="form-control" maxlength="16" /></div>
                <div id="pwdTips"></div>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('cofirmpwd') ?></td> 
            <td>
                <div class="col-xs-3"><input type="password" name="info[confirmpwd]" id="confirmpwd" class="form-control" maxlength="16" /></div>
                <div id="confirmpwdTips"></div>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('email') ?></td>
            <td>
                <div class="col-xs-3"><input type="text" name="info[email]" value="<?php echo $info['email']; ?>" class="form-control" id="email" maxlength="30" /></div>
                <div id="emailTips"></div>
            </td>
        </tr>

        <tr>
            <td><?php echo $this->lang->line('realname') ?></td>
            <td>
                <div class="col-xs-3"><input type="text" name="info[realname]" value="<?php echo $info['realname']; ?>" class="form-control" id="realname" /></div>
            </td>
        </tr>
        <?php if ($admin_roleid == 1) { ?>
            <tr>
                <td><?php echo $this->lang->line('userinrole') ?></td>
                <td>
                    <div class="col-xs-3">
                        <select class="form-control " name="info[roleid]">
                            <?php foreach ($roles as $role): ?>

                                <option value="<?php echo $role['roleid'] ?>" <?php echo (($role['roleid'] == $roleid) ? 'selected' : '') ?>><?php echo $role['rolename'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </td>
            </tr>
        <?php } ?>
        <tr><td><input name="dosubmit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" id="dosubmit"></td><td></td></tr>
    </table>



</div>
<script type="text/javascript">
    
    $("#dosubmit").click(function(){
        if($(".onError").length>0) return false;
        
         $.ajax({
            type:'post',
            url:'?d=admin&c=manage&m=edit',
            dataType:'text',
            data:$('input[name^="info"],input[type="submit"],input[type="hidden"],select[name^="info"]'),
            success:function(str){
                if(str=='yes'){
                    $('.modal-title').text("提示");
                    $('.modal-body').html("修改管理员成功！");
                    $('#myModal').modal();
                }else{
                    $('.modal-title').text("提示");
                    $('.modal-body').html("修改管理员失败！");
                    $('#myModal').modal();
                }
            }
        });
    })
    
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('edit_member');?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        var roleid="<?php echo $roleid; ?>";
        location.href='?d=admin&c=role&m=manage_member&roleid='+roleid;
    });
    
  
    
    //刷新
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    //密码验证
    $('#confirmpwd').blur(function(){
        var pwd=$.trim($('#password').val());
        var pwd_=$.trim($('#confirmpwd').val());

        if(pwd && !(/^[\w\W]{6,16}$/.test(pwd_))) {
            $("#confirmpwdTips").removeClass();
            $("#confirmpwdTips").addClass('onError');
            $("#confirmpwdTips").html("请再次输入6位以上密码！")
        }else{
            if(pwd_ && pwd!=pwd_){
                $("#confirmpwdTips").removeClass();
                $("#confirmpwdTips").addClass('onError');
                $("#confirmpwdTips").html("两次输入的密码不一致！")
            }else if(pwd &&　pwd_ && pwd==pwd_){
                $("#confirmpwdTips").removeClass();
                $("#confirmpwdTips").addClass('onCorrect');
                $("#confirmpwdTips").html("密码一致！")
            }
        }
    }).click(function(){
        var pwd=$.trim($('#password').val());
        var pwd_=$.trim($('#confirmpwd').val());
        if(pwd && !(/^[\w\W]{6,16}$/.test(pwd_))){
            $("#confirmpwdTips").removeClass();
            $("#confirmpwdTips").addClass('onFocus');
            $("#confirmpwdTips").html("请再次输入6位以上密码！")
        }
    })
    
    $('#password').blur(function(){
        var pwd=$.trim($('#password').val());
        var pwd_=$.trim($('#confirmpwd').val());
        
        if(pwd && !(/^[\w\W]{6,16}$/.test(pwd))) {
            $("#pwdTips").removeClass();
            $("#pwdTips").addClass('onError');
            $("#pwdTips").html("请输入6位以上密码！")
            
           
            
        }else{
        
            $("#pwdTips").removeClass();
            $("#pwdTips").html('');
                
            if( pwd!=pwd_){
                $("#confirmpwdTips").removeClass();
                $("#confirmpwdTips").addClass('onError');
                $("#confirmpwdTips").html("两次输入的密码不一致！")
            }else if(pwd &&　pwd_ && pwd==pwd_){
                $("#confirmpwdTips").removeClass();
                $("#confirmpwdTips").addClass('onCorrect');
                $("#confirmpwdTips").html("密码一致！")
            }
       
        }
    }).click(function(){
        $("#pwdTips").removeClass();
        $("#pwdTips").addClass('onFocus');
        $("#pwdTips").html("请输入6位以上密码！")
    })
    
    //邮件验证
    $('#email').blur(function(){
        var email=$(this).val();
        if(!(/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/.test(email))){
            $("#emailTips").removeClass();
            $("#emailTips").addClass('onError');
            $("#emailTips").html("输入的邮件格式不正确！")
        }else{
            $("#emailTips").removeClass();
            $("#emailTips").addClass('onCorrect');
            $("#emailTips").html("格式正确！")
        }
    })
</script>
<?php $this->load->view('common/footer'); ?>

