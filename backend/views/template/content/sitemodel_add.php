<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css">
<style type="text/css">
    .table{margin-bottom: 0px;}
</style>
<div class="pad_10">
    <div class="nav_">
        <ul>   
            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>
    </div>
    
    <!--<form action="?d=content&c=sitemodel&m=add" method="post" id="myform">-->
        <fieldset>
            <legend><?php echo $this->lang->line('basic_configuration') ?></legend>
            <table width="100%"  class="table  table-condensed center" >
                <tr>
                    <td width="120"><?php echo $this->lang->line('model_name') ?>：</td>
                    <td class="y-bg"><input type="text" class="form-control input-sm width_20" name="info[name]" id="name" size="30" /><div id="nameTips"></div></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('model_tablename') ?>：</td>
                    <td class="y-bg"><input type="text" class="form-control input-sm width_20" name="info[tablename]" id="tablename" size="30" /><div id="tablenameTips"></div></td>
                </tr>
                <tr>
                    <td><?php echo $this->lang->line('description') ?>：</td>
                    <td class="y-bg"><input type="text" class="form-control input-sm width_20" name="info[description]" id="description"  size="30"/><div id="descTips"></div></td>
                </tr>
            </table>
        </fieldset>
        <div class="bk15"></div>
        <fieldset>
            <legend><?php echo $this->lang->line('template_setting') ?></legend>
            <table width="100%"  class="table  table-condensed center">
                <tr>
                    <td width="200"><?php echo $this->lang->line('available_styles'); ?></td>
                    <td>
                        <?php echo $this->form->select($style_list, '', 'name="info[default_style]" id="default_style" onchange="load_file_list(this.value)"', $this->lang->line('please_select')) ?> 
                        <div id="templateTips"></div>
                    </td>
                </tr>
                <tr>
                    <td width="200"><?php echo $this->lang->line('category_index_tpl') ?>：</td>
                    <td  id="category_template">
                    </td>
                </tr>
                <tr>
                    <td width="200"><?php echo $this->lang->line('category_list_tpl') ?>：</td>
                    <td  id="list_template">
                    </td>
                </tr>
                <tr>
                    <td width="200"><?php echo $this->lang->line('content_tpl') ?>：</td>
                    <td  id="show_template">
                    </td>
                </tr>
            </table>
        </fieldset>
        <div class="bk15"></div>
<!--        <fieldset>
            <legend><?php echo $this->lang->line('other_template_setting') ?> <input type="checkbox" id="other" value="1" name="other"></legend>
            <table width="100%" id="other_tab" class="table_form" style="display:none;">
                <tr>
                    <td width="200"><?php echo $this->lang->line('admin_content_list') ?></td>
                    <td  id="admin_list_template"><?php echo $admin_list_template; ?>
                    </td>
                </tr>
                <tr>
                    <td width="200"><?php echo $this->lang->line('member_content_add') ?></td>
                    <td  id="member_add_template"><?php echo $this->form->selectTemplate($default_style, 'member', '', 'name="setting[member_add_template]" id="template_member_add"', 'content_publish') ?>
                    </td>
                </tr>
            </table>
        </fieldset>-->

        <input type="submit" class="btn btn-default" id="dosubmit" name="dosubmit" value="<?php echo $this->lang->line('submit'); ?>" />
    <!--</form>-->
</div>
<script type="text/javascript">
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('add_model');?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        location.href="?d=content&c=sitemodel&m=index";
    });
    
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    
    function load_file_list(id) {
        if (!id) return false;
        $.getJSON('?d=admin&c=category&m=publicTplFileList&style='+id+'&catid=', function(data){$('#category_template').html(data.category_template);$('#list_template').html(data.list_template);$('#show_template').html(data.show_template);});
    }
    
    $("#other").click(function() {
        if ($('#other').attr('checked')) {
            $('#other_tab').show();
        } else {
            $('#other_tab').hide();
        }
    })
    
    
     $("#dosubmit").click(function(){
        if(!$('#name').val()){
            $("#nameTips").addClass('onError');
            $("#nameTips").html("模型名称应该为2-10位之间汉字（字母）");
        }
        
        if(!$('#tablename').val()){
            $("#tablenameTips").addClass('onError');
            $("#tablenameTips").html("模型表应该为2-12位之间数字、字母");
        }
        
        if($(".onError").length>0) {
            return false;
        }
        $.ajax({
            type:'post',
            url:'?d=content&c=sitemodel&m=add',
            dataType:'text',
            data:$("input[type='text'],input[type='submit'],textarea,select"),
            success:function(str){
                if(str==1){
                    $.scojs_confirm({
                        content: "添加模型成功！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }else{
                    $.scojs_confirm({
                        content: "添加模型成功！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }
            }
        });
    });
    
     //模型名称校验
    $("#name").click(function(){
        $("#nameTips").removeClass();
        $("#nameTips").addClass('onFocus');
        $("#nameTips").html("模型名称应该为2-10位之间汉字（字母）");
    }).blur(function(){
        var name=$.trim($(this).val());
        if(!(/^[\u4E00-\u9FA5\w]{2,10}$/.test(name))) {
            $("#nameTips").removeClass();
            $("#nameTips").addClass('onError');
            $(this).focus();
        }else{
            $("#nameTips").removeClass();
            $("#nameTips").addClass('onCorrect');
            $("#nameTips").html("格式正确！")
        }
    });
    
     //模型表校验
    $("#tablename").click(function(){
        $("#tablenameTips").removeClass();
        $("#tablenameTips").addClass('onFocus');
        $("#tablenameTips").html("模型名称应该为2-12位之间数字、字母");
    }).blur(function(){
        var name=$.trim($(this).val());
        if(!(/^(\w){2,12}$/.test(name))) {
            $("#tablenameTips").removeClass();
            $("#tablenameTips").addClass('onError');
            $(this).focus();
        }else{
            $("#tablenameTips").removeClass();
            $("#tablenameTips").addClass('onCorrect');
            $("#tablenameTips").html("格式正确！")
        }
    });
</script>
<?php $this->load->view('common/footer'); ?>