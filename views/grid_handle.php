<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 8/25/2015
 * Time: 6:40 PM
 */

session_start();

if (!isset($_SESSION['authUser'])) {
  if (tryLoginFromCookie() != true) {
    $cancelProcess = true;
    header("Location: login.php");
  }
}

require '../lib/db.php';
require '../lib/_functions.php';

if(($_POST['callprocedure']) == "true" && !isset($cancelProcess)) {
  $gridHeader;
  $grid;
  $sql;
  $result;
  $mysqli;
  $style;

  $grid = "";
  $gridHeader = "";

  $gridHeader = "
  <span class='important'>Under construction. This had to be redone from upgrading php versions.</span>
  <table id='tblgrid' class='fixed tablesorter tablesorter-ice' style='margin: 0;'>
    <thead>
      <tr>
        <th>EmailAddress</th>
      </tr>
    </thead>
  ";

  $sql = 'SELECT EmailAddress FROM registration';
  $mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
  $rs = $mysqli->query($sql);
  while($row = $rs->fetch_assoc()) {
    $email = convertBlankToNBSP($row['EmailAddress']);

    $grid .= "<tr>";
    $grid .= "  <td>".$email."</td>";
    $grid .= "</tr>";
  }

  $grid = "<tbody>".$grid."</tbody>";

  $grid .= "</table>";

  echo $gridHeader.$grid;
}
?>

