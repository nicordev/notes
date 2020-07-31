const showNoteFormButtonElement = document.getElementById('show-note-form-button');
const closeNoteFormButtonElement = document.getElementById('close-note-form-button');
const noteFormElement = document.getElementById('note-form');

showNoteFormButtonElement.addEventListener('click', function (event) {
    showElement(noteFormElement);
    showElement(closeNoteFormButtonElement);
    hideElement(showNoteFormButtonElement);
});

closeNoteFormButtonElement.addEventListener('click', function (event) {
    hideElement(noteFormElement);
    hideElement(closeNoteFormButtonElement);
    showElement(showNoteFormButtonElement);
});