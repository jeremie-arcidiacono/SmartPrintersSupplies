<x-app-layout>
    <x-delete-item/>

    <x-slot name="header">
        <h2 class="font-semibold fs-4 text-gray-800 leading-tight">
            {{ __("Imprimantes") }}
        </h2>
    </x-slot>

    <x-slot name="addButton">
        {{ __("Ajouter une imprimante") }}
    </x-slot>

    <!-- Table settings -->
    <div class="py-12 overflow-hidden">
        <!-- Number of rows -->
        <div class="w-25 px-3 float-start">
            <label>Afficher </label>
            <select class="form-control-sm pe-4" id="perPage" onchange="perPageChanged(this.value)">
                <option value="10">10</option>
                <option value="25" selected="selected">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            <label> lignes</label>
        </div>
        <!-- Filters -->
        <div class="w-50 p-3 float-end card card-block card-stretch card-height">
            <div>
                <span>Rechercher dans la colonne :</span>
                <select class="form-select-sm" id="searchColumn" onchange="searchChanged()">
                    <option value="serialNumber" selected="selected">Numéro de série</option>
                    <option value="cti">CTI</option>
                    <option value="room">Salle</option>
                    <option value="model">Modèle</option>
                </select>
            </div>
            <input type="text" class="form-control mt-2" placeholder="Rechercher..." id="search"
                   style="max-width: 370px;" oninput="searchChanged()">
        </div>
    </div>

    <!-- Table -->
    <div class="">
        <table class="table" id="printersTable">
            <thead class="table-light">
            <tr>
                <th scope="col" onclick="sortChanged('idPrinter')">#
                    <i class="bi bi-arrow-up ps-1" id="sort_up_idPrinter" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_idPrinter" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col">Marque</th>
                <th scope="col">Modèle</th>
                <th scope="col" onclick="sortChanged('room')">Salle
                    <i class="bi bi-arrow-up ps-1" id="sort_up_room" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_room" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col" onclick="sortChanged('serialNumber')">Numéro de série
                    <i class="bi bi-arrow-up ps-1" id="sort_up_serialNumber" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_serialNumber" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col" onclick="sortChanged('cti')">CTI
                    <i class="bi bi-arrow-up ps-1" id="sort_up_cti" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_cti" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody class="table-group-divider" id="printersTable_body">

            </tbody>
        </table>

        <nav>
            <ul class="pagination" id="paginationContainers"></ul>
        </nav>
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/tables/common.js') }}"></script>
    <script src="{{ asset('js/tables/printers.js') }}"></script>
    <script>
        var baseUrl = "{{ route('api.printers.index') }}"
        $(document).ready(function () {
            callApiGet(baseUrl, displayPrintersTable);
        });
    </script>
</x-app-layout>
