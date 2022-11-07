var supply = null;
var compatibleModelsIds = [];

// Generate the HTML DOM for the supplies table
function displaySupplyInfos(data) {
    supply = data.data;
    
    $('#idSupply').text(supply.idSupply);
    $('#brand').text(supply.brand);
    $('#code').text(supply.code);
    $('#quantity').text(supply.quantity);

    var urlEdit = '/supplies/' + supply.idSupply + '/edit';
    var urlDelete = '/api/supplies/' + supply.idSupply;
    var printerActions = `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
    `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${supply.idSupply}, '${urlDelete}', '/supplies')"><i class="bi bi-trash3-fill"></i></button>`;

    $('#actions').html(printerActions);
    
    callApiGet(compatibleModelsUrl, displayModelsTable);
}


function displayModelsTable(data) {
    var models = data.data;
    var tableBody = $('#modelsTable_body');
    tableBody.empty();

    compatibleModelsIds = [];

    if (models.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="4">Aucun modèle d'imprimante n'est compatible avec ce consommable</td></tr>`);
    }
    else{
        for (var i = 0; i < models.length; i++) {
            var model = models[i];
            var modelId = model.idPrinterModel;
            var modelName = model.name;

            var actionButton = `<button class="btn btn-danger btn-xs" onclick="btnRemoveCompatibilityClicked(${modelId})"><i class="bi bi-x-octagon-fill"></i></button>`;
            tableBody.append(`<tr><td>${modelId}</td><td>${modelName}</td><td>${actionButton}</tr>`);
            
            compatibleModelsIds.push(modelId);
        }
    }
}

function btnDisplayIncompatibleModelsClicked() {
    displayIncompatibleModelsTable();
}

// Generate the HTML DOM for the tables of the incompatible models (to add compatibility)
function displayIncompatibleModelsTable() {
    callApiGet('/api/models?perPage=9999999999999', function(data) {
        var allModels = data.data;
        
        var tableBody = $('#addCompatibilityTable_body');
        tableBody.empty();
        $('#addCompatibilityCard').css('display', 'block');

        for (var i = 0; i < allModels.length; i++) {
            var model = allModels[i];
            var modelId = model.idPrinterModel;
            var modelName = model.name;

            if (!compatibleModelsIds.includes(modelId)) {
                // The model is not compatible with the supply, we can add it to the table

                var actionButton = `<button class="btn btn-success btn-xs" onclick="btnAddCompatibilityClicked(${modelId})"><i class="bi bi-link-45deg"></i></button>`;
                tableBody.append(`<tr><td>${modelId}</td><td>${modelName}</td><td>${actionButton}</td></tr>`);
            }
        }
        
        // Show error if no model is incompatible with the supply
        if (tableBody.children() == undefined || tableBody.children().length == 0) {
            $('#addCompatibiltyContainer p').text("Tout les modèles d'imprimantes sont déjà compatible avec ce consommable");
            $('#addCompatibiltyContainer p').css('display', 'block');

            $('#addCompatibiltyContainer button').css('display', 'none');
        }
    });
}

function btnAddCompatibilityClicked(idPrinterModel) {
    var url = `/api/models/${idPrinterModel}/compatibilities`;
    var data = {
        idSupply: supply.idSupply
    };

    callApiPost(url, data, function(data) {
        $('#addCompatibilityCard').css('display', 'none');
        callApiGet(compatibleModelsUrl, displayModelsTable);
    });
}

function btnRemoveCompatibilityClicked(idPrinterModel) {
    var url = `/api/models/${idPrinterModel}/compatibilities/${supply.idSupply}`;

    callApiDelete(url, function(data) {
        callApiGet(compatibleModelsUrl, displayModelsTable);
    });
}

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