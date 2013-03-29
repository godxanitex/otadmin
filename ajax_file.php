<?php
    session_start();
    include "config.php";
    if (!isset($_SESSION['authed']) or $_SESSION['authed'] == false)
    {
        echo 'You must have privileges to access this page.';
        die();
    }
    $name = $_GET['name'];
    $log_path = $ot_root . '/data/logs';
    if (strstr($name,$log_path) and !strstr($name,'..') or strstr($name,'dumps') and !strstr($name,'..'))
    {
        echo htmlentities(file_get_contents($name));
    }
    else
    {
        echo 'You are not able to access this file.';
    }
?>
