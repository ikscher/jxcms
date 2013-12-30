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

<!--<form action="?d=content&c=type_manage&m=add" method="post" id="myform">-->
    <div class="pad_10">
        <div class="nav_">
            <ul>   
                <li>                       
                    <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                    <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
                </li>
            </ul>
        </div>

        <div class="col-2 col-left mr6" style="width:560px;height:352px;">
            <h6><?php echo $this->lang->line('add_type'); ?></h6>
            <table width="100%"  class="table  table-condensed center">
                <tr>
                    <th width="80"><?php echo $this->lang->line('type_name') ?>：</th>
                    <td class="y-bg">
                        <textarea name="info[name]" rows="2" cols="20" id="name" class="form-control"   style="height:100px;width:460px;max-width: 460px;"><?php echo $r['name'];?></textarea>
                        <div id="nameTips"></div>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('description') ?>：</th>
                    <td class="y-bg"><textarea name="info[description]" maxlength="255" class="form-control" style="width:460px;max-width: 460px;height:100px;"><?php echo $r['description'];?></textarea></td>
                </tr>
            </table>
        </div>
        <div class="col-2 col-auto">
            <div class="content" style="overflow-x:hidden;overflow-y:auto;height:350px;">
                <table width="100%" class="table  table-condensed center">
                    <thead>
                        <tr>
                            <th width="25"><input type="checkbox" value="" id="check_box" onclick="selectAll('ids[]');" title="<?php echo $this->lang->line('selected_all'); ?>"></th><th align="left"><?php echo $this->lang->line('catname'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $categories; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bk15"></div>
        <input type='hidden' name="typeid" value="<?php echo $r['typeid'];?>" />
        <input type="hidden" name="catids_string" value="<?php echo $catids_string;?>">
        <input type="submit" class="btn btn-default" id="dosubmit" name="dosubmit" value="<?php echo $this->lang->line('submit'); ?>" />
    </div>

<!--</form>-->
<script type="text/javascript">
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('edit_type');?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        location.href="?d=content&c=type_manage&m=index";
    });
    
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    
    if(!$.trim($('.content table tbody').text())){
       $.scojs_confirm({
           content: "<?php echo $this->lang->line('empty_category');?>",
           action: function() {
              
              window.location.href='?d=content&c=type_manage&m=index';
          }
       }).show();
       $('.modal-footer a:eq(0)').addClass('hidden');
    }
    
      //模型名称校验
    $("#name").click(function(){
        $("#nameTips").removeClass();
        $("#nameTips").addClass('onFocus');
        $("#nameTips").html("请输入类别名称");
    }).blur(function(){
        var name=$.trim($(this).val());
        if(!(/^[\u4E00-\u9FA5\w]{2,20}$/.test(name))) {
            $("#nameTips").removeClass();
            $("#nameTips").addClass('onError');
            $("#nameTips").html("请输入类别名称，应该为2-20位之间汉字（字母）");
            $(this).focus();
        }else{
            $("#nameTips").removeClass();
            $("#nameTips").addClass('onCorrect');
            $("#nameTips").html("格式正确！")
        }
    });
    
    
     $("#dosubmit").click(function(){
        if(!$('#name').val() && !(/^[\u4E00-\u9FA5\w]{2,20}$/.test(name))){
            $("#nameTips").addClass('onError');
            $("#nameTips").html("请输入类别名称，应该为2-20位之间汉字（字母）!");
        }
        
        if($(".onError").length>0) {
            return false;
        }
        
        $.ajax({
            type:'post',
            url:'?d=content&c=type_manage&m=edit',
            dataType:'json',
            data:$("input[type='submit'],textarea,input[type='checkbox']:checked,input[type='hidden']"),
            success:function(data){
                console.log(data);
                $.scojs_confirm({
                    content: "<?php echo $this->lang->line('editfinished');?>",
                    action: function() {
                       this.close();
                   }
                }).show();
                $('.modal-footer a:eq(0)').addClass('hidden');
                
            }
        });
    });
    
    function selectAll(){
        //var arr = new Array();
        var inputs = document.getElementsByTagName("input");
        for(var i = 0; i< inputs.length; i ++){
            if(inputs[i].type == "checkbox")
            {
                if(inputs[i].getAttribute('checked')=='true')
                   inputs[i].removeAttribute('checked');
                 else
                   inputs[i].setAttribute('checked','true');
            }
        }
  
    }
</script>  
<?php $this->load->view('common/footer'); ?>
