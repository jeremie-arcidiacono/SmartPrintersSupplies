// Value for changing the displayed items (only for this table)
var search = {};

// Generate the HTML DOM for the events table
function displayEventsTable(data) {
    var events = data.data;
    var tableBody = $('#eventsTable_body');
    tableBody.empty();

    if (events.length == 0) {
        tableBody.append(`<tr><td class="text-center" colspan="6">Aucun élément trouvé</td></tr>`);
    }
    else{
        for (var i = 0; i < events.length; i++) {
            var event = events[i];
            var eventId = event.idEvent;
            var eventDate = event.created_at;
            var eventUser = event.author.username;
            var eventSupplyId = event.target_supply.idSupply;
            var eventSupplyName = event.target_supply.code;
            var eventQuantity = event.amount;

            var htmlString = "<tr>";

            htmlString += `<td>${eventId}</td>`;
            htmlString += `<td>${eventDate}</td>`;
            htmlString += `<td>${eventUser}</td>`;

            if(event.target_printer != null){
                var eventPrinterId = event.target_printer.idPrinter;
                var eventPrinterCti = event.target_printer.cti;
                htmlString += `<td><a href="/printers/${eventPrinterId}/detail">${eventPrinterCti}</a></td>`;
            }
            else{
                htmlString += `<td class="text-secondary">-</td>`;
            }

            htmlString += `<td><a href="/supplies/${eventSupplyId}/detail">${eventSupplyName}</a></td>`;

            if (Math.sign(eventQuantity) == 1) {
                htmlString += `<td class="text-success">+${eventQuantity}</td>`;
            }
            else if (Math.sign(eventQuantity) == -1) {
                htmlString += `<td class="text-danger">${eventQuantity}</td>`;
            }
            else{
                htmlString += `<td">${eventQuantity}</td>`;
            }

            htmlString += "</tr>";
            tableBody.append(htmlString);
        }
    }
    displayPagination(data);
}

function refreshTable(){
    var url = baseUrl;

    url += '&page=' + currentPage;
    url += '&perPage=' + perPage;

    if (sortColumn != '') {
        url += '&sort=' + sortColumn;
        url += '&dir=' + sortDir;
    }

    if (search != null) {
        for (const column in search) {
            const searchValue = search[column];
            url += '&search[' + column + ']=' + searchValue;
        }
    }

    callApiGet(url, displayEventsTable);
}


function searchChanged(searchField) {
    searchColumn = $(searchField).attr('id');
    searchValue = $(searchField).val();

    search[searchColumn] = searchValue;

    currentPage = 1;
    refreshTable();
}