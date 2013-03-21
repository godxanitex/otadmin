<?php include "header.php"; ?>

<?php

?>

<div class="well">
<h4>Dashboard</h4>
<?php
    if (is_array($services) and sizeof($services) > 0)
    {
        for ($i=0; $i<sizeof($services); $i++)
        {
            echo '<div id="'.$services[$i]['short_name'].'_status">'.$services[$i]['proper_name'].' Status: <img src="loader.gif"></div>';
        }
    }
    else
    {
        echo 'You must configure services in the config.php script to display on the dashboard.';
    }
?>
<!--<div id="ot_status">Open Tibia Server Status: <img src="loader.gif"></div>
<div id="vent_status">Ventrilo Status: <img src="loader.gif"></div>-->
</div>

<script>
$(document).ready(function(){
<?php
    if (is_array($services) and sizeof($services) > 0)
    {
        for ($i=0; $i<sizeof($services); $i++)
        {
            echo '
    $.ajax({
        url: "ajax_status.php",
        data: {
            host: "'.$services[$i]['host'].'",
            port: "'.$services[$i]['port'].'",
            title: "'.$services[$i]['proper_name'].'"
        }
    }).done(function(data){
        $("#'.$services[$i]['short_name'].'_status").html(data);
    });
    ';
        }
    }
?>
});
</script>

<?php include "footer.php"; ?>
