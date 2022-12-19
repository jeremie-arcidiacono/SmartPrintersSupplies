<x-app-layout>
    <x-delete-item/>

    <x-slot name="header">
        <h2 class="font-semibold fs-4 text-gray-800 leading-tight">
            {{ __('Modèles d\'imprimantes') }}
        </h2>
    </x-slot>

    <x-slot name="addButton">
        {{ __("Ajouter un modèle") }}
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
                <span>Rechercher dans la colonne : Nom</span>
            </div>
            <input type="text" class="form-control mt-2" placeholder="Rechercher..." id="search"
                   style="max-width: 370px;" oninput="searchChanged()">
        </div>
    </div>

    <!-- Table -->
    <div class="">
        <table class="table" id="modelsTable">
            <thead class="table-light">
            <tr>
                <th scope="col" onclick="sortChanged('idPrinterModel')">#
                    <i class="bi bi-arrow-up ps-1" id="sort_up_idPrinterModel" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_idPrinterModel" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col" onclick="sortChanged('brand')">Marque
                    <i class="bi bi-arrow-up ps-1" id="sort_up_brand" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_brand" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col" onclick="sortChanged('name')">Nom
                    <i class="bi bi-arrow-up ps-1" id="sort_up_name" style="color: rgb(183, 183, 207);"></i>
                    <i class="bi bi-arrow-down" id="sort_down_name" style="color: rgb(183, 183, 207);"></i>
                </th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody class="table-group-divider" id="modelsTable_body">

            </tbody>
        </table>

        <nav>
            <ul class="pagination" id="paginationContainers"></ul>
        </nav>
    </div>
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/tables/common.js') }}"></script>
    <script src="{{ asset('js/tables/models.js') }}"></script>
    <script>
        var baseUrl = "{{ route('api.models.index') }}"
        $(document).ready(function () {
            callApiGet(baseUrl, displayModelsTable);
        });
    </script>
</x-app-layout>
