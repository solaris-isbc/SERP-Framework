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

let snippets = document.querySelectorAll('.serp-result.hasDocuments > *');
for (var i = 0; i < snippets.length; i++) {
    snippets[i].addEventListener('click', displayDocument);
}

let documentHideButtons = document.querySelectorAll('.closePreviewButton')
for (var i = 0; i < documentHideButtons.length; i++) {
    documentHideButtons[i].addEventListener('click', hideDocument);
}

// window.addEventListener('DOMContentLoaded', function(e) {

//     var iFrame = document.getElementById( 'documentPreview' );
//     this.setTimeout(function() {
//         resizeIFrameToFitContent( iFrame );
//         document.getElementById('documentPreview').contentWindow.scrollTo(0,0);
//     }, 1500);
// } );
