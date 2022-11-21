const inputUserName  = document.getElementById("userName");
inputUserName.addEventListener("change", fUserName);


const inputUserPassword = document.getElementById("userPassword");
inputUserPassword.addEventListener("change", FormValidation);


const inputconfirmPassword = document.getElementById("confirmUserPassword");
inputconfirmPassword.addEventListener("change", FormValidation);


const inputUserMail = document.getElementById("userMail");
inputUserMail.addEventListener("change", FormValidation);


const registerButton = document.getElementById("register-button");


const arrayError = [];


var result;



/**
 * 
 */
function FormValidation(){
    let validPassword = false;
    let validConfirm  = false;
    let validMail     = false;

    if (inputUserPassword.value){
        validPassword = fUserPassword();
    }    

    if (inputconfirmPassword.value){
        validConfirm = fConfirmPassword();
    }

    if (inputUserMail.value){
        validMail = fUserMail();
    }

    registerButton.disabled = !(validPassword && validConfirm && validMail);

}


/**
 * 
 */
function fUserName(){
    let userName = inputUserName.value;
    
    /**
     * Already existing userName (Use PHP)
     */

}


/**
 * 
 * @returns 
 */
function fUserPassword(){
    let userPassword = inputUserPassword.value;
    
    let regexPswd = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

    if (!regexPswd.test(userPassword)){
        arrayError[2] = "<i class='fas fa-info-circle'></i> <span>Votre mot de passe ne respecte pas les conditions requises.</span>";
        result = false;

    } else {
        arrayError.splice(2, 1);
        result = true;
    }
    
    console.log(arrayError);
    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1);
    return result;
}


/**
 * 
 * @returns 
 */
function fConfirmPassword(){
    let userPassword    = inputUserPassword.value;
    let confirmPassword = inputconfirmPassword.value;

    if (userPassword !== confirmPassword){
        arrayError[1] = "<i class='fas fa-info-circle'></i> <span>Les mots de passe doivent être identiques.</span>";
        result = false;

    } else {
        arrayError.splice(1, 1);
        result = true;
    }

    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1);
    return result;
}


/**
 * 
 * @returns 
 */
function fUserMail(){
    let userMail = inputUserMail.value;
    
    let regexMail = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");
    
    
    if (!regexMail.test(userMail)){
        arrayError[0] = "<i class='fas fa-info-circle'></i> <span>Votre E-mail n'est pas valide.</span>";
        result = false;

    } else {
        arrayError.splice(0, 1);
        result = true;
    }

    document.getElementById("errorMessage").innerHTML = arrayError.slice(-1);
    return result;
}