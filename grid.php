<?php
/**
 * Created by PhpStorm.
 * User: Misumoo
 * Date: 8/25/2015
 * Time: 3:45 PM
 */

session_start();

if (!isset($_SESSION['authUser'])) {
  header("Location: login.php");
} else {

require 'lib/db.php';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />

  <title>BUMC Admin</title>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
  <script src="js/lib/jquery.tablesorter.js"></script>
  <script src="js/lib/jquery.tablesorter.widgets.js"></script>
  <script src="js/lib/widget-cssStickyHeaders.js"></script>
  <script src="js/regadmin.js" type="text/javascript"></script>

  <link rel="stylesheet" href="css/theme.ice.css" />
  <link href="css/regadmin.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/site.ico">

  <script>

    $(document).ready(function() {
      callProcedure();

      $('#datagrid').width(window.innerWidth).height(window.innerHeight-44);
      $(window).resize(function(){ // On resize
        $('#datagrid').width(window.innerWidth).height(window.innerHeight-44);
      });
    });

    function callProcedure() {
      $.ajax({
        type: "POST",
        url: 'views/grid_handle.php',
        data: {
          callprocedure: true
        },
        dataType: "html",
        success: function(data) {
          $('#datagrid').html(data);
          $(".af-save").on('change keyup paste', function() {
            var id = $(this).attr('id');
            var arr = id.split('-');
            $("#id-" + arr[1]).attr("src", "images/save_16.gif");
          });
          var options = {
            widthFixed : false,
            showProcessing: true,
            headerTemplate: '{content} {icon}', // Add icon for jui theme; new in v2.7!
            widgets: [ /*'zebra'*/ 'cssStickyHeaders' ,  'filter'],
            widgetOptions: {
              cssStickyHeaders_offset        : 0,
              cssStickyHeaders_addCaption    : true,
              cssStickyHeaders_attachTo      : $('#datagrid'),
              cssStickyHeaders_filteredToTop : true,
              cssStickyHeaders_zIndex        : 10
            },
            headers: {
//              1: { sorter: 'date' },
//              '.type-of-entertainment' : {
//                sorter: false
//              }
            }
          };
          $.tablesorter.addParser({
            id: "date",
            is: function (s) {
              return false;
            },
            format: function (s, table) {
              return new Date(s).getTime() || '';
            },
            type: "numeric"
          });

          $("#tblgrid").tablesorter(options);
//          $("#tblgrid").tablesorter().bind('filterEnd', function() {
//            $("#numRows").html("");
//            $("#numRows").html("&nbsp;&nbsp;<span class='footer'>" + ($('#tblgrid tr:visible').length-2) + " Rows</span>");
//          });
        }
      });
    }

  </script>

</head>

<body>
  <div id="header" class="header"></div>
  <div class="page">
    <? require "header.php"; ?>
  </div>

  <div style="top: 44px; position: absolute; overflow: scroll;" id="datagrid"></div>

</body>
</html>
<?php
}
?>