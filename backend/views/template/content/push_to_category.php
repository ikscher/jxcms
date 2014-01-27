<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
    .line_ff9966,.line_ff9966:hover td{background-color:#FF9966}
    .line_fbffe4,.line_fbffe4:hover td {background-color:#fbffe4}
    .list-dot-othors li{float:none; width:auto}
</style>
<div class="pad_10">

    <!--导航-->
    <div class="nav_">

        <ul>   

            <li>                       
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>


    </div>
    <!--导航-->

    <div class="col-tab">

        <ul class="nav nav-tabs">
            <li<?php if ($_GET['order'] == 1 || !isset($_GET['order'])) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&classname=position_api&action=positionList&order=1&modelid=<?php echo $modelid ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_position'); ?></a></li>
            <li<?php if ($_GET['order'] == 2) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&module=special&action=_get_special&order=2&modelid=<?php echo $modelid; ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_special'); ?></a></li>
            <li<?php if ($_GET['order'] == 3) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&module=content&classname=push_api&action=categoryList&order=3&tpl=push_to_category&modelid=<?php echo $modelid ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_category'); ?></a></li>
        </ul>

        <div class='content_' style="height:auto;">
            <form action="?d=content&c=push&m=index&module=<?php echo $module ?>&action=<?php echo $action ?>" method="post" name="myform" id="myform">
                <input type="hidden" name="modelid" value="<?php echo $modelid ?>">
                <input type="hidden" name="catid" value="<?php echo $catid ?>">
                <input type='hidden' name="id" value='<?php echo $id ?>'>
                <input type="hidden" value="content" name="d">
                <input type="hidden" value="content" name="c">
                <input type="hidden" value="public_relationlist" name="m">
                <input type="hidden" value="<?php echo $modelid; ?>" name="modelid">
                
             
             
                <div style="width:500px; padding:2px; border:1px solid #d8d8d8; float:left; margin-top:10px; margin-right:10px">
                    <table width="100%"  class="table  table-condensed center" >
                        <thead>
                            <tr>
                                <th width="100"><?php echo $this->lang->line('catid'); ?></th>
                                <th ><?php echo $this->lang->line('catname'); ?></th>
                                <th width="150" ><?php echo $this->lang->line('select_model_name'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="load_catgory">
                           
                        </tbody>
                    </table>
                </div>

                <div style="overflow:hidden;_float:left;margin-top:10px;*margin-top:0;_margin-top:0">
                    <fieldset>
                        <legend><?php echo $this->lang->line('category_checked'); ?></legend>
                        <ul class='list-dot-othors' id='catname'>
                            <input type='hidden' name='ids' value="" id="relation"></ul>
                    </fieldset>
                </div>


                <div class="bk15"></div>

                <input type="submit" class="btn btn-default" id="dosubmit" name="dosubmit" value="<?php echo $this->lang->line('submit') ?>" />
            </form>
        </div>

    </div>
</div>
<script type="text/javascript">

    function select_list(obj,title,id) {
     
        var relation_ids = $('#relation').val();
        var _relation_ids_='|'+relation_ids+'|';
        if(_relation_ids_.indexOf('|'+id+'|')>=0) return false;
        var sid = 'v'+id;
        $(obj).attr('class','line_fbffe4');
        var str = "<li id='"+sid+"'>·<span>"+title+"</span><a href='javascript:;' class='close' onclick=\"remove_id('"+sid+"')\"></a></li>";
        $('#catname').append(str);
        if(relation_ids =='' ) {
            $('#relation').val(id);
        } else {
            relation_ids = relation_ids+'|'+id;
            $('#relation').val(relation_ids);
        }
    }

    function change_siteid() {
        $("#load_catgory").load("?d=content&c=content&m=public_getsite_categorys");

    }
    //移除ID
    function remove_id(id) {
        $('#'+id).remove();
        id=id.replace(/v/,'');
        var relation_ids =$('#relation').val();
        var relation_ids_arr = relation_ids.split('|');
        var ids=relation_ids_arr.filter(function(x){ return x!=id;})
        var ids =ids.join('|');
        $('#relation').val(ids);
    }
    change_siteid();

</script>
<script type="text/javascript">
    //返回
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('push'); ?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');

        location.href='?d=content&c=content&m=index';
    });
    
  
    
    //刷新
    $('button[name=refresh]').click(function(){
        location.href=location.href;
    });
    
    $('.col-tab ul.nav-tabs').css('border-bottom','none');
</script>
<?php $this->load->view('common/footer'); ?>