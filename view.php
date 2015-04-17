<?php

// $Id: view.php,v 1.21p1 2005/06/27 14:47:03 benniewijs Exp $

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

require(WB_PATH.'/modules/cabin/language.php');

$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_settings WHERE section_id = '$section_id'");

if($query_content->numRows() > 0) {

	$fetch_content = $query_content->fetchRow();

	$WB_PATH = WB_PATH;
	$WB_URL = WB_URL;
        $evheader = $fetch_content['evheader'];
        $DisToEvents = $fetch_content['todayevent'];
        $noeventmsg = $fetch_content['event_msg'];
        $noeventsmsg = $fetch_content['noevents_msg'];
        $default_url = $fetch_content['default_url'];
        $Showalldata = $fetch_content['showalldata'];
}
if (isset($_GET['eventdate']))
{ $eventdate = $_GET['eventdate']; }

if (isset($_GET['monthno']))
{ $monthno = $_GET['monthno']; }
else { $monthno=date("n"); }

if (isset($_GET['year']))
{ $year = $_GET['year']; }
else { $year = date("Y"); }

if (isset($_GET['view']))
{ $view = $_GET['view']; }
else { $view = 0; }

if (isset($_GET['dview']))
{ $date_view = $_GET['dview']; }
else { $date_view = $fetch_content['date_view']; }

$monthfulltext = $month_name[$monthno];
$day_in_mth = date("t", mktime(0, 0, 0, $monthno, 1, $year)) ;

$today = date("Y-m-j");

?>


<center>
<?php
if ( $evheader <> "" )
{ ?> <div class="genfont"><H1><b><?php echo $evheader; ?></b></H1><br></div> <?php }
?>

