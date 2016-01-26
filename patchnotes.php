<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 8/26/2015
 * Time: 2:22 AM
 */


session_start();

$cancelProcess = false;

if (!isset($_SESSION['authUser'])) {
  $cancelProcess = true;
  header("Location: login.php");
}

require_once("lib/_classes.php");
require_once("lib/_functions.php");
require_once('lib/db.php');


?>

<!DOCTYPE html>
<html lang="en">


<head>

  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

  <title>BUMC Admin</title>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="js/lib/jquery-numeric.js"></script>
  <script src="js/regadmin.js" type="text/javascript"></script>

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="shortcut icon" href="images/site.ico">

</head>

<body>
  <div id="header" class="header"></div>
  <div class="page">
  <? require "header.php"; ?>
    <div id="containerHolder" class="contHolderSingle">
      <div class="changelog-header">Changelog</div>
      <br />

      <div>
        <span class="changelog-day">January 25, 2016</span>
        <ul class="changelog-entry">
          <li>Create table tbl_payment_type.</li>
          <li>Populated table.</li>
          <li>Fixed the payment plan part of event page to use tbl_payment_type. Removed unused items.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">January 24, 2016</span>
        <ul class="changelog-entry">
          <li>Create table tbl_payment_timeframes.</li>
          <li>Payment Plans on event page - Create dialog to interact with tbl_payment_timeframes.</li>
          <li>Paypal Form is now used based on tbl_payment_timeframes.</li>
          <li>Debug, test, make user friendly.</li>
          <li>Set EventID to 16.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 30, 2015</span>
        <ul class="changelog-entry">
          <li>Header now has an "invalid" entry if it does not exist.</li>
          <li>Remove old controls folder.</li>
          <li>Redid structure layout for project (more of an MVC structure) - could still use some work but looking much better.</li>
          <li>Fixed errors spit out in "error_log" - mostly undeclared variables</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 29, 2015</span>
        <ul class="changelog-entry">
          <li>Set EventID 19 to 15.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 26, 2015</span>
        <ul class="changelog-entry">
          <li>Set up paypal information for $35 and $45 on registrationsave.php.</li>
          <li>Email is now sent to the same as settings.php.</li>
          <li>Cleaned up registration page/registration save page.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 25, 2015</span>
        <ul class="changelog-entry">
          <li>Immensely cleaned up code.</li>
          <li>Removed a lot of pages unused.</li>
          <li>Removed most KoolControls from pages. Some still linger.</li>
          <li>Rewrote all ajax calls to jQuery.</li>
          <li>Rebuild grid, still in process.</li>
          <li>Changed site icon.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 24, 2015</span>
        <ul class="changelog-entry">
          <li>Upgrade PHP versions.</li>
          <li>Rewrote core KoolControls ajax calls to jQuery ajax calls.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 7, 2015</span>
        <ul class="changelog-entry">
          <li>Update Settings page - has email information in there.</li>
          <li>Emailing is now based on Settings page information.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">August 6, 2015</span>
        <ul class="changelog-entry">
          <li>Events - Added "Active" and "Inactive" categories.</li>
          <li>Events - Now able to add events.</li>
          <li>Events - Events have much more inputable information.</li>
        </ul>
      </div>

      <div>
        <span class="changelog-day">June 4, 2015</span>
        <ul class="changelog-entry">
          <li>Minor tweaks.</li>
        </ul>
      </div>
    </div>
</body>
</html>