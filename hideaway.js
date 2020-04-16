
var UZG_hideaway = {};
UZG_hideaway.hideIt = function(fieldToHide) {
    $( "tr[sq_id='"+fieldToHide+"']" ).hide();
    //Avoid that anything else makes it visible again by listening to CSS changes and hiding it again.
    //This counteracts branching logic.
    const targetNode = $( "tr[sq_id='"+fieldToHide+"']" ).get(0);

    // Options for the observer (which mutations to observe)
    const config = { attributes: true, childList: false, subtree: false };

    // Callback function to execute when mutations are observed
    const callback = function(mutationsList, observer) {
        $( "tr[sq_id='"+fieldToHide+"']" ).hide();
    };

    // Create an observer instance linked to the callback function
    const observer = new MutationObserver(callback);

    // Start observing the target node for configured mutations
    observer.observe(targetNode, config);

};