<!-- Display Today's events -->
<?php
if ($DisToEvents == 1 ){
  ?>
  <table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="1" width="95%" class="evtdborder">
    <tr>
      <td class="evtdeventheading"><?php echo $EVTEXT['TODAYEVENTS'] ?></td></tr>
      <td  class="evevent"><?php echo $TEXT['DATE'] ?> : <b><u><?php echo date('j'); echo " ".$month_name[date('n')] ?></u></b></td></tr>
      <?php
      $query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cabin_dates` WHERE section_id = '$section_id' && date = '$today'");
      if ($query_dates->numRows() > 0) {
        while( $result = $query_dates->fetchRow() ) {
          ?>
          <tr><td  class="evevent"><li><?php echo $result['event_desc'] ?>
          <?php if (!$result['evweb_url'] == "") { ?> -> <a href="<?php echo $result['evweb_url'] ?>" target="_blank">Link</a> <?php } ?>
          </li></td>
          </tr>
          <?php 
        } 
      } else { 
        ?> <td  class="evevent"><li><?php echo $noeventsmsg ?></li></td></tr><?php } ?>
        </table><BR>
        <?php 
      }

?>

<!-- Display menu of events -->
<a class="evTodayDis" href="<?php echo $_SERVER['PHP_SELF']."?"; ?>"><U><?php echo $EVTEXT['TODAY'] ?>:</U></a> <font  class="evTodayDis"><?php echo date("j")." ".$month_name[date("n")]." ".date("Y");  ?></font><BR>
<table border="0" width="95%">
<tr>
  <td class="evtdheading" width="10%">
  <?php $monthchange = $monthno-1; $yearchange=$year;
  if ($monthchange==0){ $monthchange=12; $yearchange=$year-1;} else { $yearchange=$year; }
  ?>
  <a class="evstd" href="<?php echo $_SERVER['PHP_SELF']."?monthno=".$monthchange."&amp;year=".$yearchange ?>">
  <img src="<?php echo $WB_URL; ?>/modules/cabin/leftarrow.png" border="0" alt="<?php echo date("M", mktime(0, 0, 0, $monthchange, 1, $yearchange))." ".$yearchange ?>"></img></a>
  </td>

  <td class="evtdheading" width="80%"><?php echo $monthfulltext." ".$year ?></td>

  <td class="evtdheading" width="10%">
  <?php $monthchange = $monthno+1;
  if ($monthchange==13){ $monthchange=1; $yearchange=$year+1;} else { $yearchange=$year; }
  ?>
  <a class="evstd" href="<?php echo $_SERVER['PHP_SELF']."?monthno=".$monthchange."&amp;year=".$yearchange ?>">
  <img src="<?php echo $WB_URL; ?>/modules/cabin/rightarrow.png" border="0" alt="<?php echo date("M", mktime(0, 0, 0, $monthchange, 1, $yearchange))." ".$yearchange ?>"></img></a>
  </td>
</tr>

<tr><td colspan="3">


<!-- Display of events -->
<table border="0" width="100%">
  <TR><TD>
  <table class="evtdborder" border="0" width="100%" CELLPADDING="0" CELLSPACING="0">
  <tr><td class="evtdeventheading" width="10"><?php echo $TEXT['DATE'] ?></td><td class="evtdeventheading" width="100%"><?php echo $EVTEXT['EVENT'] ?></td><td class="evtdeventheading" width="10"><?php echo $EVTEXT['INFO'] ?></td></tr>
  </table>
  </TD></TR>

<?php
$day_of_wk = date("w", mktime(0, 0, 0, $monthno, 1, $year));

for ($date_of_mth = 1; $date_of_mth <= $day_in_mth; $date_of_mth++) {

  if ($day_of_wk = 0){
    for ($i=0; $i<$day_of_wk; $i++) { ?><tr><?php }
  }

  $date_no = date("j", mktime(0, 0, 0, $monthno, $date_of_mth, $year));
  $day_of_wk = date("w", mktime(0, 0, 0, $monthno, $date_of_mth, $year));
  $currentdate = date("Y-m-d", mktime(0, 0, 0, $monthno, $date_of_mth, $year) );
   
  $query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cabin_dates` WHERE section_id = '$section_id' && date = '$currentdate'");
  $event  = $query_dates->numRows();
  if ( $date_no ==  date("j") &&  $monthno == date("n") ){
    if ($event > 0 ){
      $alt = "";
      $altdate = "";
      $evweb_url = "";
      while ($row = $query_dates->fetchRow()) {
        $alt2date=$row['date'];
        if ($date_view == 1 ) { 
          list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
          $altdate=$a2day."-".$a2month."-".$a2year;
        } else 
        	$altdate=$alt2date;
        if (strlen($default_url) > 3)	
          $alt.="<A href=\"".$default_url."?".$altdate."\" target=\"_blank\">"."- ".$row['event_desc']."</A><BR>";
        else
          $alt.="- ".$row['event_desc']."<BR>";
				$ecolor=$row['name_link'];
        $evweb_url.="<A href=\"".$row['evweb_url']."\" target=\"_blank\">".$row['name_link']."</A><BR>";
	if ($ecolor == "" ) { $bcolor="#00CC00"; }
	if ($ecolor == 1 ) { $bcolor="#CC0000"; }
	if ($ecolor == 2 ) { $bcolor="#00CC00"; }
	if ($ecolor == 3 ) { $bcolor="#FF6600"; }
	if ($ecolor == 4 ) { $bcolor="#0000FF"; }
	if ($ecolor == 5 ) { $bcolor="#FFFF66"; }
      }
      ?>
      <TR><TD>
      <table class="evtdborder" border="0" width="100%" CELLPADDING="0" CELLSPACING="0">
      <tr><td class="evtdtoday" width="80"><?php echo $altdate ?></td>
      <td class="evtdtoday"><?php echo $alt ?></td>
      <TD class="evtddate" width="30" BGCOLOR="<?php echo $bcolor ?>">&nbsp;</TD>
      </tr></table>
      </TD></TR>
      <?php

    } else {

      $altdate = "";
      $alt2date = $currentdate;
      if ($date_view == 1 ) { 
        list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
        $altdate=$a2day."-".$a2month."-".$a2year;
      } else { $altdate=$alt2date; }
      ?>
      <TR><TD>
      <table class="evtdborder" border="0" width="100%" CELLPADDING="0" CELLSPACING="0">
      <tr>
      <td class="evtdtoday" width="80"><?php echo $altdate ?></td>
      <td class="evtdtoday"><?php echo $alt ?></td>
      <TD class="evtddate" width="30" BGCOLOR="<?php echo $bcolor ?>">&nbsp;</TD>
      </tr></table>
      </TD></TR>
      <?php
    }
  } elseif ($event > 0 ){
      $alt = "";
      $altdate = "";
      $evweb_url = "";
      while ($row = $query_dates->fetchRow()) {
        $alt2date=$row['date'];
        if ($date_view == 1 ) { 
        list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
        $altdate=$a2day."-".$a2month."-".$a2year;
        } else 
          $altdate=$alt2date;
        if (strlen($default_url) > 3)	
          $alt.="<A href=\"".$default_url."?".$altdate."\" target=\"_blank\">"."- ".$row['event_desc']."</A><BR>";
        else
        $alt.="- ".$row['event_desc']."<br>";
	$ecolor=$row['name_link'];
        $evweb_url.="<A href=\"".$row['evweb_url']."\" target=\"_blank\">".$row['name_link']."</A><BR>";
	if ($ecolor == "" ) { $bcolor="#00CC00"; $title = $EVTEXT['NOEVENT'];}
	if ($ecolor == 1 ) { $bcolor="#CC0000"; $title = $EVTEXT['EVENT_TYPE_1']; }
	if ($ecolor == 2 ) { $bcolor="#00CC00"; $title = $EVTEXT['EVENT_TYPE_2'];}
	if ($ecolor == 3 ) { $bcolor="#FF6600"; $title = $EVTEXT['EVENT_TYPE_3'];}
	if ($ecolor == 4 ) { $bcolor="#0000FF"; $title = $EVTEXT['EVENT_TYPE_4'];}
	if ($ecolor == 5 ) { $bcolor="#FFFF66"; $title = $EVTEXT['EVENT_TYPE_5'];}
      }
    ?>
      <TR><TD>
    <table class="evtdborder" border="0" width="100%" CELLPADDING="0" CELLSPACING="0">
    <tr><td class="evtddate" width="80"><?php echo $altdate ?></td>
    <td class="evtddate"><?php echo $alt ?></td>
    <TD class="evtddate" title="<?php echo $title ?>" width="30" BGCOLOR="<?php echo $bcolor ?>">&nbsp;</TD>
    </tr></table>
      </TD></TR>
    <?php
  } elseif ($event == 0 ){
      $alt = "- ".$EVTEXT['NOEVENT']."<BR>";
      $altdate = "";
      $alt2date = $currentdate;
      if ($date_view == 1 ) { 
        list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
        $altdate=$a2day."-".$a2month."-".$a2year;
        } else { $altdate=$alt2date; }
        $evweb_url = "";
    ?>
      <TR><TD>
    <table class="evtdborder" border="0" width="100%" CELLPADDING="0" CELLSPACING="0">
    <tr>
    <td class="evtddate" width="80"><?php echo $altdate ?></td>
    <td class="evtddate"><?php echo $alt ?></td>
    <TD class="evtddate" title="<?php echo $EVTEXT['NOEVENT']; ?>" width="30" BGCOLOR="#00CC00">&nbsp;</TD>
    </tr></table>
      </TD></TR>
    <?php
  }
}
?>
</table>

</td></tr></table>
</center>
<BR>
<?php
if ($Showalldata == 1 ){
  ?><CENTER><table border="0" CELLPADDING="0" CELLSPACING="0" class="evtdborder">
  <tr></td><td class="evtdtoday"><?php
  echo " -------------------------------- Listing All events --------------------------------- <BR>";
  $query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cabin_dates` WHERE section_id = '$section_id' order by date");

  while($result = $query_dates->fetchRow()) {
    ?>
    <div>
    <span class="evgenfont"><?php echo $result['date']  ?> Event : <?php echo $result['event_desc']  ?></span>
    <?php
  }
?></td></tr></table></CENTER><?php
}
?>
<br />

