function setup()
{
  initialEmailLayout();
  updateMailList();
  emailDropDownListHandle();
  emailGenerateTemplates();
}

function selectNewTemplate()
{
  var changeTo = document.getElementById("templateList").value;
  if(changeTo != "") {
    $.post( "views/custommessages_handle.php", {
      changeTo: changeTo
    }).done(function( data ) {
      document.getElementById("emailLayout").innerHTML = data;
    });
  }
}

function submitNewEmail()
{
  var emailName = document.getElementById("emailName").value;
  if(emailName != "") {
    $.post( "views/custommessages_handle.php", {
      emailName: emailName
    }).done(function( data ) {
      location.reload(this);
    });
  }
}

function deleteEmail()
{
  var deleteEmail = document.getElementById("currentTemplate").value;
  if(confirm("Are you sure you want to delete EmailID " + deleteEmail + "?")) {
    if(deleteEmail != "") {
      $.post( "views/custommessages_handle.php", {
        deleteEmail: deleteEmail
      }).done(function( data ) {
        location.reload(this);
      });
    }
  }
}

function toggleDisplay()
{
  var elm1 = document.getElementById("createNew");
  var elm2 = document.getElementById("overLay");
  
  switch(elm1.style.display)
  {
    case "none":
      elm1.style.display = "block";
      break;
    case "block":
      elm1.style.display = "none";
      break;
  }
  switch(elm2.style.display)
  {
    case "none":
      elm2.style.display = "block";
      break;
    case "block":
      elm2.style.display = "none";
      break;
  }
  
  if(elm1.style.display == "block")
  {
    var winW = 640;
    var winH = 480;
    if (parseInt(navigator.appVersion)>3) {
      if (navigator.appName=="Netscape") {
        winW = window.innerWidth / 2 - 190;
        winH = window.innerHeight / 2 - 190;
      }
      if (navigator.appName.indexOf("Microsoft")!=-1) 
      {
        winW = document.body.offsetWidth / 2 - 190;
        winH = document.body.offsetHeight / 2 - 190;
      }
    }
    elm1.style.left = winW + "px";
    elm1.style.top = winH + "px";
  }
}

function ddListChange()
{
  var ddID = document.getElementById("ddList").value;
  if(ddID != "") {
    if(ddID != "all") {
      $.post( "views/custommessages_handle.php", {
        EventID: ddID,
        generateMailList: true
      }).done(function( data ) {
        document.getElementById("emailList").innerHTML = data;
      });
    } else {
      $.post( "views/custommessages_handle.php", {
        generateMailListAll: true
      }).done(function( data ) {
        document.getElementById("emailList").innerHTML = data;
      });
    }
  }
}

function emailDropDownListHandle()
{
  $.post( "views/custommessages_handle.php", {
    dropDownList: true
  }).done(function( data ) {
    document.getElementById("mass").innerHTML = data;
  });
}

function emailGenerateTemplates()
{
  $.post( "views/custommessages_handle.php", {
    generateTemplates: true
  }).done(function( data ) {
    document.getElementById("emailTemplates").innerHTML = data;
  });
}

function initialEmailLayout()
{
  current = "";
  try {
    if (!document.getElementById("currentTemplate")) {
    } else {
      current = document.getElementById("currentTemplate").value;
    }
  } catch(e) {
    alert(e);
  }
  $.post( "views/custommessages_handle.php", {
    current: current,
    emailLayoutSetup: true
  }).done(function( data ) {
    document.getElementById("emailLayout").innerHTML = data;
  });
}

function updateMailList()
{
  $.post( "views/custommessages_handle.php", {
    popEmailList: true
  }).done(function( data ) {
    document.getElementById("emailList").innerHTML = data;
  });
}

