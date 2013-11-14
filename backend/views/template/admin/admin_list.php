<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table_form.css'); ?>" rel="stylesheet" type="text/css" />
<div class="pad_10">

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
            <?php if (is_array($infos)) { ?>
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
                            <a href=""><?php echo $this->lang->line('edit') ?></a> | 
                            <?php if (!in_array($info['userid'], array($admin_founders))) { ?>
                                <a href="javascript:confirmurl('?m=admin&c=admin_manage&a=delete&userid=<?php echo $info['userid'] ?>', '<?php echo $this->lang->line('admin_del_cofirm') ?>')"><?php echo $this->lang->line('delete') ?></a>
                            <?php } else { ?>
                                <font color="#cccccc"><?php echo $this->lang->line('delete') ?></font>
                            <?php } ?> 
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php } ?>
        </tbody>
    </table>
    <div id="pages"> </div>


</div>
<?php $this->load->view('common/footer'); ?>