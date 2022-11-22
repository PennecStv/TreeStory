/**
 * This file represents the scrpit of the reset
 * password form.
 * 
 * @author  Idrissa Sall   <idrissa.sall@etu.univ-lyon1.fr>
 */


/* password restrictions.
*/
const regexMDP = new RegExp(/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!§*$@%_£ù#])([-+§!*$@%_£ù#\w]{8,})$/);


/**
 * Listener for the submit form.
 */
onsubmit = ()=>{
    if (!fPass()) {
        return false;
    }
}


/**
 * this function checks the constraints on the 
 * password and compares the two passwords.
 * 
 * @return false|true  
 */
function fPass(){
    let mdp1 = document.getElementById('new-mdp').value;
    let mdp2 = document.getElementById('new-mdp-2').value;
    if((regexMDP.test(mdp1)) && mdp1 === mdp2){
        return true
    }
    return false;
    
}


(function(){
    document.getElementById('new-mdp').addEventListener("change",function(){
        let mdp = document.getElementById('new-mdp').value;
        document.getElementById('help-new-mdp').innerHTML = (!regexMDP.test(mdp) ? "Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial." : "");
        document.getElementById('new-mdp').style.border= (!regexMDP.test(mdp) ? "2px solid red" : "2px solid green")
    });

    document.getElementById('new-mdp-2').addEventListener("change",function(){
        document.getElementById('help-new-mdp-2').innerHTML = (!fPass() ? "Les deux mots de passe ne sont pas identiques." : "");
        document.getElementById('new-mdp-2').style.border= (!fPass() ? "2px solid red" : "2px solid green")
    });
  
})();