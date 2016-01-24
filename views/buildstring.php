<?php

/**
 * @author Robert Whetzel
 * @copyright 2010
 */

session_start();

if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {


//unset the session variables
$i = 2;
$p = 31;
for ($i = 2; $i <= $p; $i++)
{
	unset($_SESSION['ch'.$i]);
}
unset($_SESSION['select']);
unset($_SESSION['update']);
unset($_SESSION['insert']);

//recreate them

// Layout for $_POST checkboxes
//	if($_POST['ch2'] == 'on')
//  {
//  }
//	if($_POST['ch3'] == 'on')
//  {
//  }
//	if($_POST['ch4'] == 'on')
//  {
//  }
//	if($_POST['ch5'] == 'on')
//  {
//  }
//	if($_POST['ch6'] == 'on')
//  {
//  }
//	if($_POST['ch7'] == 'on')
//  {
//  }
//	if($_POST['ch8'] == 'on')
//  {
//  }
//	if($_POST['ch9'] == 'on')
//  {
//  }
//	if($_POST['ch10'] == 'on')
//  {
//  }
//	if($_POST['ch11'] == 'on')
//  {
//  }
//	if($_POST['ch12'] == 'on')
//  {
//  }
//	if($_POST['ch13'] == 'on')
//  {
//  }
//	if($_POST['ch14'] == 'on')
//  {
//  }
//	if($_POST['ch15'] == 'on')
//  {
//  }
//	if($_POST['ch16'] == 'on')
//  {
//  }

	$selectString = "select regid,Name";
	$updateString = "update registration set Name='@Name'";
	$insertString = "insert into registration (Name";
	
	if($_POST['ch2'] == 'on')
  {
  	//have to store in sessions because when a button is hit, it makes an ajax call
  	//$_POST's are not set and will not give the correct info.
  	$_SESSION['ch2'] = "on";
		$selectString .= ",EmailAddress";
		$updateString .= ", EmailAddress='@EmailAddress'";
		$insertString .= ",EmailAddress";
  }
	if($_POST['ch3'] == 'on')
  {
  	$_SESSION['ch3'] = "on";
		$selectString .= ",StreetAddress";
		$updateString .= ", StreetAddress='@StreetAddress'";
		$insertString .= ",StreetAddress";
  }
	if($_POST['ch4'] == 'on')
  {
  	$_SESSION['ch4'] = "on";
		$selectString .= ",CSZ";
		$updateString .= ", CSZ='@CSZ'";
		$insertString .= ",CSZ";
  }
	if($_POST['ch5'] == 'on')
  {
  	$_SESSION['ch5'] = "on";
		$selectString .= ",PayBy";
		$updateString .= ", PayBy='@PayBy'";
		$insertString .= ",PayBy";
  }
	if($_POST['ch6'] == 'on')
  {
  	$_SESSION['ch6'] = "on";
		$selectString .= ",ReturningGuest";
		$updateString .= ", ReturningGuest='@ReturningGuest'";
		$insertString .= ",ReturningGuest";
  }
	if($_POST['ch7'] == 'on')
  {
  	$_SESSION['ch7'] = "on";
		$selectString .= ",Food";
		$updateString .= ", Food='@Food'";
		$insertString .= ",Food";
  }
	if($_POST['ch8'] == 'on')
  {
  	$_SESSION['ch8'] = "on";
		$selectString .= ",HeardAbout";
		$updateString .= ", HeardAbout='@HeardAbout'";
		$insertString .= ",HeardAbout";
  }
	if($_POST['ch9'] == 'on')
  {
  	$_SESSION['ch9'] = "on";
		$selectString .= ",ReferredBy";
		$updateString .= ", ReferredBy='@ReferredBy'";
		$insertString .= ",ReferredBy";
  }
	if($_POST['ch10'] == 'on')
  {
  	$_SESSION['ch10'] = "on";
		$selectString .= ",Phone";
		$updateString .= ", Phone='@Phone'";
		$insertString .= ",Phone";
  }
	if($_POST['ch11'] == 'on')
  {
  	$_SESSION['ch11'] = "on";
		$selectString .= ",RequestedTableBuddies";
		$updateString .= ", RequestedTableBuddies='@RequestedTableBuddies'";
		$insertString .= ",RequestedTableBuddies";
  }
	if($_POST['ch12'] == 'on')
  {
  	$_SESSION['ch12'] = "on";
		$selectString .= ",NoteToHostess";
		$updateString .= ", NoteToHostess='@NoteToHostess'";
		$insertString .= ",NoteToHostess";
  }
	if($_POST['ch13'] == 'on')
  {
  	$_SESSION['ch13'] = "on";
		$selectString .= ",EnteredBy";
		$updateString .= ", EnteredBy='@EnteredBy'";
		$insertString .= ",EnteredBy";
  }
	if($_POST['ch14'] == 'on')
  {
  	$_SESSION['ch14'] = "on";
		$selectString .= ",PaymentRecvdDate";
		$updateString .= ", PaymentRecvdDate='@PaymentRecvdDate'";
		$insertString .= ",PaymentRecvdDate";
  }
	if($_POST['ch15'] == 'on')
  {
  	$_SESSION['ch15'] = "on";
		$selectString .= ",PaymentRecvdAmount";
		$updateString .= ", PaymentRecvdAmount='@PaymentRecvdAmount'";
		$insertString .= ",PaymentRecvdAmount";
  }
	if($_POST['ch16'] == 'on')
  {
  	$_SESSION['ch16'] = "on";
		$selectString .= ",Paid";
		$updateString .= ", Paid='@Paid'";
		$insertString .= ",Paid";
  }
	if($_POST['ch17'] == 'on')
  {
  	$_SESSION['ch17'] = "on";
		$selectString .= ",FoodCategory";
		$updateString .= ", FoodCategory='@FoodCategory'";
		$insertString .= ",FoodCategory";
  }
	if($_POST['ch18'] == 'on')
  {
  	$_SESSION['ch18'] = "on";
		$selectString .= ",Refferals";
		$updateString .= ", Refferals='@Refferals'";
		$insertString .= ",Refferals";
  }
	if($_POST['ch19'] == 'on')
  {
  	$_SESSION['ch19'] = "on";
		$selectString .= ",SeatNumber";
		$updateString .= ", SeatNumber='@SeatNumber'";
		$insertString .= ",SeatNumber";
  }
	if($_POST['ch30'] == 'on')
  {
  	$_SESSION['ch30'] = "on";
		$selectString .= ",CustomMessageBody";
		$updateString .= ", CustomMessageBody='@CustomMessageBody'";
		$insertString .= ",CustomMessageBody";
  }
	if($_POST['ch31'] == 'on')
  {
  	$_SESSION['ch31'] = "on";
		$selectString .= ",CustomMessageBdySent";
		$updateString .= ", CustomMessageBdySent='@CustomMessageBdySent'";
		$insertString .= ",CustomMessageBdySent";
  }
  
	$selectString .= " from registration";
	$updateString .= " where regid=@regid";
	$insertString .= ") values ('@Name'";
	
	
	if($_POST['ch2'] == 'on')
  {
		$insertString .= ", '@EmailAddress'";
  }
	if($_POST['ch3'] == 'on')
  {
		$insertString .= ", '@StreetAddress'";
  }
	if($_POST['ch4'] == 'on')
  {
		$insertString .= ", '@CSZ'";
  }
	if($_POST['ch5'] == 'on')
  {
		$insertString .= ", '@PayBy'";
  }
	if($_POST['ch6'] == 'on')
  {
		$insertString .= ", '@ReturningGuest'";
  }
	if($_POST['ch7'] == 'on')
  {
		$insertString .= ", '@Food'";
  }
	if($_POST['ch8'] == 'on')
  {
		$insertString .= ", '@HeardAbout'";
  }
	if($_POST['ch9'] == 'on')
  {
		$insertString .= ", '@ReferredBy'";
  }
	if($_POST['ch10'] == 'on')
  {
		$insertString .= ", '@Phone'";
  }
	if($_POST['ch11'] == 'on')
  {
		$insertString .= ", '@RequestedTableBuddies'";
  }
	if($_POST['ch12'] == 'on')
  {
		$insertString .= ", '@NoteToHostess'";
  }
	if($_POST['ch13'] == 'on')
  {
		$insertString .= ", '@EnteredBy'";
  }
	if($_POST['ch14'] == 'on')
  {
		$insertString .= ", '@PaymentRecvdDate'";
  }
	if($_POST['ch15'] == 'on')
  {
		$insertString .= ", '@PaymentRecvdAmount'";
  }
	if($_POST['ch16'] == 'on')
  {
		$insertString .= ", '@Paid'";
  }
	if($_POST['ch17'] == 'on')
  {
		$insertString .= ", '@FoodCategory'";
  }
	if($_POST['ch18'] == 'on')
  {
		$insertString .= ", '@Refferals'";
  }
	if($_POST['ch19'] == 'on')
  {
		$insertString .= ", '@SeatNumber'";
  }
	if($_POST['ch30'] == 'on')
  {
		$insertString .= ", '@CustomMessageBody'";
  }
	if($_POST['ch31'] == 'on')
  {
		$insertString .= ", '@CustomMessageBodySent'";
  }
  
	$insertString .= ")";
	
  if($_SESSION['EventSet'])
  {
    $whereClause = " where UserID='".$_SESSION['UserID']."' AND EventID='".$_SESSION['EventSet']."'";
  } else {
    $whereClause = " where UserID='".$_SESSION['UserID']."'";
  }
  
	$selectString .= $whereClause;
	
	$_SESSION['select'] = $selectString;
	$_SESSION['update'] = $updateString;
	$_SESSION['insert'] = $insertString;
	
//  echo "<p style='border: 1px solid green; color: green; padding: 2px;'>";
//  echo $_SESSION['select'];
//  echo "</p>";
//  echo "<p style='border: 1px solid green; color: green; padding: 2px;'>";
//  echo $_SESSION['update'];
//  echo "</p>";
//  echo "<p style='border: 1px solid green; color: green; padding: 2px;'>";
//  echo $_SESSION['insert'];
//  echo "</p>";
	
}

?>