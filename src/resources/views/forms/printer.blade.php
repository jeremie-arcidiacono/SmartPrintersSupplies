<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (isset($idPrinter))
                {{ __("Modifier une imprimante") }}
            @else
                {{ __("Ajouter une imprimante") }}
            @endif
        </h2>
    </x-slot>

    <div class="m-auto w-50 d-flex justify-content-center align-items-center flex-column">
        <div id="alerts"></div>
        
        <div>
            <div class="form-group">
                <label for="model">Modèle</label>
                <select class="form-control" id="modelList">
                    
                </select>
            </div>
            <div class="form-group">
                <label for="serialNumber">Numéro de série</label>
                <input type="text" class="form-control" id="serialNumber" name="serialNumber" placeholder="Numéro de série" required>
            </div>
            <div class="form-group">
                <label for="cti">CTI</label>
                <input type="text" class="form-control" id="cti" name="cti" placeholder="CTI" required>
            </div>
            <button class="btn btn-primary" onclick="submit()">Ajouter</button>
        </div>
    </div>
    
    <script src="{{ asset('js/callapi.js') }}"></script>
    <script src="{{ asset('js/create/printer.js') }}"></script>
    <script>
        var modelUrl = '{{ route('api.models.index') }}';
        loadModels();

        @if (isset($idPrinter))
            const MODE = 'edit';
            var sendUrl = "{{ route('api.printers.update', ['printer' => $idPrinter])  }}"; // URl to send data
            loadPrinter("{{ route('api.printers.show', ['printer' => $idPrinter]) }}");
        @else
            const MODE = 'create';
            var sendUrl = "{{ route('api.printers.store') }}";
        @endif
    </script>
</x-app-layout>
