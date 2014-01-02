<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript">
   	parent.document.getElementById('display_center_id').style.display='';
    parent.document.getElementById('center_frame').src = '?d=content&c=content&m=showCategories&type=add&menuid=<?php echo $_GET['menuid']; ?>&token=<?php echo $this->session->userdata('token'); ?>';
</script>

<div class="pad-10">

    <div id="searchid">
        <form name="searchform" action="" method="get" >
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
                                <?php echo $this->form->date('start_time', $this->input->get('start_time'), 0, 0, 'false'); ?>- &nbsp;<?php echo $this->form->date('end_time', $this->input->get('end_time'), 0, 0, 'false'); ?>

                                <select name="posids"><option value='' <?php if (isset($_GET['posids']) && $_GET['posids'] == '') echo 'selected'; ?>><?php echo $this->lang->line('all'); ?></option>
                                    <option value="1" <?php if (isset($_GET['posids']) && $_GET['posids'] == 1) echo 'selected'; ?>><?php echo $this->lang->line('elite'); ?></option>
                                    <option value="2" <?php if (isset($_GET['posids']) && $_GET['posids'] == 2) echo 'selected'; ?>><?php echo $this->lang->line('no_elite'); ?></option>
                                </select>				
                                <select name="searchtype">
                                    <option value='0' <?php if (isset($_GET['searchtype']) && $_GET['searchtype'] == 0) echo 'selected'; ?>><?php echo $this->lang->line('title'); ?></option>
                                    <option value='1' <?php if (isset($_GET['searchtype']) && $_GET['searchtype'] == 1) echo 'selected'; ?>><?php echo $this->lang->line('intro'); ?></option>
                                    <option value='2' <?php if (isset($_GET['searchtype']) && $_GET['searchtype'] == 2) echo 'selected'; ?>><?php echo $this->lang->line('username'); ?></option>
                                    <option value='3' <?php if (isset($_GET['searchtype']) && $_GET['searchtype'] == 3) echo 'selected'; ?>>ID</option>
                                </select>

                                <input name="keyword" type="text" value="<?php if (isset($keyword)) echo $keyword; ?>" class="input-text" />
                                <input type="submit" name="search" class="button" value="<?php echo $this->lang->line('search'); ?>" />
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
    <form name="myform" id="myform" action="" method="post" >
        <div class="table-list">
            <table width="100%">
                <thead>
                    <tr>
                        <th width="16"><input type="checkbox" value="" id="check_box" onclick="selectall('ids[]');"></th>
                        <th width="37"><?php echo $this->lang->line('listorder'); ?></th>
                        <th width="40">ID</th>
                        <th><?php echo $this->lang->line('title'); ?></th>
                        <th width="40"><?php echo $this->lang->line('hits'); ?></th>
                        <th width="70"><?php echo $this->lang->line('publish_user'); ?></th>
                        <th width="118"><?php echo $this->lang->line('updatetime'); ?></th>
                        <th width="72"><?php echo $this->lang->line('operations_manage'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (is_array($datas)) {
                        $sitelist = getcache('sitelist', 'commons');
                        $release_siteurl = $sitelist[$category['siteid']]['url'];
                        $path_len = -strlen(WEB_PATH);
                        $release_siteurl = substr($release_siteurl, 0, $path_len);
                        $this->hits_db = pc_base::load_model('hits_model');

                        foreach ($datas as $r) {
                            $hits_r = $this->hits_db->get_one(array('hitsid' => 'c-' . $modelid . '-' . $r['id']));
                            ?>
                            <tr>
                                <td align="center"><input class="inputcheckbox " name="ids[]" value="<?php echo $r['id']; ?>" type="checkbox"></td>
                                <td align='center'><input name='listorders[<?php echo $r['id']; ?>]' type='text' size='3' value='<?php echo $r['listorder']; ?>' class='input-text-c'></td>
                                <td align='center' ><?php echo $r['id']; ?></td>
                                <td>
                                    <?php
                                    if ($status == 99) {
                                        if ($r['islink']) {
                                            echo '<a href="' . $r['url'] . '" target="_blank">';
                                        } elseif (strpos($r['url'], 'http://') !== false) {
                                            echo '<a href="' . $r['url'] . '" target="_blank">';
                                        } else {
                                            echo '<a href="' . $release_siteurl . $r['url'] . '" target="_blank">';
                                        }
                                    } else {
                                        echo '<a href="javascript:;" onclick=\'window.open("?m=content&c=content&a=public_preview&steps=' . $steps . '&catid=' . $catid . '&id=' . $r['id'] . '","manage")\'>';
                                    }
                                    ?><span<?php echo title_style($r['style']) ?>><?php echo $r['title']; ?></span></a> <?php if ($r['thumb'] != '') {
                                echo '<img src="' . IMG_PATH . 'icon/small_img.gif" title="' . L('thumb') . '">';
                            } if ($r['posids']) {
                                echo '<img src="' . IMG_PATH . 'icon/small_elite.gif" title="' . L('elite') . '">';
                            } if ($r['islink']) {
                                echo ' <img src="' . IMG_PATH . 'icon/link.png" title="' . L('islink_url') . '">';
                            } ?></td>
                                <td align='center' title="<?php echo $this->lang->line('today_hits'); ?>：<?php echo $hits_r['dayviews']; ?>&#10;<?php echo $this->lang->line('yestoday_hits'); ?>：<?php echo $hits_r['yestodayviews']; ?>&#10;<?php echo $this->lang->line('week_hits'); ?>：<?php echo $hits_r['weekviews']; ?>&#10;<?php echo $this->lang->line('month_hits'); ?>：<?php echo $hits_r['monthviews']; ?>"><?php echo $hits_r['views']; ?></td>
                                <td align='center'>
                                    <?php
                                    if ($r['sysadd'] == 0) {
                                        echo "<a href='?m=member&c=member&a=memberinfo&username=" . urlencode($r['username']) . "&pc_hash=" . $_SESSION['pc_hash'] . "' >" . $r['username'] . "</a>";
                                        echo '<img src="' . IMG_PATH . 'icon/contribute.png" title="' . L('member_contribute') . '">';
                                    } else {
                                        echo $r['username'];
                                    }
                                    ?></td>
                                <td align='center'><?php echo format::date($r['updatetime'], 1); ?></td>
                                <td align='center'><a href="javascript:;" onclick="javascript:openwinx('?m=content&c=content&a=edit&catid=<?php echo $catid; ?>&id=<?php echo $r['id'] ?>','')"><?php echo $this->lang->line('edit'); ?></a> | <a href="javascript:view_comment('<?php echo id_encode('content_' . $catid, $r['id'], $this->siteid); ?>','<?php echo safe_replace($r['title']); ?>')"><?php echo $this->lang->line('comment'); ?></a></td>
                            </tr>
                    <?php
                    }
                }
                ?>
                </tbody>
            </table>
            <div class="btn"><label for="check_box"><?php echo $this->lang->line('selected_all'); ?>/<?php echo $this->lang->line('cancel'); ?></label>
                <input type="hidden" value="<?php echo $pc_hash; ?>" name="pc_hash">
                <input type="button" class="button" value="<?php echo $this->lang->line('listorder'); ?>" onclick="myform.action='?m=content&c=content&a=listorder&dosubmit=1&catid=<?php echo $catid; ?>&steps=<?php echo $steps; ?>';myform.submit();"/>
                <?php if ($category['content_ishtml']) { ?>
                    <input type="button" class="button" value="<?php echo $this->lang->line('createhtml'); ?>" onclick="myform.action='?m=content&c=create_html&a=batch_show&dosubmit=1&catid=<?php echo $catid; ?>&steps=<?php echo $steps; ?>';myform.submit();"/>
<?php }
if ($status != 99) {
    ?>
                    <input type="button" class="button" value="<?php echo $this->lang->line('passed_checked'); ?>" onclick="myform.action='?m=content&c=content&a=pass&catid=<?php echo $catid; ?>&steps=<?php echo $steps; ?>';myform.submit();"/>
                <?php } ?>
                <input type="button" class="button" value="<?php echo $this->lang->line('delete'); ?>" onclick="myform.action='?m=content&c=content&a=delete&dosubmit=1&catid=<?php echo $catid; ?>&steps=<?php echo $steps; ?>';return confirm_delete()"/>
                <?php if (!isset($_GET['reject'])) { ?>
                    <input type="button" class="button" value="<?php echo $this->lang->line('push'); ?>" onclick="push();"/>
    <?php if ($workflow_menu) { ?><input type="button" class="button" value="<?php echo $this->lang->line('reject'); ?>" onclick="reject_check()"/>
                        <div id='reject_content' style='background-color: #fff;border:#006699 solid 1px;position:absolute;z-index:10;padding:1px;display:none;'>
                            <table cellpadding='0' cellspacing='1' border='0'><tr><tr><td colspan='2'><textarea name='reject_c' id='reject_c' style='width:300px;height:46px;'  onfocus="if(this.value == this.defaultValue) this.value = ''" onblur="if(this.value.replace(' ','') == '') this.value = this.defaultValue;"><?php echo $this->lang->line('reject_msg'); ?></textarea></td><td><input type='button' value=' <?php echo $this->lang->line('submit'); ?> ' class="button" onclick="reject_check(1)"></td></tr>
                            </table>
                        </div>
    <?php }
} ?>
                <input type="button" class="button" value="<?php echo $this->lang->line('remove'); ?>" onclick="myform.action='?m=content&c=content&a=remove&catid=<?php echo $catid; ?>';myform.submit();"/>
<?php echo runhook('admin_content_init') ?>
            </div>
            <div id="pages"><?php echo $pages; ?></div>
        </div>
    </form>
</div>



<script type="text/javascript">

    //	if(window.top.$("#current_pos").data('clicknum')==1 || window.top.$("#current_pos").data('clicknum')==null) {
    parent.document.getElementById('display_center_id').style.display='';
    parent.document.getElementById('center_frame').src = '?d=content&c=content&m=showCategories&type=add&menuid=<?php echo $_GET['menuid']; ?>&token=<?php echo $this->session->userdata('token'); ?>';
    //	window.top.$("#current_pos").data('clicknum',0);
    //}
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

</script>
<?php $this->load->view('common/footer'); ?>