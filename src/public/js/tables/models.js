// Value for changing the displayed items (only for this table)
let search = '';
const SEARCH_COLUMN = 'name'; // For this table, the search is only on 1 column

/**
 * Generate the HTML tags for the models table.
 * @param {Object} data
 */
function displayModelsTable(data) {
    const models = data.data;
    const tableBody = $('#modelsTable_body');
    tableBody.empty();

    if (models.length === 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    } else {
        for (let i = 0; i < models.length; i++) {
            const model = models[i];
            const modelId = model.idPrinterModel;
            const modelBrand = model.brand;
            const modelName = model.name;

            const urlEdit = '/models/' + modelId + '/edit';
            const urlDelete = '/api/models/' + modelId;
            const modelActions = `<a href="${urlEdit}" class="btn btn-success btn-xs me-1"><i class="bi bi-pencil-fill"></i></a>` +
                `<button class="btn btn-danger btn-xs me-1" onclick="btnDeleteClicked(${modelId}, '${urlDelete}')"><i class="bi bi-trash3-fill"></i></button>`;
            tableBody.append(`<tr><td>${modelId}</td><td>${modelBrand}</td><td>${modelName}</td><td>${modelActions}</td></tr>`);
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

    callApiGet(url, displayModelsTable);
}

/**
 * Change the search string and refresh the table.
 */
function searchChanged() {
    search = $('#search').val();

    currentPage = 1;
    debounceFunction(refreshTable, 300);
}
