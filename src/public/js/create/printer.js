var alerts = $('#alerts');


function loadModels() {
    callApiGet(modelUrl, function(data) {
        var modelList = $('#modelList');
        modelList.empty();
        modelList.append('<option value="" selected disabled hidden>Choisir un modèle</option>');
        models = data.data;
        for (var i = 0; i < models.length; i++) {
            modelList.append(`<option value="${models[i].idPrinterModel}">${models[i].name}</option>`);
        }
    });
}

function loadPrinter(printerUrl) {
    callApiGet(printerUrl, function(data) {
        var printer = data.data;
        $('#modelList').val(printer.model.idPrinterModel);
        $('#room').val(printer.room);
        $('#serialNumber').val(printer.serialNumber);
        $('#cti').val(printer.cti);
    });
}

function validateInput(idModel, serialNumber, cti) {
    var lstError = [];
    if (isNaN(idModel) || idModel == '' || idModel == null) {
        lstError.push('Le modèle est obligatoire.');
    } 
    if (serialNumber == '') {
        lstError.push('Le numéro de série est obligatoire.');
    }
    if (cti == '') {
        lstError.push('Le numéro CTI est obligatoire.');
    }
    else if (isNaN(cti)) {
        lstError.push('Le CTI doit être un nombre.');
    }
    else if (cti.lenght > 6 || cti.lenght < 6) {
        lstError.push('Le numéro CTI doit contenir 6 chiffres.');
    }

    return lstError;
}

function submit() {
    var idModel = $('#modelList').val();
    var room = $('#room').val();
    var serialNumber = $('#serialNumber').val();
    var cti = $('#cti').val();

    alerts.empty();
    alerts.removeClass('alert-danger alert-success');

    lstError = validateInput(idModel, serialNumber, cti);
    
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
            idModel: idModel,
            room: room,
            serialNumber: serialNumber,
            cti: cti
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
    alerts.append('Le nouveau matériel a été ajouté.');

    if (MODE == 'create') {
        $('#modelList').val('');
        $('#room').val('');
        $('#serialNumber').val('');
        $('#cti').val('');
    }
    else if (MODE == 'edit') {
        window.location.href = '/printers';
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