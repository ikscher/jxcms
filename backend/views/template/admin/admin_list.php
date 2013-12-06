<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<div class="pad_10">
    <!--导航-->
    <div class="nav_">
        
        <ul>   

            <!--<li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>-->
        </ul>


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


</div>
<script type="text/javascript">
    
    
    $('.editMember').click(function(){
        var userid=$(this).attr('data-userid');
        var roleid = $(this).attr('data-roleid');
        location.href='index.php?d=admin&c=manage&m=edit&userid='+userid+'&roleid='+roleid;
    });
    
    $('.deleteMember').click(function(){
        var userid=$(this).attr('data-userid');
        if(confirm("<?php echo $this->lang->line('admin_del_cofirm');?>")){
            $.post("?d=admin&c=manage&m=delete",{userid:userid},function(){
                
            })
            location.href=location.href;
        }
    })
</script>
<?php $this->load->view('common/footer'); ?>