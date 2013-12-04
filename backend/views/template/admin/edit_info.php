<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<div class="pad_10">
    <div class="common-form">

        <table width="100%" class="table">
            <tr>
                <td width="100"><?php echo $this->lang->line('username') ?></td> 
                <td><?php echo $info['username'] ?></td>
            </tr>

            <tr>
                <td width="100"><?php echo $this->lang->line('lastlogintime') ?></td> 
                <td><?php echo date('Y-m-d H:i:s', $info['lastlogintime']) ?></td>
            </tr>

            <tr>
                <td width="100"><?php echo $this->lang->line('lastloginip') ?></td> 
                <td><?php echo $info['lastloginip'] ?></td>
            </tr>

            <tr>
                <td><?php echo $this->lang->line('realname') ?></td>
                <td>
                    <div class="col-xs-4"><input type="text" name="info[realname]" id="realname" class="form-control"  value="<?php echo $info['realname'] ?>"></input></div>
                    <div id="realnameTip" ></div>
                </td>
            </tr>
            <tr>
                <td><?php echo $this->lang->line('email') ?></td>
                <td>
                    <div class="col-xs-4"><input type="text" name="info[email]" id="email" class="form-control"  value="<?php echo $info['email']; ?>"></input></div>
                    <div id="emailTip" ></div>
                </td>
            </tr>
            <tr><td><input name="dosubmit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" id="dosubmit"></td><td></td></tr>

        </table>



    </div>

    <script type="text/javascript">
        $("#dosubmit").click(function(){
            if($(".onError").length>0) return false;
            $.ajax({
                type:'post',
                url:'?d=admin&c=manage&m=edit_info',
                dataType:'text',
                data:$("input[type='text'],input[type='submit']"),
                success:function(str){
                    if(str==1){
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
        
        //真实姓名校验
        $("#realname").click(function(){
            $("#realnameTip").removeClass();
            $("#realnameTip").addClass('onFocus');
            $("#realnameTip").html("真实姓名应该为2-10位之间汉字（字母）");
    
        }).blur(function(){
            var name=$.trim($(this).val());
            if(!(/^[\u4E00-\u9FA5\w]{2,10}$/.test(name))) {
                $("#realnameTip").removeClass();
                $("#realnameTip").addClass('onError');
                $(this).focus();
            }else{
                $("#realnameTip").removeClass();
                $("#realnameTip").addClass('onCorrect');
                $("#realnameTip").html("格式正确！")
            }
        });
        
        //email校验
        $("#email").click(function(){
            $("#emailTip").removeClass();
            $("#emailTip").addClass('onFocus');
            $("#emailTip").html("请输入正确格式的E-mail");
    
        }).blur(function(){
            var email=$.trim($(this).val());
            if(!(/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/.test(email))) {
                $("#emailTip").removeClass();
                $("#emailTip").addClass('onError');
                $(this).focus();
            }else{
                $("#emailTip").removeClass();
                $("#emailTip").addClass('onCorrect');
                $("#emailTip").html("格式正确！")
            }
        });
        
    </script>


</div>

<?php $this->load->view('common/footer'); ?>