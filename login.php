<?php

/**
 * @author Chris & Robert Whetzel
 * @copyright 2010
 */

session_start();
    
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">


<head>
	<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

	<title>BUMC Admin</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="js/regadmin.js" type="text/javascript"></script>
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">
  
</head>

<body>
<div id="header" class="header"></div>
<div class="page">
<div class="innerHeader">
  <div class="divHdr left">
  </div>
  <div class="divHdr right">  
  </div>
</div>
  <div id="containerHolder" class="contHolderSingle">
    <div style="text-align: center;">
    	<form method="post" action="checklogin.php">
      <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr style="text-align: center;">
          <td colspan="2">
    		    <h2>Login</h2>
          </td>
        </tr>
        <tr>
          <td style="text-align: right; width: 50%;">Username:&nbsp;</td>
          <td style="text-align: left; width: 50%;"><input type="text" name="username" /></td>
        </tr>
        <tr>
          <td style="text-align: right; width: 50%;">Password:&nbsp;</td>
          <td style="text-align: left; width: 50%;"><input type="password" name="password" /></td>
        </tr>
        <tr style="text-align: center; width: 100%;">
          <td colspan="2">
    		    <p>
              <input type="submit" value="Login" />
            </p>
          </td>
        </tr>
    		<?php
    		if(isset($_SESSION['errorLogin']))
    		{
    			echo "<tr>
                  <td>
                    <p style='color: red; font-weight: bold;'>";
    			echo $_SESSION['errorLogin'];
    			echo "    </p>
                  </td>
                </tr>";
    		}
    		?>
      </table>
    </div>
  	</form>
  </div>
</div>
</body>
</html>