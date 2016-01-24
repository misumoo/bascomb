function headerMouseOver(id) {
  document.getElementById(id).style.backgroundColor = "#484848";
}

function headerMouseOut(id) {
  document.getElementById(id).style.backgroundColor = "#000000";
}

function setNewEvent() {
  var newEventID = $("#eventWorkingWith").val();
  if(eventWorkingWith != "") {
    $.post( "views/regadmin_handle.php", {
      newEventID: newEventID,
      setNewEvent: true
    }).done(function( data ) {
      window.location.href = window.location.href;
    });
  }
}

function gridSelectHandle() {
	var i = 2;
  var gridSelect = document.getElementById("gridSelect").value;
	var total = document.getElementById("totalBoxes").value;
  //reset the checkboxes, then handle the scenario's
	for (i = 2; i <= total; i++)
	{
		document.getElementById("ch" + i).checked = '';
    
    //These 2 are only for display if we are making a custom mail message, they are far too big for the rest of the info
    document.getElementById("ch30").checked = '';
    document.getElementById("ch31").checked = '';
	}
  if (gridSelect != "") {
    if (gridSelect == "referred") {
      document.getElementById("ch6").checked = 'yes';
      document.getElementById("ch8").checked = 'yes';
      document.getElementById("ch9").checked = 'yes';
      document.getElementById("ch12").checked = 'yes';
      document.getElementById("ch18").checked = 'yes';
    }
    if (gridSelect == "custom") {
      document.getElementById("ch2").checked = 'yes';
      document.getElementById("ch5").checked = 'yes';
      document.getElementById("ch7").checked = 'yes';
      document.getElementById("ch12").checked = 'yes';
      document.getElementById("ch16").checked = 'yes';
      document.getElementById("ch30").checked = 'yes';
      document.getElementById("ch31").checked = 'yes';
    }
    if (gridSelect == "payments") {
      document.getElementById("ch5").checked = 'yes';
      document.getElementById("ch14").checked = 'yes';
      document.getElementById("ch15").checked = 'yes';
      document.getElementById("ch16").checked = 'yes';
    }
    if (gridSelect == "food") {
      document.getElementById("ch7").checked = 'yes';
      document.getElementById("ch17").checked = 'yes';
    }
    if (gridSelect == "all") {
    	for (i = 2; i <= total; i++) {
    		document.getElementById("ch" + i).checked = 'yes';
    	}
    }
  }
  if (gridSelect == "") {
    //do nothing
  }
}

function buildString() {
  data = {};

  (document.getElementById("ch2").checked == true ? data["ch2"] = "on" : data["ch2"] = "off");
  (document.getElementById("ch3").checked == true ? data["ch3"] = "on" : data["ch3"] = "off");
  (document.getElementById("ch4").checked == true ? data["ch4"] = "on" : data["ch4"] = "off");
  (document.getElementById("ch5").checked == true ? data["ch5"] = "on" : data["ch5"] = "off");
  (document.getElementById("ch6").checked == true ? data["ch6"] = "on" : data["ch6"] = "off");
  (document.getElementById("ch7").checked == true ? data["ch7"] = "on" : data["ch7"] = "off");
  (document.getElementById("ch8").checked == true ? data["ch8"] = "on" : data["ch8"] = "off");
  (document.getElementById("ch9").checked == true ? data["ch9"] = "on" : data["ch9"] = "off");
  (document.getElementById("ch10").checked == true ? data["ch10"] = "on" : data["ch10"] = "off");
  (document.getElementById("ch11").checked == true ? data["ch11"] = "on" : data["ch11"] = "off");
  (document.getElementById("ch12").checked == true ? data["ch12"] = "on" : data["ch12"] = "off");
  (document.getElementById("ch13").checked == true ? data["ch13"] = "on" : data["ch13"] = "off");
  (document.getElementById("ch14").checked == true ? data["ch14"] = "on" : data["ch14"] = "off");
  (document.getElementById("ch15").checked == true ? data["ch15"] = "on" : data["ch15"] = "off");
  (document.getElementById("ch16").checked == true ? data["ch16"] = "on" : data["ch16"] = "off");
  (document.getElementById("ch17").checked == true ? data["ch17"] = "on" : data["ch17"] = "off");
  (document.getElementById("ch18").checked == true ? data["ch18"] = "on" : data["ch18"] = "off");
  (document.getElementById("ch19").checked == true ? data["ch19"] = "on" : data["ch19"] = "off");
  (document.getElementById("ch30").checked == true ? data["ch30"] = "on" : data["ch30"] = "off");
  (document.getElementById("ch31").checked == true ? data["ch31"] = "on" : data["ch31"] = "off");

  $.post( "views/buildstring.php", data)
    .done(function( data ) {
    location.href = "grid.php";
  });
}

$(document).ready(function() {
  $("#popup_SelectNewEvent").dialog({
    autoOpen: false,
    height: 100,
    width: 500,
    modal: true
  }); //popup_SelectNewEvent

  $("#header_Selected").click(function() {
    $("#popup_SelectNewEvent").dialog("open");
  }); //header_Selected
});