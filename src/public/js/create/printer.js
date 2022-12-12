const alerts = $('#alerts');


/**
 * Call the API to retrieve the infos about the existing printers models
 */
function loadModels() {
    return new Promise(function (resolve) {
        callApiGet(modelUrl, function (data) {
            const modelList = $('#modelList');
            modelList.empty();
            modelList.append('<option value="" selected disabled hidden>Choisir un modèle</option>');
            let models = data.data;
            for (let i = 0; i < models.length; i++) {
                modelList.append(`<option value="${models[i].idPrinterModel}">${models[i].brand} - ${models[i].name}</option>`);
            }
            resolve();
        });
    });
}

/**
 * Call the API to retrieve the infos about the element to edit.
 * @param {string} printerUrl - The URL to call to retrieve the infos about the element to edit.
 */
function loadPrinter(printerUrl) {
    callApiGet(printerUrl, function (data) {
        const printer = data.data;
        $('#modelList').val(printer.model.idPrinterModel);
        $('#room').val(printer.room);
        $('#serialNumber').val(printer.serialNumber);
        $('#cti').val(printer.cti);
    });
}

/**
 * Validate the input of the user.
 * @param idModel
 * @param serialNumber
 * @param cti
 * @return {string[]} A list of errors.
 */
function validateInput(idModel, serialNumber, cti) {
    const lstError = [];
    if (isNaN(idModel) || idModel === '' || idModel == null) {
        lstError.push('Le modèle est obligatoire.');
    }
    if (serialNumber === '') {
        lstError.push('Le numéro de série est obligatoire.');
    }
    if (cti === '') {
        lstError.push('Le numéro CTI est obligatoire.');
    } else if (isNaN(cti)) {
        lstError.push('Le CTI doit être un nombre.');
    } else if (cti.lenght > 6 || cti.lenght < 6) {
        lstError.push('Le numéro CTI doit contenir 6 chiffres.');
    }

    return lstError;
}

/**
 * Submit the form.
 * @return {boolean} False if the user input is invalid. (The form is not submitted)
 */
function submit() {
    const idModel = $('#modelList').val();
    const room = $('#room').val();
    const serialNumber = $('#serialNumber').val();
    const cti = $('#cti').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    let lstError = validateInput(idModel, serialNumber, cti);

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
            idModel: idModel,
            room: room,
            serialNumber: serialNumber,
            cti: cti
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
    alerts.append('Le nouveau matériel a été ajouté.');

    if (mode === 'create') {
        $('#modelList').val('');
        $('#room').val('');
        $('#serialNumber').val('');
        $('#cti').val('');
    } else if (mode === 'edit') {
        window.location.href = '/printers';
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
