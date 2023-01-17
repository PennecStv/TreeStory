const typeButtons = document.querySelectorAll("button");

const selectFilter = document.getElementById("filter-select");

const selectSort = document.getElementById("sort-select");

const formSearch = document.getElementById('form-search');


result = [selectFilter[0].value, selectSort[0].value];

typeButtons.forEach(button => button.addEventListener('click', function(){
    button.classList.toggle("button");
    button.classList.toggle("button-change");
}));


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


selectFilter.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});

selectSort.addEventListener('change', function(){
    combineAndSendForms();
    this.submit();
});