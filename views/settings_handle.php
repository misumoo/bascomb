<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 8/7/2015
 * Time: 7:03 PM
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

if(isset($_POST['save']) && !$cancelProcess)  {
  $userid = $_SESSION['UserID'];
  $bcc = $_POST['bcc'];
  $email = $_POST['email'];
  $friendlyemail = $_POST['friendlyemail'];

  $sql = "SELECT SettingID FROM tbl_Settings WHERE UserID = '".$userid."'";
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
  $rs = $mysqli->query($sql);

  while($row = $rs->fetch_assoc()) {
    $settingid = $row['SettingID'];
  }

  if($settingid == "") {
    $task = createSettings($userid, $bcc, $email, $friendlyemail);
  } else {
    $task = updateSettings($userid, $bcc, $email, $friendlyemail);
  }

  if(!$task) {
    $data = array("success" => false, "message" => "Error!");
  } else {
    $data =  array("success" => true, "message" => "Success!", "id" => $settingid, "email" => $email, "friendlyemail" => $friendlyemail, "bcc" => $bcc);
  }

  echo json_encode($data);

  $rs->free();
  $mysqli->close();
} //getSettings

mysql_close($db_con);