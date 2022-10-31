var alerts = $('#alerts');

var initialQuantity = null; // The initial quantity of the supply (if it is an edit)

function loadSupply(supplyUrl) {
    callApiGet(supplyUrl, function(data) {
        var supply = data.data;
        $('#brand').val(supply.brand);
        $('#code').val(supply.code);
        $('#quantity').val(supply.quantity);
        initialQuantity = supply.quantity;
    });
}

function validateInput(brand, code, quantity) {
    var lstError = [];
    if (brand == '') {
        lstError.push('La marque est obligatoire.');
    }
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
    var brand = $('#brand').val();
    var code = $('#code').val();
    var quantity = $('#quantity').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    lstError = validateInput(brand, code, quantity);
    
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
        if (mode == 'create') {
            var data = {
                brand: brand,
                code: code,
                quantity: quantity
            }
            callApiPost(sendUrl, data, succes, error);   
        }
        else if (mode == 'edit') {
            if (initialQuantity != quantity) {
                var data = {
                    brand: brand,
                    code: code,
                    quantity: quantity
                }
            }
            else {
                var data = {brand: brand, code: code}
            }
            callApiPut(sendUrl, data, succes, error);
        }
    }
}

// Occurs when the item is successfully created/edited
function succes(data) {
    alerts.addClass('alert alert-success');
    alerts.append('Le nouveau matériel a été ajouté.');

    if (mode == 'create') {
        $('#code').val('');
        $('#quantity').val('');
    }
    else if (mode == 'edit') {
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

function keyDown(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == 13) {
        submit();
    }
}