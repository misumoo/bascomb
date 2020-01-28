<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 5/31/2015
 * Time: 6:16 PM
 */


function createLog($filename, $task, $msg) {
  $file = 'logs/'. $filename .'.log';
  $date = new DateTime();

  try {
    //$mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
    //$task = "'".$mysqli->real_escape_string($task)."'";
  } catch (Exception $e) {
    //fail in silence
  }

  $current = file_get_contents($file);
  $logbuilder['Timestamp'] = $date->format('Y-m-d H:i:s');
  $logbuilder['Task'] = $task;
  $logbuilder['Message'] = $msg;
  $current .= "\r\n".json_encode($logbuilder);

  file_put_contents($file, $current);
  return true;
}

function updateNewLastEvent($EventID) {
  $result = db::get_connection("UPDATE tblLastEvent SET `UserID` = '".$_SESSION['UserID']."', `EventID` = '".$EventID."' WHERE UserID='".$_SESSION['UserID']."'");
  if ($result) {
    return true;
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br /> Server said: "; echo mysqli_error($result);
//    mysqli_close($db_con);
  }
}

function insertNewLastEvent($EventID) {
  echo $EventID;
  echo $_SESSION['UserID'];
  $result = db::get_connection("INSERT INTO  `tblLastEvent` (`LastEventID`,`UserID`,`EventID`) VALUES (NULL,'".$_SESSION['UserID']."','".$EventID."')");
  if ($result)
  {
    return true;
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br />  Server said: "; echo mysqli_error($result);
//    mysqli_close($db_con);
  }
}

function checkUserLastEvent() {
  $result = db::get_connection("SELECT Count(UserID) FROM tblLastEvent WHERE UserID='".$_SESSION['UserID']."'");
  if ($result)
  {
    while($row = mysqli_fetch_array($result))
    {
      if($row['Count(UserID)'] > 0)
      {
        return true;
      } else {
        return false;
      }
    }
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br />  Server said: "; echo mysqli_error($result);
//    mysqli_close($db_con);
  }
}

function convertForInsert($str) {
  if ($str != "") {
    $str = "\"".$str."\"";
  }
  else {
    $str = "NULL";
  }
  return $str;
} //convertForInsert

function convertBlankToNBSP($str) {
  if ($str == "") {
    $str = "&nbsp;";
  }
  return $str;
} //convertBlankToNBSP

function convertNullToBlank($str) {
  if ($str == "null" || $str == null) {
    $str = "";
  }
  return $str;
} //convertNullToBlank

function createEvent($eventname, $startdate, $enddate, $userid, $capacity, $displaycapacity) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventname = convertForInsert($eventname);
  $startdate = convertForInsert($startdate);
  $enddate = convertForInsert($enddate);
  $capacity = convertForInsert($capacity);
  $displaycapacity = convertForInsert($displaycapacity);

  $sql = "
    INSERT INTO `tblEvents` (EventID, UserID, BeginDate, ExpirationDate, EventName, Capacity, DisplayCapacity) VALUES
                            (NULL, ".$userid.", ".$startdate.", ".$enddate.", $eventname, $capacity, $displaycapacity);";

  try {
    $mysqli->query($sql);
    $insertid = $mysqli->insert_id;
  } catch (Exception $e) {
    return false; //something went wrong
  }

  return $insertid;
} //createEvent

function createPaymentPlan($eventid, $startdate, $enddate, $paymenttypeid, $note, $userid, $default) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $paymenttypeid = convertForInsert($paymenttypeid);
  if($default == 1) {
    $note = "Default";
    $startdate = "";
    $enddate = "";
  }
  $startdate = convertForInsert($startdate);
  $enddate = convertForInsert($enddate);
  $note = convertForInsert($note);

  $sql = "
    INSERT INTO  `tbl_payment_timeframes` (EventID, UserID, StartDate, EndDate, PaymentTypeID, Note, TimeFrameID) VALUES
                                          (".$eventid.", ".$userid.", ".$startdate.", ".$enddate.", ".$paymenttypeid.", ".$note.", NULL);";

  try {
    $mysqli->query($sql);
    $insertid = $mysqli->insert_id;
  } catch (Exception $e) {
    return false; //something went wrong
  }

  return array(
  	'id' => $insertid,
  	'sql' => $sql,
  );
} //createPaymentPlan


function updatePaymentPlan($eventid, $startdate, $enddate, $paymenttypeid, $note, $userid, $timeframeid, $default) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $paymenttypeid = convertForInsert($paymenttypeid);
  $timeframeid = convertForInsert($timeframeid);
  $default = convertForInsert($default);
  if($default == 1) {
    $note = "Default";
    $startdate = "";
    $enddate = "";
  }
  $startdate = convertForInsert($startdate);
  $enddate = convertForInsert($enddate);
  $note = convertForInsert($note);

  $sql = "
    UPDATE tbl_payment_timeframes SET
      StartDate = $startdate,
      EndDate = $enddate,
      PaymentTypeID = $paymenttypeid,
      Note = $note,
      TimeFrameID = $timeframeid
    WHERE EventID = $eventid
      AND UserID = $userid
      AND TimeFrameID = $timeframeid
    ";

  try {
    $mysqli->query($sql);
	  $success = true;
  } catch (Exception $e) {
	  $success = false;
  }
	return array(
		'success' => $success,
		'id' => $timeframeid,
		'sql' => $sql,
	);
} //updatePaymentPlan

