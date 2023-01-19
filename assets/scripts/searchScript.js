/**
 * This file represents the script of the search page
 * 
 * @author  Steve Pennec  <steve.pennec@etu.univ-lyon1.fr>
 */

/* === Constants and variables === */
//Story type buttons
const typeButtons = document.querySelectorAll("input");

//Button adding a searched type
const otherButton = document.getElementById("btn-other");

//Filter
const selectFilter = document.getElementById("filter-select");

//Sorter
const selectSort = document.getElementById("sort-select");

//The form containg the filter and the sorter
const formSearch = document.getElementById('form-search');

// The stories and chapters result of the page
const stories = document.getElementsByClassName("storyContent");

//Array containing types which are selected by the user
const activatedButton = [];


/* === Listener === */
/**
 * Will create a new input in order to add a new searched type
 */
otherButton.addEventListener('click', (e) => {
    e.preventDefault();
    var input = document.createElement("input");

    input.className    = "btn";
    input.name         = "other_type";
    input.autocomplete = "off";
    input.placeholder  = " ";
    input.type         = "text";

    //The created input will be automatically turn on the new searched type when the focus will be out
    input.addEventListener("focusout", (evt) => {
        input.type = "submit";
        evt.preventDefault(); //Transforming the input to a button then prevent the submit
        applyFilter(input);

        //When set on, this new input will be removed by clicking it
        input.addEventListener('click', (event) => {
            event.preventDefault();
            input.remove();
        });
        
        //If nothing has been written in the created input, it will be automatically removed
        if (input.value == ""){
            input.remove();
        }
    });
    //Adding the created input to the page and putting the focus on it
    document.getElementById("menu-type").appendChild(input);
    input.focus();
})

/**
 * Submit the form by changing the filter
 */
selectFilter.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});

/**
 * Submit the form by changing the sorter
 */
selectSort.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});

/**
 * Calling the function applyFilter() for all searching type buttons
 */
typeButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault(); //Prevent the submit
        applyFilter(button);
    })
});


/* === JQuery === */
/**
 * JQuery is used here for collecting all form entities that the page has and change their submit property
 */
$("#form-header").submit(function() {
    combineAndSendForms();
    return false;        // prevent default action
});


/* === Functions === */
/**
 * Using JQuery, it gathers all forms finded in the page to a single form and submit it
 */
function combineAndSendForms() {
    var $newForm = $("<form></form>")                // New from
        .attr({method : "POST", action : "/search"}) // New from attributes
    ;
    $(":input:not(:submit, :button)").each(function() {  // grab all inputs we need
        $newForm.append($("<input type=\"hidden\" />")   // create a new hidden field
            .attr('name', this.name)   // Keep the same name as the grabed input (Warning : make sure to avoid duplicates in the page)
            .val($(this).val())        // Keep the same value
        );
    });
    $newForm
        .appendTo(document.body)  // add the new form to the page but hidden
        .submit()                 // submit the form
    ;
}

/**
 * This function will sort the results according to the activated type search buttons
 * 
 * @param button  //A type search button
 */
function applyFilter(button){
    //Change the style of button as a toggle when clicked
    button.classList.toggle("btn");
    button.classList.toggle("btn-change");

    //Manage the array of activated button if it was already clicked or not
    if (activatedButton.includes(button)){
        var index = activatedButton.indexOf(button);
        activatedButton.splice(index, 1);
    } else {
        activatedButton.push(button);
    }

    //Going throught all stories showned by the page
    for (let i = 0; i < stories.length; i++) {
        let verif = true;

        /* WARNING : An error will occured if the structure of infoStory.twig and infoStoryNode.twig has been modified */
        let divInfoStory = stories[i].childNodes[3].childNodes;           //Get the div InfoStory
        let labelList = divInfoStory[divInfoStory.length - 4].childNodes; //Get the list of type of a story
        let k = 0;
        
        while (k < activatedButton.length){ //Going throught all activated button
            let is_present = false; 
            for (let j = 0; j < labelList.length; j++){ //Verify if the story has the type of the current button
                if (labelList[j].tagName == "LABEL" && (labelList[j].textContent.toLowerCase()).includes(activatedButton[k].value.toLowerCase())){
                        is_present = true; //Will be display if it has all the activated type search button in its types
                }
            }
            verif = verif && is_present;
            k++;   
        }
        
        //Display the story according to the verification above
        if (verif){
            stories[i].style.display = "";
        } else {
            stories[i].style.display = "none";
        }
    }
}