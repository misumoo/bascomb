/**
 * Created by Misumoo on 8/7/2015.
 */

$(document).ready(function() {
  fetchSettings();
});

function saveSettings() {
  $.ajax({
    method: "POST",
    url: "views/settings_handle.php",
    data: {
      save: true,
      bcc: $("#bcc").val(),
      email: $("#email").val(),
      friendlyemail: $("#friendlyemail").val()
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      alert("Data saved successfully.");
    } else {
      //error :(
      alert(response.message);
    }
  });
} //getEventInfo

function fetchSettings() {
  $.ajax({
    method: "POST",
    url: "views/settings_handle.php",
    data: {
      getSettings: true
    },
    dataType: 'json'
  }).done(function( response ) {
    if(response.success == true) {
      $("#bcc").val(response.bcc);
      $("#email").val(response.email);
      $("#friendlyemail").val(response.friendlyemail);
    } else {
      //error :(
      alert(response.message);
    }
  });
}