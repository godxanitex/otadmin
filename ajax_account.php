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
    if (isset($_GET['name']))
    {
        $pname = mysql_real_escape_string($_GET['name']);
        $data = mysql_query('SELECT `accounts`.`id`,`accounts`.`name`,`accounts`.`premium_points`,`accounts`.`page_access` FROM `accounts` INNER JOIN `players` ON `players`.`account_id` = `accounts`.`id` WHERE `players`.`name` =  "'.$pname.'";');
    }
    elseif (isset($_GET['account_name']))
    {
        $account_name = mysql_real_escape_string($_GET['account_name']);
        $data = mysql_query('SELECT `accounts`.`id`,`accounts`.`name`,`accounts`.`premium_points`,`accounts`.`page_access` FROM `accounts` WHERE `accounts`.`name` = "'.$account_name.'";');
    }
    if (mysql_num_rows($data) == 1)
    {
        while ($row = mysql_fetch_assoc($data))
        {
            $id = $row['id'];
            $name = $row['name'];
            $prem = $row['premium_points'];
            $page_access = $row['page_access'];
        }
        echo '<div id="error"></div><form method="post">';
        echo '<input type="hidden" name="id" value="'.$id.'" readonly>';
        echo '<input type="hidden" name="pname" value="'.$pname.'" readonly />';
        echo 'Account Name:<br /><input type="text" value="'.$name.'" name="name_orig" readonly> <input type="text" placeholder="Enter to change username" name="name" /><br />';
        echo 'Password:<br /><input id="pass1" type="password" name="pass1" placeholder="Enter new password"> <input type="password" placeholder="Verify new password" name="pass2" id="pass2" /><br />';
        echo 'Premium Points:<br /><input type="text" value="'.$prem.'" readonly /> <input type="text" name="premium_points" placeholder="New amount of points" /><br />';
        echo 'Page Access (3 is read-only, 5 is full admin):<br /><input type="text" value="'.$page_access.'" readonly /> <input type="text" name="page_access" placeholder="Set Page Access" /><br />';
        echo '<br /><a href="#" class="btn btn-primary" data-toggle="modal" id="update_button">Update Account</a>'; 
?>
<script>
$("#update_button").click(function(e){
    e.preventDefault();
    if ($("#pass1").val() != $("#pass2").val())
    {
        $("#error").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>The passwords entered must match.</div>');
    }
    else
    {
        if ($("#pass1").val().length < 8 && $("#pass1").val().length != 0)
        {
            $("#error").html('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>The password must be at least 8 characters in length.</div>');
        }
        else
        {
            $("#confirm_update").modal("show");
        }
    }
});
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
    <input type="submit" class="btn btn-primary" value="Update Account" />
  </div>
</div>

<?php
        echo '</form>';
    }
    else
        echo '<div class="alert alert-error">No data returned.</div>';
    mysql_close();

?>

