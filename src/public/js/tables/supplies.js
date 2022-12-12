// Value for changing the displayed items (only for this table)
let search = '';
let quantityMin = '';
let quantityMax = '';
const SEARCH_COLUMN = 'code'; // For this table, the search is only on 1 column

/**
 * Generate the HTML tags for the supplies table.
 * @param {Object} data
 */
function displaySuppliesTable(data) {
    const supplies = data.data;
    const tableBody = $('#suppliesTable_body');
    tableBody.empty();

    if (supplies.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    } else {
        for (let i = 0; i < supplies.length; i++) {
            const supply = supplies[i];
            const supplyId = supply.idSupply;
            const supplyBrand = supply.brand;
            const supplyCode = supply.code;
            const supplyQuantity = supply.quantity;

            const urlShow = '/supplies/' + supplyId + '/detail';
            const urlEdit = '/supplies/' + supplyId + '/edit';
            const urlDelete = '/api/supplies/' + supplyId;
            const modelActions = `<a href="${urlShow}" class="btn btn-primary btn-xs"><i class="bi bi-eye-fill"></i></a>` +
                `<a href="${urlEdit}" class="btn btn-success btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
                `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${supplyId}, '${urlDelete}')"><i class="bi bi-trash3-fill"></i></button>`;
            tableBody.append(`<tr><td>${supplyId}</td><td>${supplyBrand}</td><td>${supplyCode}</td><td>${supplyQuantity}</td><td>${modelActions}</td></tr>`);
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
        url += '&search=' + search;
        url += '&searchColumn=' + SEARCH_COLUMN;
    }
    if (sortColumn !== '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }
    if (quantityMin !== '') {
        url += '&quantityMin=' + quantityMin;
    }
    if (quantityMax !== '') {
        url += '&quantityMax=' + quantityMax;
    }

    callApiGet(url, displaySuppliesTable);
}

/**
 * Change the search string, the quantityMin and the quantityMax limit and refresh the table.
 */
function searchChanged() {
    search = $('#search').val();

    if (isNaN($('#quantityMin').val()) || isNaN($('#quantityMax').val())) {
        $('#quantityError').removeClass('visually-hidden');
    } else {
        $('#quantityError').addClass('visually-hidden');
        quantityMin = $('#quantityMin').val();
        quantityMax = $('#quantityMax').val();
    }

    currentPage = 1;
    debounceFunction(refreshTable, 300);
}
