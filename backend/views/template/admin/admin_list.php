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
    <!--导航-->
    <div class="nav_">
        <?php if (isset($field)){ ?>
        <form name="myform" action="?d=admin&c=manage&m=index" method="post">
            <ul >
                <li><?php echo $this->lang->line('keyword'); ?></li><li><input name="keyword" type="text" class="form-control input-sm" placeholder="" value="<?php echo $keyword;?>" /></li>
                <li> 
                    <select name="field" class="form-control input-sm">
                        <option value="username" <?php if ($field == 'username') { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('username');?></option>
                        <option value="realname" <?php if ($field == 'realname') { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('realname');?></option>
                        <option value="email" <?php if ($field == 'email') { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('email');?></option>
                    </select>
                </li>
                <li><select name="status" class="form-control input-sm">
                        <option value='1' <?php if ($status == 1) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('unlocked'); ?></option>
                        <option value='2' <?php if ($status == 2) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('locked'); ?></option>
                        <option value='3' <?php if ($status == 3) { ?>selected="selected"<?php } ?>><?php echo $this->lang->line('deleted'); ?></option>
                    </select></li>

                <li>
                    <button type="submit" class="btn btn-default"><?php echo $this->lang->line('query'); ?></button>
                    <button  name="refresh" class="btn btn-default"><?php echo $this->lang->line('refresh'); ?></button>
                </li>
            </ul>
        </form>
        <?php }else{ ?>
         <ul>   

            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>

        <?php } ?>

    </div>
    <!--导航-->
    
    <table class="table table-striped table-bordered table-hover table-condensed center">
        <thead>
            <tr>
                <th width="10%"><?php echo $this->lang->line('userid'); ?></th>
                <th width="10%"><?php echo $this->lang->line('username'); ?></th>
                <th width="10%"><?php echo $this->lang->line('userinrole'); ?></th>
                <th width="10%"><?php echo $this->lang->line('lastloginip'); ?></th>
                <th width="20%"><?php echo $this->lang->line('lastlogintime'); ?></th>
                <th width="15%" ><?php echo $this->lang->line('email'); ?></th>
                <th width="10%"><?php echo $this->lang->line('realname'); ?></th>
                <th width="15%" ><?php echo $this->lang->line('operations_manage'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $admin_founders = 1; ?>
            <?php if (is_array($infos) && count($infos)>0) { ?>
                <?php foreach ($infos as $info): ?>

                    <tr>
                        <td width="10%" align="center"><?php echo $info['userid'] ?></td>
                        <td width="10%" ><?php echo $info['username'] ?></td>
                        <td width="10%" ><?php echo $roles[$info['roleid']]['rolename'] ?></td>
                        <td width="10%" ><?php echo $info['lastloginip'] ?></td>
                        <td width="20%"  ><?php echo $info['lastlogintime'] ? date('Y-m-d H:i:s', $info['lastlogintime']) : '' ?></td>
                        <td width="15%"><?php echo $info['email'] ?></td>
                        <td width="10%"  align="center"><?php echo $info['realname'] ?></td>
                        <td width="15%"  align="center">
                            <a  class="editMember" data-roleid="<?php  echo $info['roleid']?>" data-userid="<?php  echo $info['userid']?>" href="javascript:void(0);"><?php echo $this->lang->line('edit') ?></a> | 
                            <?php if (!in_array($info['userid'], array($admin_founders))) { ?>
                                <a class="deleteMember" data-userid="<?php  echo $info['userid']?>"  href="javascript:void(0);"><?php echo $this->lang->line('delete') ?></a>
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('delete') ?></font>
                            <?php } ?> 
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php }else{ ?>
                  <tr><td colspan="8" align="center"><?php echo $this->lang->line('noresult');?></td></tr>
            <?php }?>       
        </tbody>
    </table>
    <ul class="pagination"><?php echo $pagination; ?></ul>
    <input type='hidden' name='userid' value='' />
 
</div>

<script type="text/javascript">
    
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('role_member_manage');?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        location.href="?d=admin&c=role&m=index";
    });
    
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    
    $('.editMember').click(function(){
        var userid=$(this).attr('data-userid');
        var roleid = $(this).attr('data-roleid');
        location.href='index.php?d=admin&c=manage&m=edit&userid='+userid+'&roleid='+roleid;
    });
    
    
    var confirm=$.scojs_confirm({
        content: "<?php echo $this->lang->line('user_del_confirm');?>",
        action: function() {
            var userid = $('input[name=userid]').val();
            $.post("?d=admin&c=manage&m=delete",{userid:userid},function(){
                
            })
            location.href=location.href;
        }
    });
    
    $('.deleteMember').click(function(){
        var userid=$(this).attr('data-userid');
        $('input[name=userid]').val(userid);
        confirm.show();
    })
</script>
<?php $this->load->view('common/footer'); ?>