function MarkdownParser() {

    let that = {
        handleElements: function (elements) {
            for (let element of elements) {
                element.innerHTML = marked(element.textContent);
            }
        }
    };

    return that;
}