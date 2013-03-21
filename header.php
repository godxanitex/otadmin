<?php
    session_start();
    include "config.php";
    if (strstr($_SERVER['PHP_SELF'],'logout.php'))
    {
        $_SESSION['authed'] = 'false';
        session_destroy();
        header('Location: index.php');
    }
    if ($_POST['account_number'])
    {
        mysql_connect($host,$un,$pw) or die("Cannot connect to database.");
        mysql_select_db($db);
        $an = mysql_real_escape_string($_POST['account_number']);
        $pass = mysql_real_escape_string($_POST['password']);
        $account = mysql_query('SELECT * FROM `accounts` WHERE `name` = "'.$an.'" AND `password` = sha1("'.$pass.'") AND `page_access` >= 3;');
        if (mysql_num_rows($account) == 1)
        {
            while ($row = mysql_fetch_assoc($account))
            {
                $_SESSION['id'] = $row['id'];
                $_SESSION['an'] = $row['name'];
                $char_query = mysql_query('SELECT `name` FROM `players` WHERE `account_id` = "'.$row['id'].' AND `group` > 3 ORDER BY GROUP DESC LIMIT 0,1');
                while ($char_row = mysql_fetch_assoc($char_query))
                    $_SESSION['char'] = $char_row['name'];
                $_SESSION['authed'] = true;
                $_SESSION['access'] = $row['page_access'];
            }
        }
        mysql_close();
    }
    session_write_close();
    $menu_auth = array(
        //"Home" => "index.php",
        "Dashboard" => "dashboard.php",
        "Account Management" => "account_management.php",
        "Player Management" => "player_management.php",
        "Admin Logs" => "admin_logs.php",
        "Crash Logs" => "crash_dump.php",
        "Log Out" => "logout.php"
    );
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>OTAdmin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="ico/favicon.png">
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="index.php" <?php if (strstr($_SERVER['PHP_SELF'],'index.php')) echo 'style="color: #FFF"'; ?>>OTAdmin</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
            <?php
                if (isset($_SESSION['authed']) AND $_SESSION['authed'] == true)
                {
                    foreach ($menu_auth as $name => $link)
                    {
                        if (!is_array($link))
                        {
                            echo '<li';
                            if (strstr($_SERVER['PHP_SELF'], $link))
                                echo ' class="active"';
                            echo '><a href="'.$link.'">'.$name.'</a></li>';
                        }
                        else
                        {
                            echo '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$name.' <b class="caret"></b></a><ul class="dropdown-menu">';
                            foreach ($link as $sub_name => $sub_link)
                            {
                                echo '<li';
                                    if (strstr($_SERVER['PHP_SELF'], $sub_link))
                                        echo ' class="active"';
                                echo '><a href="'.$sub_link.'">'.$sub_name.'</a></li>';
                            }
                            echo '</ul></li>';
                        }
                    }
                }
              ?>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
        <?php
            if (!isset($_SESSION['authed']) OR $_SESSION['authed'] == false)
            {
                include "login.php";
                include "footer.php";
                die();
            }
        ?>

