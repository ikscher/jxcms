<?php $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo base_url('views/javascript/kindeditor/kindeditor.js'); ?>" ></script>
<script type="text/javascript" src="<?php echo base_url('views/javascript/kindeditor/lang/zh_CN.js'); ?> "></script>
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

    <!--<form name="myform" id="myform" action="?m=admin&c=category&a=edit" method="post">-->
    <div class="col-tab">

        <ul class="nav nav-tabs">
            <li id="tab_setting_1" class="active" onclick="SwapTab('setting','active','',5,1);"><a href="#"><?php echo $this->lang->line('catgory_basic'); ?></a></li>
            <li id="tab_setting_2" onclick="SwapTab('setting','active','',5,2);"><a href="#"><?php echo $this->lang->line('catgory_createhtml'); ?></a></li>
            <li id="tab_setting_3" onclick="SwapTab('setting','active','',5,3);"><a href="#"><?php echo $this->lang->line('catgory_template'); ?></a></li>
            <li id="tab_setting_4" onclick="SwapTab('setting','active','',5,4);"><a href="#"><?php echo $this->lang->line('catgory_seo'); ?></a></li>
            <li id="tab_setting_5" onclick="SwapTab('setting','active','',5,5);"><a href="#"><?php echo $this->lang->line('catgory_private'); ?></a></li>
        </ul>
        <div id="div_setting_1" class="contentList pad-10">

            <table width="100%" class="table   table-hover  ">
                <tr>
                    <th width="200"><?php echo $this->lang->line('parent_category') ?>：</th>
                    <td>
                        <?php echo $this->form->select_category('category_content', $parentid, 'name="info[parentid]" id="parentid"', $this->lang->line('please_select_parent_category'), 0, -1); ?>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('catname') ?>：</th>
                    <td><input type="text" name="info[catname]" id="catname" class="form-control width_50" value="<?php echo $catname; ?>"></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('catdir') ?>：</th>
                    <td><input type="text" name="info[catdir]" id="catdir" class="form-control width_50" value="<?php echo $catdir; ?>"></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('catgory_img') ?>：</th>
                    <td><input  id="image" type="text" name="info[image]" class="form-control width_50" value="<?php echo $r['image']; ?>" /><button type="button" id="fileUpload" class="btn btn-primary">上传图片</button></td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('description') ?>：</th>
                    <td>
                        <textarea name="info[description]"  id="description" maxlength="255" class="form-control width_50"><?php echo $description; ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th><?php echo $this->lang->line('ismenu'); ?>：</th>
                    <td>
                        <input type='radio' name='info[ismenu]' value='1' <?php if ($ismenu) echo 'checked'; ?>> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='radio' name='info[ismenu]' value='0' <?php if (!$ismenu) echo 'checked'; ?>> <?php echo $this->lang->line('no'); ?></td>
                </tr>
            </table>

        </div>
        <div id="div_setting_2" class="contentList pad-10 hidden">
            <table width="100%" class="table   table-hover  ">
                <tr>
                    <th width="200"><?php echo $this->lang->line('html_category'); ?>：</th>
                    <td>
                        <input type='radio' name='setting[ishtml]' value='1' <?php if ($setting['ishtml']) echo 'checked'; ?> onClick="$('#category_php_ruleid').css('display','none');$('#category_html_ruleid').css('display','')"> <?php echo $this->lang->line('yes'); ?>&nbsp;&nbsp;&nbsp;&nbsp;
                        <input type='radio' name='setting[ishtml]' value='0' <?php if (!$setting['ishtml']) echo 'checked'; ?>  onClick="$('#category_php_ruleid').css('display','');$('#category_html_ruleid').css('display','none')"> <?php echo $this->lang->line('no'); ?>
                    </td>
                </tr>

                <tr>
                    <th><?php echo $this->lang->line('urlrules'); ?>：</th>
                    <td><div id="category_php_ruleid" style="display:<?php if ($setting['ishtml']) echo 'none'; ?>">
                            <?php echo $this->form->urlrule('content', 'category', 0, $setting['category_ruleid'], 'name="category_php_ruleid"');?>
                        </div>
                        <div id="category_html_ruleid" style="display:<?php if (!$setting['ishtml']) echo 'none'; ?>">
                            <?php echo $this->form->urlrule('content', 'category', 1, $setting['category_ruleid'], 'name="category_html_ruleid"');
                            ?>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="div_setting_3" class="contentList pad-10 hidden">
            <table width="100%" class="table   table-hover  ">
                <tr>
                    <th width="200"><?php echo $this->lang->line('available_styles'); ?>：</th>
                    <td>
                        <?php echo $this->form->select($template_list, $setting['template_list'], 'name="setting[template_list]" id="template_list" onchange="load_file_list(this.value)"', $this->lang->line('please_select')) ?> 
                    </td>
                </tr>
                <tr>
                    <th width="200"><?php echo $this->lang->line('page_templates') ?>：</th>
                    <td  id="page_template">
                    </td>
                </tr>
            </table>
        </div>
        <div id="div_setting_4" class="contentList pad-10 hidden">
            <table width="100%" class="table   table-hover  ">
                <tr>
                    <th width="200"><?php echo $this->lang->line('meta_title'); ?></th>
                    <td><input name='setting[meta_title]' type='text' id='meta_title' class='form-control width_50' value='<?php echo $setting['meta_title']; ?>' size='60' maxlength='60'></td>
                </tr>
                <tr>
                    <th ><?php echo $this->lang->line('meta_keywords'); ?></th>
                    <td><textarea name='setting[meta_keywords]' id='meta_keywords' class='form-control '><?php echo $setting['meta_keywords']; ?></textarea></td>
                </tr>
                <tr>
                    <th ><strong><?php echo $this->lang->line('meta_description'); ?></th>
                    <td><textarea name='setting[meta_description]' id='meta_description' class='form-control'><?php echo $setting['meta_description']; ?></textarea></td>
                </tr>
            </table>
        </div>
        <div id="div_setting_5" class="contentList pad-10 hidden">
            <table class="table   table-hover text-c" >
                <tr>
                    <td width="200"><?php echo $this->lang->line('role_private') ?>：</td>
                    <td>
                        <table width="100%" >
                            <thead>
                                <tr>
                                    <td align="left" width="200"><?php echo $this->lang->line('role_name'); ?></td><td><?php echo $this->lang->line('edit'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $role_priv; ?>

                            </tbody>
                        </table>
                    </td>

                </tr>
               
                </tr>

                <tr>
                    <td width="200"><?php echo $this->lang->line('group_private') ?>：</td>
                    <td>
                        <table width="100%" >
                            <thead>
                                <tr>
                                    <td align="left" width="200"><?php echo $this->lang->line('group_name'); ?></td><td><?php echo $this->lang->line('allow_vistor'); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php echo $group_priv; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="bk15"></div>
        <input name="catid" type="hidden" value="<?php echo $catid; ?>">
        <input name="type" type="hidden" value="<?php echo $type; ?>">
        <input type='hidden' name='dosubmit' value="1" />
        <input name="submit" type="submit" value="<?php echo $this->lang->line('submit') ?>" class="btn btn-default">

        <!--</form>-->
    </div>

</div>


<script type="text/javascript">
    //返回
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('edit'); ?>";
    
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
    
    
    
    $('input[name=submit]').click(function(){
        //if(!($.trim($('#rolename').val())) || $('.onError').length>0) return false;
        var content=$.trim($('.ke-edit-iframe').contents().find('.ke-content').html());
        $("#description").text(content);
        $.ajax({
            type:'post',
            url:'?d=admin&c=category&m=edit',
            dataType:'text',
            data:$('input[type="text"],input[type="radio"]:checked,input[type="checkbox"]:checked,textarea[name^=info],textarea[name^=setting],select,input[type="hidden"]'),
            success:function(str){
                if(str=='yes'){
                    $('.modal-title').text("提示");
                    $('.modal-body').html("修改栏目成功！");
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
    
    function load_file_list(id) {
        if(id=='') return false;
        $.getJSON('?d=admin&c=category&m=publicTplFileList&style='+id+'&catid=<?php echo $catid ?>&type=1', function(data){$('#page_template').html(data.page_template);});
    }
    <?php if (isset($setting['template_list']) && !empty($setting['template_list'])) echo "load_file_list('" . $setting['template_list'] . "')" ?>
    
</script>
<?php $this->load->view('common/footer'); ?>