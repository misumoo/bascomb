<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();


if (!isset($_SESSION['authUser'])) {
  $cancelProcess = true;
	header("Location: login.php");
} else {


$_SESSION['UserID'];
$i;
$months;
$totalMonths;
$beginningYear;
$endingYear;

require 'lib/db.php';
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xmlns="http://www.w3.org/1999/html" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="js/lib/jquery-numeric.js"></script>
  <script src="js/regadmin.js" type="text/javascript"></script>
  <script src="js/events.js"></script>

  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <link rel="shortcut icon" href="images/site.ico">

</head>

<body>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";
?>
  <div id="containerHolder" class="contHolderSingle">
  
    <div id="eventHeader" style="width: 100%;">
      <img src="images/notepad.png" id="addNew" title="Add a new event" style="cursor: pointer;" />
    </div>

    <div id="popup_SaveEvent" title="Event">
      <p class="validateTips">All form fields are required.</p>

      <form id="frm_SaveEvent">
        <div style="display: none;"> <!-- DATA -->
          <div><label for="save_EventID">EventID</label> </div>
          <div style="width: 260px;">
            <input id="save_EventID" type="hidden" class="fancyinput" value="" />
          </div>
        </div>
        <div><label for="save_EventName">Name</label> </div>
        <div style="width: 260px;">
          <input id="save_EventName" type="text" placeholder="Event Name" class="fancyinput" />
        </div>
        <div style="float: left; width: 100px;">
          <div><label for="save_DisplayCapacity">Display Cap?</label></div>
          <div>
            <select id="save_DisplayCapacity" class="fancyselect">
              <option value="1" selected>Yes</option>
              <option value="0">No</option>
            </select>
          </div>
        </div>
        <div style="float: left; width: 100px;">
          <div><label for="save_Capacity">Capacity</label></div>
          <div><input id="save_Capacity" type="text" placeholder="0" class="fancyinput numbersOnly" /></div>
        </div>
        <div style="clear: both;"></div>
        <div style="float: left; width: 100px;">
          <div><label for="save_StartDate">Start Date</label></div>
          <div><input id="save_StartDate" type="text" class="fancyinput" /></div>
        </div>
        <div style="float: left; width: 100px;">
          <div><label for="save_EndDate">End Date</label></div>
          <div><input id="save_EndDate" type="text" class="fancyinput" /></div>
        </div>
      </form>
    </div>

    <div id="popup_PaymentPlans" title="Payment Plans">
      <form id="frm_AddPaymentPlan">
        <div style="float: left; width: 180px;">
          <div><label for="payment_EventID">EventID</label></div>
          <div><input id="payment_EventID" type="text" class="fancyinput" disabled /></div>
        </div>
        <div style="float: left; width: 180px;">
          <div><label for="payment_TimeFrameID">TimeFrameID</label></div>
          <div><input id="payment_TimeFrameID" type="text" class="fancyinput" disabled /></div>
        </div>
        <div style="float: left; width: 180px;">
          <div>
            <label for="payment_Default">Default</label>
            <img class="helper" src="images/help.png" title="If there is no default already, this will be forced to yes and visa versa. Default does not have a start or end date.">
          </div>
          <div>
            <select id="payment_Default" class="fancyselect" onchange="paymentFindDefault()">
              <option selected="" value="1">Yes</option>
              <option value="0">No</option>
            </select>
          </div>
        </div>
        <div style="clear: both;"></div>
        <div style="float: left; width: 180px;">
          <div><label for="payment_Amount">Amount</label></div>
          <div><input id="payment_Amount" type="text" class="fancyinput" autocomplete="off" /></div>
        </div>
        <div style="float: left; width: 180px;">
          <div><label for="payment_StartDate">Start Date</label></div>
          <div><input id="payment_StartDate" type="text" class="fancyinput" /></div>
        </div>
        <div style="float: left; width: 180px;">
          <div><label for="payment_EndDate">End Date</label></div>
          <div><input id="payment_EndDate" type="text" class="fancyinput" /></div>
        </div>
        <div style="clear: both;"></div>
        <div style="float: left; width: 180px;">
          <div>
            <label for="payment_Form">Paypal Form</label>
            <img class="helper" src="images/help.png" title="Paypal will give you a code that is a bit extensive. Paste it in here.">
          </div>
          <div><textarea id="payment_Form" class="fancyinput" style="width: 516px; height: 100px;"></textarea></div>
        </div>
        <div style="clear: both;"></div>
      </form>
      <div style="float: right; width: 150px; margin: 4px 20px 8px 0">
        <button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" onclick="addPaymentPlan()">
          <span class="ui-button-text">Add Payment Plan</span>
        </button>
      </div>
      <table id='tbl_PaymentPlans'>
        <thead>
          <tr>
            <!-- 0 --><th>Controls</th>
            <!-- 1 --><th>TimeFrameID</th>
            <!-- 2 --><th>EventID</th>
            <!-- 3 --><th>Note</th>
            <!-- 4 --><th>Start Date</th>
            <!-- 5 --><th>End Date</th>
            <!-- 6 --><th>Amount</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    
    <input id="currentOpen" type="hidden" value="" />
    
    <br />
    
    <div id="setup" style="width: 100%;"></div>
    
    <br />
    <div style="clear: both;"></div>
  </div>
</div>
<br />
</body>
</html>
<?php
  mysql_close($db_con);
}
?>