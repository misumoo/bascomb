<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 8/30/2015
 * Time: 5:18 PM
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

if(isset($_POST['getSettings']) && !$cancelProcess)  {
  $userid = $_SESSION['UserID'];
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

  $sql = "SELECT * FROM tbl_Settings WHERE UserID='".$userid."'";

  $rs = $mysqli->query($sql);

  while($row = $rs->fetch_assoc()) {
    $settingid = $row['SettingID'];
    $email = $row['Email'];
    $friendlyemail = $row['FriendlyEmail'];
    $bcc = $row['bcc'];
  }

  $data =  array("success" => true, "message" => "Success!", "id" => $settingid, "email" => $email, "friendlyemail" => $friendlyemail, "bcc" => $bcc);

  echo json_encode($data);

  $rs->free();
  $mysqli->close();
} //getSettings

if(isset($_POST['newEventID']) && !$cancelProcess) {
  $userid = convertForInsert($_SESSION['UserID']);
  $eventid = convertForInsert($_POST['newEventID']);

  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
  $sql = "UPDATE tblLastEvent SET EventID = ".$eventid." WHERE UserID = ".$userid;

  $mysqli->query($sql);
  $mysqli->close();

  $data =  array("success" => true, "message" => "Success!");

  echo json_encode($data);
}


?>