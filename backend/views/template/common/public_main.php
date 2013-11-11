<?php $this->load->view('common/header'); ?>
<div id="main_frameid" class="pad-10 display" style="_margin-right:-12px;_width:98.9%;">
    <div class="col-2 mb10" >
        <h6><?php echo $this->lang->line('personal_information') ?></h6>
        <div class="content">
            <?php echo $this->lang->line('main_hello') ?><?php echo $adminusername ?><br />
            <?php echo $this->lang->line('main_role') ?><?php echo $rolename ?> <br />
            <div class="bk20 hr"><hr /></div>
            <?php echo $this->lang->line('main_last_logintime') ?><?php echo date('Y-m-d H:i:s', $logintime) ?><br />
            <?php echo $this->lang->line('main_last_loginip') ?><?php echo $loginip ?> <br />
        </div>
    </div>

    <div class="col-2 mb10">
        <h6><?php echo $this->lang->line('main_sysinfo') ?></h6>
        <div class="content">
            <?php echo $this->lang->line('main_os') ?><?php echo $sysinfo['os'] ?> <br />
            <?php echo $this->lang->line('main_web_server') ?><?php echo $sysinfo['web_server'] ?> <br />
            <?php echo $this->lang->line('main_sql_version') ?><?php echo $sysinfo['mysqlv'] ?><br />
            <?php echo $this->lang->line('main_upload_limit') ?><?php echo $sysinfo['fileupload'] ?><br />	
        </div>
    </div>
</div>
<?php $this->load->view('common/footer'); ?>

