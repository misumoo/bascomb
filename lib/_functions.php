<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 5/31/2015
 * Time: 6:16 PM
 */



function updateNewLastEvent($EventID) {
  $result = mysql_query("UPDATE tblLastEvent SET `UserID` = '".$_SESSION['UserID']."', `EventID` = '".$EventID."' WHERE UserID='".$_SESSION['UserID']."'");
  if ($result) {
    return true;
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br /> Server said: "; echo mysql_error($db_con);
    mysql_close($db_con);
  }
}

function insertNewLastEvent($EventID) {
  echo $EventID;
  echo $_SESSION['UserID'];
  $result = mysql_query("INSERT INTO  `tblLastEvent` (`LastEventID`,`UserID`,`EventID`) VALUES (NULL,'".$_SESSION['UserID']."','".$EventID."')");
  if ($result)
  {
    return true;
  } else {
    return false;
    echo "<p>Couldn't connect to the database. <br />  Server said: "; echo mysql_error($db_con);
    mysql_close($db_con);
  }
}

function checkUserLastEvent() {
  $result = mysql_query("SELECT Count(UserID) FROM tblLastEvent WHERE UserID='".$_SESSION['UserID']."'");
  if ($result)
  {
    while($row = mysql_fetch_array($result))
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
    echo "<p>Couldn't connect to the database. <br />  Server said: "; echo mysql_error($db_con);
    mysql_close($db_con);
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

function createPaymentPlan($eventid, $startdate, $enddate, $amount, $form, $note, $userid, $default) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $amount = convertForInsert($amount);
  $form = convertForInsert($mysqli->real_escape_string($form));
  if($default == 1) {
    $note = "Default";
    $startdate = "";
    $enddate = "";
  }
  $startdate = convertForInsert($startdate);
  $enddate = convertForInsert($enddate);
  $note = convertForInsert($note);

  $sql = "
    INSERT INTO  `tbl_payment_timeframes` (EventID, UserID, StartDate, EndDate, Amount, Form, Note, TimeFrameID) VALUES
                                          (".$eventid.", ".$userid.", ".$startdate.", ".$enddate.", ".$amount.", ".$form.", ".$note.", NULL);";

  try {
    $mysqli->query($sql);
    $insertid = $mysqli->insert_id;
  } catch (Exception $e) {
    return false; //something went wrong
  }

  return $insertid;
} //createPaymentPlan


function updatePaymentPlan($eventid, $startdate, $enddate, $amount, $form, $note, $userid, $timeframeid, $default) {
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $amount = convertForInsert($amount);
  $form = convertForInsert($form);
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
      Amount = $amount,
      Form = $form,
      Form = $note,
      TimeFrameID = $timeframeid
    WHERE EventID = $eventid
      AND UserID = $userid
      AND TimeFrameID = $timeframeid
    ";

  try {
    $mysqli->query($sql);
    return true;
  } catch (Exception $e) {
    return false; //something went wrong
  }
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
  $data = "";
  $dataArray = array();

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $eventid = convertForInsert($eventid);
  $sql = "SELECT * FROM tbl_payment_timeframes WHERE EventID = $eventid AND UserID = $userid";
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
  $result = mysql_query("SELECT EventID,ExpirationDate,BeginDate FROM tblEvents WHERE EventID='".$id."'");
  if($result) {
    while($row = mysql_fetch_array($result))  {
      if(mysql_num_rows($result) != 1)  {
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
  $sql = "SELECT Count(regid) AS MyCount FROM registration WHERE EventID='".$id."'";

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $result = mysql_query($sql);
  if($result) {
    while($row = mysql_fetch_array($result)) {
      if(mysql_num_rows($result) != 1) {
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
  $result = mysql_query("SELECT UserID FROM tblEvents WHERE EventID='".$id."'");
  if ($result)
  {
    if(mysql_num_rows($result) != 1)
    {
      $cancelProcess = true;
      return false;
    } else {
      while($row = mysql_fetch_array($result))
      {
        return $row['UserID'];
      }
    }
  } else {
    echo "<p>Database error. <br />";
  }
} //getUserID


function getPaypalForm($id) {
  $today = date('Y-m-d');

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
  $sql = "
    SELECT
      *
    FROM `tbl_payment_timeframes`
    WHERE
    StartDate <= '".$today."'
    AND EndDate >= '".$today."'
    AND EventID = '".$id."'
    OR StartDate IS NULL
    AND EndDate IS NULL
    ORDER BY StartDate DESC
    LIMIT 1
  ";
  $rs = $mysqli->query($sql);

  while($row = $rs->fetch_assoc()) {
    $form = $row['Form'];
  }

  //for now, we want >= 20 days before event end date to be $40, else $35
//  $fortyfive = '
//    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
//    <input type="hidden" name="cmd" value="_s-xclick">
//    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
//    ">
//    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
//    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
//    </form>
//  ';
//
//  $forty = '
//    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
//    <input type="hidden" name="cmd" value="_s-xclick">
//    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
//    ">
//    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
//    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
//    </form>
//  ';
//
//  $thirtyfive = '
//    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
//    <input type="hidden" name="cmd" value="_s-xclick">
//    <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
//    ">
//    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
//    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
//    </form>
//  ';

//  $result = mysql_query("SELECT ExpirationDate FROM tblEvents WHERE EventID='".$id."'");
//  while($row = mysql_fetch_array($result)) {
//    $expdate = $row['ExpirationDate'];
//  }
//
//  $today = date('Y-m-d');
//  $dateminus = date('Y-m-d', strtotime('-10 day', strtotime($expdate))); //-19 is -20 days, changed to -10 for one event

//  if(strtotime($today) >= strtotime($dateminus)) {
    //$form = $thirtyfive;
//  } else {
//    $form = $thirtyfive;
//  }

  return $form;
}