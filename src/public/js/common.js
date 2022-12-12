/**
 * HTTP REQUEST METHOD
 * Send a GET request to the API
 * @param {string} url
 * @param {function} callback
 */
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

/**
 * HTTP REQUEST METHOD
 * Send a POST request to the API
 * @param {string} url
 * @param {Object} data - Data to send to the API
 * @param {function} callback
 * @param {function} errorCallback - Optional, the function to call if an error occurs
 */
function callApiPost(url, data, callback, errorCallback = null) {
    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        success: function (data) {
            callback(data);
        },
        error: function (xhr) {
            // We display the error message only if the error is a problem with the values the user has entered
            if (xhr.status !== 422) {
                alert('Une erreur est survenu lors de l\'enregistrement des informations');
                console.error('Error while calling API (URL : ' + url + ')');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}

/**
 * HTTP REQUEST METHOD
 * Send a PUT request to the API
 * @param {string} url
 * @param {Object} data - Data to send to the API
 * @param {function} callback
 * @param {function} errorCallback - Optional, the function to call if an error occurs
 */
function callApiPut(url, data, callback, errorCallback = null) {
    $.ajax({
        url: url,
        type: 'PUT',
        data: data,
        success: function (data) {
            callback(data);
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let output = 'Veuillez vérifier les informations saisies : \n';
                for (let key in xhr.responseJSON.errors) {
                    output += xhr.responseJSON.errors[key] + '\n';
                }
                alert(output);
            } else {
                alert('Une erreur est survenu lors de la modification des informations');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}

// Send a DELETE request to the API
/**
 * HTTP REQUEST METHOD
 * Send a DELETE request to the API
 * @param {string} url
 * @param {function} callback
 * @param {function} errorCallback - Optional, the function to call if an error occurs
 */
function callApiDelete(url, callback, errorCallback = null) {
    $.ajax({
        url: url,
        type: 'DELETE',
        success: function (data) {
            callback(data);
        },
        error: function (xhr) {
            if (xhr.status !== 422) {
                alert('Une erreur est survenu lors de la suppression des informations');
                console.error('Error while calling API (URL : ' + url + ')');
            }
            if (errorCallback != null) {
                errorCallback(xhr.responseJSON);
            }
        }
    });
}


// Event - When the user click on a delete button
/**
 * Display a modal to confirm the deletion of an item
 * @param {int} id
 * @param {string} sendUrl - The URL to send the DELETE request to the API
 * @param {string|null} redirectUrl - Optional, the URL to redirect the user after the deletion
 */
function btnDeleteClicked(id, sendUrl, redirectUrl = null) {
    $("#deleteModal").modal("show");
    $('#model_idItem').text(id);
    if (redirectUrl == null || redirectUrl === "undefined" || redirectUrl === "") {
        $('#modal_btnDelete').attr('onclick', `deleteItem('${sendUrl}')`);
    } else {
        $('#modal_btnDelete').attr('onclick', `deleteItem('${sendUrl}', '${redirectUrl}')`);
    }
}

// Event - When the user click on the delete confirmation button in the modal
/**
 * Send a DELETE request to the API to delete an item and redirect the user to the specified URL
 * @param {string} url - The URL to send the DELETE request to the API
 * @param {string|null} redirectUrl - Optional, the URL to redirect the user after the deletion
 */
function deleteItem(url, redirectUrl = null) {
    $(`#deleteModal`).modal("hide");
    if (redirectUrl == null || redirectUrl === "undefined" || redirectUrl === "") {
        // By default, we refresh the table after the deletion
        callApiDelete(url, refreshTable, function (data) {
            alert(data.errors);
        });
    } else {
        callApiDelete(url, function (data) {
            window.location.href = redirectUrl;
        }, function (data) {
            alert(data.errors);
        });
    }
}


/////// Debouncing ///////
let timerId;
/**
 * Debounce a function call to avoid multiple calls in a short time
 * @param {function} func - The function to call
 * @param {int} delay - The delay in milliseconds
 */
const debounceFunction = function (func, delay) {
    // Cancels the setTimeout method execution
    clearTimeout(timerId)

    // Executes the func after delay time.
    timerId = setTimeout(func, delay)
};
