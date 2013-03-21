<?php include "header.php"; ?>
<div class="well">
<h4>Talkaction Logs</h4>
<?php 
    exec("ls $ot_root/data/logs/talkactions/*.log", $list); 
    for ($i=0; $i<sizeof($list); $i++)
    {
        //echo '<a href="#modal'.$i.'" data-toggle="modal">' . $list[$i] . '</a><br />';
        echo '<a href="#" id="launch'.$i.'">' . $list[$i] . '</a><br />';
?>
<!-- Modal -->
<div id="<?php echo "modal$i"; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
    <h3 id="myModalLabel"><?php echo $list[$i]; ?></h3>
  </div>
  <div class="modal-body">
    <div id="loading<?php echo $i; ?>" style="text-align: center; margin: auto;"><img src="loading.gif" /></div>
    <p><pre id="modal_text<?php echo $i; ?>"><?php //echo htmlentities(file_get_contents($list[$i])); ?></pre></p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
<script>
$("#launch<?php echo $i; ?>").click(function(e){
    e.preventDefault();
    $("#loading<?php echo $i; ?>").show();
    $("#<?php echo "modal$i"; ?>").modal('show');
    $.ajax({
        url: 'ajax_file.php',
        data: {
            name: "<?php echo $list[$i]; ?>"
        }
    }).done(function(data){
        $("#loading<?php echo $i; ?>").hide();
        $("#modal_text<?php echo $i; ?>").text(data);
    });
});
</script>
<?php
    }
?>
</div>

<?php include "footer.php"; ?>
