// Value for changing the displayed items (common for all tables of items)
let currentPage = 1;
let perPage = $('#perPage').val();
let sortColumn = '';
let sortDir = '';

/**
 * Generate the HTML tags for the pagination buttons.
 * @param {Object} data
 */
function displayPagination(data) {
    const pageButtons = data.links;
    pageButtons.pop();
    pageButtons.shift();

    const pagination = $('#paginationContainers');
    pagination.empty();

    pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="pageChanged(1)">&laquo;&laquo;</a></li>`);

    pageButtons.forEach(function (pageButton) {
        const active = pageButton.active ? 'active' : '';
        pagination.append(`<li class="page-item ${active}"><a class="page-link" href="#" onclick="pageChanged(${pageButton.label})">${pageButton.label}</a></li>`);
    });

    pagination.append(`<li class="page-item"><a class="page-link" href="#" onclick="pageChanged(${data.last_page})">&raquo;&raquo;</a></li>`);
}

/**
 * Change the column to sort and the direction (ASC/DESC) of the sort.
 * Then refresh the table.
 * @param {string} column
 */
function sortChanged(column) {
    const color = 'rgb(183, 183, 207)'; // Color used when the column is not sorted
    if (column !== sortColumn) {
        $(`#sort_up_${sortColumn}`).css('color', color);
        $(`#sort_down_${sortColumn}`).css('color', color);
        $(`#sort_up_${column}`).css('color', '');
        sortColumn = column;
        sortDir = 'asc';
    } else {
        if (sortDir === 'asc') {
            sortDir = 'desc';
            $(`#sort_up_${column}`).css('color', color);
            $(`#sort_down_${column}`).css('color', '');
        } else {
            sortDir = 'asc';
            $(`#sort_up_${column}`).css('color', '');
            $(`#sort_down_${column}`).css('color', color);
        }
    }

    refreshTable();
}

/**
 * Change the current displayed page in a table.
 * @param {int} newPage
 */
function pageChanged(newPage) {
    if (newPage !== currentPage) {
        currentPage = newPage;
        refreshTable();
    }
}

/**
 * Change the number of items per page in a table.
 * @param {int} newPerPage
 */
function perPageChanged(newPerPage) {
    perPage = newPerPage;

    currentPage = 1;
    refreshTable();
}
