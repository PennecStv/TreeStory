/**
 * This file is the main page sliders script.
 * 
 * @author  Rudy Boullier    <rudy.boullier@etu.univ-lyon1.fr>
 */


let slideIndex = 1;
showSlides(slideIndex);

/**
 * Changes the position of the slider using the arrow.
 * 
 * @param   int  n     Slider position. 
 */
function plusSlides(n) {
  showSlides(slideIndex += n);
}

/**
 * Changes the position of the slider using the dots.
 * 
 * @param   int  n     Slider position. 
 */
function currentSlide(n) {
  showSlides(slideIndex = n);
}

/**
 * Displays the slide that is requested.
 * 
 * @param   int  n     Slider position. 
 */
function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("slides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {
    slideIndex = 1
  }
  if (n < 1) {
    slideIndex = slides.length
  }
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
}



/**
 * Move the slide to the left.
 */
function sliderLeft() {
  let slides = document.getElementsByClassName("itemContainer");
  let slidesTmp = slides[slides.length-1].cloneNode(true);

  slides[0].parentNode.insertBefore(slidesTmp, slides[0]);
  slides[slides.length-1].parentNode.removeChild(slides[slides.length-1]);
}

/**
 * Move the slide to the Right.
 */
function sliderRight() {
  let slides = document.getElementsByClassName("itemContainer");
  let slidesTmp = slides[0].cloneNode(true);

  slides[0].parentNode.appendChild(slidesTmp);
  slides[slides.length-1].parentNode.removeChild(slides[0]);
}