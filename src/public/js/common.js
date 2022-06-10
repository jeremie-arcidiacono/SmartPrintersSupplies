/////// HTTP REQUEST METHOD ///////

// Send a GET request to the API
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

// Send a POST request to the API
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

// Send a PUT request to the API
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

// Send a DELETE request to the API
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


/////// Delete button ///////

// Event - When the user click on a delete button
// Display a modal to confirm the deletion
function btnDeleteClicked(id, sendUrl, redirectUrl) {
    $("#deleteModal").modal("show");
    $('#model_idItem').text(id);
    $('#modal_btnDelete').attr('onclick', `deleteItem('${sendUrl}', '${redirectUrl}')`);
}

// Event - When the user click on the delete confirmation button in the modal
function deleteItem(url, redirectUrl) {
    $(`#deleteModal`).modal("hide");
    if(redirectUrl == null || redirectUrl == "undefined" || redirectUrl == "") {
        // By default, we refresh the table after the deletion
        callApiDelete(url, refreshTable, function(data) {
            alert(data.errors);
        }); 
    }
    else{
        callApiDelete(url, function(data) {
            window.location.href = redirectUrl;
        }, function(data) {
            alert(data.errors);
        });
    }
}