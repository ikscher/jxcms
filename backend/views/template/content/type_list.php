<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css">


<div class="pad_10">
    <div class="nav_">

        <ul>
            <li><a class="addType" href="?d=content&c=type_manage&m=add"><?php echo $this->lang->line('add_type'); ?></a></li>
        </ul>
    </div>

    <div class="table-list">
        <table width="100%" class="table table-striped  table-hover table-condensed center" >
            <thead>
                <tr>
                    <th width="5%"><a href="index.php?d=content&c=type_manage&m=index&by=listorder&order=<?php echo $order; ?>"><?php echo $this->lang->line('listorder'); ?></a></td>
                    <th width="5%">ID</th>
                    <th width="20%"><?php echo $this->lang->line('type_name'); ?></th>
                    <th width="*"><?php echo $this->lang->line('description'); ?></th>
                    <th width="30%"><?php echo $this->lang->line('operations_manage'); ?></th>
                </tr>
            </thead>
            <tbody>


                <?php
                foreach ($datas as $r) {
                    ?>
                    <tr>
                        <td align="center"><input type="text" name="listorders[<?php echo $r['typeid'] ?>]"  value="<?php echo $r['listorder'] ?>" size="3" class='input-text-c'></td>
                        <td align="center"><?php echo $r['typeid'] ?></td>
                        <td align="center"><?php echo $r['name'] ?></td>
                        <td ><?php echo $r['description'] ?></td>
                        <td align="center"><a  class="edit" data-id="<?php echo $r['typeid'];?>" href="javascript:;"><?php echo $this->lang->line('edit'); ?></a> | <a href="javascript:;" class="delete" data-id="<?php echo $r['typeid']; ?>"><?php echo $this->lang->line('delete') ?></a> </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <ul class="pagination"><?php echo $pagination; ?></ul>
        <!--<input type="submit" class="btn btn-default" name="dosubmit" value="<?php echo $this->lang->line('listorder') ?>" />-->
    </div>

</div>


<input type='hidden' name="typeid" value="" />
<script type="text/javascript"> 
    $('input[name^=listorders]').on('keydown',function(e){
        if(e.keyCode==13){
            $.ajax({
                url:'?d=content&c=type_manage&m=listOrder',
                type:'post',
                dataType:'json',
                data:$('input[name^=listorders]'),
                success:function(){
                    $('.modal-footer a:eq(1)').addClass('hidden');
                    $.scojs_confirm({ 
                        content:"<?php echo $this->lang->line('orderfinished'); ?>"
                    }).show();
                }
            })
        }
    });
    
    
    var confirm =  $.scojs_confirm({
        content: "<?php echo $this->lang->line('confirm'); ?>",
        action: function() {
            var typeid = $('input[name=typeid]').val();
            $.get("?d=content&c=type_manage&m=delete",{typeid:typeid},function(){
                $('tbody').find('a.delete').map(function(i,w){
                    if($(w).attr('data-id')==typeid){
                        $(w).parents('tr').remove();
                    }
                });
            })
            this.close();
        }
    });
    
    $('.delete').on('click',function(){
        $('.modal-footer .btn:eq(1)').removeClass('hidden');
        var typeid=$(this).attr('data-id');
        if(!typeid) return false;
        $('input[name=typeid]').val(typeid);
        
        confirm.show();
    });
    
    $('.edit').on('click',function(){
        var typeid = $(this).attr('data-id');
        location.href = '?d=content&c=type_manage&m=edit&typeid='+typeid;
    });
   
</script>
<?php $this->load->view('common/footer'); ?>
