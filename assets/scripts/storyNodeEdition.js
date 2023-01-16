const deleteChapter = document.querySelector(".delete-chapter");

/**
 * Listen to the click event on the delete chapter button
 */
deleteChapter.addEventListener("click", function() {
    if(confirm("Voulez vous vraiment supprimer supprimer ce chaptitre ?\nAttention ! Cette action est irréversible.") ) {
        let url = '/story/'+location.href.split("/story/")[1].split("/edit")[0]+'/delete';
        fetch(url, {
            method: 'POST'
        }).then(function(response) {
            alert("Le chapitre a bien été supprimé !");
            window.history.back();
        });
    }
});