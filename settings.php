<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();

require_once("lib/_classes.php");
require_once("lib/_functions.php");
require('lib/db.php');

if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet">

  <script src="js/regadmin.js" type="text/javascript"></script>
  <script src="js/settings.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
  <link rel="shortcut icon" href="images/site.ico">
</head>

<body>
<?php

?>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";
?>
  <div id="containerHolder" class="contHolderSingle">
    <div class="shadowbox settingslabel">Email</div>
    <div class="clrBoth"></div>
    <form id="settingsform">
      <table class="tblSettings">
        <thead>
        <tr>
          <th></th>
          <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td>Always BCC Myself</td>
          <td>
            <select name="bcc" id="bcc">
              <option value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>My E-mail Address</td>
          <td><input name="email" id="email" type="text" value="ScrappingAtBascomb@gmail.com" style="width: 300px;" /></td>
        </tr>
        <tr>
          <td>Friendly E-mail Name</td>
          <td><input name="friendlyemail" id="friendlyemail" type="text" value="Scrapping At Bascomb" style="width: 300px;" /></td>
        </tr>
        </tbody>
      </table>
    </form>
    <div class="clrBoth"></div>
    <div style="width: 445px; text-align: right; margin: 5px 0px;">
      <button id="save" onclick="saveSettings();">Save</button>
      <button id="revert" onclick="fetchSettings();">Revert</button>
    </div>
  </div>
</div>
<br />
</body>
</html>
<?php
  mysql_close($db_con);
}
?>