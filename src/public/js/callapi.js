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
