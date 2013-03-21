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
    mysql_connect($host,$un,$pw);
    mysql_select_db($db);
    $player_name_query = mysql_query('SELECT `id`,`name` FROM `players`;');

    while ($row = mysql_fetch_assoc($player_name_query))
    {
        $player_name[] = $row['name'];
    }

    if (isset($_POST['id']) and $_SESSION['access'] >= 5)
    {
        $pid = mysql_real_escape_string($_POST['id']);
        $pedit = (empty($_POST['name'])) ? $_POST['name_orig'] : mysql_real_escape_string($_POST['name']);
        $output = '';
        $general_update = false;
        $general_sql_query = 'UPDATE `players` SET ';
        $general_clauses = array();
        $general_errors = array();

        $skills_output = '';
        $skill_sql_query = 'UPDATE `player_skills` SET ';
        $skill_queries = array();
        $skill_box = array(0 => 'fist', 1 => 'club', 2 => 'sword', 3 => 'axe', 4 => 'distance', 5 => 'shielding', 6 => 'fishing');
        foreach ($skill_box as $k => $v)
        {
            if ($_POST[$v] != '' and is_numeric($_POST[$v]) and $_POST[$v] > 10 and $_POST[$v] < 120)
            {
                $skill_update[$k] = $_POST[$v];
                mysql_query($skill_sql_query . 'value = '.$_POST[$v] . ' WHERE `player_id` = '.$pid.' AND `skillid` = '.$k.';');
                //echo $skill_sql_query . 'value = '.$_POST[$v] . ' WHERE `player_id` = '.$pid.' AND `skillid` = '.$k.';';
                if ($skills_output == '')
                    $skills_output = '<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button>Queries to update skills were executed, but there is no validation to ensure they were successful. Please review below.</div>';
            }
        }
        if (isset($_POST['name']) and $_POST['name'] != '')
        {
            $test_if_used = mysql_query('SELECT `id` FROM `players` WHERE `name` = "'.mysql_real_escape_string($_POST['name']).'";');
            if (mysql_num_rows($test_if_used) != 0)
                $general_errors[] = "This player name is already in use.";
            $general_clauses[] = '`name` = "'.mysql_real_escape_string($_POST['name']).'"';
        }
        if (isset($_POST['level']))
        {
            if (is_numeric($_POST['level']))
            {
                $level = $_POST['level'];
                $general_clauses[] = '`level` = "'.$level.'"';
                $exp = ((50 * (($level - 1) * ($level - 1) * ($level - 1 ))) - (150 * (($level-1)*($level-1))) + (400 * ( $level - 1))) / 3; 
                $general_clauses[] = '`experience` = "'.$exp.'"';
            }
            elseif (!empty($_POST['level'])) 
            {
                $general_errors[] = 'Level is a non-numeric value.';
            }
        }
        if (isset($_POST['world_id']) and is_numeric($_POST['world_id']))
        {
            $world_id = $_POST['world_id'];
            $general_clauses[] = '`world_id` = "'.$world_id.'"';
        }
        if (isset($_POST['group']) and is_numeric($_POST['group']))
        {
            $group = $_POST['group'];
            if ($group > 0 and $group < 7)
            {
                $general_clauses[] = '`group_id` = '.$group;
            }
            else
                $general_errors[] = "Group value is out of range.";
        }
        if (isset($_POST['maglevel']) and $_POST['maglevel'] != '')
        {
            $maglevel = $_POST['maglevel'];
            if (is_numeric($maglevel))
            {
                $general_clauses[] = '`maglevel` = "'.$maglevel.'"';
            }
            else
                $general_errors[] = "Magic level is a non-numeric value.";
        }
        if (!is_numeric($pid))
            $general_errors[] = "Player ID appears to be non-numeric.";
        if (sizeof($general_errors) == 0)
        {
            if (sizeof($general_clauses) > 0)
            {
                for ($i = 0; $i < sizeof($general_clauses); $i++)
                {
                    $general_sql_query .= $general_clauses[$i];
                    if ($i < (sizeof($general_clauses) - 1))
                        $general_sql_query .= ', ';
                }
                $general_sql_query .= ' WHERE `id` = '.$pid.';';
                $general_update = mysql_query($general_sql_query);
            }
            else
            {
                //$general_errors[] = "There were no modifications entered so no update could be made.";
            }
        }
        if ($general_update)
            $output .= '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Successfully updated this player! Keep in mind, if this player is online, the changes will not actually take effect.</div>';
        else
        {
            $pedit = $_POST['name_orig'];
            $output .= '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Update Failure</h4>';
            if (sizeof($general_errors) > 0)
            {
                $output .= '<b>Errors:</b><br />';
                foreach ($general_errors as $error)
                    $output .= "$error<br />";
            }
            $output .= '</div>';
        }
    }
    else if (isset($_POST['id']))
    {
        $output = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Update Failure</h4>';
        $general_errors = array();
        $general_errors[] = "You do not have the proper permissions to submit changes.";
        foreach ($general_errors as $error)
            $output .= "$error<br />";
        $output .= '</div>';
        $pedit = $_POST['name_orig'];
    }

    mysql_close();
?>
<script>
$("#player_name").typeahead();
</script>

<div class="well">
<h4>Player Management</h4>
<?php if ($general_update == true or sizeof($general_errors) > 0) echo $output; echo $skills_output; ?>
<input id="player_name" placeholder="Enter Player Name" type="text" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source="[<?php for ($i=0;$i<sizeof($player_name);$i++) {echo '&quot;'.$player_name[$i].'&quot;'; if ($i != sizeof($player_name) - 1) echo ",";} ?>]" <?php if (isset($pedit)) echo 'value="'.$pedit.'"'; ?>>  <button class="btn btn-primary" type="submit" id="search">Search</button>
<br /><br />
<div id="result"></div>
</div>

<script>
$("#search").click(function(e){
    e.preventDefault();
    $.ajax({
        url: "ajax_player.php",
        data: {
            name: $("#player_name").val()
        }
    }).done(function(data){
        $("#result").html(data);
    })
});

<?php if (isset($pedit))
{
?>
$("#search").click();
<?php
}
?>
</script>
<?php include "footer.php"; ?>
