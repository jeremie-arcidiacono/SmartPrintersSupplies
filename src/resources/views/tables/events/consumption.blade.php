<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Historique des changements de stock de consommables") }}
        </h2>
    </x-slot>

    <!-- Table settings -->
    <div class="py-12 overflow-hidden">
        <!-- Number of rows -->
        <div class="w-25 px-3 float-start">
            <label>Afficher </label>
            <select class="form-control-sm" id="perPage" onchange="perPageChanged(this.value)">
                <option value="10">10</option>
                <option value="25" selected="selected">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label> lignes</label>
        </div>
        <!-- Filters -->
        <div class="w-50 p-3 float-end card card-block card-stretch card-height">
            <div class="d-inline-flex">
                <label for="author">Utilisateur : </label>
                <input type="text" class="form-control ms-1" placeholder="Rechercher..." id="author" name="author" style="max-width: 370px;" oninput="searchChanged(this)">
            </div>
            <div class="d-inline-flex">
                <label for="printerCti">Imprimante CTI : </label>
                <input type="text" class="form-control ms-1" placeholder="Rechercher..." id="printerCti" name="printerCti" style="max-width: 370px;" oninput="searchChanged(this)">
            </div>
            <div class="d-inline-flex">
                <label for="supply">Consommable : </label>
                <input type="text" class="form-control ms-1" placeholder="Rechercher..." id="supply" name="supply" style="max-width: 370px;" oninput="searchChanged(this)">
            </div>

        </div>
    </div>

    <!-- Table -->
    <div class="">
        <table class="table" id="eventsTable">
            <thead class="table-light">
                <tr>
                    <th scope="col" onclick="sortChanged('idEvent')">#
                        <i class="bi bi-arrow-up ps-1" id="sort_up_idEvent" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_idEvent" style="color: rgb(183, 183, 207);"></i>
                    </th>
                    <th scope="col" onclick="sortChanged('created_at')">Date
                        <i class="bi bi-arrow-up ps-1" id="sort_up_created_at" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_created_at" style="color: rgb(183, 183, 207);"></i>
                    </th>
                    <th scope="col">Utilisateur</th>
                    <th scope="col">Imprimante (CTI)</th>
                    <th scope="col">Consommable</th>
                    <th scope="col" onclick="sortChanged('amount')">Quantité changée
                        <i class="bi bi-arrow-up ps-1" id="sort_up_amount" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_amount" style="color: rgb(183, 183, 207);"></i>
                    </th>
                </tr>
            </thead>
            <tbody class="table-group-divider" id="eventsTable_body">
                
            </tbody>
        </table>

        <nav >
            <ul class="pagination" id="paginationContainers"></ul>
        </nav>
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/tables/common.js') }}"></script>
    <script src="{{ asset('js/tables/events/consumption.js') }}"></script>
    <script>
        var baseUrl = "{{ route('api.events.index', ['type[]' => 'changeAmount']) }}"
        $(document).ready(function() {
            callApiGet(baseUrl, displayEventsTable);
        });
    </script>
</x-app-layout>