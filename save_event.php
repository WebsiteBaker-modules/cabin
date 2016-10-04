<?php

// $Id: save_event.php,v 1.21p1 2005/06/21 18:37:21 benniewijs Exp $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');

// Get id
if(!isset($_POST['event_id']) OR !is_numeric($_POST['event_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$event_id = $_POST['event_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Validate all fields
if($admin->get_post('event_desc') == '' AND $admin->get_post('date') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/cabin/change_event.php?page_id='.$page_id.'&section_id='.$section_id.'&event_id='.$event_id);
} else {
	$event_date = $admin->get_post('event_date');
	$event_desc = $admin->get_post('event_desc');
	$evweb_url = $admin->get_post('evweb_url');
	$year = $admin->get_post('year');
	$month = $admin->get_post('month');
	$day = $admin->get_post('day');
	$copydays = $admin->get_post('copydays');
	$color = $admin->get_post('color');
}
$fulldate = $year."-".$month."-".$day;
if ($event_date == $fulldate) {
  $date=$event_date;
} else { 
  $date=$fulldate;
}

$name_link = "";
if (!$evweb_url == "") { $name_link="Link"; }

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_cabin_dates SET page_id = '$page_id', section_id = '$section_id', date = '$date', event_desc = '$event_desc', evweb_url = '$evweb_url', name_link = '$color' WHERE event_id = '$event_id'");

if ($copydays > 1){
  $i=1; 
  while ($i <= $copydays-1) {
    $theday = mktime (0,0,0,$month ,$day+$i ,$year);
    $option=date("D M j, Y",$theday);
    $value=date("Y-m-d",$theday);
//  echo "$i $page_id $section_id $value $event_desc $evweb_url $name_link<br />"; 
    $database->query("INSERT INTO ".TABLE_PREFIX."mod_cabin_dates (page_id,section_id,date,event_desc,evweb_url,name_link) VALUES ('$page_id','$section_id','$value','$event_desc','$evweb_url','$color')");
    $i++; 
  }
}
// Check if there is a db error, otherwise say successful
if($database->is_error()) {
  $admin->print_error($database->get_error(), WB_URL.'/modules/cabin/change_event.php?page_id='.$page_id.'&section_id='.$section_id.'&event_id='.$event_id);
} else {
  $admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/cabin/modify_event.php?page_id='.$page_id.'&section_id='.$section_id.'&event_id='.$event_id);
}

// Print admin footer
$admin->print_footer();

?>
