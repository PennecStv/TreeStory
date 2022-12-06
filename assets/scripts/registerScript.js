/* === Constants and variables === */
//UserName
const inputUserName  = document.getElementById("userName");
inputUserName.addEventListener("focusout", FormValidation);

//Password
const inputUserPassword = document.getElementById("userPassword");
inputUserPassword.addEventListener("change", FormValidation);

//Confirmation Password
const inputconfirmPassword = document.getElementById("confirmUserPassword");
inputconfirmPassword.addEventListener("change", FormValidation);

//Mail field
const inputUserMail = document.getElementById("userMail");
inputUserMail.addEventListener("change", FormValidation);

//Register button
const registerButton = document.getElementById("register-button");

//Introduction text
const registerIntroTitle = document.getElementsByClassName("drop-in")[0];
const registerIntroText  = document.getElementsByClassName("drop-in-2")[0];
const registerIntroForm  = document.getElementsByClassName("drop-in-3")[0];

//LocalStorage
const storage = window.localStorage;

//Array containing the possible error in a specific order to show the user what he needs to fix first
const arrayError = [];

var result;

//Regular Expression for Mail
const regexMail = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");
//REgular Expression for Password
const regexPswd = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!§*$@%_£ù#])([-+§!*$@%_£ù#\w]{8,})$/);



/* === Functions === */

/**
 * This function manage the different errors that can occur in the register form
 */
function FormValidation(){
    let validUserName = false;
    let validPassword = false; 
    let validConfirm  = false;
    let validMail     = false;

    validUserName = fUserName();           //UserName is empty or not

    if (inputUserPassword.value){
        validPassword = fUserPassword();   //Password is correct or not
    }    

    if (inputconfirmPassword.value){
        validConfirm = fConfirmPassword(); //Confirm password is correct or not
    }

    if (inputUserMail.value){
        validMail = fUserMail();           //Mail is correct or not
    }

    registerButton.disabled = !(validUserName && validPassword && validConfirm && validMail);

}


/**
 * Verify if the password follows the regex
 * @returns boolean result where true when valid, false when error
 */
 function fUserName(){
    let userName = inputUserName.value;

    if (userName === ""){
        //Put the error in the right index of arrayError
        arrayError[3] = "<i class='fas fa-info-circle'></i> <span>Veuillez choisir un identifiant.</span>";
        inputUserName.style.border = "2px solid red";
        result = false;

    } else {
        //Remove the error of arrayError if the userName field is filled
        arrayError.splice(3, 1);
        inputUserName.style.border = "2px solid green";
        result = true;
    }
    
    //console.log(arrayError);
    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1); //Show the corresponding error if arrayError is not empty
    return result;
}


/**
 * Verify if the password follows the regex
 * @returns boolean result where true when valid, false when error
 */
function fUserPassword(){
    let userPassword = inputUserPassword.value;

    if (!regexPswd.test(userPassword)){
        //Put the error in the right index of arrayError
        arrayError[2] = "<i class='fas fa-info-circle'></i> <span>Votre mot de passe ne respecte pas les conditions requises.</span>";
        inputUserPassword.style.border = "2px solid red";
        result = false;

    } else {
        //Remove the error of arrayError if the password is correct
        arrayError.splice(2, 1);
        inputUserPassword.style.border = "2px solid green";
        result = true;
    }
    
    //console.log(arrayError);
    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1); //Show the corresponding error if arrayError is not empty
    return result;
}


/**
 * Verify if the confirm password is the same as the password typed above
 * @returns boolean result where true when valid, false when error
 */
function fConfirmPassword(){
    let userPassword    = inputUserPassword.value;
    let confirmPassword = inputconfirmPassword.value;

    if (userPassword !== confirmPassword){
        //Put the corresponding error in the right index of arrayError
        arrayError[1] = "<i class='fas fa-info-circle'></i> <span>Les mots de passe doivent être identiques.</span>";
        inputconfirmPassword.style.border = "2px solid red";
        result = false;

    } else {
        //Remove the error of arrayError if the password and the confirm one match
        arrayError.splice(1, 1);
        inputconfirmPassword.style.border = "2px solid green";
        result = true;
    }

    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1);
    return result;
}


/**
 * Verify if the mail follows the regex
 * @returns boolean result where true when valid, false when error
 */
function fUserMail(){
    let userMail = inputUserMail.value;
    storage.setItem("mail", userMail);
    
    if (!regexMail.test(userMail)){
        //Put the corresponding error in the right index of arrayError
        arrayError[0] = "<i class='fas fa-info-circle'></i> <span>Votre E-mail n'est pas valide.</span>";
        inputUserMail.style.border = "2px solid red";
        result = false;

    } else {
        //Remove the error of arrayError if the mail is correct
        arrayError.splice(0, 1);
        inputUserMail.style.border = "2px solid green";
        result = true;
    }

    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1);
    return result;
}


/* === LocalStorage === */
//Retrieve the data when the page refresh
inputUserName.addEventListener("input", function(){
    storage.setItem("user", inputUserName.value);
});

inputUserName.addEventListener("input", function(){
    storage.setItem("mail", inputUserMail.value);
});

inputUserName.value = storage.getItem("user");
inputUserMail.value = storage.getItem("mail");

if (storage.key(1) !== null){
    FormValidation();
}


//Clear the storage when the user has registered
registerButton.addEventListener('click', function(){
    storage.clear();
});

//Prevent CSS animation when the page is reload
if (performance.body.type === performance.body.TYPE_RELOAD) {
    registerIntroTitle.classList.remove("drop-in");
    registerIntroText.classList.remove("drop-in-2");
    registerIntroForm.classList.remove("drop-in-3");
    console.log("This page is reloaded");
};