/**
 * This file is the account menu script in the header when the user is logged.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */

const accountButton = document.getElementsByClassName("accountAvatar")[0];
const accountSousMenuButton = document.getElementsByClassName("submenu")[0];

accountButton.addEventListener('click', () => {
  accountSousMenuButton.classList.toggle('close');
})