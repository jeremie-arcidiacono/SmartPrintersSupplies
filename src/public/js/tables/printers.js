// Value for changing the displayed items (only for this table)
var search = '';
var searchColumn = $('#searchColum').val();

// Generate the HTML DOM for the printers table
function displayPrintersTable(data) {
    var printers = data.data;
    var tableBody = $('#printersTable_body');
    tableBody.empty();

    if (printers.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    }
    else{
        for (var i = 0; i < printers.length; i++) {
            var printer = printers[i];
            var printerId = printer.idPrinter;
            var printerBrand = printer.model.brand;
            var printerModel = printer.model.name;
            var printerRoom = printer.room;
            var printerSerialNumber = printer.serialNumber;
            var printerCti = printer.cti;

            if (printerRoom == null) {
                printerRoom = '-';
            }

            var urlShow = '/printers/' + printerId + '/detail';
            var urlEdit = '/printers/' + printerId + '/edit';
            var urlDelete = '/api/printers/' + printerId;
            var printerActions = `<a href="${urlShow}" class="btn btn-primary btn-xs"><i class="bi bi-eye-fill"></i></a>` +
            `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
            `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${printerId}, '${urlDelete}')"><i class="bi bi-trash3-fill"></i></button>`;
            tableBody.append(`<tr><td>${printerId}</td><td>${printerBrand}</td><td>${printerModel}</td><td>${printerRoom}</td><td>${printerSerialNumber}</td><td>${printerCti}</td><td>${printerActions}</td></tr>`);
        }
    }
    displayPagination(data);
}

function refreshTable(){
    var url = baseUrl;

    url += '?page=' + currentPage;
    url += '&perPage=' + perPage;

    if (search != '') {
        if(searchColumn == 'model') {
            url += '&searchModel=' + search;
        }
        else{
            url += '&search=' + search;
            url += '&searchColumn=' + searchColumn;
        }
    }
    if (sortColumn != '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }

    callApiGet(url, displayPrintersTable);
}


function searchChanged() {
    searchColumn = $('#searchColumn').val();
    search = $('#search').val();

    currentPage = 1;
    debounceFunction(refreshTable, 300);
}