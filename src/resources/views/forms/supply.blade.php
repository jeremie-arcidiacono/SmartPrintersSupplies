<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold fs-4 text-gray-800 leading-tight">
            @if (isset($idSupply))
                {{ __("Modifier un consommable") }}
            @else
                {{ __("Ajouter un consommable") }}
            @endif
        </h2>
    </x-slot>

    <div class="m-auto w-50 d-flex justify-content-center align-items-center flex-column">
        <div id="alerts"></div>

        <div>
            <div class="form-group">
                <label for="brand">Marque</label>
                <input type="text" class="form-control" id="brand" name="brand" placeholder="Marque" required
                       onkeydown="keyDown(event)">
            </div>
            <div class="form-group">
                <label for="code">Référence</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Référence" required
                       onkeydown="keyDown(event)">
            </div>
            <div class="form-group">
                <label for="quantity">Quantité</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantité" required
                       onkeydown="keyDown(event)">
            </div>
            <button class="btn btn-primary" onclick="submit()">Enregistrer</button>
        </div>
    </div>

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/create/supply.js') }}"></script>
    <script>
        var mode = '';
        var sendUrl = '';
        @if (isset($idSupply))
            mode = 'edit';
        sendUrl = "{{ route('api.supplies.update', ['supply' => $idSupply])  }}"; // URl to send data
        loadSupply("{{ route('api.supplies.show', ['supply' => $idSupply]) }}");
        @else
            mode = 'create';
        sendUrl = "{{ route('api.supplies.store') }}";
        @endif
    </script>
</x-app-layout>
