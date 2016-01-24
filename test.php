<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 9/22/2015
 * Time: 8:57 PM
 */

require_once("lib/_classes.php");
require_once("lib/_functions.php");
require_once('lib/db.php');

$eventid = 16;
$today = date('Y-m-d');

$mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);
$sql = "
    SELECT
      *
    FROM `tbl_payment_timeframes`
    WHERE
    StartDate <= '".$today."'
    AND EndDate >= '".$today."'
    AND EventID = '".$id."'
    OR StartDate IS NULL
    AND EndDate IS NULL
    ORDER BY StartDate DESC
    LIMIT 1
  ";

//echo $sql;

echo getPaypalForm($eventid);