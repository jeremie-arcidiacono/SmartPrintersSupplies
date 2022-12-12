const alerts = $('#alerts');

let initialQuantity = null; // The initial quantity of the supply (if it is an edit)

/**
 * Call the API to retrieve the infos about the element to edit.
 * @param {string} supplyUrl - The URL to call to retrieve the infos about the element to edit.
 */
function loadSupply(supplyUrl) {
    callApiGet(supplyUrl, function (data) {
        const supply = data.data;
        $('#brand').val(supply.brand);
        $('#code').val(supply.code);
        $('#quantity').val(supply.quantity);
        initialQuantity = supply.quantity;
    });
}

/**
 * Validate the input of the user.
 * @param brand
 * @param code
 * @param quantity
 * @return {string[]} A list of errors.
 */
function validateInput(brand, code, quantity) {
    const lstError = [];
    if (brand === '') {
        lstError.push('La marque est obligatoire.');
    }
    if (code === '') {
        lstError.push('Le code est obligatoire.');
    }
    if (quantity === '') {
        lstError.push('La quantité est invalide.');
    } else if (isNaN(quantity)) {
        lstError.push('La quantité doit être un nombre.');
    }

    return lstError;
}

/**
 * Submit the form.
 * @return {boolean} False if the user input is invalid. (The form is not submitted)
 */
function submit() {
    const brand = $('#brand').val();
    const code = $('#code').val();
    const quantity = $('#quantity').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    let lstError = validateInput(brand, code, quantity);

    if (lstError.length > 0) {
        alerts.addClass('alert alert-danger');

        let htmlString = '<ul>';
        for (let i = 0; i < lstError.length; i++) {
            htmlString += '<li>' + lstError[i] + '</li>';
        }
        htmlString += '</ul>';

        alerts.append(htmlString);
        return false;
    } else {
        let data;
        if (mode === 'create') {
            data = {
                brand: brand,
                code: code,
                quantity: quantity
            }
            callApiPost(sendUrl, data, success, error);
        } else if (mode === 'edit') {
            if (initialQuantity !== quantity) {
                data = {
                    brand: brand,
                    code: code,
                    quantity: quantity
                }
            } else {
                data = {brand: brand, code: code}
            }
            callApiPut(sendUrl, data, success, error);
        }
        return true;
    }
}

/**
 * Clear the form and display a success message OR redirect after the success of the creation/modification of the element.
 * @param {Object} data
 */
function success(data) {
    alerts.addClass('alert alert-success');
    alerts.append('Le nouveau matériel a été ajouté.');

    if (mode === 'create') {
        $('#code').val('');
        $('#quantity').val('');
    } else if (mode === 'edit') {
        window.location.href = '/supplies';
    }
}

/**
 * Display the error message returned by the server after the failure of the creation/modification of the element.
 * @param {Object} data
 */
function error(data) {
    data = data.errors;
    alerts.addClass('alert alert-danger');

    let htmlString = '<ul>';
    for (const errorsColumn in data) {
        for (const error in data[errorsColumn]) {
            htmlString += '<li>' + data[errorsColumn][error] + '</li>';
        }
    }
    htmlString += '</ul>';
    alerts.append(htmlString);
}

/**
 * Submit the form when the user click on the ENTER key.
 * @param event
 */
function keyDown(event) {
    const keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode === 13) {
        submit();
    }
}
