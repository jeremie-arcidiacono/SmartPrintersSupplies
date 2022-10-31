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
                <label for="room">Salle</label>
                <input type="text" class="form-control" id="room" name="room" placeholder="Salle" onkeydown="keyDown(event)">
            </div>
            <div class="form-group">
                <label for="serialNumber">Numéro de série</label>
                <input type="text" class="form-control" id="serialNumber" name="serialNumber" placeholder="Numéro de série" required onkeydown="keyDown(event)">
            </div>
            <div class="form-group">
                <label for="cti">CTI</label>
                <input type="text" class="form-control" id="cti" name="cti" placeholder="CTI" required minlength="6" maxlength="6" onkeydown="keyDown(event)">
            </div>
            <button class="btn btn-primary" onclick="submit()">Enregistrer</button>
        </div>
    </div>
    
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/create/printer.js') }}"></script>
    <script>
        var modelUrl = '{{ route('api.models.index') }}';
        
        var mode = '';
        var sendUrl = '';
        loadModels().then(() => {
            @if (isset($idPrinter))
                mode = 'edit';
                sendUrl = "{{ route('api.printers.update', ['printer' => $idPrinter])  }}"; // URl to send data
                loadPrinter("{{ route('api.printers.show', ['printer' => $idPrinter]) }}");     // The loadPrinter() need to be called after the loadModels() function
            @else
                mode = 'create';
                sendUrl = "{{ route('api.printers.store') }}";
            @endif
        });
    </script>
</x-app-layout>
