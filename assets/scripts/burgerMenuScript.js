/**
 * This file is the script of burger menu in the header.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */

const burgerIcon = document.querySelector(".fa-icon")
const burgerMenu = document.querySelector(".burgerMenu")
const navLinks = document.querySelector(".navLinks")
 
burgerMenu.addEventListener('click', () => {
  navLinks.classList.toggle('mobileMenu')
  burgerIcon.classList.toggle('fa-xmark')
})