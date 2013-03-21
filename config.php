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
	// MySQL Configuration Information
    $host = "localhost";
    $un = "demo";
	$pw = "demo_password";
	$db = "tfstest";

    // Arrays that contain vocation information based on promotion
    $vocation_name[0] = array(0 => 'None', 1 => 'Sorcerer', 2 => 'Druid', 3 => 'Paladin', 4 => 'Knight', 5 => 'Barbarian', 6 => 'Assassin'); 
    $vocation_name[1] = array(1 => 'Master Sorcerer', 2 => 'Elder Druid', 3 => 'Royal Paladin', 4 => 'Elite Knight', 5 => 'Savage Barbarian', 6=> 'Dark Assassin'); // id => 'name' , $vocation_name[1] - promotion level 1, 
    $vocation_name[2] = array(1 => 'Unholy Sorcerer', 2 => 'Unholy Druid', 3 => 'Unholy Paladin', 4 => 'Unholy Knight', 5 => 'Unholy Barbarian', 6 => 'Unholy Assassin');
    $vocation_name[3] = array(1 => 'Templar Sorcerer', 2 => 'Templar Druid', 3 => 'Templar Paladin', 4 => 'Templar Knight', 5 => 'Templar Barbarian', 6 => 'Templar Assassin');

    // Promotion Names
    $promotion_name = array(0 => 'None', 1 => 'First', 2 => 'Unholy', 3 => 'Templar');

    // Group List based off the data/XML/groups.xml
    $group_list = array(1 => "Player", 2 => "Tutor", 3 => "Senior Tutor", 4 => "Gamemaster", 5 => "Higher Gamemaster", 6 => "God");

    // List of game servers
    $world_list = array(0 => 'Live Server', 1 => 'Test Server');

    // Path to root of OT installation (folder above data)
    $ot_root = "/devOT";

    // Services for the dashboard
    $services = array();
    // Sample Services
    /*$services[] = array(
        'short_name' => 'ot', 
        'proper_name' => 'Open Tibia', 
        'host' => '127.0.0.1', 
        'port' => '7171'
    );
    $services[] = array(
        'short_name' => 'vent', 
        'proper_name' => 'Ventrilo', 
        'host' => '127.0.0.1', 
        'port' => '3784'
    );
    $services[] = array(
        'short_name' => 'fail',
        'proper_name' => 'Failed Service',
        'host' => '127.0.0.1',
        'port' => '1'
    );*/
?>
