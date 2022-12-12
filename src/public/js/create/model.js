const alerts = $('#alerts');

/**
 * Call the API to retrieve the infos about the element to edit.
 * @param {string} modelUrl - The URL to call to retrieve the infos about the element to edit.
 */
function loadModel(modelUrl) {
    callApiGet(modelUrl, function (data) {
        const model = data.data;
        $('#name').val(model.name);
        $('#brand').val(model.brand);
    });
}

/**
 * Validate the input of the user.
 * @param {string} name
 * @param {string} brand
 * @returns {string[]} A list of errors.
 */
function validateInput(name, brand) {
    const lstError = [];

    if (name === '') {
        lstError.push('Le nom du modèle est obligatoire.');
    }
    if (brand === '') {
        lstError.push('La marque est obligatoire.');
    }

    return lstError;
}

/**
 * Submit the form.
 * @return {boolean} False if the user input is invalid. (The form is not submitted)
 */
function submit() {
    const name = $('#name').val();
    const brand = $('#brand').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    let lstError = validateInput(name, brand);

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
        const data = {
            name: name,
            brand: brand
        };

        if (mode === 'create') {
            callApiPost(sendUrl, data, success, error);
        } else if (mode === 'edit') {
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
    alerts.append('Le nouveau item a été ajouté.');

    if (mode === 'create') {
        $('#name').val('');
    } else if (mode === 'edit') {
        window.location.href = '/models';
    }
}

/**
 * Display the error message returned by the server after the failure of the creation/modification of the element.
 * @param {Object} data
 */
function error(data) {
    data = data.errors;

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
