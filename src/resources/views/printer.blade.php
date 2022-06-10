<x-app-layout>
    <x-delete-item />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Imprimante : $idPrinter") }}
        </h2>
    </x-slot>

    <div class="w-50 p-3 float-start overflow-hidden">
        <div class="w-100 p-3" id="info">
            <div>
                <span># :</span>
                <span id="idPrinter"></span>
            </div>
            <div>
                <span>Modèle :</span>
                <span id="model"></span>
            </div>
            <div>
                <span>Salle :</span>
                <span id="room"></span>
            </div>
            <div>
                <span>Numéro de série :</span>
                <span id="serialNumber"></span>
            </div>
            <div>
                <span>CTI :</span>
                <span id="cti"></span>
            </div>
            <div id="actions"></div>
        </div>

        <div class="w-75 py-3 card">
            <div class="card-body">
                <h5 class="card-title">Consommables compatibles</h5>
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Références</th>
                            <th scope="col" style="max-width: 30px">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="suppliesTable_body"></tbody>
                </table>
            </div>
            <div class="card-footer text-muted">
                Cliquez sur un bouton pour utiliser un consommable sur cette imprimante
            </div>
        </div>

    </div>
    <div class="w-50 p-3 float-end overflow-hidden">
        <div class="w-100 card">
            <div class="card-body">
                <h5 class="card-title">{{ config('modelQuery.printer_nbEvent') }} derniers consommables utilisés</h5>
                <table class="table">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Utilisateur</th>
                            <th scope="col">Date</th>
                            <th scope="col">Consommable</th>
                            <th scope="col">Nombre</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="eventsTable_body"></tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/printer.js') }}"></script>
    <script>
        var baseUrl = "{{ route('api.printers.show', ['printer' => $idPrinter]) }}";
        var eventsUrl = "{{ route('api.printers.events', ['printer' => $idPrinter]) }}";
        var suppliesGenericUrl = "{{ route('api.models.index') . '/#idPrinterModel/compatibilities' }}"; // URL but without the model id
        var suppliesUrl = ""; // URL with the model id (it will be set when the printers infos are fetched)
        $(document).ready(function () {
            callApiGet(baseUrl, displayPrinterInfos);
        });
    </script>
</x-app-layout>