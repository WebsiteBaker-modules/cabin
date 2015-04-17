<?php

// $Id: add_event.php,v 1.21p1 2005/06/27 14:52:35 benniewijs Exp $

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

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Insert new row into database

$date = date('Y-m-j');  
$database->query("INSERT INTO ".TABLE_PREFIX."mod_cabin_dates (page_id,section_id,date) VALUES ('$page_id','$section_id','$date')");

// Get the id
$event_id = $database->get_one("SELECT LAST_INSERT_ID()");

// Say that a new record has been added, then redirect to modify page
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/cabin/modify.php?page_id='.$page_id.'&section_id='.$section_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], WB_URL.'/modules/cabin/change_event.php?page_id='.$page_id.'&section_id='.$section_id.'&event_id='.$event_id);
}

// Print admin footer
$admin->print_footer();

?>
