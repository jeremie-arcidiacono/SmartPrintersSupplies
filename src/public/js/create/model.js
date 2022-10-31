var alerts = $('#alerts');


function loadModel(modelUrl) {
    callApiGet(modelUrl, function(data) {
        var model = data.data;
        $('#name').val(model.name);
        $('#brand').val(model.brand);
    });
}

function validateInput(name, brand) {
    var lstError = [];
    
    if (name == '') {
        lstError.push('Le nom du modèle est obligatoire.');
    }
    if (brand == '') {
        lstError.push('La marque est obligatoire.');
    }

    return lstError;
}

function submit() {
    var name = $('#name').val();
    var brand = $('#brand').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    lstError = validateInput(name, brand);
    
    if (lstError.length > 0) {
        alerts.addClass('alert alert-danger');

        var htmlString = '<ul>';
        for (var i = 0; i < lstError.length; i++) {
            htmlString += '<li>' + lstError[i] + '</li>';
        }
        htmlString += '</ul>';

        alerts.append(htmlString);
        return false;
    }
    else {
        var data = {
            name: name,
            brand: brand
        }

        if (MODE == 'create') {
            callApiPost(sendUrl, data, succes, error);   
        }
        else if (MODE == 'edit') {
            callApiPut(sendUrl, data, succes, error);
        }
    }
}

// Occurs when the item is successfully created/edited
function succes(data) {
    alerts.addClass('alert alert-success');
    alerts.append('Le nouveau item a été ajouté.');

    if (MODE == 'create') {
        $('#name').val('');
    }
    else if (MODE == 'edit') {
        window.location.href = '/models';
    }
}

// Occurs when an error in the creation/modification of the element is due to user input is reported by the server.
function error(data) {
    data = data.errors;
    alerts.addClass('alert alert-danger');

    var htmlString = '<ul>';
    for (var errorsColumn in data) {
        for (var error in data[errorsColumn]) {
            htmlString += '<li>' + data[errorsColumn][error] + '</li>';
        }
    }
    htmlString += '</ul>';
    alerts.append(htmlString);
}

function keyDown(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == 13) {
        submit();
    }
}