<?php

// $Id: change_event.php,v 1.21p1 2005/06/27 15:21:16 benniewijs Exp $

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

require(WB_PATH.'/modules/cabin/language.php');

$page_id = $_GET['page_id'];
$section_id = $_GET['section_id'];
$event_id = $_GET['event_id'];

// Get Settings from database
$query_page_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_settings WHERE section_id = '$section_id'");
$fetch_page_content = $query_page_content->fetchRow();
$date_view = $fetch_page_content['date_view'];

// Get Data from database
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_dates WHERE event_id = '$event_id'");
$fetch_content = $query_content->fetchRow();

?>
<a class="link" href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>"><?php echo $TEXT['SETTINGS']; ?></a> | 
<a class="link" href="<?php echo WB_URL; ?>/modules/cabin/modify_event.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>"><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' Event'; ?></a><br />
<form name="modify" action="<?php echo WB_URL; ?>/modules/cabin/save_event.php" method="post" style="margin: 0;">
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

<?php
$ev_date = $fetch_content['date'];
$event_desc = $fetch_content['event_desc']; 
$evweb_url = $fetch_content['evweb_url']; 
list($year, $month, $day) = preg_split('#[/.-]#', $ev_date);
$name_link = $fetch_content['name_link']; 
?>

<table width="90%" class="tdborder" cellSpacing=1 cellPadding=6  border=0>
 <tr>
   <td>
      <table width="100%" class=tableborder cellSpacing=1 cellPadding=0  border=0>
       <tr>
         <td><div class="genfont"><b><?php echo $TEXT['DATE'] ?></b></div>
              <div>
<?php
$year_interval=6;

function SelectYear($year_interval, $year, $TITLE){
  $CurrYear=date("Y");
  If(isset($year)) { $CurrYear=$year; }
  echo $TITLE.":<select name=year>\n"; 
  $i=$CurrYear-$year_interval+1; 
  while ($i < $CurrYear+$year_interval) { 
    if ($i == $CurrYear) { 
      echo "<option selected> $i\n"; 
    } else { 
      echo "<option> $i\n"; 
    } 
    $i++; 
  }
echo "</select>\n"; 
}

Function SelectMonth($month, $TITLE){

  echo " ".$TITLE.":<select name=month>\n"; 
  $i=1; 
  $CurrMonth=date("m"); 
  while ($i <= 12) {
    If(IsSet($month)) { 
      If($month == $i || ($i == substr($month,1,1) && (substr($month,0,1) == 0))) { 
        echo"<option selected> $month\n"; 
        $i++; 
      }Else{ 
        If($i<10) { 
          echo "<option> 0$i\n"; 
        }Else { 
          echo "<option> $i\n"; 
        } 
      $i++; 
      } 
    }Else { 
      If($i == $CurrMonth) { 
        If($i<10) { 
          echo "<option selected> 0$i\n"; 
        }Else { 
          echo "<option selected> $i\n"; 
        } 
      }Else { 
        If($i<10){ 
          echo "<option> 0$i\n"; 
        }Else { 
          echo "<option> $i\n"; 
        } 
      } 
      $i++; 
    } 
  } 
echo "</select>\n"; 
}

function SelectDay($day, $TITLE){

  echo " ".$TITLE.":<select name=day>\n"; 
  $i=1; 
  $CurrDay=date("d"); 
  If(!IsSet($day)) $day=$CurrDay; 
  while ($i <= 31) { 
    If(IsSet($day)) { 
      If($day == $i || ($i == substr($day,1,1) && (substr($day,0,1) == 0))) { 
        echo"<option selected> $day\n"; 
        $i++; 
      }Else{ 
        If($i<10) { 
          echo "<option> 0$i\n"; 
        }Else { 
          echo "<option> $i\n"; 
        } 
        $i++; 
      } 
    }Else { 
      If($i == $CurrDay) 
      If($i<10) { 
        echo "<option selected> 0$i\n"; 
      }Else { 
        echo"<option selected> $i\n"; 
      } Else { 
        If($i<10) { 
          echo "<option> 0$i\n"; 
        }Else { 
          echo "<option> $i\n"; 
        } 
      } 
    $i++; 
    } 
  } 
echo "</select>\n"; 
}

function CopyDays($days, $TITLE){
  echo "  ".$TITLE.":<select name=copydays>\n"; 
$i=1; 
while ($i <= $days) {
  if ($i == 1) { 
    echo "<option selected> $i\n"; 
    $i++; 
  } else {
  echo "<option> $i\n"; 
  $i++; 
  }
} 

echo "</select>\n"; 
}

function Color($number, $name_link, $TITLE){
  echo "  ".$TITLE.":<select name=color>\n"; 
$i=1; 
while ($i <= $number) {
  if ($i == $name_link) { 
    echo "<option selected> $i\n"; 
    $i++; 
  } else {
  echo "<option> $i\n"; 
  $i++; 
  }
} 

echo "</select>\n"; 
}

if ($date_view == 1 ) { 
SelectDay($day,$EVTEXT['DAY']);
SelectMonth($month,$EVTEXT['MONTH']);
SelectYear(5,$year,$EVTEXT['YEAR']);
CopyDays(21,$EVTEXT['COPYDAYS']);
echo "<br /><br />";
Color(5,$name_link,$EVTEXT['COLOR']);
} else { 
SelectYear($year_interval,$year,$EVTEXT['YEAR']);
SelectMonth($month,$EVTEXT['MONTH']);
SelectDay($day,$EVTEXT['DAY']);
CopyDays(21,$EVTEXT['COPYDAYS']);
echo "<br /><br />";
Color(5,$name_link,$EVTEXT['COLOR']);
}

?><br />
<font size="1" face="Tahoma" color="#CC0000">&#9608;&#9608;</font> <font size="1" face="Tahoma">1 <?php echo $EVTEXT['EVENT_TYPE_1']; ?></font>
<br />

<font size="1" face="Tahoma" color="#2A520B">&#9608;&#9608;</font> <font size="1" face="Tahoma">2 <?php echo $EVTEXT['EVENT_TYPE_2']; ?></font>
<br />

<font size="1" face="Tahoma" color="#FF7401">&#9608;&#9608;</font> <font size="1" face="Tahoma">3 <?php echo $EVTEXT['EVENT_TYPE_3']; ?></font>
<br />

<font size="1" face="Tahoma" color="#0000FF">&#9608;&#9608;</font> <font size="1" face="Tahoma">4 <?php echo $EVTEXT['EVENT_TYPE_4']; ?></font>
<br />

<font size="1" face="Tahoma" color="#FFC501">&#9608;&#9608;</font> <font size="1" face="Tahoma">5 <?php echo $EVTEXT['EVENT_TYPE_5']; ?></font>

              </div>
         </td>
       </tr>
     </table>
   </td>
  <tr>
   <td><div class="genfont"><?php echo $EVTEXT['ENTEREVENT'] ?> :</div>
       <textarea name="event_desc" rows="4" cols="15" wrap="virtual" style="width:550px; font-size:12px" tabindex="3"><?php echo $event_desc; ?></textarea>
   </td>
  </tr>
  <!-- tr>
   <td><div class="genfont"><?php echo $EVTEXT['ENTERLINK'] ?> (http://www.websitebaker.org) :</div>
       <input name="evweb_url" style="width:550px; font-size:12px" tabindex="4" value="<?php echo $evweb_url; ?>">
   </td>
  </tr> -->
  <tr>
    <td><div align="center">
      <input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 200px; margin-top: 5px;"></form></div>
    </td>
  </tr>
</table>
</form>
<?php

// Print admin footer
$admin->print_footer();

?>