function changeEmailLayout(id, imgID, dbColumn, cols, rows, includeTiny)
{
  try
  {
    var stopProcess = false;
    
    var currentTemplateID = document.getElementById("currentTemplate");
    var check = document.getElementById("checkIfOpen");
    var divIDToChange = document.getElementById(id);
    
    //count for everytime we make a new textarea, add it with the ID so it will be different everytime for tinymce to not bork
    var tinyCount = document.getElementById("tinyCount").value;
    var add = "1";
    
    //setup textarea to change
    var newTextareaID = id + tinyCount;
    var newTextareaInnerHTML = "<textarea id='" + newTextareaID + "' cols='" + cols + "' rows='" + rows + "'></textarea>";
    //fix for tinymce to not kick off on subject
    if(includeTiny != "false")
    {
      newTextareaInnerHTML += "<a href=\"javascript:toggleEditor('" + newTextareaID + "');\">[Toggle Editor On/Off]</a>";
      newTextareaInnerHTML += "&nbsp;&nbsp;<a href=\"javascript:;\" onmousedown=\"alert(tinyMCE.get('" + newTextareaID + "').getContent());\">[Get contents]</a>";
  		newTextareaInnerHTML += "&nbsp;&nbsp;<a href=\"javascript:;\" onmousedown=\"alert(tinyMCE.get('" + newTextareaID + "').selection.getContent());\">[Get selected HTML]</a>";
  		newTextareaInnerHTML += "&nbsp;&nbsp;<a href=\"javascript:;\" onmousedown=\"alert(tinyMCE.get('" + newTextareaID + "').selection.getContent({format : 'text'}));\">[Get selected text]</a>";
    }
   
    //setup image to change -- onclick send the ID to snag the innerHTML, and the column it will be editting
    var imgIDToChange = document.getElementById(imgID);
    var imgSetupSave = "<img src='images/save-small.png' style=\"cursor: pointer;\" onclick=\"saveEmailLayout('" + newTextareaID + "','" + dbColumn + "','" + includeTiny + "')\" />";
    
    var divSetupCancel = document.getElementById(imgID + "Cancel");
    var imgSetupCancel = "<img src=\"images/cancel.png\" style=\"cursor: pointer;\" onclick=\"cancelChanges()\" />";
    
    //check to see if another is opened already
    if(check.value == "1")
    {
      stopProcess = true;
      alert("You can only edit one at a time.");
    }
    
    if(!document.getElementById(newTextareaID) && !stopProcess)
    {
      //set value of checkIfOpen to 1 so we know there is one being modified already
      check.value = "1";
      
      //switch the html to the textarea
      divIDToChange.innerHTML = newTextareaInnerHTML;
      
      //query innerHTML
      //fix for tinymce to not kick off on subject
      if(includeTiny != "false")
      { 
        updateTextArea(dbColumn,newTextareaID,currentTemplateID.value);
      } else {
        updateTextAreaWithoutTiny(dbColumn,newTextareaID,currentTemplateID.value);
      }
      
      //update done, change tinyCount
      document.getElementById("tinyCount").value = parseInt(tinyCount)+parseInt(add);
      
      //change button to update
      imgIDToChange.innerHTML = imgSetupSave;
      divSetupCancel.innerHTML = imgSetupCancel;
    } 
  } catch(e) {
    alert(e);
  }
}

function cancelChanges()
{
  if(confirm("Are you sure you want to discard your changes?"))
  {
    initialEmailLayout();
  }
}

function updateTextAreaWithoutTiny(dbColumn, idToFill, id)
{
  var can1 = idToFill.substring(0,idToFill.length-1);
  var can2 = idToFill.substring(0,idToFill.length-2);
  var stopIf = "subjectAjaxResult";

  $.post( "views/custommessages_handle.php", {
    id: id,
    dbColumn: dbColumn,
    getInformation: true
  }).done(function( data ) {
    document.getElementById(idToFill).value = data;
  });
}

