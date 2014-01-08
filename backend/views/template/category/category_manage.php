<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css">

<form name="myform" action="?m=admin&c=category&a=listorder" method="post">
    <div class="pad_10">
        <div class="explain-col">
            <?php echo $this->lang->line('category_cache_tips'); ?>，<a href="javascript:void(0);" class="updateCategory"><?php echo $this->lang->line('update_cache'); ?></a>
        </div>


        <table class="table table-striped  table-hover table-condensed center">
            <thead>
                <tr>
                    <th width="5%"><?php echo $this->lang->line('listorder'); ?></th>
                    <th width="5%">ID</th>
                    <th ><?php echo $this->lang->line('catname'); ?></th>
                    <th width='7%'><?php echo $this->lang->line('category_type'); ?></th>
                    <th width="7%"><?php echo $this->lang->line('modelname'); ?></th>
                    <th width="7%"><?php echo $this->lang->line('items'); ?></th>
                    <th  width="7%"><?php echo $this->lang->line('vistor'); ?></th>
                    <th width="9%"><?php echo $this->lang->line('domain_help'); ?></th>
                    <th width='25%'><?php echo $this->lang->line('operations_manage'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php echo $categories; ?>
            </tbody>
        </table>
        <input type="hidden" name="catid" value="" />
    </div>
</form>
<script type="text/javascript">
    $('.updateCategory').on('click',function(){
        setTimeout( updateCategoryCache,3000);
    });
    
    <?php if(empty($categories)) :?>
        updateCategoryCache();
    <?php endif;?>
    
    function updateCategoryCache(){
        $.ajax({
            type:'post',
            url:'?d=admin&c=category&m=updateCache',
            success:function(){
                location.href=location.href;
            }
        })
    }
    
    $('input[name^=listorders]').on('keydown',function(e){
       if(e.keyCode==13){
          $.ajax({
              url:'?d=admin&c=category&m=listOrder',
              type:'post',
              data:$('input[name^=listorders]'),
              success:function(){
                   $('.modal-footer .btn:eq(1)').addClass('hidden');
                   $.scojs_confirm({ 
                        content:"<?php echo $this->lang->line('orderfinished');?>"
                    }).show();
              } 
          })
          
       }
    })
    
    var confirm =  $.scojs_confirm({
            content: "<?php echo $this->lang->line('confirm');?>",
            action: function() {
                var catid_ = $('input[name=catid]').val();
                $.get("?d=admin&c=category&m=delete",{catid:catid_},function(){
                    $('tbody').find('a.delete').map(function(i,w){
                         if($(w).attr('data-id')==catid_){
                             $(w).parents('tr').remove();
                         }
                    });
                })
                this.close();
            }
        });
    
    $('.delete').on('click',function(){
        $('.modal-footer .btn:eq(1)').removeClass('hidden');
        var catid=$(this).attr('data-id');
        if(!catid) return false;
        $('input[name=catid]').val(catid);
        
        confirm.show();
    });
  
   
</script>
<?php $this->load->view('common/footer'); ?>
