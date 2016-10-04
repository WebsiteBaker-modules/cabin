<?php

// $Id: delete_event.php,v 1.21p1 2005/06/27 13:36:11 benniewijs Exp $

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
if(!isset($_GET['event_id']) OR !is_numeric($_GET['event_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$event_id = $_GET['event_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Get post details
$query_details = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_dates WHERE event_id = '$event_id'");
if($query_details->numRows() > 0) {
	$get_details = $query_details->fetchRow();
} else {
	$admin->print_error($TEXT['NOT_FOUND'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Delete post
$database->query("DELETE FROM ".TABLE_PREFIX."mod_cabin_dates WHERE event_id = '$event_id' LIMIT 1");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/cabin/modify_event.php?page_id='.$page_id.'&event_id='.$event_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/cabin/modify_event.php?page_id='.$page_id.'&section_id='.$section_id);
}

// Print admin footer
$admin->print_footer();

?>
