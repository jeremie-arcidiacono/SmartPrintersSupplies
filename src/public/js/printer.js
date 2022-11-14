var printer = null;

// Generate the HTML DOM for the printers table
function displayPrinterInfos(data) {
    printer = data.data;
    
    $('#idPrinter').text(printer.idPrinter);
    $('#brand').text(printer.model.brand);
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
    callApiGet(eventsUrl, displayEvents);

    suppliesUrl = suppliesGenericUrl.replace('#idPrinterModel', printer.model.idPrinterModel);
    callApiGet(suppliesUrl, displaySuppliesTable);
}

function displayEvents(data) {
    var events = data.data;
    displayEventsTable(events.slice(0, nbEventsToDisplay)); // get the X first events
    displayEventsChart(events);
}

function displayEventsTable(events) {
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

            tableBody.append(`<tr><td>${supplyId}</td><td><a href="/supplies/${supplyId}/detail">${code}</a></td><td>${buttonHtml}</td></tr>`);
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

let mainChart = null;
function displayEventsChart(pData) {
    // Ensure that the chart is destroyed before creating a new one
    if (mainChart != null) {
        mainChart.destroy();
    }

    const ctx = document.getElementById('eventsChart').getContext('2d');
    ctx.clearRect(0, 0, ctx.width, ctx.height); // Clear the canvas before drawing the chart

    if (pData.length <= 0) {
        return;
    }

    var events = []; // Array as : ['supplies01' => 3, 'supplies4' => 1, ...]
    pData.forEach(event => {
        // Check if the key event.target_supply.code is already in the array
        if (event.target_supply.code in events) {
            events[event.target_supply.code] += Math.abs(event.amount);
        }
        else {
            events[event.target_supply.code] = 1;
        }
    });

    // Transform the associative array into two arrays (labels and data)
    const labels = Object.keys(events);
    const data = Object.values(events);
    const colors = randomColor({count: data.length, format: 'rgba', hue: 'blue', luminosity: 'bright', alpha: 0.7}); // This library is used to generate attractive random colors

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Nombre d\'items consommés',
            backgroundColor: colors,
            borderColor: 'rgba(200, 200, 200, 0.75)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',
            data: data
        }]
    };
    const chartConfig = {
        type: 'pie',
        data: chartData,
        options: {
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Nombre d\'items consommés',
                    font: {
                        size: 15
                    }
                }
            }
        },

    };
    mainChart = new Chart(ctx, chartConfig);
}
