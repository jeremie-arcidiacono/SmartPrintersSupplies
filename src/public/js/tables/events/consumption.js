// Value for changing the displayed items (only for this table)
const search = {};

/**
 * Generate the HTML for the events table
 * @param {Object} data
 */
function displayEventsTable(data) {
    const events = data.data;
    const tableBody = $('#eventsTable_body');
    tableBody.empty();

    if (events.length === 0) {
        // No events found
        tableBody.append(`<tr><td class="text-center" colspan="6">Aucun élément trouvé</td></tr>`);
    } else {
        for (let i = 0; i < events.length; i++) {
            const event = events[i];
            const eventId = event.idEvent;
            const eventDate = event.created_at;
            const eventUser = event.author.username;
            const eventSupplyId = event.target_supply.idSupply;
            const eventSupplyName = event.target_supply.code;
            const eventQuantity = event.amount;

            let htmlString = "<tr>";

            htmlString += `<td>${eventId}</td>`;
            htmlString += `<td>${eventDate}</td>`;
            htmlString += `<td>${eventUser}</td>`;

            if (event.target_printer != null) {
                const eventPrinterId = event.target_printer.idPrinter;
                const eventPrinterCti = event.target_printer.cti;
                htmlString += `<td><a href="/printers/${eventPrinterId}/detail">${eventPrinterCti}</a></td>`;
            } else {
                htmlString += `<td class="text-secondary">-</td>`;
            }

            htmlString += `<td><a href="/supplies/${eventSupplyId}/detail">${eventSupplyName}</a></td>`;

            if (Math.sign(eventQuantity) === 1) {
                htmlString += `<td class="text-success">+${eventQuantity}</td>`;
            } else if (Math.sign(eventQuantity) === -1) {
                htmlString += `<td class="text-danger">${eventQuantity}</td>`;
            } else {
                htmlString += `<td">${eventQuantity}</td>`;
            }

            htmlString += "</tr>";
            tableBody.append(htmlString);
        }
    }
    displayPagination(data);
}

/**
 * Recall the API (with the new filters) and refresh the table with the new data.
 */
function refreshTable() {
    let url = baseUrl;

    url += '&page=' + currentPage;
    url += '&perPage=' + perPage;

    if (sortColumn !== '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }

    if (search != null) {
        for (const column in search) {
            const searchValue = search[column];
            if (searchValue !== '') {
                url += '&search[' + column + ']=' + searchValue;
            }
        }
    }

    callApiGet(url, displayEventsTable);
}

/**
 * Change the search string for the given column and refresh the table.
 * @param {string} searchField
 */
function searchChanged(searchField) {
    searchColumn = $(searchField).attr('id');
    search[searchColumn] = $(searchField).val();

    currentPage = 1; // Reset the page number
    refreshTable();
}
