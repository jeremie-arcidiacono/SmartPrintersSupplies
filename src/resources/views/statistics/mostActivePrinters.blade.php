<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Liste des imprimantes qui ont utilisé le plus de consommables") }}
        </h2>
    </x-slot>

    <p class="text-center p-6 fs-2" id="errorMsg">Aucune donnée à afficher</p>
    <div class="p-12">
        <canvas id="stockChart"></canvas>
    </div>


    <div class="w-50 p-3 float-end card card-block card-stretch card-height">
        <!-- Number of rows -->
        <div class="px-3 float-start">
            <label for="limit">Afficher </label>
            <select class="form-control-sm" id="limit" onchange="limitChanged(this.value)">
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="50">50</option>
            </select>
            <label for="limit"> imprimantes</label>
        </div>

        <!-- Date -->
        <div class="px-3 float-end">
            <label for="startDate">Depuis le </label>
            <input type="date" class="form-control-sm" id="startDate" onchange="startDateChanged(this.value)" max="{{ now()->format("Y-m-d") }}">
            <label for="startDate"> inclus</label>

            <label class="ps-3" for="endDate">Jusqu'au </label>
            <input type="date" class="form-control-sm" id="endDate" onchange="endDateChanged(this.value)" max="{{ now()->format("Y-m-d") }}">
        </div>
    </div>

    <script src="{{ asset('js/common.js') }}"></script>
    <script src="{{ asset('js/statistics/mostActivePrinters.js') }}"></script>
    <script src="{{ asset('js/lib/chart.min.js') }}"></script>
    <script>
        const baseUrl = "{{ route('api.printers.mostActive') }}";
        $(document).ready(refresh);
    </script>
</x-app-layout>
