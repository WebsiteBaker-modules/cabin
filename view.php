<?php
if(defined('WB_PATH') == false) { exit("Cannot access this file directly"); }
require(WB_PATH.'/modules/cabin/language.php');

$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_cabin_settings WHERE section_id = '$section_id'");

if($query_content->numRows() > 0) {

	$fetch_content = $query_content->fetchRow();

	$WB_PATH = WB_PATH;
	$WB_URL = WB_URL;
        $title = $fetch_content['title'];
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
if ( $title <> "" )
{ ?> 
	<h1><strong><?php echo $title; ?></strong></h1>
<?php }
?>

<?php
if ($DisToEvents == 1 ){
  ?>
<div class="border">
	<div class="head">
    	<?php echo $EVTEXT['TODAYEVENTS'] ?>&nbsp;
			<?php
              $query_dates = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_cabin_dates` WHERE section_id = '$section_id' && date = '$today'");
              if ($query_dates->numRows() > 0) {
                while( $result = $query_dates->fetchRow() ) {
            ?>
        <div class="status-rent">
	        <?php echo $result['event_desc'] ?>
            <?php if (!$result['evweb_url'] == "") { ?> -> <a href="<?php echo $result['evweb_url'] ?>" target="_blank">Link</a> <?php } ?>
        </div>
	</div>
	<?php
        } 
    } else { 
    ?>
    	<div class="status-free">
			<?php echo $noeventsmsg ?>
        </div>
	<?php } ?>
<?php } ?>
</div>

<div class="heading">
    <?php $monthchange = $monthno-1; $yearchange=$year; if ($monthchange==0){ $monthchange=12; $yearchange=$year-1;} else { $yearchange=$year; } ?>
    <a href="<?php echo $_SERVER['PHP_SELF']."?monthno=".$monthchange."&amp;year=".$yearchange ?>">
        <i class="fa fa-arrow-left" title="<?php echo date("M", mktime(0, 0, 0, $monthchange, 1, $yearchange))." ".$yearchange ?>" aria-hidden="true">&nbsp;</i>
    </a>
    <?php echo $monthfulltext." ".$year ?>
    
	<?php $monthchange = $monthno+1; if ($monthchange==13){ $monthchange=1; $yearchange=$year+1;} else { $yearchange=$year; } ?>
    <a href="<?php echo $_SERVER['PHP_SELF']."?monthno=".$monthchange."&amp;year=".$yearchange ?>">
        <i class="fa fa-arrow-right" title="<?php echo date("M", mktime(0, 0, 0, $monthchange, 1, $yearchange))." ".$yearchange ?>" aria-hidden="true">&nbsp;</i>
    </a>
</div>

<div class="head">
	<div class="date-info">
		<?php echo $TEXT['DATE'] ?>
    </div>
    <div class="event-head">
		<?php echo $EVTEXT['EVENT'] ?>
	</div>
	<div class="info-head">
		<?php echo $EVTEXT['INFO'] ?>
    </div>
</div>

<?php
$day_of_wk = date("w", mktime(0, 0, 0, $monthno, 1, $year));

for ($date_of_mth = 1; $date_of_mth <= $day_in_mth; $date_of_mth++) {

  if ($day_of_wk = 0){
    for ($i=0; $i<$day_of_wk; $i++) { ?> <?php }
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
          $alt.="<a href=\"".$default_url."?".$altdate."\" target=\"_blank\">"." ".$row['event_desc']."</a><br />";
        else
          $alt.=" ".$row['event_desc']."<br />";
				$ecolor=$row['name_link'];
        $evweb_url.="<a href=\"".$row['evweb_url']."\" target=\"_blank\">".$row['name_link']."</a><br />";
	if ($ecolor == "" ) { $bcolor="#2A520B"; }
	if ($ecolor == 1 ) { $bcolor="#CC0000"; }
	if ($ecolor == 2 ) { $bcolor="#2A520B"; }
	if ($ecolor == 3 ) { $bcolor="#FF7401"; }
	if ($ecolor == 4 ) { $bcolor="#0000FF"; }
	if ($ecolor == 5 ) { $bcolor="#FFC501"; }
      }
      ?>
<div class="date">
    <div class="date-info today-rent">
        <?php echo $altdate ?>
    </div>
    <div class="event-info today-rent">
        <?php echo $alt ?>
    </div>
    <div class="info-info" style="background:<?php echo $bcolor ?>">&nbsp;</div>
</div>
      <?php

    } else {

      $altdate = "";
      $alt2date = $currentdate;
      if ($date_view == 1 ) { 
        list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
        $altdate=$a2day."-".$a2month."-".$a2year;
      } else { $altdate=$alt2date; }
      ?>
<div class="date">
    <div class="date-info today-free">
        <?php echo $altdate ?>
    </div>
    <div class="event-info today-free">
        <?php echo $alt ?>
    </div>
    <div class="info-info" style="background:<?php echo $bcolor ?>">&nbsp;</div>
</div>
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
          $alt.="<a href=\"".$default_url."?".$altdate."\" target=\"_blank\">"." ".$row['event_desc']."</a><br />";
        else
        $alt.=" ".$row['event_desc']."<br />";
	$ecolor=$row['name_link'];
        $evweb_url.="<a href=\"".$row['evweb_url']."\" target=\"_blank\">".$row['name_link']."</a><br />";
	if ($ecolor == "" ) { $bcolor="#2A520B"; $title = $EVTEXT['NOEVENT'];}
	if ($ecolor == 1 ) { $bcolor="#CC0000"; $title = $EVTEXT['EVENT_TYPE_1']; }
	if ($ecolor == 2 ) { $bcolor="#2A520B"; $title = $EVTEXT['EVENT_TYPE_2'];}
	if ($ecolor == 3 ) { $bcolor="#FF7401"; $title = $EVTEXT['EVENT_TYPE_3'];}
	if ($ecolor == 4 ) { $bcolor="#0000FF"; $title = $EVTEXT['EVENT_TYPE_4'];}
	if ($ecolor == 5 ) { $bcolor="#FFC501"; $title = $EVTEXT['EVENT_TYPE_5'];}
      }
    ?>
<div class="date">
    <div class="date-info rent">
        <?php echo $altdate ?>
    </div>
    <div class="event-info rent">
        <?php echo $alt ?>
    </div>
    <div class="info-info" title="<?php echo $title ?>" style="background:<?php echo $bcolor ?>">&nbsp;</div>
</div>
    <?php
  } elseif ($event == 0 ){
      $alt = " ".$EVTEXT['NOEVENT']."<br />";
      $altdate = "";
      $alt2date = $currentdate;
      if ($date_view == 1 ) { 
        list($a2year, $a2month, $a2day) = preg_split('#[/.-]#', $alt2date);
        $altdate=$a2day."-".$a2month."-".$a2year;
        } else { $altdate=$alt2date; }
        $evweb_url = "";
    ?>
<div class="date">
    <div class="date-info">
        <?php echo $altdate ?>
    </div>
    <div class="event-info">
        <?php echo $alt ?>
    </div>
    <div class="info-info free" title="<?php echo $EVTEXT['NOEVENT']; ?>">&nbsp;</div>
</div>
    <?php
  }
}
?>
</center>