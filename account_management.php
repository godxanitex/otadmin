<?php include "header.php"; ?>

<?php 
    mysql_connect($host,$un,$pw);
    mysql_select_db($db);
    $player_name_query = mysql_query('SELECT `id`,`name` FROM `players`;');
    $account_name_query = mysql_query('SELECT `name` FROM `accounts`;');

    $name = '';

    while ($row = mysql_fetch_assoc($player_name_query))
    {
        $player_name[] = $row['name'];
    }

    while ($row = mysql_fetch_assoc($account_name_query))
    {
        $account_name[] = $row['name'];
    }

    if (isset($_POST['id']) and $_SESSION['access'] >= 5)
    {
        $pid = mysql_real_escape_string($_POST['id']);
        $pedit = mysql_real_escape_string($_POST['pname']);
        $name = (empty($_POST['name'])) ? $_POST['name_orig'] : mysql_real_escape_string($_POST['name']);
        $pass1 = mysql_real_escape_string($_POST['pass1']);
        $pass2 = mysql_real_escape_string($_POST['pass2']);
        $premium_points = mysql_real_escape_string($_POST['premium_points']);
        $page_access = mysql_real_escape_string($_POST['page_access']);
        $output = '';
        $update = false;
        $sql_query = 'UPDATE `accounts` SET ';
        $clauses = array();
        $errors = array();
        if (isset($_POST['name']) and $_POST['name'] != '')
        {
            $test_if_used = mysql_query('SELECT `id` FROM `accounts` WHERE `name` = "'.mysql_real_escape_string($_POST['name']).'";');
            if (mysql_num_rows($test_if_used) != 0)
                $errors[] = "This account name is already in use.";
            $clauses[] = '`name` = "'.mysql_real_escape_string($_POST['name']).'"';
        }
        if ($pass1 != '' and $pass1 == $pass2)
        {
            $clauses[] = '`password` = "'.sha1($pass1).'"';
        }
        elseif ($pass1 != $pass2)
        {
            $errors[] = "Passwords must match!";
        }
        if (is_numeric($premium_points))
        {
            $clauses[] = '`premium_points` = "'.$premium_points.'"';
        }
        else if ($premium_points != '')
            $errors[] = "Premium points must be a numeric value.";
        if (is_numeric($page_access))
        {
            $clauses[] = '`page_access` = "'.$page_access.'"';
        }
        else if ($page_access != '')
            $errors[] = "Page access must be a numeric value.";
        if (!is_numeric($pid))
            $errors[] = "Account ID appears to be non-numeric.";
        if (sizeof($errors) == 0)
        {
            if (sizeof($clauses) > 0)
            {
                for ($i = 0; $i < sizeof($clauses); $i++)
                {
                    $sql_query .= $clauses[$i];
                    if ($i < (sizeof($clauses) - 1))
                        $sql_query .= ', ';
                }
                $sql_query .= ' WHERE `id` = '.$pid.';';
                $update = mysql_query($sql_query);
            }
            else
            {
                $errors[] = "There were no modifications entered so no update could be made.";
            }
        }
        if ($update)
            $output = '<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>Successfully updated this account!</div>';
        else
        {
            $output = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Update Failure</h4>';
            if (sizeof($errors) > 0)
            {
                $output .= '<br /><b>Errors:</b><br />';
                foreach ($errors as $error)
                    $output .= "$error<br />";
            }
            $output .= '</div>';
        }
    }
    else if (isset($_POST['id']))
    {
        $output = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><h4>Update Failure</h4>';
        $errors = array();
        $errors[] = "You do not have the proper permissions to submit changes.";
        foreach ($errors as $error)
            $output .= "$error<br />";
        $output .= '</div>';
        $pedit = mysql_real_escape_string($_POST['pname']);
    }

    mysql_close();
?>
<script>
$("#player_name").typeahead();
</script>

<div class="well">
<h4>Account Management</h4>
<?php echo $output; ?>

<input placeholder="Enter Account Name" id="account_name" type="text" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source="[<?php for ($i=0;$i<sizeof($account_name);$i++) {echo '&quot;'.$account_name[$i].'&quot;'; if ($i != sizeof($account_name) - 1) echo ",";} ?>]" <?php if (isset($name)) echo 'value="'.$name.'"'; ?>>  <button class="btn btn-primary" type="submit" id="account_search">Search</button>

<input placeholder="Enter Player Name" id="player_name" type="text" class="span3" style="margin: 0 auto;" data-provide="typeahead" data-items="4" data-source="[<?php for ($i=0;$i<sizeof($player_name);$i++) {echo '&quot;'.$player_name[$i].'&quot;'; if ($i != sizeof($player_name) - 1) echo ",";} ?>]" <?php if (isset($pedit)) echo 'value="'.$pedit.'"'; ?>>  <button class="btn btn-primary" type="submit" id="search">Search</button>
<br /><br />
<div id="result"></div>
</div>

<script>
$("#account_search").click(function(e){
    e.preventDefault();
    $.ajax({
        url: "ajax_account.php",
        data: {
            account_name: $("#account_name").val()
        }
    }).done(function(data){
        $("#result").html(data);
    })
});
$("#search").click(function(e){
    e.preventDefault();
    $.ajax({
        url: "ajax_account.php",
        data: {
            name: $("#player_name").val()
        }
    }).done(function(data){
        $("#result").html(data);
    })
});

<?php if (isset($pedit) and $pedit != '')
{
?>
$("#account_search").click();
<?php
}
elseif (isset($pedit) and $pedit == '')
{
?>
$("#account_search").click();
<?php
}
?>
</script>
<?php include "footer.php"; ?>
