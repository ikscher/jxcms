<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css">
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

        <ul>
            <li><a class="addModel" href="?d=content&c=sitemodel&m=add"><?php echo $this->lang->line('add_model'); ?></a></li>
            <li><a class="importModel" href="?d=admin&c=role&m=add"><?php echo $this->lang->line('import_model'); ?></a></li>
        </ul>
    </div>
    <div class="table-list">
        <table width="100%" class="table table-striped  table-hover table-condensed center" >
            <thead>
                <tr>
                    <th width="100">modelid</th>
                    <th width="100"><?php echo $this->lang->line('model_name'); ?></th>
                    <th width="100"><?php echo $this->lang->line('tablename'); ?></th>
                    <th ><?php echo $this->lang->line('description'); ?></th>
                    <th width="100"><?php echo $this->lang->line('status'); ?></th>
                    <th width="100"><?php echo $this->lang->line('items'); ?></th>
                    <th width="230"><?php echo $this->lang->line('operations_manage'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($datas as $r) {
                    $tablename = $r['name'];
                    ?>
                    <tr>
                        <td align='center'><?php echo $r['modelid'] ?></td>
                        <td align='center'><?php echo $tablename ?></td>
                        <td align='center'><?php echo $r['tablename'] ?></td>
                        <td align='center'>&nbsp;<?php echo $r['description'] ?></td>
                        <td align='center'><?php echo $r['disabled'] ? $this->lang->line('icon_locked') : $this->lang->line('icon_unlock') ?></td>
                        <td align='center'><?php echo $r['items'] ?></td>
                        <td align='center'>
                            <!--<a href="?m=content&c=sitemodel_field&a=init&modelid=<?php echo $r['modelid'] ?>&menuid=<?php echo $menuid; ?>"><?php echo $this->lang->line('field_manage'); ?></a> |--> 
                            <a href="javascript:;" class="edit" data-modelid="<?php echo $r['modelid']?>"><?php echo $this->lang->line('edit'); ?></a> | 
                            <a href="javascript:;" class="yorn" data-modelid="<?php echo $r['modelid']?>" data-enabled="<?php echo $r['disabled'];?>"><?php echo $r['disabled'] ? $this->lang->line('field_enabled') : $this->lang->line('field_disabled'); ?></a> | 
                            <a href="javascript:;" class="delete" data-modelid="<?php echo $r['modelid']?>"><?php echo $this->lang->line('delete') ?></a> | 
                            <a href="?m=content&c=sitemodel&a=export&modelid=<?php echo $r['modelid'] ?>&menuid=<?php echo $menuid; ?>""><?php echo $this->lang->line('export'); ?></a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <ul class="pagination"><?php echo $pagination; ?></ul>
    </div>
</div>
<input type="hidden" name="modelid" value="" />
<script type="text/javascript"> 
    var confirm=$.scojs_confirm({
        content: "<?php echo $this->lang->line('confirm_delete_model');?>",
        action: function() {
            var modelid = $('input[name=modelid]').val();
            $.post("?d=content&c=sitemodel&m=delete",{modelid:modelid},function(){
                $('tbody').find('a.delete').map(function(i,w){
                    if($(w).attr('data-modelid')==modelid){
                        $(w).parents('tr').remove();
                    }
                });
            })
            
        }
    });
    
    $('.delete').click(function(){
        var modelid=$(this).attr('data-modelid');
        $('input[name=modelid]').val(modelid);
        confirm.show();
    })
    
    $('.edit').click(function(){
        var modelid=$(this).attr('data-modelid');
        location.href='index.php?d=content&c=sitemodel&m=edit&modelid='+modelid;
    });
    
    $('.yorn').click(function(){
        var modelid=$(this).attr('data-modelid');
        var disabled = $(this).attr('data-enabled');
        $.get('index.php?d=content&c=sitemodel&m=disabled&modelid='+modelid+'&disabled='+disabled,function(){
            location.href=location.href;
        });
    });


</script>
<?php
$this->load->view('common/footer');
