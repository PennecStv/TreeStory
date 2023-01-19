/*
Listen to the click event on the like button
*/

const boutonLike = document.getElementsByClassName('like-chapter')[0];
const author = document.getElementById('comments');

boutonLike.addEventListener('click', function(e) {
    let baseURL = '/story/chapter/'+e.target.attributes['story-id'].value;
    
    if(boutonLike.innerHTML.split("&nbsp;")[1] == "J'aime"){
        url = baseURL+'/like';
    }else if(boutonLike.innerHTML.split("&nbsp;")[1] == "Je n'aime plus"){
        url = baseURL+'/dislike';
    }

    fetch(url, {
        method: 'POST'
    }).then(function(response) {
        let val = boutonLike.innerHTML.split("&nbsp;")[1];
        if(val == "J'aime"){
            boutonLike.innerHTML = '<i class="fas fa-thumbs-up"></i>&nbsp;Je n\'aime plus';
        }else if(val == "Je n'aime plus"){
            boutonLike.innerHTML = '<i class="fas fa-thumbs-up"></i>&nbsp;J\'aime';
        }
    });
});

/*
retrieve the form element
*/
const form = document.getElementById("form-commenter");

/**
 * Listen to the submit event on the form
 */
form.addEventListener("submit", function(event) {
    event.preventDefault(); // empêche le navigateur de soumettre le formulaire
    let id = location.href.split("/chapter/")[1].split("/")[0];
    
    //retreive the data from the form
    const formElements = form.elements;
    const formData = new FormData();

    data = formElements[0].value;
    formData.append("comment", data);

    /*
    send the form data in the background using XMLHttpRequest
     */
    const xhr = new XMLHttpRequest();
    xhr.open("POST","/story/chapter/"+id+"/comment", true);
    xhr.onload = function() {
        if (xhr.status === 200) {
        console.log("Données soumises avec succès");
        } else {
            console.log("Erreur lors de la soumission des données");
        }
    };
    xhr.send(formData);
});


/**
 * Listen to the click event on the author in the comment section
 */
if(author){
    author.addEventListener('click', function(e) {
        const id = e.target.attributes['author'].value;
        if(id){
            window.location.href = '/user/'+id;
        }
    });
}

/**
 * Listen to the click event on the comment button
 */
document.getElementsByClassName("comment-chapter")[0].addEventListener('click', function(e) {
    console.log(document.getElementById('section-commentaire').style.display)
    if(document.getElementById('section-commentaire').style.display == "none"){
        document.getElementById('section-commentaire').style.display = "flex";
    }else{
        document.getElementById('section-commentaire').style.display = "none";
    }
});


