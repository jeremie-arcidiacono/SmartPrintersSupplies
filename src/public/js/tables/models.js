// Value for changing the displayed items (only for this table)
var search = '';
const SEARCH_COLUMN = 'name';

// Generate the HTML DOM for the models table
function displayModelsTable(data) {
    var models = data.data;
    var tableBody = $('#modelsTable_body');
    tableBody.empty();

    if (models.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    }
    else{
        for (var i = 0; i < models.length; i++) {
            var model = models[i];
            var modelId = model.idPrinterModel;
            var modelName = model.name;

            var urlEdit = '/models/' + modelId + '/edit';
            var modelActions = '<a href="' + urlEdit + '" class="btn btn-primary btn-xs"><i class="bi bi-pencil-fill"></i></a>' +
                '<a href="#" class="btn btn-danger btn-xs" onclick=""><i class="bi bi-trash3-fill"></i></a>';
                tableBody.append('<tr><td>' + modelId + '</td><td>' + modelName + '</td><td>' + modelActions + '</td></tr>');
        }
    }
    displayPagination(data);
}

function refreshTable(){
    var url = baseURL;

    url += '?page=' + currentPage;
    url += '&perPage=' + perPage;

    if (search != '') {
        url += '&search=' + search;
        url += '&searchColumn=' + SEARCH_COLUMN;
    }
    if (sortColumn != '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }

    callApiGet(url, displayModelsTable);
}


function searchChanged() {
    search = $('#search').val();

    currentPage = 1;
    refreshTable();
}