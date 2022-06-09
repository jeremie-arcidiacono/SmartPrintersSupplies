var alerts = $('#alerts');

var initialQuantity = null; // The initial quantity of the supply (if it is an edit)

function loadSupply(supplyUrl) {
    callApiGet(supplyUrl, function(data) {
        var supply = data.data;
        $('#code').val(supply.code);
        $('#quantity').val(supply.quantity);
        initialQuantity = supply.quantity;
    });
}

function validateInput(code, quantity) {
    var lstError = [];
    if (code == '') {
        lstError.push('Le code est obligatoire.');
    }
    if (quantity == '') {
        lstError.push('La quantité est invalide.');
    }
    else if (isNaN(quantity)) {
        lstError.push('La quantité doit être un nombre.');
    }

    return lstError;
}

function submit() {
    var code = $('#code').val();
    var quantity = $('#quantity').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    lstError = validateInput(code, quantity);
    
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
        if (MODE == 'create') {
            var data = {
                code: code,
                quantity: quantity
            }
            callApiPost(sendUrl, data, succes, error);   
        }
        else if (MODE == 'edit') {
            if (initialQuantity != quantity) {
                var data = {
                    code: code,
                    quantity: quantity
                }
            }
            else {
                var data = {code: code}
            }
            callApiPut(sendUrl, data, succes, error);
        }
    }
}

// Occurs when the item is successfully created/edited
function succes(data) {
    alerts.addClass('alert alert-success');
    alerts.append('Le nouveau matériel a été ajouté.');

    if (MODE == 'create') {
        $('#code').val('');
        $('#quantity').val('');
    }
    else if (MODE == 'edit') {
        window.location.href = '/supplies';
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