<?php foreach ($datas as $_value): ?>
<h3 class="f14"><span class="switchs cu on" title="<?php echo $this->lang->line('expand_or_contract') ?>"> </span><?php echo $this->lang->line($_value['name']); ?> </h3>
<ul>
        <?php $sub_array = $this->admin->admin_menu($_value['id']); ?>
        <?php foreach ($sub_array as $_key => $_m) : ?>
            <!--        //附加参数-->
            <?php $data = $_m['data'] ? '&' . $_m['data'] : ''; ?>

            <?php $classname = 'class="sub_menu"'; ?>

            <li id="_MP<?php echo $_m['id']; ?>"  <?php echo $classname; ?>><a href='javascript:_MP("<?php echo $_m['id'];?>" ,"index.php?d=<?php echo $_m['d'];?>&c=<?php echo $_m['c'];?>&m=<?php echo $_m['m'] . $data ;?>")'><?php echo $this->lang->line($_m['name']); ?></a></li>
        <?php endforeach; ?>
    </ul>

<?php endforeach; ?>
<script type="text/javascript">
    $(".switchs").each(function(i){
        var ul = $(this).parent().next();
        $(this).click(
        function(){
            if(ul.is(':visible')){
                ul.hide();
                $(this).removeClass('on');
            }else{
                ul.show();
                $(this).addClass('on');
            }
        })
    });
    
    function _MP(menuid,targetUrl) {
        $("#rightMain").attr('src', targetUrl+'&menuid='+menuid);//+'&pc_hash='+pc_hash
        $('.sub_menu').removeClass("on fb blue");
        menuid=parseInt(menuid);
        $('#_MP'+menuid).addClass("on fb blue");
        $.get("index.php?d=common&c=main&m=public_current_pos",{menuid:menuid}, function(data){
            $("#current_pos").html(data+'<span id="current_pos_attr"></span>');
        });
        $("#current_pos").data('clicknum', 1);
       
    }
</script>
