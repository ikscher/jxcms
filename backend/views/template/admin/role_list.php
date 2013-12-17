<?php $this->load->view('common/header'); ?>

<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<style type='text/css'>
    .modal {
	top: 10%;
	left: 50%;
	z-index: 1050;
	width: 560px;
	margin-left: -280px;
	background-color: #fff;
	border: 1px solid #999;
	border: 1px solid rgba(0,0,0,0.3);
	*border: 1px solid #999;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
	outline: 0;
	-webkit-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
	-moz-box-shadow: 0 3px 7px rgba(0,0,0,0.3);
	box-shadow: 0 3px 7px rgba(0,0,0,0.3);
	-webkit-background-clip: padding-box;
	-moz-background-clip: padding-box;
	background-clip: padding-box;
    overflow-y:hidden;
    height:140px;
}
</style>
<div class="pad_10">

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="nav_">
        <form name="myform" action="?d=admin&c=role&m=index" method="post">
            <ul >
                <li><?php echo $this->lang->line('role_name'); ?></li><li><input name="rolename" type="text" class="form-control input-sm" placeholder="" value="<?php echo $rolename; ?>" /></li>
                <li><?php echo $this->lang->line('role_desc'); ?></li><li><input name="roledesc" type="text" class="form-control input-sm" placeholder="" value="<?php echo $roledesc; ?>" /></li>

                <li><select name="status" class="form-control input-sm">
                        <option value='0' <?php if ($status == 0) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('unlocked'); ?></option>
                        <option value='1' <?php if ($status == 1) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('locked'); ?></option>
                    </select></li>

                <li>
                    <button type="submit" class="btn btn-default"><?php echo $this->lang->line('query'); ?></button>
                    <button  name="refresh" class="btn btn-default"><?php echo $this->lang->line('refresh'); ?></button>
                </li>
            </ul>
        </form>


        <ul>
            <li><a class="roleAdd" href="?d=admin&c=role&m=add"><?php echo $this->lang->line('role_add'); ?></a></li>

        </ul>
    </div>


    <table class="table table-striped table-bordered table-hover table-condensed center">
        <thead>
            <tr>
                <th width="10%"><a href="?d=admin&c=role&m=index&by=listorder&order=<?php echo $order; ?>"><?php echo $this->lang->line('listorder'); ?></a></th>
                <th width="10%"><a href="?d=admin&c=role&m=index&by=roleid&order=<?php echo $order; ?>">ID</a></th>
                <th width="15%"  ><?php echo $this->lang->line('role_name'); ?></th>
                <th width="23%"  ><?php echo $this->lang->line('role_desc'); ?></th>
                <th width="5%"  ><?php echo $this->lang->line('role_status'); ?></th>
                <th ><?php echo $this->lang->line('role_operation'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($roles)) { ?>
                <?php foreach ($roles as $info): ?>
                    <tr>
                        <td width="10%" align="center"><input name='listorders[<?php echo $info['roleid'] ?>]' type='text' size='3' value='<?php echo $info['listorder'] ?>' class="input-text-c"></td>
                        <td width="10%" align="center"><?php echo $info['roleid'] ?></td>
                        <td width="15%"  ><?php echo $info['rolename'] ?></td>
                        <td width="23%" ><?php echo $info['description'] ?></td>
                        <td width="5%" align="center"><a class="updateStatus" data-roleid="<?php echo $info['roleid']?>" data-disabled="<?php echo $info['disabled']==1?0:1;?>" href="javascript:void(0);"><?php echo $info['disabled'] ? $this->lang->line('icon_locked') : $this->lang->line('icon_unlock') ?></a></td>
                        <td  align="center">
                            <?php if ($info['roleid'] > 1) { ?>
                                <a data-roleid="<?php echo $info['roleid'] ?>" data-rolename="<?php echo $info['rolename'] ?>" class="setPriv" href="javascript:void(0);"><?php echo $this->lang->line('role_setting'); ?></a> | <!--<a href="javascript:void(0)" onclick="setting_cat_priv(<?php echo $info['roleid'] ?>, '<?php echo $info['rolename'] ?>')"><?php echo $this->lang->line('usersandmenus') ?></a> |-->
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('role_setting'); ?></font>  |
                            <?php } ?>
                            <a  data-roleid="<?php echo $info['roleid'] ?>" class="getRoleMembers" href="javascript:void(0);" ><?php echo $this->lang->line('role_member_manage'); ?></a> | 
                            <?php if ($info['roleid'] > 1) { ?><a class='editRole' data-roleid="<?php echo $info['roleid'] ?>" href="javascript:void(0);"><?php echo $this->lang->line('edit') ?></a> | 
                                <a  data-roleid="<?php echo $info['roleid'] ?>"   class="deleteRole" href="javascript:void(0);"><?php echo $this->lang->line('delete') ?></a>
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('edit') ?></font> | <font color="#cccccc"><?php echo $this->lang->line('delete') ?></font>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php } ?>

        </tbody>
    </table>
    <ul class="pagination"><?php echo $pagination; ?></ul>
    <input type='hidden' name='roleid' value='' />
</div>

<script type="text/javascript" src="<?php echo base_url('views/javascript/sco.modal.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/javascript/sco.confirm.js'); ?>"></script>
<script type="text/javascript">
    $("button[name=refresh]").click(function(){
        location.href="?d=admin&c=role&m=index";return false;
    });
    
    //角色删除
    var confirm=$.scojs_confirm({
        content: "<?php echo $this->lang->line('role_del_confirm');?>",
        action: function() {
            
            var roleid = $('input[name=roleid]').val();
            $.get('?d=admin&c=role&m=delete',{roleid:roleid},function(str){
                if(str=='yes'){
                    location.href=location.href;
                }else if(str=='exist'){
                     $('.modal-footer .btn:eq(1)').addClass('hidden');
                     $.scojs_confirm({
                        content: "<?php echo $this->lang->line('role_del_exists');?>"
                      }).show();
                    
                }else if(str=='no'){
                     $('.modal-footer .btn:eq(1)').addClass('hidden');
                     $.scojs_confirm({
                        content: "<?php echo $this->lang->line('role_del_fail');?>"
                      }).show();
                }
            });
            this.close();
        }
    });
    
    $('.deleteRole').on('click',function(){
       var roleid=$(this).attr('data-roleid');
       if (!roleid) return false;
       $('input[name=roleid]').val(roleid);
       $('.modal-footer .btn:eq(1)').removeClass('hidden');
       confirm.show();
    });
    

   
       
   
  
    
    //权限设置
    $('.setPriv').click(function(){
        var roleid=$(this).attr('data-roleid');
        var rolename = $(this).attr('data-rolename');
        if(!roleid) return false;
        location.href='?d=admin&c=role&m=setPriv&roleid='+roleid+'&rolename='+rolename;
    });
    
    //角色编辑
    $('.editRole').click(function(){
        var roleid=$(this).attr('data-roleid');
        if(!roleid) return false;
        location.href='?d=admin&c=role&m=edit&roleid='+roleid;
    });
 
    //更新角色状态
    $('.updateStatus').click(function(){
        var that = $(this);
        var roleid = $(this).attr('data-roleid');
        var disabled = $(this).attr('data-disabled');
        
        if(!roleid) return false;
        $.get('?d=admin&c=role&m=change_status',{roleid:roleid,disabled:disabled},function(str){
            if(str=='yes'){
                if(disabled==1){
                    that.html('<font color="blue">×</font>');
                    that.attr('data-disabled',0);
                }else if (disabled==0){
                    that.html('<font color="red">√</font>');
                    that.attr('data-disabled',1);
               }
            }else{
                $('.modal-title').text("提示");
                $('.modal-body').html("更新角色状态失败！");
                $('#myModal').modal();
            }
        })
    });
    
    //成员管理
    $('.getRoleMembers').click(function(){
        var roleid=$(this).attr('data-roleid');
        if(!roleid) return false;
        location.href='?d=admin&c=role&m=manage_member&roleid='+roleid;
    })
</script>
<?php $this->load->view('common/footer'); ?>
