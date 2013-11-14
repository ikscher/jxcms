<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table_form.css'); ?>" rel="stylesheet" type="text/css" />
<div class="pad_10">

    <div class="navbar navbar-default" role="navigation">
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse " id="bs-example-navbar-collapse-1">
            <form name="myform" action="?d=admin&c=role&m=index" method="post" class="navbar-form navbar-left" role="search">
                <ul class="nav navbar-nav">
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



            <ul class="nav navbar-nav navbar-right">
                <li><a class="roleAdd" href="?d=admin&c=role&m=add"><?php echo $this->lang->line('role_add');?></a></li>

            </ul>
        </div><!-- /.navbar-collapse -->
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
                        <td width="5%" align="center"><a href="?m=admin&c=role&a=change_status&roleid=<?php echo $info['roleid'] ?>&disabled=<?php echo ($info['disabled'] == 1 ? 0 : 1) ?>"><?php echo $info['disabled'] ? $this->lang->line('icon_locked') : $this->lang->line('icon_unlock') ?></a></td>
                        <td  align="center">
                            <?php if ($info['roleid'] > 1) { ?>
                                <a href="javascript:setting_role(<?php echo $info['roleid'] ?>, '<?php echo $info['rolename'] ?>')"><?php echo $this->lang->line('role_setting'); ?></a> | <a href="javascript:void(0)" onclick="setting_cat_priv(<?php echo $info['roleid'] ?>, '<?php echo $info['rolename'] ?>')"><?php echo $this->lang->line('usersandmenus') ?></a> |
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('role_setting'); ?></font> | <font color="#cccccc"><?php echo $this->lang->line('usersandmenus') ?></font> |
                            <?php } ?>
                            <a href="?m=admin&c=role&a=member_manage&roleid=<?php echo $info['roleid'] ?>&menuid="><?php echo $this->lang->line('role_member_manage'); ?></a> | 
                            <?php if ($info['roleid'] > 1) { ?><a href="?m=admin&c=role&a=edit&roleid=<?php echo $info['roleid'] ?>&menuid="><?php echo $this->lang->line('edit') ?></a> | 
                                <a href="javascript:confirmurl('?m=admin&c=role&a=delete&roleid=<?php echo $info['roleid'] ?>', '<?php echo $this->lang->line('posid_del_cofirm') ?>')"><?php echo $this->lang->line('delete') ?></a>
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('edit') ?></font> | <font color="#cccccc"><?php echo $this->lang->line('delete') ?></font>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php } ?>
        </tbody>
    </table>
</div>
</body>
<script type="text/javascript">
    $("button[name=refresh]").click(function(){
        location.href="?d=admin&c=role&m=index";return false;
    });
  
</script>
<?php $this->load->view('common/footer'); ?>
