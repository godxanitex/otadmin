<?php
    session_start();
    include "config.php";
    if (!isset($_SESSION['authed']) or $_SESSION['authed'] == false)
    {
        echo 'You must have privileges to access this page.';
        die();
    }
    $host = $_GET['host'];
    $port = $_GET['port'];
    $title = $_GET['title'];
    echo "$title Status: ";
    $fp = fsockopen($host,$port, $errno, $errstr, 30);
    if ($fp)
    {
        echo '<span class="label label-success">Online</span>';
        fclose($fp);
    }
    else
    {
        echo '<span class="label label-important">Offline</span>';
    }
?>
