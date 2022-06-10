// Value for changing the displayed items (common for all tables of items)
var currentPage = 1;
var perPage = $('#perPage').val();
var sortColumn = '';
var sortDir = '';

// Generate the pagination buttons in HTML
function displayPagination(data) {
    var pageButtons = data.links;
    pageButtons.pop();
    pageButtons.shift();

    var pagination = $('#paginationContainers');
    pagination.empty();
    
    pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="pageChanged(1)">&laquo;&laquo;</a></li>`);

    pageButtons.forEach(function (pageButton) {
        if(pageButton.active) {
            var active = 'active';
        } else {
            var active = '';
        }
        pagination.append(`<li class="page-item ${active}"><a class="page-link" href="#" onclick="pageChanged(${pageButton.label})">${pageButton.label}</a></li>`);
    });

    pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="pageChanged(${data.last_page})">&raquo;&raquo;</a></li>`);
}

// Event - When the user click on a column header
// Change the sort column/direction and refresh the table
function sortChanged(column) {
    var color = 'rgb(183, 183, 207)'; // Color used when the column is not sorted
    if (column != sortColumn) {
        $(`#sort_up_${sortColumn}`).css('color', color);
        $(`#sort_down_${sortColumn}`).css('color', color);
        $(`#sort_up_${column}`).css('color', '');
        sortColumn = column;
        sortDir = 'asc';
    }
    else {
        if (sortDir == 'asc') {
            sortDir = 'desc';
            $(`#sort_up_${column}`).css('color', color);
            $(`#sort_down_${column}`).css('color', '');
        }
        else {
            sortDir = 'asc';
            $(`#sort_up_${column}`).css('color', '');
            $(`#sort_down_${column}`).css('color', color);
        }
    }
    
    refreshTable();
}

// Event - When the user click on a pagination button
// Change the current page and refresh the table
function pageChanged(newPage) {
    if (newPage != currentPage) {
        currentPage = newPage;
        refreshTable();
    }
}

// Event - When the user change the number of items per page
// Change the per page attribute and refresh the table
function perPageChanged(newPerPage) {
    perPage = newPerPage;

    currentPage = 1;
    refreshTable();
}
