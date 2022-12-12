<x-app-layout>
    <x-delete-item/>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Consommable : $idSupply") }}
        </h2>
    </x-slot>

    <div class="py-12 float-start" style="width: 35%">
        <div class="px-3 pb-3" id="info">
            <div>
                <span># :</span>
                <span id="idSupply"></span>
            </div>
            <div>
                <span>Marque :</span>
                <span id="brand"></span>
            </div>
            <div>
                <span>Référence :</span>
                <span id="code"></span>
            </div>
            <div>
                <span>Quantité :</span>
                <span id="quantity"></span>
            </div>
            <div id="actions"></div>
        </div>

        <div class="px-3 card">
            <div class="card-body">
                <h5 class="card-title">Modèles d'imprimantes compatibles</h5>
                <table class="table">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nom</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody class="table-group-divider" id="modelsTable_body"></tbody>
                </table>
            </div>
            <div class="card-body" id='addCompatibiltiyContainer'>
                <button class="btn btn-success" onclick="btnDisplayIncompatibleModelsClicked()"><i
                        class="bi bi-plus"></i>Ajouter une compatibilité consommable-modèle
                </button>
                <p class="text-danger"></p>

                <div class="px-3 card" id="addCompatibilityCard" style="display: none">
                    <div class="card-body">
                        <h5 class="card-title">Cliquez sur un bouton pour rendre le modèle d'imprimante compatible avec
                            ce consommable</h5>
                        <table class="table">
                            <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nom du modèle</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody class="table-group-divider" id="addCompatibilityTable_body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="py-12 float-end" style="width: 60%">
        <canvas id="stockChart"></canvas>
    </div>

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/supply.js') }}"></script>
    <script src="{{ asset('js/lib/chart.min.js') }}"></script>
    <script>
        var baseUrl = "{{ route('api.supplies.show', ['supply' => $idSupply]) }}";
        var compatibleModelsUrl = "{{ route('api.supplies.indexCompatibility', ['supply' => $idSupply]) }}";
        var suppliesStatisticsUrl = "{{ route('api.supplies.stockHistory', ['supply' => $idSupply, 'showAll' => true]) }}";
        $(document).ready(function () {
            callApiGet(baseUrl, displaySupplyInfos);
            callApiGet(suppliesStatisticsUrl, displayChart);
        });
    </script>
</x-app-layout>
