<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();


if (!isset($_SESSION['authUser'])) {
	header("Location: login.php");
} else {



$UserID = $_SESSION['UserID'];


require 'lib/db.php';

//$KoolControlsFolder = "KoolControls";
//
//require $KoolControlsFolder."/KoolAjax/koolajax.php";

?>
<!DOCTYPE html>
<html lang="en">


<head>
	<title>BUMC Admin</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">

  <script src="js/regadmin.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">

<!--	--><?php //echo $koolajax->Render(); ?>
  
</head>

<body>
<div id="header" class="header"></div>
<div class="page">
<?php
require "header.php";


//determined in header.php
$_SESSION['EventSet'];
$_SESSION['EventName'];


?>
  <div id="containerHolder" class="contHolder">
  	<div class="leftContainer">
  		
  		<div class="panelWrapper">
  			<?php
  			$result = mysql_query("SELECT UserID,ExpirationDate,EventName FROM tblEvents WHERE UserID='".$UserID."'");
        if ($result)
        {
    			while($row = mysql_fetch_array($result))
    			{
    				echo $row['EventName'];
            if($row['ExpirationDate'] <= date("Y-m-d",time()))
            {
              echo " has expired. <br />";
            } else {
              echo " is expiring: ".$row['ExpirationDate']."<br />";
            }
    			}
        } else {
  				echo "<p>Couldn't connect to the database. <br />
          Server said: "; echo mysql_error($db_con);
  				mysql_close($db_con);
          echo "</p>";
  			}
    			?>
   		</div>
      
      <?php
      if(isset($_SESSION['EventSet']) && $_SESSION['EventSet'] != "")
      {
      ?>
  		<div class="panelWrapper">
  			<?php
  			$result = mysql_query("SELECT Count(regid),Paid FROM registration WHERE UserID='".$UserID."' AND EventID='".$_SESSION['EventSet']."' AND EmailAddress != 'TheAustins4@gmail.com' AND EmailAddress != 'ScrappingatBascomb@gmail.com'");
        if ($result)
        {
    			while($row = mysql_fetch_array($result))
    			{
    				echo $row['Count(regid)'];
            if($row['Count(regid)'] == 1)
            {
              echo " person is";
            } else {
              echo " people are";
            }
    				echo " registered.";
    			}
        } else {
  				echo "<p>Couldn't connect to the database. <br />
          Server said: "; echo mysql_error($db_con);
  				mysql_close($db_con);
          echo "</p>";
  			}
  			?>
  		</div>
    
    
    
				<?php
        
//        echo 'enter';
        
				$result = mysql_query("SELECT Count(regid),PayBy,Paid,Sum(PaymentRecvdAmount) FROM registration WHERE UserID='".$UserID."' AND EventID='".$_SESSION['EventSet']."' GROUP BY PayBy, Paid");
				if ($result)
				{
            echo "<div class=\"panelWrapper\">
                  <div class=\"innerPanelLeft\">
                			<table border=\"1\">
                				<tr>
                					<th><label>Total Paying by</label></th>
                					<th><label>Paid by</label></th>
                					<th><label>Paid</label></th>
                					<th><label>Totals</label></th>
                				</tr>
                      <label>";
  					while($row = mysql_fetch_array($result))
  					{
  						echo "<tr>";
  						echo "<td>";
  						echo $row['PayBy'];
  						echo "</td>";
  						echo "<td>";
  						echo $row['Count(regid)'];
  						echo "</td>";
  						echo "<td>";
  						echo $row['Paid'];
  						echo "</td>";
  						echo "<td>";
  						echo $row['Sum(PaymentRecvdAmount)'];
  						echo "</td>";
  						echo "</tr>";
  					}
  					echo "</label>";
            echo "		</table>
                    </div>   
              		</div>";
          }
//				} else {
//					echo "<p>Couldn't connect to the database. </p>";
//					echo "<br />";
//				  mysql_close($db_con);
//					echo mysql_error($db_con);
//				}
        
        
				?>
        
          <?php
  				$result = mysql_query("SELECT Count(regid),FoodCategory FROM registration WHERE UserID='".$UserID."' AND EventID='".$_SESSION['EventSet']."' GROUP BY FoodCategory");
          if ($result)
          {
			      if(mysql_fetch_array($result) != 0 && mysql_fetch_array($result) != "" && mysql_fetch_array($result) != NULL)
            {
              echo "<div class=\"panelWrapper\">
                      <div class=\"innerPanelLeft\">
                        <p>
                        <a href='foodlist.php?e=".$_SESSION['EventSet']."' target='_blank'>View Food List</a>
                        </p>";
//    					while ($row = mysql_fetch_array($result))
//    					{
//  					    echo "<p>";
//                if ($row['Count(regid)'] == "1")
//                {
//          				echo $row['Count(regid)'];
//          				echo " person is bringing ";
//                } else {
//          				echo $row['Count(regid)'];
//          				echo " people are bringing ";
//                }
//                if($row['FoodCategory'] == "")
//                {
//                  echo "Nothing.</p>";
//                } else {
//    						  echo $row['FoodCategory'];
//                }
//    				    echo "</p>";
//    					}
    					echo "</div></div>";
   				  } 
    				mysql_close($db_con);
          } else {
    					echo "<p>Couldn't connect to the database. </p>";
    					echo "<br />";
    					echo mysql_error($db_con);
    				  mysql_close($db_con);
    				}
          }
        ?>      
      
  		<div class="panelWrapper">
        <div class="innerPanelLeft">
          Printer-friendly reports:<br />
          <a target="_blank" href="printerreport.php?order=SeatNumber<?php echo "&id=".$_SESSION['EventSet'].""; ?>">Order by Seat Number</a><br />
          <a target="_blank" href="printerreport.php?order=Name<?php echo "&id=".$_SESSION['EventSet'].""; ?>">Order by Name</a>
        </div>
  		</div>
      
  	</div>
  	<div class="rightContainer">
  		<table class="select">
  			<tr>
  				<th colspan="2" style="text-align: center;"><label>View/Edit Grid</label></th>
  			</tr>
  			<tr>
  				<td colspan="2" style="text-align: center;">
            <!--
            Old way:
            <button style="display: block;" id="check" onclick="selectAll('yes')">Select All</button>
            <button style="display: none;" id="uncheck" onclick="selectAll('')">Unselect All</button>
            -->
            <select id='gridSelect' onchange='gridSelectHandle()' style="width: 100%;">
              <option value=''></option>
              <option value='referred'>Set up Referrals</option>
              <option value='custom'>Custom Mail Message</option>
              <option value='payments'>Payments</option>
              <option value='food'>Food</option>
              <option value='all'>All</option>
            </select>
  				</td>
  			</tr>
  			<form id="form1" method="post" action="grid.php">
				<tr>
					<td class="checkbox"><input name="ch2" id="ch2" type="checkbox" /></td>
					<td>Email</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch3" id="ch3" type="checkbox" /></td>
					<td>StreetAddress</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch4" id="ch4" type="checkbox" /></td>
					<td>CSZ</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch5" id="ch5" type="checkbox" /></td>
					<td>PayBy</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch6" id="ch6" type="checkbox" /></td>
					<td>ReturningGuest</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch7" id="ch7" type="checkbox" /></td>
					<td>Food</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch8" id="ch8" type="checkbox" /></td>
					<td>HeardAbout</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch9" id="ch9" type="checkbox" /></td>
					<td>ReferredBy</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch10" id="ch10" type="checkbox" /></td>
					<td>Phone</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch11" id="ch11" type="checkbox" /></td>
					<td>RequestedTableBuddies</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch12" id="ch12" type="checkbox" /></td>
					<td>NoteToHostess</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch13" id="ch13" type="checkbox" /></td>
					<td>EnteredBy</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch14" id="ch14" type="checkbox" /></td>
					<td>PaymentRecvdDate</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch15" id="ch15" type="checkbox" /></td>
					<td>PaymentRecvdAmount</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch16" id="ch16" type="checkbox" /></td>
					<td>Paid</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch17" id="ch17" type="checkbox" /></td>
					<td>FoodCategory</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch18" id="ch18" type="checkbox" /></td>
					<td>Refferals</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch19" id="ch19" type="checkbox" /></td>
					<td>Seat Number</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch30" id="ch30" type="checkbox" /></td>
					<td>Custom Message Body</td>
				</tr>
				<tr>
					<td class="checkbox"><input name="ch31" id="ch31" type="checkbox" /></td>
					<td>Custom Message Body Sent</td>
				</tr>
			</form>
			<tr>
				<td colspan="2" style="text-align: center;"><button onclick="buildString()">View Grid</button></td>
			</tr>
			<input id="totalBoxes" value="19" type="hidden" />
		</table>
		<br />
    </div>
    <div style="clear: both;"></div>
  </div>
</div>
</body>
</html>
<?php
}
?>