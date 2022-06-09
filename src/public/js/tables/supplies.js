// Value for changing the displayed items (only for this table)
var search = '';
var quantityMin = '';
var quantityMax = '';
const SEARCH_COLUMN = 'code';

// Generate the HTML DOM for the supplies table
function displaySuppliesTable(data) {
    var supplies = data.data;
    var tableBody = $('#suppliesTable_body');
    tableBody.empty();

    if (supplies.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="5">Aucun élément trouvé</td></tr>`);
    }
    else{
        for (var i = 0; i < supplies.length; i++) {
            var supply = supplies[i];
            var supplyId = supply.idSupply;
            var supplyCode = supply.code;
            var supplyQuantity = supply.quantity;

            var urlEdit = '/supplies/' + supplyId + '/edit';
            var urlDelete = '/api/supplies/' + supplyId;
            var modelActions = `<a href="${urlEdit}" class="btn btn-primary btn-xs"><i class="bi bi-pencil-fill"></i></a>` +
                `<button class="btn btn-danger btn-xs" onclick="btnDeleteClicked(${supplyId}, '${urlDelete}')"><i class="bi bi-trash3-fill"></i></button>`;
            tableBody.append(`<tr><td>${supplyId}</td><td>${supplyCode}</td><td>${supplyQuantity}</td><td>${modelActions}</td></tr>`);        
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
    if (quantityMin != '') {
        url += '&quantityMin=' + quantityMin;
    }
    if (quantityMax != '') {
        url += '&quantityMax=' + quantityMax;
    }

    callApiGet(url, displaySuppliesTable);
}


function searchChanged() {
    search = $('#search').val();

    if (isNaN($('#quantityMin').val()) || isNaN($('#quantityMax').val())) {
        $('#quantityError').removeClass('visually-hidden');
    }
    else {
        $('#quantityError').addClass('visually-hidden');
        quantityMin = $('#quantityMin').val();
        quantityMax = $('#quantityMax').val();
    }

    currentPage = 1;
    refreshTable();
}