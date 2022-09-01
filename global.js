// define functions
function displayDocument(event) {
    var clicked = event.target;
    let target = clicked.parentNode.getAttribute('data-document');

    // create iframe
    var iframe = document.createElement('iframe');
    iframe.setAttribute('id', 'documentPreview');
    iframe.onload = function(event) { 
        resizeIFrameToFitContent(event.target);
        event.target.contentWindow.scrollTo(0,0);
    }; 
    iframe.src = target; 
    document.querySelector('.previewContainerHeader').after(iframe); 

    document.getElementById('previewContainer').classList.remove('hidden');
}

function hideDocument(event) {
    document.getElementById('documentPreview').remove();
    document.getElementById('previewContainer').classList.add('hidden');
}

function resizeIFrameToFitContent( iFrame ) {
    iFrame.width  = iFrame.contentWindow.document.body.scrollWidth;
    iFrame.height = iFrame.contentWindow.document.body.scrollHeight;
}

function showPreview(target) {
    // create iframe
    var iframe = document.createElement('iframe');
    iframe.setAttribute('id', 'serpPreview');
    iframe.onload = function(event) { 
        resizeIFrameToFitContent(event.target);
            event.target.width  = document.body.scrollWidth;
            event.target.height = iFrame.contentWindow.document.body.scrollHeight;
        event.target.contentWindow.scrollTo(0,0);
    }; 
    iframe.src = target; 
    document.getElementById('serpPreviewAnchor').after(iframe); 

}


// register event listeners on dom loaded
(function() {
let snippets = document.querySelectorAll('.serp-result.hasDocuments > *');
for (var i = 0; i < snippets.length; i++) {
    snippets[i].addEventListener('click', displayDocument);
}

let documentHideButtons = document.querySelectorAll('.closePreviewButton')
for (var i = 0; i < documentHideButtons.length; i++) {
    documentHideButtons[i].addEventListener('click', hideDocument);
}

let previewAnchor = document.getElementById('serpPreviewAnchor');
if(previewAnchor) {
    let targetSystem = previewAnchor.getAttribute('data-system');
    showPreview(targetSystem);
}
}

)();


