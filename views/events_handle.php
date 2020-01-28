<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

require_once("../lib/_classes.php");
require_once("../lib/_functions.php");
require('../lib/db.php');

session_start();

$cancelProcess = false;

if (!isset($_SESSION['authUser'])) {
  $cancelProcess = true;
	header("Location: login.php");
}

if(isset($_POST['saveevent']) && !$cancelProcess) {
  $eventname = $_POST['eventname'];
  $startdate = $_POST['startdate'];
  $enddate = $_POST['enddate'];
  $userid = $_SESSION['UserID'];
  $capacity = $_POST['capacity'];
  $displaycapacity = $_POST['displaycapacity'];
  $eventid = $_POST['eventid'];

  if($eventid == "") {
    $task = createEvent($eventname, $startdate, $enddate, $userid, $capacity, $displaycapacity);
  } else {
    $task = updateEvent($eventname, $startdate, $enddate, $userid, $capacity, $displaycapacity, $eventid);
  }

  if(!$task) {
    $data = array("success" => false, "message" => "Error!");
  } else {
    $data =  array("success" => true, "message" => "Success!", "id" => $task);
  }

  echo json_encode($data);
} //saveevent

if(isset($_POST['deleteEvent']) && !$cancelProcess) {
  $userid = $_SESSION['UserID'];
  $eventid = $_POST['eventid'];

  $task = deleteEvent($userid, $eventid);

  if(!$task) {
    $data = array("success" => false, "message" => "Error!");
  } else {
    $data =  array("success" => true, "message" => "Success!");
  }

  echo json_encode($data);
} //deleteEvent

if(isset($_POST['geteventinfo']) && !$cancelProcess) {
  $eventid = $_POST['eventid'];
  $userid = $_SESSION['UserID'];

  $data = getEventInfo($eventid, $userid);

  $data =  array(
    "success" => true,
    "message" => "Success!",
    "capacity" => $data->capacity,
    "startdate" => $data->startdate,
    "eventname" => $data->eventname,
    "eventid" => $data->eventid,
    "enddate" => $data->enddate,
    "displaycapacity" => $data->displaycapacity
  );

  echo json_encode($data);
}

if(isset($_POST['addPaymentPlan']) && !$cancelProcess) {
  $eventid = $_POST['eventid'];
  $timeframeid = $_POST['timeframeid'];
  $startdate = $_POST['startdate'];
  $enddate = $_POST['enddate'];
  $paymenttypeid = $_POST['paymenttypeid'];
  $default = $_POST['setdefault'];
  $note = "";

  $userid = $_SESSION['UserID'];

  if($timeframeid == "") {
    $task = createPaymentPlan($eventid, $startdate, $enddate, $paymenttypeid, $note, $userid, $default);
  } else {
    $task = updatePaymentPlan($eventid, $startdate, $enddate, $paymenttypeid, $note, $userid, $timeframeid, $default);
  }

  if(!$task) {
    $data = array("success" => false, "message" => "Error!");
  } else {
    $data = array("success" => true, "message" => "Success!", "id" => $task['id'], "sql" => $task['sql']);
  }

  echo json_encode($data);
} //saveevent

if(isset($_POST['deletePaymentPlan']) && !$cancelProcess) {
  $userid = $_SESSION['UserID'];
  $timeframeid = $_POST['timeframeid'];

  $task = deletePaymentPlan($userid, $timeframeid);

  if(!$task) {
    $data = array("success" => false, "message" => "Error!");
  } else {
    $data =  array("success" => true, "message" => "Success!");
  }

  echo json_encode($data);
} //deletePaymentPlan

if(isset($_POST['openPaymentPlan']) && !$cancelProcess) {
  $eventid = $_POST['eventid'];
  $userid = $_SESSION['UserID'];

  $data = getPaymentPlan($eventid, $userid);

  $data =  array(
    "success" => true,
    "message" => "Success!",
    "data" => $data
  );

  echo json_encode($data);
}

