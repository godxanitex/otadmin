<?php
    /*
    Copyright (C) 2013 - God Xanitex

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */
?>
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
