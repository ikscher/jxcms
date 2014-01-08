<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />

<div class="pad_10">
    <div class="nav_">
        
        <ul>   
            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>


    </div>


    <table  class="table">
        <tr>
            <td><?php echo $this->lang->line('role_name') ?></td> 
            <td>
                <div class="col-xs-3"><input type="text" name="info[rolename]" value="" class="form-control" id="rolename"></input></div>
                <div id="rolenameTips"></div>
            </td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('role_desc') ?></td>
            <td><textarea name="info[description]" rows="2" cols="20" id="description" class="form-control" style="height:100px;width:500px;"></textarea></td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('enabled') ?></td>
            <td><label for="disabled"> <input id="disabled"  type="radio" name="info[disabled]" value="1" /><?php echo $this->lang->line('ban') ?></label><label for="enabled"><input id="enabled" type="radio" name="info[disabled]" value="0" checked /> <?php echo $this->lang->line('unlocked') ?>  </label></td>
        </tr>
        <tr>
            <td><?php echo $this->lang->line('listorder') ?></td>
            <td><div class="col-xs-2"><input type="text" name="info[listorder]" class="form-control" maxlength='3' onkeyup="value=value.replace(/[^\d]/ig,'')" /></div></td>
        </tr>

        <tr>
            <td><input name="dosubmit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" /></td>
            <td></td>
        </tr>
    </table>


</div>
<script type="text/javascript">
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('role_add');?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        location.href="?d=admin&c=role&m=index";
    });
    
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    $('input[name=dosubmit]').click(function(){
        if(!($.trim($('#rolename').val())) || $('.onError').length>0) return false;
        $.ajax({
            type:'post',
            url:'?d=admin&c=role&m=add',
            dataType:'text',
            data:$('input[name^="info"],input[type="submit"],textarea[name^="info"]'),
            success:function(str){
                if(str=='yes'){
                    $.scojs_confirm({
                        content: "添加角色成功！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }else{
                    $.scojs_confirm({
                        content: "添加角色失败！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }
            }
        });
    });
    
  
    
    $('#rolename').blur(function(){
        var rolename=$.trim($(this).val());
        if(!(/^[\u4E00-\u9FA5\w]{2,26}/.test(rolename))) {
            $("#rolenameTips").removeClass();
            $("#rolenameTips").addClass('onError');
            $("#rolenameTips").html("输入的字符不合法！")

        }else{
            $.ajax({
                type:'post',
                url:'?d=admin&c=role&m=exists',
                dataType:'text',
                data:{rolename:rolename},
                success:function(str){
                    if(str=='no'){
                        $("#rolenameTips").removeClass();
                        $("#rolenameTips").addClass('onCorrect');
                        $("#rolenameTips").html("可以使用，输入正确！")
                    }else{
                        $("#rolenameTips").removeClass();
                        $("#rolenameTips").addClass('onError');
                        $("#rolenameTips").html("已经存在同名的角色！")
                    }
                }
            });
              
        }
    });
</script>
<?php $this->load->view('common/footer'); ?>


