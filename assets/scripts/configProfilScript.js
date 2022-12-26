//Get the reset Password Button
const resetPasswordButton = document.getElementById("pswd_button");

// Get the modal
const modal = document.getElementById("delete-Modal");

// Get the button that opens the modal
const suppButton = document.getElementById("supp_button");

const confirmButton = document.getElementById("confirm_button");

const cancelButton = document.getElementById("cancel_button");

// Get the <span> element that closes the modal
const span = document.getElementsByClassName("close")[0];


resetPasswordButton.addEventListener("click", function () {
	location.href = "/resetPassword";
});


// When the user clicks the button, open the modal 
suppButton.onclick = function () {
	modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
	modal.style.display = "none";
}

// When the user clicks on "Non" button in the modal, close the modal
cancelButton.onclick = function () {
	modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
	if (event.target == modal) {
		modal.style.display = "none";
	}
}