function updateTextArea(dbColumn, idToFill, id)
{
  var can1 = idToFill.substring(0,idToFill.length-1);
  var can2 = idToFill.substring(0,idToFill.length-2);
  var stopIf = "subjectAjaxResult";



  $.post( "views/custommessages_handle.php", {
    id: id,
    dbColumn: dbColumn,
    getInformation: true
  }).done(function( data ) {
    document.getElementById(idToFill).value = data;

    tinyMCE.init({
      mode : "textareas",
      theme : "advanced",
      plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

      theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,search,replace,|,outdent,indent,blockquote,|,undo,redo,styleselect,formatselect,fontselect,fontsizeselect",
      theme_advanced_buttons2 : "",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      theme_advanced_resizing : false,

      content_css : "regadmin.css",

      template_external_list_url : "tinymce/exmamples/lists/template_list.js",
      external_link_list_url : "tinymce/exmamples/lists/link_list.js",
      external_image_list_url : "tinymce/exmamples/lists/image_list.js",
      media_external_list_url : "tinymce/exmamples/lists/media_list.js",

      style_formats : [
        {title : 'Black text', inline : 'span', styles : {color : '#000000'}},
        {title : 'Pink text', inline : 'span', styles : {color : '#FF00FF'}},
        {title : 'Blue text', inline : 'span', styles : {color : '#3366FF'}},
        {title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
        {title : 'Bold text', inline : 'b'},
        {title : 'Table styles'},
        {title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
      ],

      template_replace_values : {
      }
    });

  });
}

function toggleEditor(id) {
  if (!tinyMCE.get(id))
    tinyMCE.execCommand('mceAddControl', false, id);
  else
    tinyMCE.execCommand('mceRemoveControl', false, id);
}

function saveEmailLayout(newTextareaID, dbColumn, includeTiny)
{
  var newInnerHTML = document.getElementById(newTextareaID).value;
  if(includeTiny != "false") {
    info = tinyMCE.get(newTextareaID).getContent();
  } else {
    info = newInnerHTML;
  }

  try {
    if (!document.getElementById("currentTemplate")) {
    } else {
      current = document.getElementById("currentTemplate").value;
    }
  } catch(e) {
    alert(e);
  }

  $.post( "views/custommessages_handle.php", {
    newInnerHTML: info,
    current: current,
    dbColumn: dbColumn,
    setNewEmailLayout: true
  }).done(function( data ) {
    initialEmailLayout();
  });
}

function sendEmail(regid)
{
  document.getElementById("btn" + regid).innerHTML = "<label>Processing</label>";
  try {
    if (!document.getElementById("currentTemplate")) {
    } else {
      template = document.getElementById("currentTemplate").value;
    }
  } catch(e) {
    alert(e);
  }

  $.post( "views/custommessages_handle.php", {
    regid: regid,
    template: template,
    sendEmail: true
  }).done(function( data ) {
    document.getElementById("viewError").style.display = "block";
    document.getElementById("viewError").innerHTML = data;
    document.getElementById("btn" + regid).innerHTML = "<label>Sent</label>";
  });
}

function sendEmailAll(address) {
  var template = document.getElementById("currentTemplate").value;
  document.getElementById("btn" + address).innerHTML = "<label>Processing</label>";

  $.post( "views/custommessages_handle.php", {
    address: address,
    template: template,
    sendEmailAll: true
  }).done(function( data ) {
    document.getElementById("viewError").style.display = "block";
    document.getElementById("viewError").innerHTML = data;
    document.getElementById("btn" + address).innerHTML = "<label>Sent</label>";
  });
}

function saveHeader() {
  document.getElementById("headerAjaxResult").innerHTML = "Processing...";
  var newHeader = document.getElementById("newHeader").value;

  $.post( "views/custommessages_handle.php", {
    newHeader: newHeader,
    changeHeader: true
  }).done(function( data ) {
    document.getElementById("headerAjaxResult").innerHTML = data;
  });
}

function resetCustomMessageBody()
{
  $.post( "views/custommessages_handle.php", {
    resetCustomMessageBody: true
  }).done(function( data ) {
    window.location.reload();
  });
}

function resetReceived()
{
  $.post( "views/custommessages_handle.php", {
    resetReceived: true
  }).done(function( data ) {
    window.location.reload();
  });
}

function massEmailAll()
{
  var template = document.getElementById("currentTemplate").value;

  if(confirm("Are you sure you want to send an e-mail to this list?"))
  {
    document.getElementById("mass").innerHTML = "Processing... This may take a few seconds.";
    $.post( "views/custommessages_handle.php", {
      template: template,
      massEmailDoAll: true
    }).done(function( data ) {
      document.getElementById("mass").innerHTML = data;
    });
  } else {
    return false;
  }
}

function massEmail(list)
{
  var template = document.getElementById("currentTemplate").value;

  if(confirm("Are you sure you want to send an e-mail to this list?"))
  {
    document.getElementById("mass").innerHTML = "Processing... This may take a few seconds.";

    $.post( "views/custommessages_handle.php", {
      template: template,
      list: list,
      massEmailDo: true
    }).done(function( data ) {
      document.getElementById("mass").innerHTML = data;
    });
  } else {
    return false;
  }
}