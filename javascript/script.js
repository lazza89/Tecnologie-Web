const CHECK_PASSWORD = /^[A-Z\d]{1,20}$/i;
const CHECK_USERNAME = /^[A-Z\d]{1,20}$/i;
const CHECK_EMAIL = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const CHECK_NAME_AND_SURNAME = /^[A-Z ]{2,30}$/i;
const CHECK_CITY = /^[A-Z ùèàéòì]{2,40}$/i;
const CHECK_COMMENT = /^[A-Z\d,.èéù'ì% àò\n!?() ]{1,300}$/i;


window.onload = function () {

  if (document.getElementById("loginForm") != null) {
    items = [
      "LUsername",
      "LPassword"
    ];
    items.forEach(function (item) {
      document.getElementById(item).addEventListener("blur", checkLogin);
      document.querySelector('button').addEventListener("blur", checkLogin);
    });
  }

  if (document.getElementById("registerForm") != null) {
      items = [
        "REmail",
        "RUsername",
        "RName",
        "RSurname",
        "RCity",
        "RPassword",
        "RPasswordRepeat"
      ];
      items.forEach(function (item) {
        document.getElementById(item).addEventListener("blur", checkRegister);
        document.querySelector('button').addEventListener("blur", checkRegister);
      });
  }

  if (document.getElementById("personalAreaForm") != null) {
    items = [
      "PEmail",
      "PUsername",
      "PName",
      "PSurname",
      "PCity",
      "POldPassword",
      "PPassword",
      "PRPassword"
    ];
    items.forEach(function (item) {
      document.getElementById(item).addEventListener("blur", checkPersonalArea);
      document.querySelector('button').addEventListener("blur", checkPersonalArea);
    });
  }

  if (document.getElementById("commentForm") != null) {
    document.getElementById("commentBox").addEventListener("blur", checkComment);
    document.querySelector('button').addEventListener("blur", checkComment);
  }

}

function checkComment(){
  const button = document.querySelector('button');
  var comment = document.getElementById("commentBox").value;
  console.log(comment);
 
  if(checkInput(comment, "commentERR", "Commento non conforme, per il commento non si possono usare certi tipi di caratteri speciali", CHECK_COMMENT)){
    document.querySelector('button').style["background-color"] = "red";
    button.disabled = true;
  }else{
    document.querySelector('button').style["background-color"] = "#336ef0";
    button.disabled = false;
  }

}

function checkLogin(){
  const button = document.querySelector('button');
  var i = 0;

  var username = document.getElementById("LUsername").value;
  var password = document.getElementById("LPassword").value;

  i += checkInput(username, "loginUsernameERR", "Username non conforme", CHECK_USERNAME);
  i += checkInput(password, "loginPasswordERR", "Password non conforme", CHECK_PASSWORD);

  if(i > 0){
    document.querySelector('button').style["background-color"] = "red";
    button.disabled = true;
  }else{
    document.querySelector('button').style["background-color"] = "#336ef0";
    button.disabled = false;
  }
}

function checkRegister(){
  const button = document.querySelector('button');
  var i = 0;

  var mail = document.getElementById("REmail").value;
  var username = document.getElementById("RUsername").value;
  var name = document.getElementById("RName").value;
  var surname = document.getElementById("RSurname").value;
  var city = document.getElementById("RCity").value;
  var password = document.getElementById("RPassword").value;
  var RPassword = document.getElementById("RPasswordRepeat").value;

  i += checkInput(mail, "registerEmailERR", "Email non conforme, la mail deve essere in formato: testo@dominio.nomedominio", CHECK_EMAIL);
  i += checkInput(username, "registerUsernameERR", "Username non conforme, per l'username si possono usare solo caratteri alfanumerici ", CHECK_USERNAME);
  i += checkInput(name, "registerNameERR", "Nome non conforme, per il nome si possono usare solo caratteri alfabetici ", CHECK_NAME_AND_SURNAME);
  i += checkInput(surname, "registerSurnameERR", "Cognome non conforme, per ll cognome si possono usare solo caratteri alfabetici ", CHECK_NAME_AND_SURNAME);
  i += checkInput(city, "registerCityERR", "Città non conforme, per la città si possono usare solo caratteri alfabetici ", CHECK_CITY);
  i += checkInput(password, "registerPasswordERR", "Password non conforme, per la password si possono usare solo caratteri alfanumerici ", CHECK_PASSWORD);
  i += checkRepetedPassword(password, RPassword, "registerRPasswordERR", "Le password non combaciano");

  if(i > 0){
    document.querySelector('button').style["background-color"] = "red";
    button.disabled = true;
  }else{
    document.querySelector('button').style["background-color"] = "#336ef0";
    button.disabled = false;
  }
}

function checkPersonalArea(){
  const button = document.querySelector('button');
  var i = 0;

  var mail = document.getElementById("PEmail").value;
  var username = document.getElementById("PUsername").value;
  var name = document.getElementById("PName").value;
  var surname = document.getElementById("PSurname").value;
  var city = document.getElementById("PCity").value;
  var password = document.getElementById("POldPassword").value;
  var RPassword = document.getElementById("PPassword").value;
  var RepeatPassword = document.getElementById("PRPassword").value;

  i += checkInput(mail, "PAreaEmailERR", "Email non conforme, la mail deve essere in formato: testo@dominio.nomedominio", CHECK_EMAIL);
  i += checkInput(username, "PAreaUsernameERR", "Username non conforme, per l'username si possono usare solo caratteri alfanumerici ", CHECK_USERNAME);
  i += checkInput(name, "PAreaNameERR", "Nome non conforme, per il nome si possono usare solo caratteri alfabetici ", CHECK_NAME_AND_SURNAME);
  i += checkInput(surname, "PAreaSurnameERR", "Cognome non conforme, per ll cognome si possono usare solo caratteri alfabetici ", CHECK_NAME_AND_SURNAME);
  i += checkInput(city, "PAreaCityERR", "Città non conforme, per la città si possono usare solo caratteri alfabetici ", CHECK_CITY);
  i += checkInput(password, "PAreaPasswordERR", "Password non conforme, per la password si possono usare solo caratteri alfanumerici ", CHECK_PASSWORD);
  i += checkInput(RPassword, "PAreaRPasswordERR", "Password non conforme, per la password si possono usare solo caratteri alfanumerici ", CHECK_PASSWORD);
  i += checkRepetedPassword(RPassword, RepeatPassword, "PAreaRepeatPasswordERR", "Le password non combaciano");


  if(i > 0){
    document.querySelector('button').style["background-color"] = "red";
    button.disabled = true;
  }else{
    document.querySelector('button').style["background-color"] = "#336ef0";
    button.disabled = false;
  }
}

function checkInput(inputType, errorID, failureText, RE){
  if(inputType == ""){
    document.getElementById(errorID).innerHTML = "";
  }else if(!RE.test(inputType)){
      document.getElementById(errorID).innerHTML = failureText;
      return 1;
  }else{
    document.getElementById(errorID).innerHTML = "";
    return 0;
  }

  return 0;
}

function checkRepetedPassword(password, RPassword, errorID, failureText){
  if(password != "" && RPassword != ""){
    if(password != RPassword){
      document.getElementById(errorID).innerHTML = failureText;
      return 1;
    }else{
      document.getElementById(errorID).innerHTML = "";
      return 0;
    }
  }else{
    document.getElementById(errorID).innerHTML = "";
  }
  return 0;
}



