function paymentHandle() {
  var payment = document.getElementById("paymentCheck").value;
  var method = document.getElementById("method");
          
  var check = "<br /><label class='l3'>Make Check Payable to Bascomb <br /> UMC (subject:scrapbook crop)</label><p class='important'>Please send payment to:<br />Susan Austin<br />Bascomb UMC<br />6021 Hollow Dr.<br />Woodstock, Ga 30189</p>";
  var paypal = "<p class='important' style='text'>You will be able to pay once you complete<br /> registration.</p>";
  if (payment != "")
  {
    if (payment == "paypal")
    {
      method.innerHTML = paypal;
    }
    if (payment == "check")
    {
      method.innerHTML = check;
    }
  }
  if (payment == "")
  {
    method.innerHTML = "";
  }
}

function onLoadFunction()
{
  var paybyValue = document.getElementById("paybyValue").value;
  var returningguestValue = document.getElementById("returningguestValue").value;
  var typeOfFoodValue = document.getElementById("typeOfFoodValue").value;
  if (paybyValue != "")
  {
    document.getElementById("paymentCheck").value = paybyValue;
  }
  if (returningguestValue != "")
  {
    document.getElementById("returningguestCheck").value = returningguestValue;
  }
  if (typeOfFoodValue != "")
  {
    document.getElementById("typeOfFoodCheck").value = typeOfFoodValue;
  }
  paymentHandle()
}

function registerCheckErrors()
{
  var errors = false;
  var msg = "";
  var e1 = document.getElementById("emailCheck").value;
  var e2 = document.getElementById("emailCheck2").value;
  var name = document.getElementById("nameCheck").value;
  var payment = document.getElementById("paymentCheck").value;

//  var foodCheck = document.getElementById("foodCheck").value;

  var typeOfFood = document.getElementById("typeOfFoodCheck");
  //Begin checking for errors in required feilds, if none, submit the document.
  if (name == "")
  {
    msg = msg + "Name is required.\n\r";
    errors = true;
  }
  if (name != "")
  {
    if (!isNaN(name)) {
      msg = msg + "Name contains number(s).\n\r";
      errors = true;
    }
  }
  if (e1 == "")
  {
    msg = msg + "Email address is required.\n\r";
    errors = true;
  }
  if (e1 != e2)
  {
    msg = msg + "Submitted emails do not match.\n\r";
    errors = true;
  }
  if (payment == "")
  {
    msg = msg + "Please select payment method.\n\r";
    errors = true;
  }
  
//  try
//  {
//    if (foodCheck != "" && typeOfFood.value == "")
//    {
//      typeOfFood.value = "Misc";
//    }
//  } catch(e) {}
  
  if (!errors) {
    $("#btn_register").prop("disabled", true);
    document.forms["registrationForm"].submit();
  } else {
    alert(msg);  
  }
}

function clearAll()
{
//  document.getElementById("emailCheck").value = "";
//  document.getElementById("emailCheck2").value = "";
//  document.getElementById("nameCheck").value = "";
//  document.getElementById("paymentCheck").value = "";
//  document.getElementById("streetaddressCheck").value = "";
//  document.getElementById("cszCheck").value = "";
//  document.getElementById("phoneCheck").value = "";
//  document.getElementById("paymentCheck").value = "";
//  document.getElementById("returningguestCheck").value = "No";
//  document.getElementById("heardaboutCheck").value = "";
//  document.getElementById("referredbyCheck").value = "";
//  document.getElementById("requestedtablebuddiesCheck").value = "";
//  document.getElementById("notetohostessCheck").value = "";
//  document.getElementById("foodCheck").value = "";
//  document.getElementById("sessionCheck").innerHTML = "";
//  paymentHandle();
}