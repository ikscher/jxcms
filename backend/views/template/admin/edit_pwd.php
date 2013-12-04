<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<div class="pad_10">
    <div class="common-form">

        <table width="100%" class="table">
            <tr>
                <td width="80"><?php echo $this->lang->line('username') ?></td> 
                <td><?php echo $info['username'] ?> (<?php echo $this->lang->line('realname') ?> <?php echo $info['realname'] ?>)</td>
            </tr>

            <tr>
                <td><?php echo $this->lang->line('email') ?></td>
                <td>
                    <?php echo $info['email'] ?>
                </td>
            </tr>

            <tr>
                <td><?php echo $this->lang->line('old_password') ?></td> 
                <td>
                    <div class="col-xs-3"><input type="password" name="old_password" id="old_password" class="form-control"/></div>
                    <div id="old_passwordTip" ></div>
                </td>
            </tr>

            <tr>
                <td><?php echo $this->lang->line('new_password') ?></td> 
                <td>
                    <div class="col-xs-3"><input type="password" name="new_password" id="new_password" class="form-control" /></div>
                    <div id="new_passwordTip" ></div>
                </td>
            </tr>
            <tr>
                <td><?php echo $this->lang->line('new_pwdconfirm') ?></td> 
                <td>
                    <div class="col-xs-3"><input type="password" name="new_pwdconfirm" id="new_pwdconfirm" class="form-control"/></div>
                    <div id="new_pwdconfirmTip" ></div>
                </td>
            </tr>

            <tr><td><input name="dosubmit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" id="dosubmit"></td><td></td></tr>
        </table>

    </div>
</div>
<script type="text/javascript">
    //密码校验
    $("#old_password").click(function(){
        $("#old_passwordTip").removeClass();
        $("#old_passwordTip").addClass('onFocus');
        $("#old_passwordTip").html("密码应该为6-20位之间");
    
    }).blur(function(){
        var pwd=$.trim($(this).val());
        if(!(/^.{6,20}$/.test(pwd))) {
            $("#old_passwordTip").removeClass();
            $("#old_passwordTip").addClass('onError');

        }else{
            $.ajax({
                type:'post',
                url:'?d=admin&c=manage&m=edit_pwd_ajax',
                dataType:'text',
                data:$("input[name='old_password']"),
                success:function(str){
                    if(str=='yes'){
                        $("#old_passwordTip").removeClass();
                        $("#old_passwordTip").addClass('onCorrect');
                        $("#old_passwordTip").html("旧密码输入正确！")
                    }else{
                        $("#old_passwordTip").removeClass();
                        $("#old_passwordTip").addClass('onError');
                        $("#old_passwordTip").html("旧密码错误！")
                    }
                }
            });
              
        }
    });
        
    $("#new_password").click(function(){
        $("#new_passwordTip").removeClass();
        $("#new_passwordTip").addClass('onFocus');
        $("#new_passwordTip").html("密码应该为6-20位之间");
    
    }).blur(function(){
        var pwd=$.trim($(this).val());
        if(!(/^.{6,20}$/.test(pwd))) {
            $("#new_passwordTip").removeClass();
            $("#new_passwordTip").addClass('onError');

        }else{
            $("#new_passwordTip").removeClass();
            $("#new_passwordTip").addClass('onCorrect');
            $("#new_passwordTip").html("格式正确！")
        }
    });
        
        
    $("#new_pwdconfirm").click(function(){
        $("#new_pwdconfirmTip").removeClass();
        $("#new_pwdconfirmTip").addClass('onFocus');
        $("#new_pwdconfirmTip").html("两次输入的密码应该一致");
    
    }).blur(function(){
        var pwd1=$.trim($(this).val());
        var pwd2=$.trim($('#new_password').val());
        if(pwd1!=pwd2 || pwd1.length<6 || pwd2.length<6) {
            $("#new_pwdconfirmTip").removeClass();
            $("#new_pwdconfirmTip").addClass('onError');

        }else{
            $("#new_pwdconfirmTip").removeClass();
            $("#new_pwdconfirmTip").addClass('onCorrect');
            $("#new_pwdconfirmTip").html("密码一致！")
        }
    });
        
    $("#dosubmit").click(function(){
        if($(".onError").length>0) return false;
        if(!$('#old_password').val()) return false;
        $.ajax({
            type:'post',
            url:'?d=admin&c=manage&m=edit_pwd',
            dataType:'text',
            data:$("input[type='password'],input[type='submit']"),
            success:function(str){
                console.log(str);
                if(str=='yes'){
                    $('.modal-title').text("提示");
                    $('.modal-body').html("修改成功！");
                    $('#myModal').modal()
                }else{
                    $('.modal-title').text("提示");
                    $('.modal-body').html("修改失败！");
                    $('#myModal').modal()
                }
            }
        });
    });
        
        
</script>
<?php $this->load->view('common/footer'); ?>
