<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table_form.css'); ?>" rel="stylesheet" type="text/css">
<div class="pad_10">
    <div class="common-form">
        <form name="myform" action="?m=admin&c=admin_manage&a=edit" method="post" id="myform">
<!--            <input type="hidden" name="info[userid]" value="<?php echo $userid ?>"></input>
            <input type="hidden" name="info[username]" value="<?php echo $username ?>"></input>-->
            <table width="100%" class="table">
                <tr>
                    <td width="80"><?php echo $this->lang->line('username') ?></td> 
                    <td>username</td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('password') ?></td> 
                    <td><input type="password" name="info[password]" id="password" class="input-text"></input></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('cofirmpwd') ?></td> 
                    <td><input type="password" name="info[pwdconfirm]" id="pwdconfirm" class="input-text"></input></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('email') ?></td>
                    <td>
                        <input type="text" name="info[email]" value="" class="input-text" id="email" size="30"></input>
                    </td>
                </tr>

                <tr>
                    <td><?php echo $this->lang->line('realname') ?></td>
                    <td>
                        <input type="text" name="info[realname]" value="" class="input-text" id="realname"></input>
                    </td>
                </tr>
                <?php if ($roleid == 1) { ?>
                    <tr>
                        <td><?php echo $this->lang->line('userinrole') ?></td>
                        <td>
                            <select name="info[roleid]">
                                <?php foreach ($roles as $role): ?>
                                    
                                    <option value="<?php echo $role['roleid'] ?>" <?php echo (($role['roleid'] == $roleid) ? 'selected' : '') ?>><?php echo $role['rolename'] ?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>
                <?php } ?>
                <tr><td><input name="dosubmit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" id="dosubmit"></td><td></td></tr>
            </table>

            
        </form>
    </div>
</div>
<?php $this->load->view('common/footer'); ?>

