var printer = null;

// Generate the HTML DOM for the printers table
function displayPrinterInfos(data) {
    printer = data.data;
    
    $('#idPrinter').text(printer.idPrinter);
    $('#model').text(printer.model.name);
    $('#room').text(printer.room);
    $('#serialNumber').text(printer.serialNumber);
    $('#cti').text(printer.cti);

    var urlEdit = '/printers/' + printer.idPrinter + '/edit';
    var urlDelete = '/api/printers/' + printer.idPrinter;
    var printerActions = `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
    `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${printer.idPrinter}, '${urlDelete}', '/printers')"><i class="bi bi-trash3-fill"></i></button>`;

    $('#actions').html(printerActions);

    if (printer.room == null) {
        $('#room').text('-');
    }
    
    refreshTables();
}

function refreshTables() {
    callApiGet(eventsUrl, displayEventsTable);

    suppliesUrl = suppliesGenericUrl.replace('#idPrinterModel', printer.model.idPrinterModel);
    callApiGet(suppliesUrl, displaySuppliesTable);
}

function displayEventsTable(data) {
    var events = data.data;
    var tableBody = $('#eventsTable_body');
    tableBody.empty();

    if (events.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun événement récent trouvé</td></tr>`);
    }
    else{
        for (var i = 0; i < events.length; i++) {
            var event = events[i];
            var username = event.author.username;
            var date = event.created_at;
            var supplyCode = event.target_supply.code;
            var amount = event.amount;

            tableBody.append(`<tr><td>${username}</td><td>${date}</td><td>${supplyCode}</td><td>${amount}</td></tr>`);
        }
    }
}

function displaySuppliesTable(data) {
    var supplies = data.data;
    var tableBody = $('#suppliesTable_body');
    tableBody.empty();

    if (supplies.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun fourniture compatible avec ce modèle d'imprimante</td></tr>`);
    }
    else{
        for (var i = 0; i < supplies.length; i++) {
            var supply = supplies[i];
            var supplyId = supply.idSupply;
            var code = supply.code;

            var buttonHtml = `<button class="btn btn-primary btn-xs" onclick="btnConsumeClicked(${supplyId})"><i class="bi bi-box-arrow-in-down"></i></button>`;

            tableBody.append(`<tr><td>${supplyId}</td><td><a href="/supplies/${supplyId}">${code}</a></td><td>${buttonHtml}</td></tr>`);
        }
    }
}

function btnConsumeClicked(supplyId) {
    var urlConsume = '/api/supplies/' + supplyId;

    var data = {
        removeQuantity: 1,
        idPrinter : printer.idPrinter
    }

    callApiPut(urlConsume, data, refreshTables);
}
