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
