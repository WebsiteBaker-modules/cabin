<?php

// $Id: install.php,v 1.21p1 2005/06/27 17:05:23 benniewijs Exp $

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

if(defined('WB_URL')) {
	
  // Create table 1
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_cabin_settings`");
  $mod_cabin = 'CREATE TABLE `'.TABLE_PREFIX.'mod_cabin_settings` ('
				. ' `page_id` INT NOT NULL,'
				. ' `section_id` INT NOT NULL,'
				. ' `evheader` VARCHAR(128) NOT NULL,'
				. ' `event_msg` VARCHAR(128) NOT NULL,'
				. ' `noevents_msg` VARCHAR(128) NOT NULL,'
				. ' `default_url` VARCHAR(128) NOT NULL,'
				. ' `todayevent` INT NOT NULL,'
				. ' `showalldata` INT NOT NULL,'
                                . ' `date_view` INT NOT NULL,'
				. ' PRIMARY KEY  (`section_id`)'
				. ' )';
  $database->query($mod_cabin);
	
  // Create table 2
  $database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_cabin_dates`");
  $mod_cabin = 'CREATE TABLE `'.TABLE_PREFIX.'mod_cabin_dates` ('
				. '`page_id` INT NOT NULL,'
				. '`section_id` INT NOT NULL,'
				. '`event_id` INT NOT NULL AUTO_INCREMENT,'
				. '`date` date NOT NULL,'
				. '`event_desc` varchar(255) NOT NULL,'
				. '`evweb_url` varchar(128) NOT NULL,'
				. '`name_link` varchar(128) NOT NULL,'
				. 'PRIMARY KEY  (`event_id`)'
				. ')';
  $database->query($mod_cabin);

	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'event', '$field_info')");
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link	FROM [TP]mod_cabin_settings, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'event')");
	// Query body
	$query_body_code = " [TP]pages.page_id = [TP]mod_cabin_settings.page_id AND [TP]mod_cabin_settings.galtitle [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
	OR [TP]pages.page_id = [TP]mod_cabin_settings.page_id AND [TP]mod_cabin_settings.adminname [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'event')");
	// Query end
	$query_end_code = "";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'event')");
	
	// Insert blank row (there needs to be at least on row for the search to work
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_cabin_settings (page_id,section_id) VALUES ('0','0')");
}

?>
