// Make a GET request to the API
function callApiGet(url, callback) {
    $.ajax({
        url: url,
        type: 'GET',
        success: function (data) {
            callback(data);
        },
        error: function () {
            alert('Une erreur est survenu lors de la récupération des informations');
            console.error('Error while calling API (URL : ' + url + ')');
        }
    });
}

function callApiPost(url, data, callback, errorCallback) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            callback(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            // We display the error message only if the error is a problem with the values the user has entered
            if (xhr.status != 422) {
                alert('Une erreur est survenu lors de l\'enregistrement des informations');
                console.error('Error while calling API (URL : ' + url + ')');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}

function callApiPut(url, data, callback, errorCallback) {
    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        success: function (data) {
            callback(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            // We display the error message only if the error is a problem with the values the user has entered
            if (xhr.status != 422) {
                alert('Une erreur est survenu lors de la modification des informations');
                console.error('Error while calling API (URL : ' + url + ')');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}

function callApiDelete(url, callback, errorCallback) {
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function (data) {
            callback(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            if (xhr.status != 422) {
                alert('Une erreur est survenu lors de la suppression des informations');
                console.error('Error while calling API (URL : ' + url + ')');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}