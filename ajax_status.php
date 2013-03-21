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
