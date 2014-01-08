<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />

<div class="pad-10">

    <div id="searchid">
        <form name="searchform" action="?d=content&c=content&m=index" method="post" >
            <input type="hidden" value="content" name="d">
            <input type="hidden" value="content" name="c">
            <input type="hidden" value="index" name="m">
            <input type="hidden" value="" name="catid">
            <input type="hidden" value="" name="steps">
            <input type="hidden" value="1" name="search">
            <input type="hidden" value="" name="token">
            <table width="100%" cellspacing="0" class="search-form">
                <tbody>
                    <tr>
                        <td>
                            <div class="explain-col">

                                <?php echo $this->lang->line('addtime'); ?>：
                                <?php echo $this->form->date('start_time', $start_time, 0, 0, 'false'); ?>- &nbsp;<?php echo $this->form->date('end_time', $end_time, 0, 0, 'false'); ?>
                                <select name="posids" class='form-control input-sm width_12'>
                                    <option value='all' <?php if (isset($posids) && $posids=='all') echo 'selected'; ?>><?php echo $this->lang->line('all'); ?></option>
                                    <option value="1" <?php if (isset($posids) && $posids == '1') echo 'selected'; ?>><?php echo $this->lang->line('elite'); ?></option>
                                    <option value="0" <?php if (isset($posids) && $posids == '0') echo 'selected'; ?>><?php echo $this->lang->line('no_elite'); ?></option>
                                </select>
                                <select name="status" class='form-control input-sm width_12'>
                                    <option value='1' <?php if (isset($status) && $status == '1') echo 'selected'; ?>><?php echo $this->lang->line('unchecked'); ?></option>
                                    <option value="2" <?php if (isset($status) && $status == '2') echo 'selected'; ?>><?php echo $this->lang->line('checked'); ?></option>
                                    <option value="3" <?php if (isset($status) && $status == '3') echo 'selected'; ?>><?php echo $this->lang->line('archived'); ?></option>
                                </select>	
                                <select name="searchtype" class="form-control input-sm width_12">
                                    <option value='0' <?php if (isset($searchtype) && $searchtype == '0') echo 'selected'; ?>><?php echo $this->lang->line('title'); ?></option>
                                    <option value='1' <?php if (isset($searchtype) && $searchtype == '1') echo 'selected'; ?>><?php echo $this->lang->line('intro'); ?></option>
                                    <option value='2' <?php if (isset($searchtype) && $searchtype == '2') echo 'selected'; ?>><?php echo $this->lang->line('username'); ?></option>
                                    <option value='3' <?php if (isset($searchtype) && $searchtype == '3') echo 'selected'; ?>>ID</option>
                                </select>
                                
                                <input name="keyword" type="text" value="<?php if (isset($keyword)) echo $keyword; ?>" class="form-control input-sm width_12" />
                                <input type="submit" name="search" class="btn btn-default" value="<?php echo $this->lang->line('search'); ?>" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <form name="myform" id="myform" action="" method="post" >
        <div class="table-list">
            <table class="table table-striped table-bordered table-hover table-condensed center">
                <thead>
                    <tr>
                        <th width="26"><input type="checkbox" value="" id="check_box" onclick="selectAll('ids[]');"></th>
                        <th width="80"><?php echo $this->lang->line('models'); ?></th>
                        <th width="80"><?php echo $this->lang->line('btcategory'); ?></th>
                        <th width="40">ID</th>
                        <th><?php echo $this->lang->line('title'); ?></th>
                        <th width="60"><?php echo $this->lang->line('hits'); ?></th>
                        <th width="70"><?php echo $this->lang->line('publish_user'); ?></th>
                        <th width="160"><?php echo $this->lang->line('updatetime'); ?></th>
                        <th width="100"><?php echo $this->lang->line('operations_manage'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (is_array($datas) && sizeof($datas)>0) :?>
                        <?php foreach ($datas as $r): ?>
          
                            <tr>
                                <td align="center"><input class="inputcheckbox " name="ids[]" value="<?php echo $r['id']; ?>,<?php echo $r['modelid'];?>,<?php echo $r['catid'];?>" type="checkbox"></td>
                                <!--<td align='center'><input name='listorders[<?php echo $r['id']; ?>]' type='text' size='3' value='<?php echo $r['listorder']; ?>' class='input-text-c'></td>-->
                                <td align='center' ><a href="?d=content&c=content&m=index&modelid=<?php echo $r['modelid'];?>"><?php echo $r['modelname']; ?></a></td>
                                <td align='center' ><?php echo $r['catname']; ?></td>
                                <td align='center' ><?php echo $r['id']; ?></td>
                                <td>
                                    <?php
//                                    if ($status == 99) {
//                                        if ($r['islink']) {
//                                            echo '<a href="' . $r['url'] . '" target="_blank">';
//                                        } elseif (strpos($r['url'], 'http://') !== false) {
//                                            echo '<a href="' . $r['url'] . '" target="_blank">';
//                                        } else {
//                                            echo '<a href="' . $release_siteurl . $r['url'] . '" target="_blank">';
//                                        }
//                                    } else {
//                                        echo '<a href="javascript:;" onclick=\'window.open("?m=content&c=content&a=public_preview&steps=' . $steps . '&catid=' . $r['catid'] . '&id=' . $r['id'] . '","manage")\'>';
//                                    }
//                                    ?>
                                    <span><?php echo $r['title']; ?></span></a> 
                                          <?php
                                            if ($r['thumb'] != '') {
                                                echo '<img src="views/default/image/icon/small_img.gif" title="' . $this->lang->line('thumb') . '">';
                                            } if ($r['posids']) {
                                                echo '<img src="views/default/image/icon/small_elite.gif" title="' . $this->lang->line('elite') . '">';
                                            } if ($r['islink']) {
                                                echo ' <img src="views/default/image/icon/link.png" title="' . $this->lang->line('islink_url') . '">';
                                            }
                                            ?></td>
                                <td align='center' <?php if(isset($r['hits']['views']) && $r['hits']['views']>0):?>title="<?php if(isset($r['hits']['dayviews'])):?>    <?php echo $this->lang->line('today_hits'); ?>：<?php echo $r['hits']['dayviews']; ?><?php endif;?>&#10;<?php if(isset($r['hits']['yesterdayviews'])):?>   <?php echo $this->lang->line('yestoday_hits'); ?>：<?php echo $r['hits']['yesterdayviews']; ?><?php endif;?>&#10;<?php if(isset($r['hits']['weekviews'])):?>    <?php echo $this->lang->line('week_hits'); ?>：<?php echo $r['hits']['weekviews']; ?><?php endif;?>&#10;<?php if(isset($r['hits']['monthviews'])):?>   <?php echo $this->lang->line('month_hits'); ?>：<?php echo $r['hits']['monthviews']; ?><?php endif;?>&#10;"<?php endif;?>> <?php echo isset($r['hits']['views'])?$r['hits']['views']:0; ?></td>
                                <td align='center'>
                                    <?php
                                    if ($r['sysadd'] == 0) {
                                        echo "<a href='?m=member&c=member&a=memberinfo&username=" . urlencode($r['username']) . "&token=" . $this->session->userdata('token') . "' >" . $r['username'] . "</a>";
                                        echo '<img src="views/default/image/icon/contribute.png" title="' . $this->lang->line('member_contribute') . '">';
                                    } else {
                                        echo $r['username'];
                                    }
                                    ?></td>
                                <td align='center'><?php echo date('Y-m-d H:i:s', $r['updatetime']); ?></td>
                                <td align='center'><a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $r['catid']; ?>&id=<?php echo $r['id'] ?>','')"><?php echo $this->lang->line('edit'); ?></a> | <a href="javascript:view_comment('<?php echo urlencode('content_' . $r['catid'].'-'. $r['id']); ?>','<?php echo safeReplace($r['title']); ?>')"><?php echo $this->lang->line('comment'); ?></a></td>
                            </tr>
                         <?php endforeach;?>
                   <?php else :?>
                            <tr><td colspan="8" align='center'><?php echo $this->lang->line('noResults');?></td></tr>
                   <?php endif;?>
                </tbody>
            </table>
            
            <label for="check_box"><?php echo $this->lang->line('selected_all'); ?><?php echo $this->lang->line('cancel'); ?></label>
                <!--<input type="hidden" value="<?php echo $token; ?>" name="token">-->
                <!--<input type="button" class="btn btn-default" value="<?php echo $this->lang->line('listorder'); ?>" onclick="myform.action='?m=content&c=content&a=listorder&dosubmit=1&catid=&steps=';myform.submit();"/>-->
                <input type='hidden' name="steps" value="1" />
                <input type='hidden' name="type" value="pass" />

                <?php if($status==1):?>
                    <input type="button" id="pass" class="btn btn-default" value="<?php echo $this->lang->line('passed_checked'); ?>" />
                    <input type="button" id="delete" class="btn btn-default" value="<?php echo $this->lang->line('delete'); ?>" />
                <?php endif;?>
                <?php if ($status==2) : ?>
                    <input type="button" class="btn btn-default" value="<?php echo $this->lang->line('push'); ?>" onclick="push();"/>
                    <input type="button" id="reject" class="btn btn-default" value="<?php echo $this->lang->line('reject'); ?>" />
                    <div id='reject_content' style='background-color: #fff;border:#006699 solid 1px;position:absolute;z-index:10;padding:1px;display:none;'>
                        <table cellpadding='0' cellspacing='1' border='0'><tr><tr><td colspan='2'><textarea name='reject_c' id='reject_c' style='width:300px;height:46px;'  onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;"><?php echo $this->lang->line('reject_msg'); ?></textarea></td><td><input type='button' value=' <?php echo $this->lang->line('submit'); ?> ' class="button" onclick="reject_check(1)"></td></tr>
                        </table>
                    </div>
                <?php endif;?>
                <?php if($status==3):?>
                    <input type="button" id="restore" class="btn btn-default" value="<?php echo $this->lang->line('restore'); ?>" />
                <?php endif;?>
                    
                <input type="button" class="btn btn-default" id="move" value="<?php echo $this->lang->line('remove'); ?>" />
           
            <div class="pagination"><?php echo $pagination; ?></div>
            
        </div>
    </form>
    
</div>

<div id="category" style="display:none;">
    <select name="category">
        <option value="1">测试</option>
        <option value="2">合肥</option>
    </select>
    <button class="btn btn-default">确定</button>
</div>
<div class="modal fade" id="modal">
  <div class="modal-header">
    <a class="close" href="#" data-dismiss="modal">×</a>
    <h5> </h5>
  </div>
  <div class="inner"></div>
</div>


<script type="text/javascript">
     function selectAll(id){
        //var arr = new Array();
        var inputs = document.getElementsByTagName("input");
        var checkBox = document.getElementById('check_box');

        for(var i = 0; i< inputs.length; i ++){
            if( inputs[i].getAttribute('name')==id &&　inputs[i].getAttribute('type')=='checkbox')
            {
                if(checkBox.checked){
                   inputs[i].setAttribute('checked',"checked");
                   inputs[i].checked=true;
                } else{
                   inputs[i].setAttribute('checked','');
                   inputs[i].removeAttribute('checked');
                   inputs[i].checked=false;
                }
            }
        }
        
    }
    
    /*是否选中*/
    function checkSubmit(){
        var inputs = document.getElementsByTagName('input');
        var len=inputs.length;
        var checkedNum=0;
        for(var i=0;i<len;i++){
            if( inputs[i].getAttribute('name')=='ids[]' &&　inputs[i].getAttribute('type')=='checkbox'){
                if(inputs[i].checked){
                    checkedNum++;
                }
            }
        }
        
        if(checkedNum<=0) {
            var _m_="<?php echo $this->lang->line('operateRecord');?>";
            $.scojs_confirm({
                content: _m_,
                action: function() {
                   this.close();
               }
            }).show();
            return false;
        }
        
        return true;
    }
    
    //审核(待审核=》已发布）
    $('#pass').on('click',function(){
        if(!checkSubmit()) return false;
        $('input[name=steps]').val(1);
        $('input[name=type]').val('pass');
        var serializedData=$('#myform').serialize();
       
        $.ajax({
             url:'?d=content&c=content&m=operate',
             type:'post',
             data:serializedData,
             dataType:'text',
             success:function(){
                 location.href=location.href;
             }
        });
    });
    
     //批量移动（从一个栏目 Move 到另一个栏目）
    $('#move').on('click',function(){
        if(!checkSubmit()) return false;
        
      var modal = $.scojs_modal({
           title:"请选择要移动到的栏目",
           content:$('#category').html(),
            keyboard: true
          });
          modal.show();
            return false;
        var serializedData=$('#myform').serialize();
       
        $.ajax({
             url:'?d=content&c=content&m=move',
             type:'post',
             data:serializedData,
             dataType:'text',
             success:function(){
                 location.href=location.href;
             }
        });
    });
    
    //删除（待审核=》归档）
    $('#delete').on('click',function(){
        if(!checkSubmit()) return false;
        $('input[name=steps]').val(3);
        $('input[name=type]').val('delete');
        var serializedData=$('#myform').serialize();
        $.ajax({
             url:'?d=content&c=content&m=operate',
             type:'post',
             data:serializedData,
             dataType:'text',
             success:function(){
                 $('.table-list table tbody tr td').map(function(){
                     if(($(this).children('input').attr('checked')=='checked')){
                         $(this).parent().remove();
                     }
                 })
             }
        });
    });
    
    //还原（删除=》待审核)
    $('#restore').on('click',function(){
        if(!checkSubmit()) return false;
         $('input[name=steps]').val(1);
        $('input[name=type]').val('restore');
        var serializedData=$('#myform').serialize();
        $.ajax({
             url:'?d=content&c=content&m=operate',
             type:'post',
             data:serializedData,
             dataType:'text',
             success:function(){
                 $('.table-list table tbody tr td').map(function(){
                     if(($(this).children('input').attr('checked')=='checked')){
                         $(this).parent().remove();
                     }
                 })
             }
        });
    });
    
    //退稿(退稿=>待审核）
    $('#reject').on('click',function(){
        if(!checkSubmit()) return false;
        $('input[name=steps]').val(2);
        $('input[name=type]').val('reject');
        var serializedData=$('#myform').serialize();
        $.ajax({
             url:'?d=content&c=content&m=operate',
             type:'post',
             data:serializedData,
             dataType:'text',
             success:function(){
                 $('.table-list table tbody tr td').map(function(){
                     if(($(this).children('input').attr('checked')=='checked')){
                         $(this).parent().remove();
                     }
                 })
             }
        });
    })
    
    
    
    //左侧加载
    parent.document.getElementById('display_center_id').style.display='';
    parent.document.getElementById('center_frame').src = '?d=content&c=content&m=showCategories&type=add&menuid=<?php echo $this->input->get('menuid'); ?>&token=<?php echo $this->session->userdata('token'); ?>';
    
    /*
    $(document).ready(function(){
        setInterval(closeParent,5000);
    });
    function closeParent() {
        if($('#closeParentTime').html() == '') {
            window.top.$(".left_menu").addClass("left_menu_on");
            window.top.$("#openClose").addClass("close");
            window.top.$("html").addClass("on");
            $('#closeParentTime').html('1');
            window.top.$("#openClose").data('clicknum',1);
        }
    }
    */
    /*
    $(document).ready(
        function(){
            $('#cat_search').keyup(
            function(){
                var value = $("#cat_search").val();
                if (value.length > 0){
                    $.getJSON('?m=admin&c=category&a=public_ajax_search', {catname: value}, function(data){
                        if (data != null) {
                            var str = '';
                            $.each(data, function(i,n){
                                if(n.type=='0') {
                                    str += '<li><a href="?m=content&c=content&a=init&menuid=822&catid='+n.catid+'&pc_hash='+pc_hash+'">'+n.catname+'</a></li>';
                                } else {
                                    str += '<li><a href="?m=content&c=content&a=add&menuid=822&catid='+n.catid+'&pc_hash='+pc_hash+'">'+n.catname+'</a></li>';
                                }
                            });
                            $('#search_div').html(str);
                            $('#search_div').show();
                        } else {
                            $('#search_div').hide();
                        }
                    });
                } else {
                    $('#search_div').hide();
                }
            }
        );
        }
    )
    */
</script>
<?php $this->load->view('common/footer'); ?>