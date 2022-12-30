/**
 * This file is the account menu script in the header when the user is logged.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */

const accountButton = document.querySelector(".accountAvatar");
const accountSousMenuButton = document.querySelector(".submenu");

accountButton.addEventListener('click', () => {
  accountSousMenuButton.classList.toggle('close');
})