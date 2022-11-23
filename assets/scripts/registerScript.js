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

const regexMail = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");
const regexPswd = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!§*$@%_£ù#])([-+§!*$@%_£ù#\w]{8,})$/);


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
    
    //let regexPswd = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

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
    
    //let regexMail = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");
    
    
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


(function(){
    inputUserName.addEventListener("change",function(){
        let id = inputUserName.value;
        inputUserName.style.border= (id === "" ? "2px solid red" : "2px solid green")
    });

    inputUserPassword.addEventListener("change",function(){
        let mdp = inputUserPassword.value;
        inputUserPassword.style.border= (!regexPswd.test(mdp) ? "2px solid red" : "2px solid green")
    });

    inputconfirmPassword.addEventListener("change",function(){
        inputconfirmPassword.style.border= (!fConfirmPassword() ? "2px solid red" : "2px solid green")
    });

    inputUserMail.addEventListener("change",function(){
        let mel = inputUserMail.value;
        inputUserMail.style.border= (!regexMail.test(mel) ? "2px solid red" : "2px solid green")
    });
  
})();