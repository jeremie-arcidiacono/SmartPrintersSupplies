<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold fs-4 text-gray-800 leading-tight">
            {{ __("Utilisateurs") }}
        </h2>
    </x-slot>

    <x-slot name="addButton">
        {{ __("Créer un compte") }}
    </x-slot>


    @error('disable')
    <div class="alert alert-danger 2y-1" role="alert">
        {{ $message }}
    </div>
    @enderror
    <!-- Table -->
    <div class="py-12">
        <table class="table">
            <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nom d'utilisateur</th>
                <th scope="col">Email</th>
                <th scope="col">Status</th>
                <th scope="col">Inscrit depuis</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            @foreach ($users as $user)
                <tr>
                    <th scope="row">{{ $user->idUser }}</th>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="align-middle fs-6">
                        @if ($user->status == 1)
                            <span class="badge text-bg-success">Actif</span>
                        @else
                            <span class="badge text-bg-danger">Inactif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('users.toggleStatus', ['user' => $user]) }}">
                            @method('PATCH')
                            @csrf
                            <button type="submit" class="btn btn-primary">Activer/Désactiver</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
