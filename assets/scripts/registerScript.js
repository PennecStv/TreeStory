const inputUserName        = document.getElementById("userName");
inputUserName.addEventListener("change", fUserName);

const inputUserPassword    = document.getElementById("userPassword");
inputUserPassword.addEventListener("change", fUserPassword);

const inputconfirmPassword = document.getElementById("confirmUserPassword");
inputconfirmPassword.addEventListener("change", fConfirmPassword);

const inputUserMail        = document.getElementById("userMail");
inputUserMail.addEventListener("change", fUserMail);



function fUserName(){
    let userName = inputUserName.value;
    
    /**
     * Already existing userName (Use PHP)
     */

}


function fUserPassword(){
    let userPassword = inputUserPassword.value;
    
    let regexPswd = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})");

    console.log(regexPswd.test(userPassword));

    if (!regexPswd.test(userPassword)){
        console.log(userPassword);
        
        document.getElementById("errorPassword").innerHTML = "<i class='fas fa-info-circle'></i> <span>Votre mot de passe ne respecte pas les conditions requises.</span>";
    } else {
        document.getElementById("errorPassword").innerHTML = "";
    }
}


function fConfirmPassword(){
    let userPassword    = inputUserPassword.value;
    let confirmPassword = inputconfirmPassword.value;
    let errorMessage = document.getElementById("errorPassword");
    
    if (userPassword !== confirmPassword){
        if (errorMessage.innerHTML.length == 0){
            console.log(errorMessage.innerHTML.length);
            errorMessage.innerHTML = "<i class='fas fa-info-circle'></i> <span>Les mots de passe doivent être identiques.</span>";
        }
    } else {
        errorMessage.innerHTML = "";
    }
}


function fUserMail(){
    let userMail = inputUserMail.value;
    
    let regexMail = new RegExp("([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\"\(\[\]!#-[^-~ \t]|(\\[\t -~]))+\")@([!#-'*+/-9=?A-Z^-~-]+(\.[!#-'*+/-9=?A-Z^-~-]+)*|\[[\t -Z^-~]*])");

    if (!regexMail.test(userMail)){
        
        document.getElementById("errorPassword").innerHTML = "<i class='fas fa-info-circle'></i> <span>Votre E-mail n'est pas valide.</span>";
    } else {
        document.getElementById("errorPassword").innerHTML = "";
    }

}