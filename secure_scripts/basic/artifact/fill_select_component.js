function loadSelect(urlFetch, idSelect) {
    let data = makeRequest(urlFetch);
    if (data) {
        data.forEach(function (elem) {
            $(idSelect).append($('<option>', {
                value: elem.id,
                text: elem.name
            }));
        });
    }
}