function deletePaymentPlan($userid, $timeframeid) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $userid = convertForInsert($userid);
  $timeframeid = convertForInsert($timeframeid);

  $sql = "DELETE FROM tbl_payment_timeframes WHERE TimeFrameID = ".$timeframeid." AND UserID = ".$userid;

  try {
    $mysqli->query($sql);
    return true;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //deleteEventTest

function deleteEvent($userid, $eventid) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $userid = convertForInsert($userid);
  $eventid = convertForInsert($eventid);

  $sql = "DELETE FROM tblEvents WHERE EventID = ".$eventid." AND UserID = ".$userid;

  try {
    $mysqli->query($sql);
    return true;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //deleteEventTest

function getUserEmail($userid) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT Email FROM tbl_Settings WHERE UserID = ".$userid;
  $rs = $mysqli->query($sql);

  try {
    while($row = $rs->fetch_assoc()) {
      $data = $row['Email'];
    }
    return $data;
  } catch (Exception $e) {
    return false; //something went wrong
  }
}

function getSettings($userid) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT * FROM tbl_Settings WHERE UserID = ".$userid;
  $rs = $mysqli->query($sql);

  try {
    while($row = $rs->fetch_assoc()) {
      $data = new Settings();
      $data->bcc = $row['bcc'];
      $data->email = $row['Email'];
      $data->friendlyemail = $row['FriendlyEmail'];
    }
    return $data;
  } catch (Exception $e) {
    return false; //something went wrong
  }
}

function createSettings($userid, $bcc, $email, $friendlyemail) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $userid = convertForInsert($userid);
  $bcc = convertForInsert($bcc);
  $email = convertForInsert($email);
  $friendlyemail = convertForInsert($friendlyemail);

  $sql = "
    INSERT INTO `tbl_Settings` (SettingID, Email, FriendlyEmail, bcc, UserID) VALUES
                            (NULL, ".$email.", ".$friendlyemail.", ".$bcc.", ".$userid.");";

  try {
    $mysqli->query($sql);
    $insertid = $mysqli->insert_id;
  } catch (Exception $e) {
    return false; //something went wrong
  }

  return $insertid;
} //createEvent

