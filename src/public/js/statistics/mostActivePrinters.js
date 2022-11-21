let limit = $('#limit').val();
let startDate = $('#startDate').val();
let endDate = $('#endDate').val();

let mainChart = null;
let links = [];
function displayChart(pData) {
    pData = pData.data;

    // Ensure that the chart is destroyed before creating a new one
    if (mainChart != null) {
        mainChart.destroy();
    }
    $('#errorMsg').hide();

    const ctx = document.getElementById('stockChart').getContext('2d');
    ctx.clearRect(0, 0, ctx.width, ctx.height); // Clear the canvas before drawing the chart

    if (pData.length <= 0) {
        // Display a message if there is no data
        $('#errorMsg').show();
        return;
    }

    const printers = []; // Array as : ['printer01' => 3, 'printer02' => 1, ...]
    links = [];
    pData.forEach(printer => {
        printers[printer.model.name + ' (' + printer.room + ')'] = printer.events_sum_amount;
        links.push('/printers/' + printer.idPrinter + '/detail');
    });

    // Transform the associative array into two arrays (labels and data)
    const labels = Object.keys(printers);
    const data = Object.values(printers);

    const chartData = {
        labels: labels,
        datasets: [{
            label: 'Nombre d\'items consommés',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgb(75, 192, 192)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',

            borderWidth: 1,
            data: data,
            links: links
        }]
    };
    const chartConfig = {
        type: 'bar',
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

    // Add a click event on the chart
    mainChart.canvas.onclick = function (evt) {
        const activePoints = mainChart.getElementsAtEventForMode(evt, 'index', mainChart.options);
        if (activePoints[0]) {
            const idx = activePoints[0]['index'];
            const link = links[idx];
            window.open(link, '_blank');
        }
    };
}

// Event - When the user change the amount of printers to display
// Change the limit attribute and refresh the table
function limitChanged(newLimit) {
    limit = newLimit;
    refresh();
}

function startDateChanged(newStartDate) {
    startDate = newStartDate;
    refresh();
}

function endDateChanged(newEndDate) {
    endDate = newEndDate;
    refresh();
}

function refresh(){
    let url = baseUrl;

    url += '?limit=' + limit;

    if (startDate != null && startDate !== '') {
        url += '&startDate=' + startDate;
    }
    if (endDate != null && endDate !== '') {
        url += '&endDate=' + endDate;
    }

    callApiGet(url, displayChart);
}
