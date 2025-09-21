{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
@extends('layouts.slaves')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
                <div class="card mb-4 mt-2 shadow-sm">
                    <section class="container mt-4 mb-4">
                        <header>
                            <h2 class="text-lg font-medium text-dark">Se déconnecter</h2>
                        </header>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4">
                            @csrf
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn btn-danger">Déconnexion</button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
