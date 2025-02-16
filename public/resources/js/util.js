const urlSearchArtifacts = '/api/generic/artifacts';
const urlListArtifacts = "/api/list/artifacts";
const urlListComponents = "/api/list/components";
const urlImagesNames = "/api/images";

//Create alert
function createAlert(response, container = "#alert-container") {
    $("html, body").animate({
        scrollTop: 0
    }, 50);
    $(container).prepend(
        '<div class="alert alert-' + (isError(response.status) ? "danger" : "success") + ' alert-dismissible fade show" role="alert" id="alert">' +
        '<p><strong>' + (isError(response.status) ? "Error!" : "Success!") + '</strong></p>' + response.message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    );
    $("#alert").fadeTo(3000, 0).slideUp(500, function () {
        $(this).remove();
    });
}

function createStaticAlert(response, container = "#alert-container") {
    $("html, body").animate({
        scrollTop: 0
    }, 50);
    $(container).prepend(
        '<div class="alert alert-' + (isError(response.status) ? "danger" : "success") + ' alert-dismissible fade show" role="alert" id="alert">' +
        '<p><strong>' + (isError(response.status) ? "Error!" : "Success!") + '</strong></p>' + response.message +
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
    );
}

//Make a request and return a json response
function makeRequest(url, method = 'GET', headers = [], params = [], dataType = "text") {
    let returnData = {};
    $.ajax({
        url: url,
        method: method,
        async: false,
        headers: headers,
        data: params,
        processData: false,
        contentType: false,
        dataType: dataType,
        xhrFields: {
            withCredentials: true
        },
        success: function (data, textStatus, xhr) {
            returnData = JSON.parse(data);
            returnData.status = xhr.status;
        },
        error: function (xhr, status, error) {
            returnData.message = JSON.parse(xhr.responseText).message;
            returnData.status = xhr.status;
        }
    });
    return returnData;
}

function isError(status) {
    return status >= 400 && status <= 599;
}