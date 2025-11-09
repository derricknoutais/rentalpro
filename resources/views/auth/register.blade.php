@extends('layouts.app')

@section('content')
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-900 flex-col justify-between p-12 text-white">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-xs uppercase tracking-wide">
                    <span class="h-2 w-2 rounded-full bg-emerald-300 animate-pulse"></span>
                    Rejoindre RentalPro
                </div>
                <h1 class="mt-10 text-4xl font-semibold leading-tight">Une seule plateforme pour gérer votre flotte, vos chambres et vos équipes.</h1>
                <p class="mt-6 text-sm leading-relaxed text-indigo-100 max-w-lg">
                    Simplifiez la gestion des contrats, automatisez les rappels et gardez une vision claire de vos paiements.
                    RentalPro accompagne déjà des agences de location, résidences et conciergeries en Afrique centrale.
                </p>
            </div>
            <div class="rounded-3xl bg-white/10 border border-white/15 backdrop-blur-xl p-6 space-y-4">
                <p class="text-sm font-medium text-indigo-100 uppercase tracking-wide">Ce que vous obtenez :</p>
                <ul class="space-y-3 text-sm text-indigo-50">
                    <li class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-indigo-300"></span> Contrats numériques et signatures intégrées</li>
                    <li class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-indigo-300"></span> Planification visuelle des véhicules/chambres</li>
                    <li class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-indigo-300"></span> Alertes automatiques (paiements, entretiens, expirations)</li>
                </ul>
            </div>
        </div>

        <div class="flex items-center justify-center px-6 py-12">
            <div class="w-full max-w-md space-y-10">
                <div class="text-center space-y-4">
                    <img src="/img/logoonly.png" class="mx-auto h-16" alt="RentalPro">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Créer mon espace RentalPro</h2>
                        <p class="text-sm text-gray-500">Complétez les informations ci-dessous pour commencer gratuitement.</p>
                    </div>
                    @if ($errors->any())
                        <div class="rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700 text-left">
                            <p class="font-semibold">Nous ne pouvons pas créer votre compte</p>
                            <ul class="mt-2 list-disc pl-4 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-gray-700">Nom complet</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            placeholder="Ex : Christelle Biyogho">
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-gray-700">Adresse e-mail professionnelle</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            placeholder="agence@exemple.com">
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                        <input id="password" name="password" type="password" required autocomplete="new-password"
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            placeholder="••••••••">
                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full rounded-2xl border border-gray-200 px-4 py-3 text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-white shadow-sm"
                            placeholder="••••••••">
                    </div>

                    <p class="text-xs text-gray-500">
                        En créant un compte, vous acceptez nos conditions d’utilisation et notre politique de confidentialité.
                    </p>

                    <button type="submit"
                        class="w-full rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:bg-indigo-500 transition">
                        Créer mon compte
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500">
                    Vous avez déjà un compte ?
                    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
@endsection
