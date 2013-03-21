<?php
    session_start();
    include "config.php";
    if (!isset($_SESSION['authed']) or $_SESSION['authed'] == false)
    {
        echo 'You must have privileges to access this page.';
        die();
    }
    mysql_connect($host,$un,$pw) or die("Cannot connect to MySQL");
    mysql_select_db($db);
    $name = mysql_real_escape_string($_GET['name']);
    $data = mysql_query('SELECT `id`,`name`,`promotion`,`vocation`,`level`,`experience`,`maglevel`,`group_id`,`online`,`world_id` FROM `players` WHERE `name` = "'.$name.'";');
    if (mysql_num_rows($data) == 1)
    {
        //$skills = mysql_query('SELECT 
        while ($row = mysql_fetch_assoc($data))
        {
            $id = $row['id'];
            $name = $row['name'];
            $promotion = $row['promotion'];
            $vocation = $row['vocation'];
            $level = $row['level'];
            $experience = $row['experience'];
            $maglevel = $row['maglevel'];
            $group = $row['group_id'];
            $online = $row['online'];
            $world_id = $row['world_id'];
        }
        $skills = mysql_query('SELECT * FROM `player_skills` WHERE `player_id` = '.$id.';');
        //SKILL_FIST (0) = Fist Fighting
        //SKILL_CLUB (1) = Club Fighting
        //SKILL_SWORD (2) = Sword Fighting
        //SKILL_AXE (3) = Axe Fighting
        //SKILL_DISTANCE (4) = Distance Fighting
        //SKILL_SHIELD (5) = Shielding
        //SKILL_FISHING (6) = Fishing
        $skill_name = array(0 => 'Fist Fighting', 1 => 'Club Fighting', 2 => 'Sword Fighting', 3 => 'Axe Fighting', 4 => 'Distance Fighting', 5 => 'Shielding', 6 => 'Fishing');
        $skill_box = array(0 => 'fist', 1 => 'club', 2 => 'sword', 3 => 'axe', 4 => 'distance', 5 => 'shielding', 6 => 'fishing');
        $pskills = array();
        while ($row = mysql_fetch_assoc($skills))
        {
            $pskills[$row['skillid']] = $row['value'];
        }

        if ($online)
        {
            echo '<div class="alert alert-warning">This player is currently online. Changes will not be saved unless they log off before you submit the update or delete request.</div>';
        }
        echo '<form method="post">';
        echo '<input type="hidden" name="id" value="'.$id.'" readonly>';
        echo '<h5>General</h5>';
        echo 'Player Name:<br /><input type="text" value="'.$name.'" name="name_orig" readonly> <input type="text" placeholder="Enter to change name" name="name" /><br />';
        echo 'Level:<br /><input type="text" value="'.$level.'" readonly> <input type="text" placeholder="Enter to change level" name="level" id="level" onchange="determineExp()" /><br />';
        echo 'Experience:<br /><input type="text" value="'.$experience.'" readonly> <input type="text" placeholder="Value will change automatically" name="experience" id="exp" readonly /><br />';
        echo 'Magic Level:<br /><input type="text" value="'.$maglevel.'" readonly> <input type="text" placeholder="Enter to change magic level" name="maglevel" /><br />';
        echo 'Vocation:<br /><input type="text" value="'.$vocation_name[$promotion][$vocation].'" readonly><br />';
        /*echo 'Vocation:<br /><select name="vocation">';
        foreach ($vocation_name[0] as $k => $v)
        {
            echo '<option value="'.$k.'"';
            if ($k == $vocation)
                echo ' selected';
            echo '>'.$v.'</option>';
        }
        echo '</select><br />';
        echo 'Promotion:<br /><select name="promotion">';
        foreach ($promotion_name as $k => $v)
        {
            echo '<option value="'.$k.'"';
            if ($k == $promotion)
                echo ' selected';
            echo '>'.$v.'</option>';
        }
        echo '</select><br />';*/
        echo 'Group:<br /><input type="text" value="'.$group_list[$group].'" readonly> <select name="group"><option>-------------</option>';
        foreach ($group_list as $k => $v)
        {
            echo '<option value="'.$k.'">'.$v.'</option>';
        }
        echo '</select><br />';
        echo 'Game World:<br /><input type="text" value="'.$world_list[$world_id].'" readonly> <select name="world_id"><option>-------------</option>';
        foreach ($world_list as $k => $v)
        {
            echo '<option value="'.$k.'">'.$v.'</option>';
        }
        echo '</select><br />';
        echo '<h5>Player Skills</h5>';
        for ($i=0;$i<sizeof($skill_name);$i++)
        {
            echo $skill_name[$i] . ':<br />';
            echo '<input type="text" value="'.$pskills[$i].'" readonly /> ';
            echo '<input type="text" name="'.$skill_box[$i].'" placeholder="Change '.$skill_name[$i].'" /><br />';
        }
        echo '<br /><a href="#confirm_update" class="btn btn-primary" data-toggle="modal">Update Player</a> <a href="#confirm_delete" class="btn btn-danger" data-toggle="modal">Delete Player</a>';
?>
<script>
function determineExp()
{
    level = $("#level").val();
    if (level == '')
    {
        exp = '';
    }
    else
    {
        exp = ((50 * ((level - 1) * (level - 1) * (level - 1 ))) - (150 * ((level-1)*(level-1))) + (400 * ( level - 1))) / 3;
    }
    $("#exp").val(exp);
}
</script>

<div id="confirm_update" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Confirm Update?</h3>
  </div>
  <div class="modal-body">
    <p>Please verify your changes before finalizing the update. There is no audit trail.</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close and Review</a>
    <input type="submit" class="btn btn-primary" value="Update Player" />
  </div>
</div>

<div id="confirm_delete" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Confirm Delete<?php echo " $name"; ?>?</h3>
  </div>
  <div class="modal-body">
    <p>This is irreversible. Seriously, don't ask me to reverse this.</p>
  </div>
  <div class="modal-footer">
    <a href="#" class="btn" data-dismiss="modal">Close</a>
    <a href="#" class="btn btn-danger">Fuck it, delete the player</a>
  </div>
</div>

<?php
        echo '</form>';
    }
    else
        echo '<div class="alert alert-error">No data returned.</div>';
    mysql_close();

?>