if(isset($_POST['saveConvert']) && $cancelProcess == false) {
  $info = $_POST['info'];
  $oldID = $_POST['oldID'];
  $eventID = $_POST['eventID'];
  $rowMod = $_POST['row'];

  $result = db::get_connection("UPDATE tblEvents SET `".$rowMod."` = '".$info."' WHERE UserID='".$_SESSION['UserID']."' AND EventID='".$eventID."'");
  if ($result) {
    $result2 = db::get_connection("SELECT UserID,ExpirationDate,EventName,BeginDate FROM tblEvents WHERE UserID='".$_SESSION['UserID']."' AND EventID='".$eventID."'");
    if ($result2) {
      while($row = mysqli_fetch_array($result2)) {
        $eventName = $row['EventName'];
        $expirationDate = $row['ExpirationDate'];
        $beginDate = $row['BeginDate'];
      }
    }

    if($rowMod == "EventName") {
      echo "<div id='nameInner".$eventID."' onclick=\"convert('name".$eventID."','nameInner".$eventID."','".$eventID."','EventName')\"
      style='width: 500px;cursor: text;'>".$eventName."</div>";
    }
    if($rowMod == "BeginDate") {
      echo "<div id='begins".$eventID."'><div id='beginsInner".$eventID."' onclick=\"convert('begins".$eventID."','beginsInner".$eventID."','".$eventID."','BeginDate')\"
      style='width: 500px;cursor: text;'>".$beginDate."</div></div><br /><br />";
    }
    if($rowMod == "ExpirationDate") {
      echo "<div id='exp".$eventID."'><div id='expInner".$eventID."' onclick=\"convert('exp".$eventID."','expInner".$eventID."','".$eventID."','ExpirationDate')\"
      style='width: 500px;cursor: text;'>".$expirationDate."</div></div><br />";
    }
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br />
    Server said: "; echo mysqli_error($result);
//    mysqli_close($db_con);
  }
} //saveConvert

if(isset($_POST['changeEvent']) && $cancelProcess == false) {
  if(checkUserLastEvent() == false)
  {
    //we need to create one
    if(insertNewLastEvent($_POST['changeEvent']) == true)
    {
      //set session variable so we don't have to continuously look for it
      $_SESSION['EventSet'] = $_POST['changeEvent'];
      unset($_SESSION['Checked']);
      //echo "insert went well\r\n";
    }
  } else {
    if(updateNewLastEvent($_POST['changeEvent']) == true)
    {
      //set session variable so we don't have to continuously look for it
      $_SESSION['EventSet'] = $_POST['changeEvent'];
      unset($_SESSION['Checked']);
      //echo "update went well\r\n";
    }
  }
} //changeEvent

if(isset($_POST['setup']) && !$cancelProcess)  {
  $active = "";
  $inactive = "";
  $trigger = false;
  $userid = $_SESSION['UserID'];
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT EventID, UserID, ExpirationDate, EventName, BeginDate FROM tblEvents WHERE UserID='".$userid."' ORDER BY BeginDate DESC";

  $rs = $mysqli->query($sql);


  while($row = $rs->fetch_assoc()) {
    $eventName = $row['EventName'];
    $expirationDate = $row['ExpirationDate'];
    $beginDate = $row['BeginDate'];
    $eventID = $row['EventID'];

    $currpage = '
      <div class="eventslist">
        <div style="float: left; margin-right: 8px;"></div>
        <div style="float: left;"><span style="font-weight: bold;">'.$eventName.'</div>
        <div style="margin-left: 8px; float: left;"><a href="javascript: getEventInfo(\''.$eventID.'\')">Modify</a></div>
        <div style="margin-left: 8px; float: left;"><a href="javascript: openPaymentPlan(\''.$eventID.'\')">Payment Plan</a></div>
        <div style="margin-left: 8px; float: left;"><a href="javascript: expandHandle(\''.$eventID.'\')">Expand</a></div>
        <div style="float: right;"><a href="javascript: deleteEvent(\''.$eventID.'\')">Delete</a></div>
        <div class="clrBoth"></div>
        <table id="'.$eventID.'" style="padding-left: 28px; display: none; margin-top: 8px;" cellpadding="0" cellspacing="0" class="padded">
          <tr>
            <th></th>
            <th style="width: 300px;"></th>
          </tr>
          <tr>
            <td><span style="font-weight: bold;">URL: </span></a></td>
            <td id="URL"><input type="text" style="width: 500px;" value="http://creativewebworks.net/bascomb/registration.php?e='.$eventID.'" /></td>
          </tr>
        </table>
      </div>
      ';
    ($row['ExpirationDate'] <= date("Y-m-d",time()) ? $inactive .= $currpage : $active .= $currpage);
  }

  $page = "<div class='active'>Active: <br /></div>";
  $page .= $active;
  $page .= "<div class='inactive'>Inactive: <br /></div>";
  $page .= $inactive;
  $page .= "<input id='currOpen' type='hidden' />";

  echo $page;

  $rs->free();
  $mysqli->close();

} //setup
//mysqli_close($db_con);