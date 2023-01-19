/**
 * This file is the script of burger menu in the header.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */

const burgerIcon = document.querySelector("#iconBurgerMenu")
const burgerMenu = document.querySelector("#eventBurgerMenu")
const navLinks = document.querySelector("#navLinks")
const burgerCloseSubmenu = document.querySelector("#burgerCloseSubmenu")
const accountAvatarButton = document.querySelector("#accountAvatarButton");
 
burgerMenu.addEventListener('click', () => {
  navLinks.classList.toggle('mobileMenu')
  burgerIcon.classList.toggle('fa-xmark')
  burgerCloseSubmenu.classList.toggle('close')
});

accountAvatarButton.addEventListener('click', () => {
  navLinks.classList.toggle('mobileMenu')
  burgerIcon.classList.toggle('fa-xmark')
  burgerCloseSubmenu.classList.toggle('close')
});