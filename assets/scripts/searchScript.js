const typeButtons = document.querySelectorAll("input");

const actionButton = document.getElementById("btn-action");

const horrorButton = document.getElementById("btn-horror");

const adventureButton = document.getElementById("btn-adventure");

const otherButton = document.getElementById("btn-other");

const selectFilter = document.getElementById("filter-select");

const selectSort = document.getElementById("sort-select");

const formSearch = document.getElementById('form-search');

const stories = document.getElementsByClassName("storyContent");

console.log(document.getElementsByClassName("storyType"));


const activatedButton = [];
console.log(otherButton)
otherButton.addEventListener('click', (e) => {
    e.preventDefault();
    var input = document.createElement("input");

    input.className = "btn";
    input.name = "other_type";
    input.autocomplete = "off";
    input.placeholder = " ";
    input.type = "text";
    input.addEventListener("focusout", (evt) => {
        input.type = "submit";
        evt.preventDefault();
        applyFilter(input);
        input.addEventListener('click', (event) => {
            event.preventDefault();
            input.remove();
        });
        
        
        if (input.value == ""){
            input.remove();
        }
    });
    console.log(input);
    document.getElementById("menu-type").appendChild(input);
    input.focus();
})




$("#form-header").submit(function() {
    combineAndSendForms();
    return false;        // prevent default action
});

function combineAndSendForms() {
    var $newForm = $("<form></form>")    // our new form.
        .attr({method : "POST", action : "/search"}) // customise as required
    ;
    $(":input:not(:submit, :button)").each(function() {  // grab all the useful inputs
        $newForm.append($("<input type=\"hidden\" />")   // create a new hidden field
            .attr('name', this.name)   // with the same name (watch out for duplicates!)
            .val($(this).val())        // and the same value
        );
    });
    $newForm
        .appendTo(document.body)  // not sure if this is needed?
        .submit()                 // submit the form
    ;
}

// formSearch.bind("keypress", function (e) {
//     if (e.keyCode == 13) {
//         $("#btnSearch").attr('value');
//         //add more buttons here
//         return false;
//     }
// });


selectFilter.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});

selectSort.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});

typeButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        e.preventDefault();
        applyFilter(button);
    })
});


function applyFilter(button){
    button.classList.toggle("btn");
    button.classList.toggle("btn-change");

    if (activatedButton.includes(button)){
        var index = activatedButton.indexOf(button);
        activatedButton.splice(index, 1);
    } else {
        activatedButton.push(button);
    }

    for (let i = 0; i < stories.length; i++) {
        let verif = true;
        let divInfoStory = stories[i].childNodes[3].childNodes;
        let labelList = divInfoStory[divInfoStory.length - 2].childNodes;
        let k = 0;
        
        while (k < activatedButton.length){
            let is_present = false; 
            for (let j = 0; j < labelList.length; j++){
                if (labelList[j].tagName == "LABEL" && (labelList[j].textContent.toLowerCase()).includes(activatedButton[k].value.toLowerCase())){
                        is_present = true;
                }
            }
            verif = verif && is_present;
            k++;   
        }
        
        if (verif){
            stories[i].style.display = "";
        } else {
            stories[i].style.display = "none";
        }
    }
}