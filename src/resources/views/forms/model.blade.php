<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if (isset($idPrinterModel))
                {{ __("Modifier un modèle d'imprimante") }}
            @else
                {{ __("Ajouter un modèle d'imprimante") }}
            @endif
        </h2>
    </x-slot>

    <div class="m-auto w-50 d-flex justify-content-center align-items-center flex-column">
        <div id="alerts"></div>
        
        <div>
            <div class="form-group">
                <label for="brand">Marque</label>
                <input type="text" class="form-control" id="brand" name="brand" placeholder="Marque" maxlength="60" onkeydown="keyDown(event)">
            </div>
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom du modèle" maxlength="60" onkeydown="keyDown(event)">
            </div>
            <button class="btn btn-primary" onclick="submit()">Enregistrer</button>
        </div>
    </div>
    
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/create/model.js') }}"></script>
    <script>
        var mode = '';
        var sendUrl = '';
        @if (isset($idPrinterModel))
            mode = 'edit';
            sendUrl = "{{ route('api.models.update', ['printerModel' => $idPrinterModel])  }}"; // URl to send data
            loadModel("{{ route('api.models.show', ['printerModel' => $idPrinterModel]) }}");
        @else
            mode = 'create';
            sendUrl = "{{ route('api.models.store') }}";
        @endif
    </script>
</x-app-layout>
