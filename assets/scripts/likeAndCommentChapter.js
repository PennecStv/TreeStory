/*
Listen to the click event on the like button
*/

const boutonLike = document.getElementsByClassName('like-chapter')[0];

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