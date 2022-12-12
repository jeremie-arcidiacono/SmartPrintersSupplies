let printer = null;

/**
 * Generate the HTML tags for the printer details
 * @param {Object} data
 */
function displayPrinterInfos(data) {
    printer = data.data;

    $('#idPrinter').text(printer.idPrinter);
    $('#brand').text(printer.model.brand);
    $('#model').text(printer.model.name);
    $('#room').text(printer.room);
    $('#serialNumber').text(printer.serialNumber);
    $('#cti').text(printer.cti);

    const urlEdit = '/printers/' + printer.idPrinter + '/edit';
    const urlDelete = '/api/printers/' + printer.idPrinter;
    const printerActions = `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
        `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${printer.idPrinter}, '${urlDelete}', '/printers')"><i class="bi bi-trash3-fill"></i></button>`;

    $('#actions').html(printerActions);

    if (printer.room == null) {
        $('#room').text('-');
    }

    refreshTables();
}

/**
 * Refresh the supplies tables and the events table and chart
 */
function refreshTables() {
    callApiGet(eventsUrl, displayEvents);

    suppliesUrl = suppliesGenericUrl.replace('#idPrinterModel', printer.model.idPrinterModel);
    callApiGet(suppliesUrl, displaySuppliesTable);
}

/**
 * Display the events table and the events chart
 * @param {Object} data
 */
function displayEvents(data) {
    const events = data.data;
    displayEventsTable(events.slice(0, nbEventsToDisplay)); // get the X first events
    displayEventsChart(events);
}

/**
 * Generate the HTML tags for the events table
 * The table contains the last X consumptions of supplies by the printer
 * @param {Object} events
 */
function displayEventsTable(events) {
    const tableBody = $('#eventsTable_body');
    tableBody.empty();

    if (events.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun événement récent trouvé</td></tr>`);
    } else {
        for (let i = 0; i < events.length; i++) {
            const event = events[i];
            const username = event.author.username;
            const date = event.created_at;
            const supplyCode = event.target_supply.code;
            const amount = event.amount;

            tableBody.append(`<tr><td>${username}</td><td>${date}</td><td>${supplyCode}</td><td>${amount}</td></tr>`);
        }
    }
}

/**
 * Generate the HTML tags for the supplies table
 * The table contains the supplies that are used by the printer. It also contains a button to consume the supply
 * @param {Object} data
 */
function displaySuppliesTable(data) {
    const supplies = data.data;
    const tableBody = $('#suppliesTable_body');
    tableBody.empty();

    if (supplies.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun fourniture compatible avec ce modèle d'imprimante</td></tr>`);
    } else {
        for (let i = 0; i < supplies.length; i++) {
            const supply = supplies[i];
            const supplyId = supply.idSupply;
            const code = supply.code;

            const buttonHtml = `<button class="btn btn-primary btn-xs" onclick="btnConsumeClicked(${supplyId})"><i class="bi bi-box-arrow-in-down"></i></button>`;

            tableBody.append(`<tr><td>${supplyId}</td><td><a href="/supplies/${supplyId}/detail">${code}</a></td><td>${buttonHtml}</td></tr>`);
        }
    }
}

/**
 * Send a request to the API to consume a supply by the printer
 * @param {Number} supplyId - The id of the supply to consume
 */
function btnConsumeClicked(supplyId) {
    const urlConsume = '/api/supplies/' + supplyId;

    const data = {
        removeQuantity: 1,
        idPrinter: printer.idPrinter
    };

    callApiPut(urlConsume, data, refreshTables);
}

let mainChart = null;

/**
 * Generate a pie chart with the events
 * The chart show the amount of supplies that were consumed by the printer
 * @param {Object} pData
 */
function displayEventsChart(pData) {
    // Ensure that the chart is destroyed before creating a new one
    if (mainChart != null) {
        mainChart.destroy();
    }

    const ctx = document.getElementById('eventsChart').getContext('2d');
    ctx.clearRect(0, 0, ctx.width, ctx.height); // Clear the canvas before drawing the chart

    if (pData.length <= 0) {
        return; // No data to display : don't draw the chart
    }

    const events = []; // Array as : ['supplies01' => 3, 'supplies4' => 1, ...]
    pData.forEach(event => {
        // Check if the key event.target_supply.code is already in the array
        if (event.target_supply.code in events) {
            events[event.target_supply.code] += Math.abs(event.amount);
        } else {
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
