<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
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
                <label for="code">Référence</label>
                <input type="text" class="form-control" id="code" name="code" placeholder="Référence" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantité</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantité" required>
            </div>
            <button class="btn btn-primary" onclick="submit()">Enregistrer</button>
        </div>
    </div>
    
    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/create/supply.js') }}"></script>
    <script>
        @if (isset($idSupply))
            const MODE = 'edit';
            var sendUrl = "{{ route('api.supplies.update', ['supply' => $idSupply])  }}"; // URl to send data
            loadSupply("{{ route('api.supplies.show', ['supply' => $idSupply]) }}");
        @else
            const MODE = 'create';
            var sendUrl = "{{ route('api.supplies.store') }}";
        @endif
    </script>
</x-app-layout>