function updateSettings($userid, $bcc, $email, $friendlyemail) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $userid = convertForInsert($userid);
  $bcc = convertForInsert($bcc);
  $email = convertForInsert($email);
  $friendlyemail = convertForInsert($friendlyemail);

  $sql = "
    UPDATE tbl_Settings SET
      Email = $email,
      FriendlyEmail = $friendlyemail,
      bcc = $bcc
    WHERE UserID = $userid
  ";

  try {
    $mysqli->query($sql);
    return true;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //deleteEventTest

function updateEvent($eventname, $startdate, $enddate, $userid, $capacity, $displaycapacity, $eventid) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventname = convertForInsert($eventname);
  $startdate = convertForInsert($startdate);
  $enddate = convertForInsert($enddate);
  $capacity = convertForInsert($capacity);
  $displaycapacity = convertForInsert($displaycapacity);
  $eventid = convertForInsert($eventid);

  $sql = "
    UPDATE tblEvents SET
      BeginDate = $startdate,
      ExpirationDate = $enddate,
      EventName = $eventname,
      Capacity = $capacity,
      DisplayCapacity = $displaycapacity
    WHERE EventID = $eventid
      AND UserID = $userid
    ";

  try {
    $mysqli->query($sql);
    return true;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //updateEvent

function getEventInfo($eventid, $userid) {
  $data = "";
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $sql = "SELECT * FROM tblEvents WHERE EventID = $eventid AND UserID = $userid";
  $rs = $mysqli->query($sql);

  try {
    while($row = $rs->fetch_assoc()) {
      $data = new EventInfo();
      $data->capacity = $row['Capacity'];
      $data->displaycapacity = $row['DisplayCapacity'];
      $data->enddate = $row['ExpirationDate'];
      $data->eventid = $row['EventID'];
      $data->eventname = $row['EventName'];
      $data->startdate = $row['BeginDate'];
    }
    return $data;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //getEventInfo

function getPaymentPlan($eventid, $userid) {
  $data = array();
  $dataArray = array();

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $sql = "SELECT
            tbl_payment_timeframes.*,
            tbl_payment_type.*
          FROM `tbl_payment_timeframes`
          LEFT JOIN `tbl_payment_type` ON `tbl_payment_timeframes`.PaymentTypeID = `tbl_payment_type`.PaymentTypeID
          WHERE `tbl_payment_timeframes`.EventID = $eventid AND `tbl_payment_timeframes`.UserID = $userid";
  $rs = $mysqli->query($sql);

  try {
    while($row = $rs->fetch_assoc()) {
      $data['TimeFrameID'] = convertNullToBlank($row['TimeFrameID']);
      $data['EventID'] = convertNullToBlank($row['EventID']);
      $data['Note'] = convertNullToBlank($row['Note']);
      $data['StartDate'] = convertNullToBlank($row['StartDate']);
      $data['EndDate'] = convertNullToBlank($row['EndDate']);
      $data['Amount'] = convertNullToBlank($row['Amount']);
      $data['Form'] = convertNullToBlank($row['Form']);
      $dataArray[] = $data;
    }
    return $dataArray;
  } catch (Exception $e) {
    return false; //something went wrong
  }
} //getPaymentPlan

/**
 * @param $eventid
 * @param $userid
 * @param $name
 * @param $emailaddress
 * @return array
 */
function getCustomerCount($eventid, $userid, $name, $emailaddress) {
  $data = "";
  $dataArray = array();

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $sql = "SELECT
            COUNT(regid) AS CountOfRegid
          FROM `registration`
          WHERE EventID = $eventid AND UserID = $userid AND EmailAddress = $emailaddress AND Name = $name";
  $rs = $mysqli->query($sql);

  try {
    while($row = $rs->fetch_assoc()) {
      if($row['CountOfRegid'] > 0) {
        //Already in database.
        $data = $row['CountOfRegid'];
        //$data['Message'] = "Already in database. Do not add me.";
      } else {
        //we have a match in the database already
        $data = $row['CountOfRegid'];
        //$data['Message'] = "Not in database. Add me!";
      }
    }
    //$dataArray[] = $data;
    //return $dataArray;
    return $data;
  } catch (Exception $e) {
    $data['Count'] = 1;
    $data['Message'] = "Caught an error.. $e";
    $dataArray[] = $data;
    return $dataArray;
  }
} //getCustomerCount

function getCapacity($id) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT Capacity FROM tblEvents WHERE EventID = '".$id."'";

  $rs = $mysqli->query($sql);
  while($row = $rs->fetch_assoc()) {
    if($row['Capacity'] != 0) {
      return $row['Capacity'];
    } else {
      return "";
    }
  }
} //getCapacity

function getCapacityDisplay($id, $capacity) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT DisplayCapacity FROM tblEvents WHERE EventID = '".$id."'";

  $rs = $mysqli->query($sql);
  while($row = $rs->fetch_assoc()) {
    if($row['DisplayCapacity'] != 1 || $capacity == 0 || $capacity == "") {
      return false;
    } else {
      return true;
    }
  }
} //getCapacity

function checkEvent($id) {
  $result = db::get_connection("SELECT EventID,ExpirationDate,BeginDate FROM tblEvents WHERE EventID='".$id."'");
  if($result) {
    while($row = mysqli_fetch_array($result))  {
      if(mysqli_num_rows($result) != 1)  {
        echo "Could not find registration ID.";
        return false;
      }
      if($row['ExpirationDate'] <= date("Y-m-d",time())) {
        echo "This event expired ".$row['ExpirationDate'].".";
        return false;
      } else {
        return true;
      }
    }
  } else {
    return false;
  }
} //checkEvent

function totalRegistered($id) {
  $sql = "SELECT Count(regid) AS MyCount FROM registration WHERE EventID='".$id."' AND UserID != '99'";

  $result = db::get_connection($sql);
  if($result) {
    while($row = mysqli_fetch_array($result)) {
      if(mysqli_num_rows($result) != 1) {
        echo "Could not find registration ID.";
        return false;
      }
      return $row['MyCount'];
    }
  } else {
    return false;
  }
} //totalRegistered


function getUserID($id) {
  $result = db::get_connection("SELECT UserID FROM tblEvents WHERE EventID='".$id."'");
  if ($result)
  {
    if(mysqli_num_rows($result) != 1)
    {
      $cancelProcess = true;
      return false;
    } else {
      while($row = mysqli_fetch_array($result))
      {
        return $row['UserID'];
      }
    }
  } else {
    echo "<p>Database error. <br />";
  }
} //getUserID


function getPaypalForm($id) {
	return "<label class='l3'>Send to:<br></label><p class='important' style='text'>TheBascombCrop@gmail.com</p>";
//  $today = date('Y-m-d');
//
//  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
//  $sql = "
//    SELECT
//      tbl_payment_timeframes.*,
//      tbl_payment_type.*
//    FROM `tbl_payment_timeframes`
//    LEFT JOIN `tbl_payment_type` ON `tbl_payment_timeframes`.PaymentTypeID = `tbl_payment_type`.PaymentTypeID
//    WHERE
//    StartDate <= '".$today."'
//    AND EndDate >= '".$today."'
//    AND EventID = '".$id."'
//    OR StartDate IS NULL
//    AND EndDate IS NULL
//    ORDER BY StartDate DESC
//    LIMIT 1
//  ";
//  $rs = $mysqli->query($sql);
//
//  while($row = $rs->fetch_assoc()) {
//    $form = $row['Form'];
//  }
//
//  return $form;
}