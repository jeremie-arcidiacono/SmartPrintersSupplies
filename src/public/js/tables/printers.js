// Value for changing the displayed items (only for this table)
let search = '';
let searchColumn = $('#searchColum').val();

// For this table, the search is possible on many columns

/**
 * Generate the HTML tags for the printers table.
 * @param {Object} data
 */
function displayPrintersTable(data) {
    const printers = data.data;
    const tableBody = $('#printersTable_body');
    tableBody.empty();

    if (printers.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    } else {
        for (let i = 0; i < printers.length; i++) {
            const printer = printers[i];
            const printerId = printer.idPrinter;
            const printerBrand = printer.model.brand;
            const printerModel = printer.model.name;
            const printerRoom = printer.room ?? '-';
            const printerSerialNumber = printer.serialNumber;
            const printerCti = printer.cti;

            const urlShow = '/printers/' + printerId + '/detail';
            const urlEdit = '/printers/' + printerId + '/edit';
            const urlDelete = '/api/printers/' + printerId;
            const printerActions = `<a href="${urlShow}" class="btn btn-primary btn-xs"><i class="bi bi-eye-fill"></i></a>` +
                `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
                `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${printerId}, '${urlDelete}')"><i class="bi bi-trash3-fill"></i></button>`;
            tableBody.append(`<tr><td>${printerId}</td><td>${printerBrand}</td><td>${printerModel}</td><td>${printerRoom}</td><td>${printerSerialNumber}</td><td>${printerCti}</td><td>${printerActions}</td></tr>`);
        }
    }
    displayPagination(data);
}

/**
 * Recall the API (with the new filters) and refresh the table with the new data.
 */
function refreshTable() {
    let url = baseUrl;

    url += '?page=' + currentPage;
    url += '&perPage=' + perPage;

    if (search !== '') {
        if (searchColumn === 'model') {
            url += '&searchModel=' + search;
        } else {
            url += '&search=' + search;
            url += '&searchColumn=' + searchColumn;
        }
    }
    if (sortColumn !== '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }

    callApiGet(url, displayPrintersTable);
}

/**
 * Change the search string and refresh the table.
 */
function searchChanged() {
    searchColumn = $('#searchColumn').val();
    search = $('#search').val();

    currentPage = 1;
    debounceFunction(refreshTable, 300);
}
