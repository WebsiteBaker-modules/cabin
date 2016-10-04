<?php

// $Id: modify.php,v 1.21p1 2005/06/27 21:58:16 benniewijs Exp $

/* -------------------------------------------------------------------------
			Website Baker project <http://www.websitebaker.org/>
					Copyright (C) 2004 - Ryan Djurovich
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

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }

$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$fetch_page_content = $query_page_content->fetchRow();
$page_created = $fetch_page_content['link'];

$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_settings WHERE section_id = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();

?>

<form name="edit" action="<?php echo WB_URL; ?>/modules/cabin/save_set.php" method="post" style="margin: 0;">

<input type=hidden name="page_id" value="<?php echo $page_id; ?>">
<input type=hidden name="section_id" value="<?php echo $section_id; ?>">

<BODY>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
  <tr>
      <td width="50%" valign="top">
			<?php
			$title = stripslashes($fetch_page_content['title']);

 			$event_msg = stripslashes($fetch_page_content['event_msg']);
			if ($event_msg == '') { $event_msg = 'Nothing for today.'; };

			$noevents_msg = stripslashes($fetch_page_content['noevents_msg']);
			if ($noevents_msg == '') { $noevents_msg = 'No events for this period.'; };

			$default_url = stripslashes($fetch_page_content['default_url']);
			?>
			Give header name<br />
			<input name="evl_header" type="text" value="<?php echo $title; ?>" style="width: 300px;">
			<br />
			Give Line if today no event<br />
			<input name="evl_event_msg" type="text" value="<?php echo $event_msg; ?>" style="width: 300px;">
			<br />
			Give Line for no events this month<br />
			<input name="evl_noevents_msg" type="text" value="<?php echo $noevents_msg; ?>" style="width: 300px;">
			<br />
			Give Default url<br />
			<input name="default_url" type="text" value="<?php echo $default_url; ?>" style="width: 300px;">
      </td>
      <td width="50%" valign="top">

<table cellpadding="5" cellspacing="0" border="0" width="400" style="margin-top: 10px; margin-bottom: 10px;">
<tr>
	<td width="150">Display Today event</td>
	<td>
		<input type="radio" name="evl_todayevent" id="evl_todayevent1" value="1" <?php if($fetch_page_content['todayevent'] == "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_todayevent1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
		
		<input type="radio" name="evl_todayevent" id="evl_todayevent0" value="0" <?php if($fetch_page_content['todayevent'] != "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_todayevent0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
	</td>
</tr>
<tr>
	<td width="150">Display all Events</td>
	<td>
		<input type="radio" name="evl_showalldata" id="evl_showalldata1" value="1" <?php if($fetch_page_content['showalldata'] == "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_showalldata1').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['YES']; ?></font>
		
		<input type="radio" name="evl_showalldata" id="evl_showalldata0" value="0" <?php if($fetch_page_content['showalldata'] != "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_showalldata0').checked = true; return false;" style="cursor: default;"><?php echo $TEXT['NO']; ?></font>
	</td>
</tr>
<tr>
	<td width="150"><?php echo $TEXT['DATE_FORMAT'] ?></td>
	<td>
		<input type="radio" name="evl_dateview" id="evl_dateview0" value="0" <?php if($fetch_page_content['date_view'] != "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_dateview0').checked = true; return false;" style="cursor: default;">Year-Month-Day</font><br />

		<input type="radio" name="evl_dateview" id="evl_dateview1" value="1" <?php if($fetch_page_content['date_view'] == "1") { echo "checked"; } ?>>
		<font onclick="document.getElementById('evl_dateview1').checked = true; return false;" style="cursor: default;">Day-Month-Year</font>
	</td>
</tr>
</table>


      </td>
  </tr>
</table>

<br />
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top: 5px;">
<tr>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 300px;">
	</td>
	<td align="right">
		<input type="button" value="Cancel" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/index.php'; return false;" style="width: 150px;" />
	</td>
</tr>
</table>
</form>

<h3 style="text-align: center; font-size: 14px; color: #336699; border-bottom: 1px solid #CCCCCC; padding-bottom: 2px;">EVENTS</h3>

<p style="text-align: justify;">
<center>
<a class="link" href="<?php echo WB_URL; ?>/modules/cabin/modify_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>"><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' Event'; ?></a><br />
<a class="link" href="<?php echo WB_URL; ?>/modules/cabin/add_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>"><?php echo $TEXT['ADD'].' Event'; ?> </a><br />
</center>
</P>
