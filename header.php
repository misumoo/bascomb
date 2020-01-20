<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

//header

$_SESSION['UserID'];
$_SESSION['EventSet'];
$_SESSION['EventName'];
$currentevent = "";

//if(!isset($_SESSION['Checked'])) {
$mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
$sql = "SELECT EventID FROM tblLastEvent WHERE UserID='".$_SESSION['UserID']."' LIMIT 1";
$rs = $mysqli->query($sql);
if ($rs) {
  if($rs->num_rows < 1) {
    $_SESSION['EventSet'] = 0;
  } else {
    while($row = $rs->fetch_assoc()) {
      $currentevent = $row['EventID'];
    }
  }
}

//check to see if this event exists and belongs to this user
$mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
$sql = "SELECT EventID FROM tblEvents WHERE UserID='".$_SESSION['UserID']."' AND EventID = '".$currentevent."'";
$rs = $mysqli->query($sql);
if ($rs) {
  if($rs->num_rows < 1) {
    //we don't have this event
    $_SESSION['EventSet'] = 0;
  } else {
    while($row = $rs->fetch_assoc()) {
      $_SESSION['EventSet'] = $row['EventID'];
    }
  }
}

if($_SESSION['EventSet'] == 0) {
  //we don't have an event
  $_SESSION['EventName'] = "Invalid, click to change";
} else {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
  $sql = "SELECT EventName FROM tblEvents WHERE UserID='".$_SESSION['UserID']."' AND EventID='".$_SESSION['EventSet']."' LIMIT 1";
  $rs = $mysqli->query($sql);
  if ($rs) {
    while($row = $rs->fetch_assoc()) {
      $_SESSION['EventName'] = $row['EventName'];
    }
  }
}


?>

<div class="innerHeader">
  <div class="divHdr left">
    <a class="header-href" href="reglogin.php"><label class="lblButton">Home</label></a>
    <a class="header-href" href="events.php"><label class="lblButton">Events</label></a>
    <a class="header-href" href="custommessages.php"><label class="lblButton">E-Mail</label></a>
    <? echo "<div id='header_Selected' class='header_Current' title='Click to change currently selected event.'>".$_SESSION['EventName']."</div>"; ?>
  </div>

  <div class="divHdr right">
<!--    <a class="header-href" href="zz_testing.php"><label id="h2" class="lblButton">Testing</label></a>-->
    <a class="header-href" href="patchnotes.php"><label class="lblButton">Patch Notes</label></a>
    <a class="header-href" href="settings.php"><label class="lblButton">Settings</label></a>
  </div>
</div>



<div id="popup_SelectNewEvent" title="Select New Event">
    <?php
    $result = db::get_connection("SELECT EventID,EventName FROM tblEvents WHERE UserID='".$_SESSION['UserID']."'");
    if ($result) {
      echo "<select title='Click to change' style='width: 100%;' id='eventWorkingWith' onchange='setNewEvent();'>
                <option value=''>".$_SESSION['EventName']."</option>
              ";
        while ($row = mysqli_fetch_array($result))
        {
          echo "<option value='".$row['EventID']."'>".$row['EventName']."</option>";
        }
        echo "</select>";


    } else {
//      mysqli_close($db_con);
    }
    ?>
</div>