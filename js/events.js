/**
 * Created by Misumoo on 6/4/2015.
 */

$(function() {
  $( "#save_StartDate" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });
  $( "#save_EndDate" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });

  $( "#payment_StartDate" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });
  $( "#payment_EndDate" ).datepicker({
    dateFormat: 'yy-mm-dd'
  });

  $( "#popup_SaveEvent" ).dialog({
    autoOpen: false,
    height: 310,
    width: 300,
    modal: true,
    buttons: {
      "Save Event": function() {
        saveEvent();
      },
      Cancel: function() {
        closedialog();
      }
    }
  }).on('dialogclose', function() {
    closedialog();
  });

  $( "#popup_PaymentPlans" ).dialog({
    autoOpen: false,
    minHeight: 0,
    width: 600,
    modal: true,
    create: function() {
      $(this).css("maxHeight", 550);
    },
    buttons: {
      Close: function() {
        closePaymentPlans();
      }
    }
  }).on('dialogclose', function() {
    closePaymentPlans();
  });

  setup();
});

function closePaymentPlans() {
  $('#frm_AddPaymentPlan')[0].reset();
  //our eventid does not get reset for some reason, manual reset
  $("#payment_EventID").val("");
  $("#popup_PaymentPlans").dialog("close");
}

function closedialog() {
  $('#frm_SaveEvent')[0].reset();
  //our eventid does not get reset for some reason, manual reset
  $("#save_EventID").val("");
  $("#popup_SaveEvent").dialog("close");
}

function setup() {
  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      setup: true
    },
    dataType: 'html'
  }).done(function( response ) {
    $("#setup").html(response);
  });
} //setup

function getEventInfo(eventid) {
  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      geteventinfo: true,
      eventid: eventid
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      $("#popup_SaveEvent").dialog("open");
      $("#save_Capacity").val(response.capacity);
      $("#save_StartDate").val(response.startdate);
      $("#save_EventName").val(response.eventname);
      $("#save_EndDate").val(response.enddate);
      $("#save_DisplayCapacity").val(response.displaycapacity);
      $("#save_EventID").val(response.eventid);
    } else {
      //error :(
      alert(response.message);
    }
  });
} //getEventInfo

function deleteEvent(eventid) {
  conf = confirm('Are you sure you want to delete event id: ' + eventid + '?');

  if(conf) {
    $.ajax({
      method: "POST",
      url: "views/events_handle.php",
      data: {
        deleteEvent: true,
        eventid: eventid
      },
      dataType: 'json'
    }).done(function( response ) {
      if(response.success == true) {
        setup();
      } else {
        //error :(
        alert(response.message);
      }
    });
  }
} //deleteEvent

function saveEvent() {
  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      saveevent: true,
      eventname: $("#save_EventName").val(),
      startdate: $("#save_StartDate").val(),
      enddate: $("#save_EndDate").val(),
      capacity: $("#save_Capacity").val(),
      displaycapacity: $("#save_DisplayCapacity").val(),
      eventid: $("#save_EventID").val()
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      closedialog();
      setup();
    } else {
      //error :(
      alert(response.message);
    }
  });
} //saveEvent

function openPaymentPlan(id) {
  resetPaymentPlanForm(id);
  $("#popup_PaymentPlans").dialog("open");
  refreshPaymentTable(id);
} //openPaymentPlan

function refreshPaymentTable(id) {
  $("#tbl_PaymentPlans").find("tbody").html("");

  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      openPaymentPlan: true,
      eventid: id
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      $("#payment_EventID").val(id);
      for (i = 0; i < response.data.length; i++) {
        sImg = "<img title='Delete this record.' src='images/cancel.png' onclick=\"deletePaymentPlan('" + response.data[i].TimeFrameID + "')\" />";
        sRow = "<td>" + sImg + "</td>";
        sRow += "<td>" + response.data[i].TimeFrameID + "</td>";
        sRow += "<td>" + response.data[i].EventID + "</td>";
        sRow += "<td>" + response.data[i].Note + "</td>";
        sRow += "<td>" + response.data[i].StartDate + "</td>";
        sRow += "<td>" + response.data[i].EndDate + "</td>";
        sRow += "<td>" + response.data[i].Amount + "</td>";
        sRow = "<tr>" + sRow + "</tr>";

        $("#tbl_PaymentPlans").find('tbody').append($(sRow));
      }
      paymentFindDefault();
    } else {
      //error :(
      alert(response.message);
    }
  });
}

function resetPaymentPlanForm(eventid) {
  $('#frm_AddPaymentPlan')[0].reset();
  $("#payment_EventID").val(eventid);
}

function addPaymentPlan() {
  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      addPaymentPlan: true,
      eventid: $("#payment_EventID").val(),
      timeframeid: $("#payment_TimeFrameID").val(),
      startdate: $("#payment_StartDate").val(),
      enddate: $("#payment_EndDate").val(),
      amount: $("#payment_Amount").val(),
      form: $("#payment_Form").val(),
      setdefault: $("#payment_Default").val()
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      //openPaymentPlan($("#payment_EventID").val());
      resetPaymentPlanForm($("#payment_EventID").val());
      refreshPaymentTable($("#payment_EventID").val());
    } else {
      //error :(
      alert(response.message);
    }
  });
} //addPaymentPlan

function paymentFindDefault() {
  if($("#tbl_PaymentPlans").html().search("Default") != -1) {
    $("#payment_Default").val(0);
  }
} //paymentFindDefault

function deletePaymentPlan(timeframeid) {
  $.ajax({
    method: "POST",
    url: "views/events_handle.php",
    data: {
      deletePaymentPlan: true,
      timeframeid: timeframeid
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      refreshPaymentTable($("#payment_EventID").val());
      //openPaymentPlan($("#payment_EventID").val());
    } else {
      //error :(
      alert(response.message);
    }
  });
} //deletePaymentPlan

function expandHandle(id) {
  var open = document.getElementById("currOpen");
  document.getElementById(id).style.display = "block";
  if(open.value != "") {
    document.getElementById(open.value).style.display = "none";
  }
  open.value = id;
} //expandHandle

function display(elShow,elHide) {
  document.getElementById(elShow).style.display = "block";
  document.getElementById(elHide).style.display = "none";
} //display

$(function() {
  $("#addNew").click(function() {
    $("#popup_SaveEvent").dialog("open");
  }); //addNew
});