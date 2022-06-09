<x-app-layout>
    <x-delete-item />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Consommables") }}
        </h2>
    </x-slot>

    <x-slot name="addButton">
        {{ __("Ajouter un consommable") }}
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
            <div>
                <span>Rechercher dans la colonne : Code</span>
            </div>
            <input type="text" class="form-control mt-2" placeholder="Rechercher..." id="search" style="max-width: 370px;" oninput="searchChanged()">
            <hr>
            <div>
                <label for="quantityMin">Quantité minimum :</label>
                <input type="number" class="form-control" id="quantityMin" placeholder="0" min="0" style="max-width: 370px;" oninput="searchChanged()">
                
                <label for="quantityMax">Quantité maximum :</label>
                <input type="number" class="form-control" id="quantityMax" placeholder="99999" min="0" style="max-width: 370px;" oninput="searchChanged()">

                <div class="alert alert-danger visually-hidden" role="alert" id="quantityError">
                    Problème de saisie ! Vérifiez que les champs de quantités contiennent uniquement des nombres.
                </div>                  
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="">
        <table class="table" id="suppliesTable">
            <thead class="table-light">
                <tr>
                    <th scope="col" onclick="sortChanged('idSupply')">#
                        <i class="bi bi-arrow-up ps-1" id="sort_up_idSupply" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_idSupply" style="color: rgb(183, 183, 207);"></i>
                    </th>
                    <th scope="col" onclick="sortChanged('code')">Référence
                        <i class="bi bi-arrow-up ps-1" id="sort_up_code" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_code" style="color: rgb(183, 183, 207);"></i>
                    </th>
                    <th scope="col" onclick="sortChanged('quantity')">Quantité
                        <i class="bi bi-arrow-up ps-1" id="sort_up_quantity" style="color: rgb(183, 183, 207);"></i>
                        <i class="bi bi-arrow-down" id="sort_down_quantity"style="color: rgb(183, 183, 207);"></i>
                    </th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider" id="suppliesTable_body">
                
            </tbody>
        </table>

        <nav >
            <ul class="pagination" id="paginationContainers"></ul>
        </nav>
    </div>
    <script src="{{ asset('js/callapi.js') }}"></script>
    <script src="{{ asset('js/tables/common.js') }}"></script>
    <script src="{{ asset('js/tables/supplies.js') }}"></script>
    <script>
        var baseURL = "{{ route('api.supplies.index') }}"
        $(document).ready(function() {
            callApiGet(baseURL, displaySuppliesTable);
        });
    </script>
</x-app-layout>
