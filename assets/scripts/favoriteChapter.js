/**
 * Listen to the click event on the favorite button
 */

const buttonFav = document.getElementsByClassName('favorite-chapter')[0];

if (buttonFav) buttonFav.addEventListener('click', function(e) {

    let baseURL = '/story/chapter/'+e.target.attributes['story-node-id'].value;

    let attribute = buttonFav.getAttribute("aria-label");

    if (attribute == "unfavorite") {
        url = baseURL+'/favorite';
        buttonFav.setAttribute("aria-label", "favorite")
    } else {
        url = baseURL+'/unfavorite';
        buttonFav.setAttribute("aria-label", "unfavorite")
    }
    
    fetch(url, {
        method: 'POST'
    }).then(function(response) {
        if (attribute == "unfavorite") {
            buttonFav.style.color = "#1C86EE";
        } else {
            buttonFav.style.color = "#000";
        }
    });

});