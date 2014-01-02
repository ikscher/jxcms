<?php $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo base_url('views/javascript/kindeditor/kindeditor.js');?>" ></script>
<script type="text/javascript" src="<?php echo base_url('views/javascript/kindeditor/lang/zh_CN.js');?> "></script>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />

<div class="pad_10">
    <!--导航开始-->
    <div class="nav_">

        <ul>   
            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>
    </div>
    <!--导航结束-->
    <!--<form name="myform" id="myform" action="?d=admin&c=category&m=add" method="post" >-->

        <div class="col-tab">

            <ul class="nav nav-tabs">
                <li id="tab_setting_1" onclick="SwapTab('setting','active','',5,1);"  class="active" ><a href="#"><?php echo $this->lang->line('catgory_basic'); ?></a></li>
                <li id="tab_setting_2" onclick="SwapTab('setting','active','',5,2);" ><a href="#"><?php echo $this->lang->line('catgory_createhtml'); ?></a></li>
                <li id="tab_setting_3" onclick="SwapTab('setting','active','',5,3);"><a href="#"><?php echo $this->lang->line('catgory_template'); ?></a></li>
                <li id="tab_setting_4" onclick="SwapTab('setting','active','',5,4);"><a href="#"><?php echo $this->lang->line('catgory_seo'); ?></a></li>
                <li id="tab_setting_5" onclick="SwapTab('setting','active','',5,5);"><a href="#"><?php echo $this->lang->line('catgory_private'); ?></a></li>
                <!--   <li id="tab_setting_6" onclick="SwapTab('setting','active','',6,6);"><a href="#"><?php echo $this->lang->line('catgory_readpoint'); ?></a></li>-->
            </ul>
            <div id="div_setting_1" class="contentList pad-10">

                <table class="table   table-hover  ">
                    <tr>
                        <td width="200"><?php echo $this->lang->line('select_model') ?>：</td>
                        <td>
                            <?php
                                echo $this->form->select($model_datas, $r['modelid'], 'name="info[modelid]" id="modelid" ' . $disabled, $this->lang->line('select_model'));
                                echo $this->lang->line('modelid_edit_tips');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="200"><?php echo $this->lang->line('parent_category') ?>：</td>
                        <td>
                            <?php echo $this->form->select_category('category_content', $parentid, 'name="info[parentid]" id="parentid"', $this->lang->line('please_select_parent_category'), 0, -1); ?> 
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('catname') ?>：</td>
                        <td><input type="text" name="info[catname]" id="catname" class="form-control width_50" value=""><div id="catnameTips"></div></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('catdir') ?>：</td>
                        <td><input type="text" name="info[catdir]" id="catdir" class="form-control width_50" value=""></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('catgory_img') ?>：</td>
                        <td><input  id="image" type="text" name="info[image]" class="form-control width_50" value="" /><button type="button" id="fileUpload" class="btn btn-primary">上传图片</button></td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('description') ?>：</td>
                        <td>
                            <textarea name="info[description]" id="description"  maxlength="255" class="form-control width_50" row="3"></textarea>
                        </td>
                    </tr>
                   <!--<tr>
                    <td><?php echo $this->lang->line('workflow'); ?>：</td>
                    <td>
                    <?php
                    /*
                      $workflows = getcache('workflow_'.$this->siteid,'commons');
                      if($workflows) {
                      $workflows_datas = array();
                      foreach($workflows as $_k=>$_v) {
                      $workflows_datas[$_v['workflowid']] = $_v['workname'];
                      }
                      echo form::select($workflows_datas,$setting['workflowid'],'name="setting[workflowid]"',L('catgory_not_need_check'));
                      } else {
                      echo '<input type="hidden" name="setting[workflowid]" value="">';
                      echo L('add_workflow_tips');
                      } */
                    ?>
                    </td>
                  </tr>-->
                    <tr>
                        <td><?php echo $this->lang->line('ismenu'); ?>：</td>
                        <td>
                            <input type='radio' name='info[ismenu]' value='1' checked> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='info[ismenu]' value='0' > <?php echo $this->lang->line('no'); ?></td>
                    </tr>

                </table>

            </div>
            <div id="div_setting_2" class="contentList pad-10 hidden">
                <table width="100%" class="table   table-hover">
                    <tr>
                        <td width="200"><?php echo $this->lang->line('html_category'); ?>：</td>
                        <td>
                            <input type='radio' name='setting[ishtml]' value='1' checked onClick="$('#category_php_ruleid').css('display','none');$('#category_html_ruleid').css('display','');$('#tr_domain').css('display','');"> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='setting[ishtml]' value='0'   onClick="$('#category_php_ruleid').css('display','');$('#category_html_ruleid').css('display','none');$('#tr_domain').css('display','none');"> <?php echo $this->lang->line('no'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('html_show'); ?>：</td>
                        <td>
                            <input type='radio' name='setting[content_ishtml]' value='1' checked onClick="$('#show_php_ruleid').css('display','none');$('#show_html_ruleid').css('display','')"> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='setting[content_ishtml]' value='0'   onClick="$('#show_php_ruleid').css('display','');$('#show_html_ruleid').css('display','none')"> <?php echo $this->lang->line('no'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('category_urlrules'); ?>：</td>
                        <td><div id="category_php_ruleid" style="display:<?php if (!empty($setting['ishtml'])) echo 'none'; ?>">
                                <?php
                                echo $this->form->urlrule('content', 'category', 0, isset($setting['category_ruleid'])?$setting['category_ruleid']:'', 'name="category_php_ruleid"');
                                ?>
                            </div>
                            <div id="category_html_ruleid" style="display:<?php if (empty($setting['ishtml'])) echo 'none'; ?>">
                                <?php
                                echo $this->form->urlrule('content', 'category', 1, !empty($setting['category_ruleid'])?$setting['category_ruleid']:'', 'name="category_html_ruleid"');
                                ?>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $this->lang->line('show_urlrules'); ?>：</td>
                        <td><div id="show_php_ruleid" style="display:<?php if (!empty($setting['content_ishtml'])) echo 'none'; ?>">
                                <?php
                                echo $this->form->urlrule('content', 'show', 0, !empty($setting['show_ruleid'])?$setting['show_ruleid']:'', 'name="show_php_ruleid"');
                                ?>
                            </div>
                            <div id="show_html_ruleid" style="display:<?php if (empty($setting['content_ishtml'])) echo 'none'; ?>">
                                <?php
                                echo $this->form->urlrule('content', 'show', 1, !empty($setting['show_ruleid'])?$setting['show_ruleid']:'', 'name="show_html_ruleid"');
                                ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $this->lang->line('create_to_rootdir'); ?>：</td>
                        <td>
                            <input type='radio' name='setting[create_to_html_root]' value='1' <?php if (!empty($setting['create_to_html_root'])) echo 'checked'; ?> > <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='setting[create_to_html_root]' value='0' <?php if (empty($setting['create_to_html_root'])) echo 'checked'; ?> > <?php echo $this->lang->line('no'); ?>
                            （<?php echo $this->lang->line('create_to_rootdir_tips'); ?>）</td>
                    </tr>
                    <tr id="tr_domain" style="display:<?php if (empty($setting['ishtml'])) echo 'none'; ?>">
                        <td><?php echo $this->lang->line('domain') ?>：</td>
                        <td><input type="text" name="info[url]" id="url" class="form-control width_50" size="50" value="<?php if (preg_match('/^http:\/\/([a-z0-9\-\.]+)/i', $r['url'])) echo $r['url']; ?>"></td>
                    </tr>
                </table>
            </div>
            <div id="div_setting_3" class="contentList pad-10 hidden">
                <table width="100%" class="table   table-hover">
                    <tr>
                        <td width="200"><?php echo $this->lang->line('available_styles'); ?>：</td>
                        <td>
                            <?php echo $this->form->select($template_list, !empty($setting['template_list'])?$setting['template_list']:'', 'name="setting[template_list]" id="template_list" onchange="load_file_list(this.value)"', $this->lang->line('please_select')) ?> 
                        </td>
                    </tr>
                    <tr>
                        <td width="200"><?php echo $this->lang->line('category_index_tpl') ?>：</td>
                        <td  id="category_template">
                        </td>      </tr>
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

                    <!--模版应用到子栏目配置-->
                    <tr>
                        <td width="200"><?php echo '模板应用到子栏目'; ?></td>
                        <td><input type='radio' name='template_child' value='1' /> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='template_child' value='0' checked /> <?php echo $this->lang->line('no'); ?></td></td>
                    </tr>
                    <!--end 模版应用到子栏目配置-->

                </table>
            </div>
            <div id="div_setting_4" class="contentList pad-10 hidden">
                <table width="100%" class="table   table-hover ">
                    <tr>
                        <td width="200"><?php echo $this->lang->line('meta_title'); ?></td>
                        <td><input name='setting[meta_title]' type='text' id='meta_title' class='form-control width_50' value='' size='60' maxlength='60'></td>
                    </tr>
                    <tr>
                        <td ><?php echo $this->lang->line('meta_keywords'); ?></td>
                        <td><textarea name='setting[meta_keywords]' id='meta_keywords' class='form-control' row='3'></textarea></td>
                    </tr>
                    <tr>
                        <td ><strong><?php echo $this->lang->line('meta_description'); ?></td>
                        <td><textarea name='setting[meta_description]' id='meta_description' class='form-control' row='3'></textarea></td>
                    </tr>
                </table>
            </div>



            <div id="div_setting_5" class="contentList pad-10 hidden">
                <table width="100%" class="table   table-hover text-c" >
                    <tr>
                        <td width="200" ><?php echo $this->lang->line('role_private') ?>：</td>
                        <td>
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td align="left"><?php echo $this->lang->line('role_name'); ?></td><td><?php echo $this->lang->line('view'); ?></td><td><?php echo $this->lang->line('add'); ?></td><td><?php echo $this->lang->line('edit'); ?></td><td><?php echo $this->lang->line('delete'); ?></td><td><?php echo $this->lang->line('listorder'); ?></td><td><?php echo $this->lang->line('push'); ?></td><td><?php echo $this->lang->line('move'); ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $role_priv;?>

                                </tbody>
                            </table>
                        </td>

                    </tr>
                    

                    <tr>
                        <td width="200" ><?php echo $this->lang->line('group_private') ?>：</td>
                        <td>
                            <table width="100%" >
                                <thead>
                                    <tr>
                                        <td align="left"><?php echo $this->lang->line('group_name'); ?></td><td align="left"><?php echo $this->lang->line('allow_vistor'); ?></td><td align="left"><?php echo $this->lang->line('allow_contribute'); ?></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo $group_priv;?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td width="200"><?php echo $this->lang->line('apply_to_child'); ?></td>
                        <td align='left'><input type='radio' name='priv_child' value='1'> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type='radio' name='priv_child' value='0' checked /> <?php echo $this->lang->line('no'); ?></td>
                    </tr>
                </table>
            </div>
            
            <!--<input name="catid" type="hidden" value="<?php echo $catid; ?>" />-->
            <input type='hidden' name='dosubmit' value="1" />
            <input name='submit' type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default" />
        </div>


    <!--</form>-->
    <!--table_form_off-->

</div>
<script type="text/javascript">
   
   $('input[name=submit]').click(function(){
        if(!$('#catname').val()){
            $("#catnameTips").addClass('onError');
            $("#catnameTips").html("模型名称应该为2-10位之间汉字（字母）");
        }
        if($('.onError').length>0) return false;
        
        var content=$.trim($('.ke-edit-iframe').contents().find('.ke-content').html());
        $("#description").text(content);
        $.ajax({
            type:'post',
            url:'?d=admin&c=category&m=add',
            dataType:'text',
            data:$('input[type="text"],input[type="radio"]:checked,input[type="checkbox"]:checked,.ke-edit iframe body[class=ke-content],textarea[name^=info],textarea[name^=setting],select,input[type="hidden"]'),
            success:function(str){
                if(str=='yes'){
                    $('.modal-title').text("提示");
                    $('.modal-body').html("添加栏目成功！");
                    $('#myModal').modal();
                    
                }
            }
        });
    });
   
   
   KindEditor.ready(function(K) {
        var editor = K.create('#description',{items:['source','fontsize','fontname','|','forecolor','hilitecolor','bold','italic','underline','removeformat','|','justifyleft','justifycenter','justifyright','|','emoticons','image','multiimage','table','link','unlink','|','preview','fullscreen'],resizeType:1});
        K('#fileUpload').click(function() {
            editor.loadPlugin('image', function() {
                editor.plugin.imageDialog({
                    imageSizeLimit:"512KB",
                    showRemote : false,
                    clickFn : function(url, title, width, height, border, align) {  
                        K('#image').val(url);
                        editor.hideDialog();
                    }
                });
            });
        });
    });
    
    function SwapTab(name,cls_show,cls_hide,cnt,cur){
        for(i=1;i<=cnt;i++){
            if(i==cur){
                $('#div_'+name+'_'+i).removeClass('hidden');
                $('#tab_'+name+'_'+i).attr('class',cls_show);
            }else{
                $('#div_'+name+'_'+i).addClass('hidden');
                $('#tab_'+name+'_'+i).attr('class',cls_hide);
            }
        }
    }
    

</script>
<script type="text/javascript">
	
    //返回
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('add'); ?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');

        location.href='?d=admin&c=category&m=index';
    });
    
  
    
    //刷新
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
   
    
    $('.col-tab ul.nav-tabs').css('border-bottom','none');
    
    
    function change_tpl(modelid) {
		if(modelid) {
			$.getJSON('?d=admin&c=category&m=publicChangeTpl&modelid='+modelid, function(data){console.log(data);$('#template_list').val(data.template_list);$('#category_template').html(data.category_template);$('#list_template').html(data.list_template);$('#show_template').html(data.show_template);});
		}
	}
    
    function load_file_list(id) {
        if(id=='') return false;
        $.getJSON('?d=admin&c=category&m=publicTplFileList&style='+id+'&catid=<?php echo $parentid ?>', function(data){$('#category_template').html(data.category_template);$('#list_template').html(data.list_template);$('#show_template').html(data.show_template);});
    }
    
<?php if(isset($modelid)) echo "change_tpl($modelid);";?>
</script>
<?php $this->load->view('common/footer'); ?>