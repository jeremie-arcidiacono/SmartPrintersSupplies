let supply = null;
let compatibleModelsIds = [];

/**
 * Generate the HTML tags for the supply details
 * @param {Object} data
 */
function displaySupplyInfos(data) {
    supply = data.data;

    $('#idSupply').text(supply.idSupply);
    $('#brand').text(supply.brand);
    $('#code').text(supply.code);
    $('#quantity').text(supply.quantity);

    const urlEdit = '/supplies/' + supply.idSupply + '/edit';
    const urlDelete = '/api/supplies/' + supply.idSupply;
    const printerActions = `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
        `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${supply.idSupply}, '${urlDelete}', '/supplies')"><i class="bi bi-trash3-fill"></i></button>`;

    $('#actions').html(printerActions);

    callApiGet(compatibleModelsUrl, displayModelsTable);
}

/**
 * Generate the HTML tags for the table of the compatible models of printers
 * @param {Object} data
 */
function displayModelsTable(data) {
    const models = data.data;
    const tableBody = $('#modelsTable_body');
    tableBody.empty();

    compatibleModelsIds = []; // Reset the array of compatible models ids

    if (models.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun modèle d'imprimante n'est compatible avec ce consommable</td></tr>`);
    } else {
        for (let i = 0; i < models.length; i++) {
            const model = models[i];
            const modelId = model.idPrinterModel;
            const modelName = model.name;

            const actionButton = `<button class="btn btn-danger btn-xs" onclick="btnRemoveCompatibilityClicked(${modelId})"><i class="bi bi-x-octagon-fill"></i></button>`;
            tableBody.append(`<tr><td>${modelId}</td><td>${modelName}</td><td>${actionButton}</tr>`);

            compatibleModelsIds.push(modelId);
        }
    }
}

// Event that is triggered when the user clicks on the button to add compatibility
function btnDisplayIncompatibleModelsClicked() {
    displayIncompatibleModelsTable();
}

/**
 * Generate the HTML tags for the table of the incompatible models of printers
 * There is a button to add compatibility with the supply
 * A model is incompatible if it is not already compatible with the supply
 */
function displayIncompatibleModelsTable() {
    callApiGet('/api/models?perPage=9999999999999', function (data) {
        const allModels = data.data;

        const tableBody = $('#addCompatibilityTable_body');
        tableBody.empty();
        $('#addCompatibilityCard').css('display', 'block');

        for (let i = 0; i < allModels.length; i++) {
            const model = allModels[i];
            const modelId = model.idPrinterModel;
            const modelName = model.name;

            if (!compatibleModelsIds.includes(modelId)) {
                // The model is not compatible with the supply, we can add it to the table

                const actionButton = `<button class="btn btn-success btn-xs" onclick="btnAddCompatibilityClicked(${modelId})"><i class="bi bi-link-45deg"></i></button>`;
                tableBody.append(`<tr><td>${modelId}</td><td>${modelName}</td><td>${actionButton}</td></tr>`);
            }
        }

        // Show error if no model is incompatible with the supply
        if (tableBody.children === undefined || tableBody.children.length === 0) {
            const errorContainer = $('#addCompatibilityContainer p');
            errorContainer.text("Tout les modèles d'imprimantes sont déjà compatible avec ce consommable");
            errorContainer.css('display', 'block');

            $('#addCompatibilityContainer button').css('display', 'none');
        }
    });
}

/**
 * Add compatibility between the supply and the printer model
 * A model compatible with the supply means that all the printers of this model can use (consume) this supply
 * Then refresh the table of compatible models
 * @param {int} idPrinterModel
 */
function btnAddCompatibilityClicked(idPrinterModel) {
    const url = `/api/models/${idPrinterModel}/compatibilities`;
    const data = {
        idSupply: supply.idSupply
    };

    callApiPost(url, data, function (data) {
        $('#addCompatibilityCard').css('display', 'none');
        callApiGet(compatibleModelsUrl, displayModelsTable);
    });
}

/**
 * Remove compatibility between the supply and the model of printer
 * Then refresh the table of compatible models
 * @param {int} idPrinterModel
 */
function btnRemoveCompatibilityClicked(idPrinterModel) {
    const url = `/api/models/${idPrinterModel}/compatibilities/${supply.idSupply}`;

    callApiDelete(url, function (data) {
        callApiGet(compatibleModelsUrl, displayModelsTable);
    });
}

/**
 * Display the bar chart of the stocks of the supply
 * It is displayed by month
 * @param {Object} pData
 */
function displayChart(pData) {
    if (pData.length <= 0) {
        return;
    }
    pData = pData.data;

    const ctx = document.getElementById('stockChart').getContext('2d');
    ctx.clearRect(0, 0, ctx.width, ctx.height); // Clear the canvas before drawing the chart

    // Transform the associative array into two arrays (labels and data)
    const labels = Object.keys(pData);
    const data = Object.values(pData);

    const chartData = {
        labels: labels,
        datasets: [
            {
                type: 'line',
                label: 'Tendance du stock',
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                order: 1,
                // Make the line rounded
                cubicInterpolationMode: 'monotone',
                tension: 0.5,
            }, {
                type: 'bar',
                label: 'Quantité en stock',
                data: data,
                backgroundColor: 'rgba(255, 177, 193, 0.7)',
                borderColor: 'rgba(255, 177, 193, 1)',
                order: 2,
            }]
    };
    const chartConfig = {
        type: 'scatter',
        data: chartData,
        options: {
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Evolution de la quantité du stock',
                    font: {
                        size: 20
                    }
                }
            }
        },

    };
    const mainChart = new Chart(ctx, chartConfig);
}
