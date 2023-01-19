
const pswdButton = document.getElementById("pswd_button");

pswdButton.addEventListener("click", (evt) => {
	evt.preventDefault();
	window.location.href = "/forgot-password";
}); 

// Get the modal
const modal = document.getElementById("delete-Modal");

// Get the button that opens the modal
const suppButton = document.getElementById("supp_button");

const confirmButton =  document.getElementById("confirm_button");

const cancelButton =  document.getElementById("cancel_button");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[1];

console.log(span)
// When the user clicks the button, open the modal 
suppButton.onclick = function() {
 	modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
	console.log("oui");
  	modal.style.display = "none";
}

// When the user clicks on "Non" button in the modal, close the modal
cancelButton.onclick = function(e) {
	e.preventDefault();
	modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}