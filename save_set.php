<?php

// $Id: save_set.php,v 1.21p1 2005/06/27 22:51:31 benniewijs Exp $

/* -------------------------------------------------------------------------
	Website Baker project <http://www.websitebaker.org/>
		Copyright (C) 2004-2005, Ryan Djurovich
----------------------------------------------------------------------------
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
--------------------------------------------------------------------------*/


require('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// This code removes any <?php tags
$title = $admin->get_post('evl_header');
$event_msg = $admin->get_post('evl_event_msg');
$noevents_msg = $admin->get_post('evl_noevents_msg');
$default_url = $admin->get_post('default_url');
$todayevent = $admin->get_post('evl_todayevent');
$showalldata = $admin->get_post('evl_showalldata');
$date_view = $admin->get_post('evl_dateview');

$header = addslashes(str_replace('?php', '', $admin->get_post('header')));
$footer = addslashes(str_replace('?php', '', $admin->get_post('footer')));


$database->query("UPDATE ".TABLE_PREFIX."mod_cabin_settings SET page_id = '$page_id', title = '$title', event_msg = '$event_msg', noevents_msg = '$noevents_msg', default_url = '$default_url', todayevent = '$todayevent', showalldata = '$showalldata', date_view = '$date_view' WHERE section_id = '$section_id'");


// Check if there is a database error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), $js_back);
} else {
	$admin->print_success($MESSAGE['PAGES']['SAVED'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer()

?>
