<?php $this->load->view('common/header'); ?>
<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
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
            <li<?php if ((isset($_GET['order']) && $_GET['order'] == 1) || !isset($_GET['order'])) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&classname=position_api&action=positionList&order=1&modelid=<?php echo $modelid ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_position'); ?></a></li>
            <li<?php if (isset($_GET['order']) && $_GET['order'] == 2) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&module=special&action=_get_special&order=2&modelid=<?php echo $modelid ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_special'); ?></a></li>
            <li<?php if (isset($_GET['order']) && $_GET['order'] == 3) { ?> class="active"<?php } ?>><a href="?d=content&c=push&m=index&module=content&classname=push_api&action=categoryList&order=3&tpl=push_to_category&modelid=<?php echo $modelid ?>&catid=<?php echo $catid ?>&id=<?php echo $id ?>"><?php echo $this->lang->line('push_to_category'); ?></a></li>
        </ul>
        <div class='content' style="height:auto;">
            <form action="?d=content&c=push&m=index&module=<?php echo $module; ?>&action=<?php echo $action; ?>" method="post" name="myform" id="myform">
                <input type="hidden" name="modelid" value="<?php echo $modelid;?>">
                <input type="hidden" name="catid" value="<?php echo $catid; ?>">
                <input type='hidden' name="id" value='<?php echo $id; ?>'>
                <table width="100%"  class="table_form">

                    <?php if (isset($html) && is_array($html)) : ?>
                        <?php foreach ($html as $k => $v) : ?>

                            <tr>
                                <th width="80"><?php echo $v['name'] ?>：</th>
                                <td class="y-bg"><?php echo $this->form->creatForm($k, $v) ?></td>
                            </tr>
                            <?php if (isset($v['ajax']['name'])) : ?>
                                <tr>
                                    <th width="80"><?php echo $v['ajax']['name'] ?>：</th>
                                    <td class="y-bg" id="<?php echo $k ?>_td"><input type="hidden" name="<?php echo $v['ajax']['id'] ?>" id="<?php echo $v['ajax']['id'] ?>"></td>
                                </tr>
                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php echo $html; ?>
                    <?php endif; ?>
                </table>
                <div class="bk15"></div>

                <!--<input type="hidden" name="return" value="<?php echo $return ?>" />-->
                <input type="submit" class="btn btn-default" id="dosubmit" name="dosubmit" value="<?php echo $this->lang->line('submit') ?>" />
            </form>
        </div>
    </div>
</div>
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