/**
 * This file is the script of drop down menu in the account page.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 * @author  Idrissa Sall    <idrissa.sall@etu.univ-lyon1.fr>
 */

const icon = document.querySelector(".fas");
const modif = document.querySelector(".modifStoryTitle");
const modifStoryContent = document.querySelector(".modifStoryContent");

//constants for the list of subscribed and subscription
const userSubscribed = document.getElementById('subscribed-list');
const userSubscription = document.getElementById('subscription-list');

//constants for the menu of subscribed and subscription
const abonnes = document.getElementById("subscribed-menu");
const abonnements = document.getElementById("subscription-menu");
const subscribedList = document.getElementById("subscribed-list");
const subscriptionList = document.getElementById("subscription-list");

//listener for the button follow and unfollow
const statSubscribed = document.getElementById("statFollowers");
const statSubscriptions = document.getElementById("statFollowings");

if (modif) modif.addEventListener('click', () => {
  icon.classList.toggle('fa-chevron-down');
  icon.classList.toggle('fa-chevron-up');
  modifStoryContent.classList.toggle('close');
});


/**
 * listener for the stat of subscribed and subscription
 */
statSubscribed.addEventListener('click', () => {
  abonnes.click();
  document.getElementsByClassName("follow-option")[0].classList.toggle("follow-option-visible");
});


/**
 * listener for the stat of subscribed and subscription  
 */
statSubscriptions.addEventListener('click', function() {
  abonnements.click();
  document.getElementsByClassName("follow-option")[0].classList.toggle("follow-option-visible");

});


/**
 * listener for the menu of subscribed and subscription
 */
abonnes.addEventListener("click", function() {
  subscribedList.classList.add("active");
  subscriptionList.classList.remove("active");
  abonnes.getElementsByClassName("indicatorAbonnes")[0].style.display = "block";
  abonnements.getElementsByClassName("indicatorAbonnements")[0].style.display = "none";
});


/**
 * listener for the menu of subscribed and subscription
 */
abonnements.addEventListener("click", function() {
  subscribedList.classList.remove("active");
  subscriptionList.classList.add("active");
  abonnes.getElementsByClassName("indicatorAbonnes")[0].style.display = "none";
  abonnements.getElementsByClassName("indicatorAbonnements")[0].style.display = "block";
});


/**
 * listener for the list of subscribed and subscription
 */
if (userSubscribed) userSubscribed.addEventListener('click', function(e) {
  window.location.href = '/user/'+e.target.attributes['id-subscribed'].value;
});


/**
 * listener for the list of subscribed and subscription
 */
if (userSubscription) userSubscription.addEventListener('click', function(e) {
  window.location.href = '/user/'+e.target.attributes['id-subscription'].value;
});

