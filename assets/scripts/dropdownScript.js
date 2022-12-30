/**
 * This file is the script of drop down menu in the account page.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */

const icon = document.querySelector(".fas");
const modif = document.querySelector(".modifStoryTitle");
const modifStoryContent = document.querySelector(".modifStoryContent");
 
modif.addEventListener('click', () => {
  icon.classList.toggle('fa-chevron-down');
  icon.classList.toggle('fa-chevron-up');
  modifStoryContent.classList.toggle('close');
})