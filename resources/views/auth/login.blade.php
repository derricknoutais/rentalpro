@extends('layouts.app')

@section('content')
    <div class="min-h-screen grid lg:grid-cols-2">
        <div
            class="hidden lg:flex bg-gradient-to-br from-gray-900 via-indigo-900 to-gray-800 flex-col justify-between p-12 text-white">
            <div>
                <div
                    class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-xs uppercase tracking-wide">
                    <span class="h-2 w-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    Plateforme RentalPro
                </div>
                <h1 class="mt-10 text-4xl font-semibold leading-tight">Gérez vos locations avec un tableau de bord pensé pour
                    l’action quotidienne.</h1>
                <p class="mt-6 text-sm leading-relaxed text-indigo-100 max-w-lg">Visualisez vos contrats, paiements et
                    disponibilités en un clin d’œil. RentalPro accompagne vos équipes sur le terrain et au bureau.</p>
            </div>
            <div class="rounded-3xl p-6 space-y-4 bg-white/10 border border-white/15 backdrop-blur-xl">
                <p class="text-sm font-medium text-indigo-100 uppercase tracking-wide">Statut en direct</p>
                <div class="rounded-2xl bg-white/10 px-4 py-3 text-sm flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-white">Contrats actifs</p>
                        <p class="text-xs text-indigo-100">Mise à jour il y a 2 minutes</p>
                    </div>
                    <p class="text-2xl font-bold text-white">24</p>
                </div>
                <div class="rounded-2xl border border-white/15 px-4 py-3 text-sm text-indigo-100">
                    Prochaine restitution aujourd’hui à 18h15 · Toyota Prado · Client : NGUEMA P.
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md space-y-10">
                <div class="text-center space-y-4">
                    <img src="/img/rentalpro_logo.png" class="mx-auto h-48" alt="RentalPro">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Connexion à votre espace</h2>
                        <p class="text-sm text-gray-500">Entrez vos identifiants pour accéder à votre tableau de bord.</p>
                    </div>
                    @if ($errors->any())
                        <div class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 text-left">
                            <p class="font-semibold">Impossible de vous connecter</p>
                            <ul class="mt-2 list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-700">Adresse e-mail</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            value="{{ old('email') }}" placeholder="vous@exemple.com">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-gray-600">
                            <input type="checkbox" name="remember"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                {{ old('remember') ? 'checked' : '' }}>
                            Se souvenir de moi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="font-semibold text-indigo-600 hover:text-indigo-500">Mot de passe oublié ?</a>
                        @endif
                    </div>

                    <button type="submit"
                        class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-500 transition">
                        Se connecter
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Créer un
                        espace RentalPro</a>
                </p>
            </div>
        </div>
    </div>
@endsection
