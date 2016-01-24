<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();


if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {

require 'lib/db.php';

$_SESSION['UserID'];

function check($id)
{
	$result = mysql_query("SELECT UserID FROM tblEvents WHERE EventID='".$id."'");
  if ($result)
  {
    if(mysql_num_rows($result) == 0)
    {
      $cancelProcess = true;
      return false;
    }
    if(!$cancelProcess)
    {			
      while($row = mysql_fetch_array($result))
			{
	      //check to make sure it's theirs
        if($row['UserID'] != $_SESSION['UserID'])
        {
          $cancelProcess = true;
          return false;
        } else {
          return true;
        }
			}
    }
  } else {
		echo "<p>Couldn't connect to the database. <br />
    Server said: "; echo mysql_error($db_con);
		mysql_close($db_con);
    echo "</p>";
	}
}

//printerfriendly

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <link rel="shortcut icon" href="images/site.ico">

	<title>BUMC Seating Report</title>
  
  <style>
  body
  {
    font: 14px Comic Sans MS;
    font-weight: normal;
    margin: 0 auto;
  }
  div.food
  {
    height: 20px;
    overflow: hidden;
    width: 300px;
  }
  th {
    border-bottom: thick double;
    font-weight: bold;
     vertical-align: bottom;
  }
  td {
    border-bottom: thin solid;
  }
  </style>
</head>

<body>

<?php
//  echo "Bringing food - 1 ticket<br />
//  Referred someone - 3 tickets<br />
//  Registered before March 1st - 5 tickets<br />
//  Figure out if payed, if not figure out what they owe.($25 if bringing food $30 if not)<br />
//  Seat numbers, Susan will manually put this in.<br />";
  
  $cancelProcess = false;
  $paid;
  $name;
  $food;
  $balance;
  $seatNumber;
  $ticketsFromReferrals;
  $ticketsFromFood;
  $ticketsFromOnTime;
  $ticketsTotal;
  $tickets;
  $rowNumber;
  $sortOrder;
  $id;
  
  if($_GET["id"] == "" || !$_GET["id"] || $_GET["order"] == "" || !$_GET["order"])
  {
    $cancelProcess = true;
  } else {
    $id = " WHERE EventID='".$_GET["id"]."'";
    $sortOrder = " ORDER BY ".$_GET["order"]." ASC";
  }
  
  if(check($_GET["id"]) != true)
  {
    $cancelProcess = true;
  }
  
  if(!$cancelProcess)
  {
    $result = mysql_query("SELECT Name,Paid,Food,Submitted,Refferals,SeatNumber FROM registration".$id."".$sortOrder."");
    if ($result)
    {
      $rowNumber = $rowNumber + "1";
      echo "<table cellpadding='0' cellspacing='0'>
        <tr>
          <th style='width:75px;'>Seat#</th>
          <th style='width:220px;'>Name</th>
          <th style='width:75px;'>Balance</th>
          <th style='width:75px;'>FYFS</th>
          <th style='width:75px;'>Ref</th>
          <th style='width:75px;'>On-time</th>
          <th style='width:75px;'>Tickets Totals</th>
        </tr>";
        while($row = mysql_fetch_array($result))
        {
          //reset all variables used to calculate
          $seatNumber = "";
          $balance = "$0";
          $ticketsFromReferrals = "0";
          $ticketsFromFood = "0";
          $ticketsFromOnTime = "0";
          $tickets = "0";
          $name = $row['Name'];
          $food = $row['Food'];
          $paid = strtoupper($row['Paid']);
          
          //figure out if there's a seatnumber
          if ($row['SeatNumber'] != "")
          {
            $seatNumber = $row['SeatNumber'];
          } else {
            $seatNumber = "&nbsp;";
          }
          //figure out how many tickets for referrals
          if ($row['Refferals'] != "" && $row['Refferals'] != "0")
          {
            $tickets = $row['Refferals'] * 3;
            $ticketsFromReferrals = $row['Refferals'] * 3;
          }
          
            
          //figure out their balance
          if($paid == "Y")
          {
            $balance = "$0";
          } else {
            if($food != "")
            {
              $balance = "$25";
            }
            if($food == "")
            {
              $balance = "$35";
            }
          }
            
          //if they bring food, give them 1 ticket
          if($food != "")
          {
            $ticketsFromFood = "1";
            $tickets = $tickets + "1";
          }
            
          //if they registered on or before March 1st, give them 5 tickets.
          if($row['Submitted'] < "2010-03-02 00:00:00")
          {
            $ticketsFromOnTime = "5";
            $tickets = $tickets + "5";
          }
          $ticketsTotal = $ticketsTotal + $tickets;
          
    //      change style for every other row;
    //      $rowNumber = $rowNumber + "1";
    //      if($rowNumber % "2")
    //      { 
    //        echo "<tr style=\"background-color: #C4C4C4;\">"; 
    //      } else {
    //      }
          echo "<tr>";
            echo "<td style='text-align: center;'>".$seatNumber."</td>"; 
            echo "<td style='border-bottom: thin solid'>".$name."</td>"; 
            echo "<td style='text-align: center;'>".$balance."</td>";
            echo "<td style='text-align: center;'>".$ticketsFromFood."</td>"; 
            echo "<td style='text-align: center;'>".$ticketsFromReferrals."</td>"; 
            echo "<td style='text-align: center;'>".$ticketsFromOnTime."</td>"; 
            echo "<td style='text-align: center;'>".$tickets."</td>"; 
          echo "</tr>";
        }
    //    $rowNumber = $rowNumber + "1";
    //    if($rowNumber % "2")
    //    { 
    //      echo "<tr style=\"background-color: #C4C4C4;\">"; 
    //    } else {
    //      echo "<tr><td>";
    //    }
      echo "
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style=\"text-align: center;\">".$ticketsTotal."</td>";
        echo "</tr>";
      echo "</table>";
    } else {
      $cancelProcess = true;
    	echo "<p>Couldn't connect to the database. </p>";
    	echo "<br />";
    	echo mysql_error($db_con);
      mysql_close($db_con);
    }
  }
  
?>
</body>
</html>
<?php
}
?>