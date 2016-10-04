<?php

// $Id: modify_event.php,v 1.21p1 2005/06/27 14:24:18 benniewijs Exp $

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

$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_settings WHERE section_id = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();
$date_view = $fetch_page_content['date_view'];

?>
<HEAD>
<link rel="stylesheet" type="text/css" href="<?php echo $WB_URL; ?>/modules/cabin/event.css">
</HEAD>
<BODY>

<a class="link" href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>"><?php echo $TEXT['SETTINGS']; ?></a> | 
<a class="link" href="<?php echo WB_URL; ?>/modules/cabin/add_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>"><?php echo $TEXT['ADD'].' Event'; ?> </a><br />

<table class="evtdborder" border=0 cellspacing=0 cellpadding=2 >
      <?php
      $query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cabin_dates` WHERE section_id = '$section_id' ORDER BY date");
      if ($query_dates->numRows() > 0) {
        while( $result = $query_dates->fetchRow() ) {
          $altdate="";
          $alt2date=$result['date'];
          if ($date_view == 1 ) { 
          list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
          $altdate=$a2day."-".$a2month."-".$a2year;
          } else { $altdate=$alt2date; }

          ?>
          <TR>
          <TD width="65"><a class="evlink" href="<?php echo WB_URL; ?>/modules/cabin/change_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&event_id=<?php echo $result['event_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>"><img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['MODIFY']; ?> - "></a> : <a class="evlink" href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/cabin/delete_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&event_id=<?php echo $result['event_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>"><img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="<?php echo $TEXT['DELETE']; ?> - "></a> - </TD>
          <TD width="130"><span class="evgenfont"> <?php echo $altdate ?> Event : </span></TD>
          <TD><span class="evgenfont"><?php echo $result['event_desc']  ?></span></TD>
          <?php if (!$result['evweb_url'] == "") { echo "<TD width=\"50\"> - Link</TD>"; } ?>
          </TR>
          <?php
        } 
      } else { echo "No Events"; }
?>
</TD></TR></TABLE>
</BODY>
<?php

// Print admin footer
$admin->print_footer();

?>