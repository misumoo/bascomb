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

$eventid = 17;
$today = date('Y-m-d');

$mysqli = new mysqli(db::dbserver, db::dbuser, db::dbpass, db::dbname);

echo getPaypalForm($eventid);