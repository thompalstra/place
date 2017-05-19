_(document).on('click', '.edit li', function(e){
    // has list
    ul = _(this).findOne('ul');
    if(ul.exists()){
        ul.toggle();
    } else {

    }
});
_(document).on('focousout', '.edit div[contenteditable="true"]', function(e){
    console.log("dsadsa");
});
var range = "";
_('.edit div[contenteditable="true"]').on('mouseleave', function(e){
    e.preventDefault();
    p = this.parentNode;
    range = saveSelection();
    // var thisText = this.innerHTML;
    // var selectedText = getSelectionText();
    // var start = thisText.indexOf(selectedText);
    // var end = start + selectedText.length;
    // //console.log('d');
    // if (start >= 0 && end >= 0){
    //     p.setAttribute('selection-start', start);
    //     p.setAttribute('selection-end', start);
    // }
});

_('.edit div[contenteditable="true"]').on('focusout', function(e){
    console.log('restoring...');
    // var selection = window.getSelection();
    // var range = document.createRange();
    //
    // //range.setStart(this, this.parentNode.getAttribute('selection-start'));
    // //range.setEnd(this, this.parentNode.getAttribute('selection-end'));
    //
    // selection.removeAllRanges();
    // sel.addRange
    restoreSelection();
});

function saveSelection() {
    if (window.getSelection) {
        sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount) {
            return sel.getRangeAt(0);
        }
    } else if (document.selection && document.selection.createRange) {
        return document.selection.createRange();
    }
    return null;
}

function restoreSelection(range) {
    if (range) {
        if (window.getSelection) {
            sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (document.selection && range.select) {
            range.select();
        }
    }
}
