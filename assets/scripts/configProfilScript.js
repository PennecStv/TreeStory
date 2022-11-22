// Get the modal
var modal = document.getElementById("delete-Modal");

// Get the button that opens the modal
var suppButton = document.getElementById("supp_button");

var confirmButton =  document.getElementById("confirm_button");

var cancelButton =  document.getElementById("cancel_button");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
suppButton.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks on "Non" button in the modal, close the modal
cancelButton.onclick = function() {
    modal.style.display = "none";
  }

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}