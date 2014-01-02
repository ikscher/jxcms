<?php $this->load->view('common/header'); ?>
<style type="text/css">
    html{_overflow-y:scroll}
</style>
<div class="bk10"></div>
<link rel="stylesheet" href="<?php echo base_url('views/default/css/jquery.treeview.css'); ?>" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('views/javascript/jquery.cookie.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('views/javascript/jquery.treeview.js'); ?>"></script>
<?php if ($ajax_show) { ?>
    <script type="text/javascript" src="<?php echo base_url('views/javascript/jquery.treeview.async.js'); ?>"></script>
<?php } ?>
<script type="text/javascript">

<?php if ($ajax_show) { ?>
        $(document).ready(function(){
            $("#category_tree").treeview({
                control: "#treecontrol",
                url: "index.php?m=content&c=content&a=public_sub_categorys&menuid=<?php echo $_GET['menuid'] ?>",
                ajax: {
                    data: {
                        "additional": function() {
                            return "time: " + new Date;
                        },
                        "modelid": function() {
                            return "<?php echo $modelid ?>";
                        }
                    },
                    type: "post"
                }
            });
        });
<?php } else { ?>
        $(document).ready(function(){
            $("#category_tree").treeview({
                control: "#treecontrol",
                persist: "cookie",
                cookieId: "treeview-black"
            });
        });
<?php } ?>

</script>
<style type="text/css">
    .filetree *{white-space:nowrap;}
    .filetree span.folder, .filetree span.file{display:auto;padding:1px 0 1px 16px;}
</style>
<div id="treecontrol">
    <span style="display:none">
        <a href="#"></a>
        <a href="#"></a>
    </span>
    <a href="#"><img src="views/default/image/minus.gif" /> <img src="views/default/image/application_side_expand.png" /> 展开/收缩</a>
</div>
<?php  echo $categories; ?>
<?php $this->load->view('common/footer'); ?>