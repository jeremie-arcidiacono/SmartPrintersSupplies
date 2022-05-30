@props(['successful'])

@if ($successful)
    <div {{ $attributes }}>
        <div class="font-medium text-green-600">
            {{ __('Utilisateur crée avec succes !') }}
        </div>
    </div>
@endif
