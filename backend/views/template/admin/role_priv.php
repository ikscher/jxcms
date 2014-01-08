<?php $this->load->view('common/header'); ?>

<link href="<?php echo base_url('views/default/css/table.form.css'); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url('views/default/css/jquery.treeTable.css'); ?>" rel="stylesheet" type="text/css" />

<script type="text/javascript">
    $(document).ready(function() {
        $("#treeTable").treeTable({
            indent: 20
        });
    });
    function checknode(obj)
    {
        var chk = $("input[type='checkbox']");
        var count = chk.length;
        var num = chk.index(obj);
        var level_top = level_bottom =  chk.eq(num).attr('level')
        for (var i=num; i>=0; i--)
        {
            var le = chk.eq(i).attr('level');
            if(eval(le) < eval(level_top)) 
            {
                chk.eq(i).attr("checked",'checked');
                var level_top = level_top-1;
            }
        }
        for (var j=num+1; j<count; j++)
        {
            var le = chk.eq(j).attr('level');
            if(chk.eq(num).attr("checked")=='checked') {
                if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",'checked');
                else if(eval(le) == eval(level_bottom)) break;
            }
            else {
                if(eval(le) > eval(level_bottom)) chk.eq(j).attr("checked",false);
                else if(eval(le) == eval(level_bottom)) break;
            }
        }
    }
</script>

<div class="pad_10" id="load_priv">
    <div class="nav_">
        <span><a href="javascript:void(0)" onClick="javascript:$('input[name^=menuid]').attr('checked', true)"><?php echo $this->lang->line('select_all'); ?></a></span>
        <span><a href="javascript:void(0)" onClick="javascript:$('input[name^=menuid]').attr('checked', false)"><?php echo $this->lang->line('cancel'); ?></a></span>
        <span><a href="javascript:void(0)" onClick="javascript:$('#treeTable').expandAll();"><?php echo $this->lang->line('expand'); ?></a></span>
        <span><a href="javascript:void(0)" onClick="javascript:$('#treeTable').collapseAll();"><?php echo $this->lang->line('collapse'); ?></a></span>
        <ul >
            <li>
                <button  type="button" name="return" class="btn btn-default navbar-btn"><?php echo $this->lang->line('return'); ?></button>
                <button  type="button" name="refresh" class="btn btn-default navbar-btn"><?php echo $this->lang->line('refresh'); ?></button>
            </li>
        </ul>
       
    </div>

    <!--<form name="myform" action="?d=admin&c=role&m=setPriv" method="post">-->
        <input type="hidden" name="roleid" value="<?php echo $roleid ?>" />

        <table width="100%" cellspacing="0" id="treeTable" class="table table-condensed table-hover ">
            <tbody>
                <?php echo $categories; ?>
            </tbody>
        </table>
        <input type="submit" class="btn btn-default" name="dosubmit" id="dosubmit" value="<?php echo $this->lang->line('submit'); ?>" />
    <!--</form>-->
</div>
<script type="text/javascript">
    
    var curpos=$(window.parent.document).find('#current_pos_attr').text();
    var title ="<?php echo $this->lang->line('priv_setting');?>";
    var rolename = "<?php echo $rolename;?>";
    
    if(curpos.indexOf(title, 0)<0) $(window.parent.document).find('#current_pos_attr').text(curpos+'>>'+title+'>>'+rolename);
    
    curpos=null;

    $("button[name=return]").click(function(){
        $(window.parent.document).find('#current_pos_attr').text('');
        location.href="?d=admin&c=role&m=index";return false;
    });
    
    $("button[name=refresh]").click(function(){
        location.href=location.href;return false;
    });
    
    $.fn.expandAll = function() {
        $(this).find("tr").removeClass("collapsed").addClass("expanded").each(function(){
            $(this).expand();
        });
    };
    
    $.fn.collapseAll = function() {
        $(this).find("tr").removeClass("expanded").addClass("collapsed").each(function(){
            $(this).collapse();
        });
    };
    
    $('#treeTable tr').click(function(){
        if($(this).hasClass('expanded')){
            $(this).removeClass('expanded');
            $(this).collapse();
        }else if ($(this).hasClass('collapsed')){
            $(this).removeClass('collapsed');
            $(this).expand();
        }
    });
    
    
    
    
    $('input[name=dosubmit]').click(function(){
        $.ajax({
            type:'post',
            url:'?d=admin&c=role&m=setPriv',
            dataType:'text',
            data:$('input[type="hidden"],input[type="submit"],input[name^="menuid"]:checked'),
            success:function(str){
                if(str=='yes'){
                     $.scojs_confirm({
                        content: "权限设置成功！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }else{
                     $.scojs_confirm({
                        content: "权限设置失败！",
                        action: function() {
                           window.location.href=location.href;
                       }
                    }).show();
                    $('.modal-footer a:eq(0)').addClass('hidden');
                }
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url('views/javascript/jquery.treetable.js'); ?>"></script>
<?php $this->load->view('common/footer'); ?>