/**
 * Listen to the click event on the follow button
 */
document.getElementsByClassName('follow-button')[0].addEventListener('click', function(e) {
    
    let url = null;

    if(document.getElementsByClassName('follow-button')[0].innerHTML == "Suivre"){
        url = '/user/'+e.target.attributes['user-id'].value+'/follow';
    }else if(document.getElementsByClassName('follow-button')[0].innerHTML == "Ne plus suivre"){
        url = '/user/'+e.target.attributes['user-id'].value+'/unfollow';
    }
    fetch(url, {
        method: 'POST'
    }).then(function(response) {
        let val = document.getElementsByClassName("follow-button")[0].innerHTML;

        if(val == "Suivre"){
            document.getElementsByClassName("follow-button")[0].innerHTML = "Ne plus suivre";
        }else if(val == "Ne plus suivre"){
            document.getElementsByClassName("follow-button")[0].innerHTML = "Suivre";
        }
    }); 
});
