<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>RentalPro | Simplifiez vos locations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app2.css') }}" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .gradient {
            background: radial-gradient(circle at top, rgba(79, 70, 229, 0.25), transparent 55%),
                linear-gradient(135deg, #0f172a 0%, #111827 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(18px);
        }
    </style>
</head>

<body class="antialiased bg-gray-50 text-gray-900">
    <header class="gradient text-white">
        <div class="max-w-7xl mx-auto px-6 py-12 md:py-20">
            <nav class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-lg font-semibold tracking-wide uppercase">
                    <img src="/img/rentalpro_logo.png" alt="" class="h-32">
                </div>
                <div class="hidden md:flex items-center gap-4 text-sm font-medium">
                    <a href="#features" class="hover:text-indigo-300 transition">Fonctionnalités</a>
                    <a href="#stats" class="hover:text-indigo-300 transition">Performances</a>
                    <a href="#cta" class="hover:text-indigo-300 transition">Commencer</a>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-indigo-100 hover:text-white transition">Se connecter</a>
                    <a href="{{ route('register') }}"
                        class="rounded-full bg-indigo-500 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-indigo-500/40 hover:bg-indigo-400 transition">
                        Créer un compte
                    </a>
                </div>
            </nav>

            <div class="mt-16 grid gap-12 lg:grid-cols-2 lg:items-center">
                <div>
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-100">
                        <span class="h-2 w-2 rounded-full bg-green-400 animate-pulse"></span>
                        Disponible en 2025
                    </span>
                    <h1 class="mt-6 text-4xl md:text-5xl font-bold leading-tight">
                        Pilotez vos locations voitures & hébergements depuis une seule plateforme.
                    </h1>
                    <p class="mt-6 text-lg text-indigo-100 leading-relaxed">
                        Automatisez vos contrats, suivez les paiements, visualisez la disponibilité de votre flotte
                        et transformez vos données en décisions concrètes. RentalPro accompagne les équipes qui veulent
                        gagner du temps et rassurer leurs clients.
                    </p>
                    <div class="mt-8 flex flex-wrap gap-4">
                        <a href="{{ route('register') }}"
                            class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-indigo-600 shadow-xl shadow-indigo-500/30 hover:translate-y-0.5 transition">
                            Démarrer gratuitement
                        </a>
                        <a href="#features"
                            class="rounded-full border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                            Découvrir les fonctionnalités
                        </a>
                    </div>
                    <div class="mt-10 flex flex-wrap items-center gap-6 text-xs text-indigo-100/90">
                        <div>
                            <p class="text-2xl font-semibold text-white">+2 000</p>
                            agences & gestionnaires
                        </div>
                        <div class="hidden h-8 w-px bg-white/20 md:block"></div>
                        <div>
                            <p class="text-2xl font-semibold text-white">98%</p>
                            taux de satisfaction
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <div class="glass rounded-3xl p-6 text-sm text-indigo-100 shadow-2xl">
                        <p class="text-xs font-semibold text-white/70 uppercase tracking-wide">Aperçu en direct</p>
                        <h2 class="mt-2 text-xl font-semibold text-white">Contrats du jour</h2>
                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl bg-white/5 p-4">
                                <div class="flex justify-between text-sm">
                                    <p class="font-medium text-white">Contrat #023/09/2025</p>
                                    <span class="rounded-full bg-green-500/20 px-3 py-0.5 text-xs text-green-300">En
                                        cours</span>
                                </div>
                                <p class="mt-2 text-xs text-white/70">Toyota Prado · 3 jours · 250 000 F CFA</p>
                                <div class="mt-4 flex items-center gap-2 text-xs text-white/60">
                                    <div class="h-1 w-full rounded-full bg-white/10">
                                        <div class="h-1 rounded-full bg-indigo-400" style="width: 62%"></div>
                                    </div>
                                    62%
                                </div>
                            </div>
                            <div class="rounded-2xl border border-white/10 p-4">
                                <p class="text-sm text-white/80">Flux de trésorerie</p>
                                <p class="mt-1 text-3xl font-semibold text-white">12,8 M F CFA</p>
                                <p class="text-xs text-green-300">+18% cette semaine</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="bg-white py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center">
                <span class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">Fonctionnalités</span>
                <h2 class="mt-3 text-3xl font-bold text-gray-900">Tout est prévu pour vos équipes</h2>
                <p class="mt-4 text-gray-500 text-lg">
                    Vente, opérations, finances : chacun dispose des bons outils pour gagner en efficacité.
                </p>
            </div>

            <div class="mt-12 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @php
                    $features = [
                        [
                            'title' => 'Contrats digitaux',
                            'desc' =>
                                'Générez et signez vos contrats en ligne, ajoutez des options, imprimez en un clic.',
                        ],
                        [
                            'title' => 'Plan des disponibilités',
                            'desc' => 'Visualisez vos véhicules et chambres en temps réel, anticipez les conflits.',
                        ],
                        [
                            'title' => 'Paiements & cautions',
                            'desc' => 'Encaissez, remboursez, suivez les cautions et exportez vos flux financiers.',
                        ],
                        [
                            'title' => 'Alertes automatiques',
                            'desc' => 'Rappels de restitution, entretien, renouvellement d’assurance ou de documents.',
                        ],
                        [
                            'title' => 'Reporting détaillé',
                            'desc' =>
                                'Taux d’occupation, CA, ROI maintenance… tout est accessible en quelques secondes.',
                        ],
                        [
                            'title' => 'API & intégrations',
                            'desc' => 'Connectez RentalPro à votre site, WhatsApp Business, outils comptables et CRM.',
                        ],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <div
                        class="rounded-2xl border border-gray-100 bg-gray-50 p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <div class="flex items-center justify-between">
                            <div
                                class="h-10 w-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 4h16v4H4zm0 6h10v10H4z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold uppercase tracking-wide text-indigo-400">Essentiel</span>
                        </div>
                        <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm text-gray-500">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="stats" class="bg-gray-900 text-white py-20">
        <div class="max-w-6xl mx-auto px-6 grid gap-10 lg:grid-cols-2">
            <div>
                <p class="text-sm font-semibold text-indigo-300 uppercase tracking-wide">Résultats</p>
                <h2 class="mt-3 text-3xl font-bold">Des entreprises gagnent déjà du temps avec RentalPro.</h2>
                <p class="mt-4 text-gray-300">
                    Loueurs de véhicules, résidences hôtelières, conciergeries et agences événementielles améliorent
                    leur rentabilité avec notre plateforme.
                </p>
                <div class="mt-8 grid gap-6 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-4xl font-semibold text-indigo-300">-35%</p>
                        <p class="text-sm text-gray-300">de temps passé sur la paperasse</p>
                    </div>
                    <div class="rounded-2xl bg-white/5 p-4">
                        <p class="text-4xl font-semibold text-indigo-300">+22%</p>
                        <p class="text-sm text-gray-300">de chiffre d’affaires en moyenne</p>
                    </div>
                </div>
            </div>
            <div class="rounded-3xl bg-white/5 p-8 space-y-6">
                <blockquote class="text-lg italic text-gray-100">
                    “Grâce à RentalPro, nous créons nos contrats en quelques minutes, suivons nos cautions et gardons
                    un œil sur nos véhicules. L’équipe support est ultra-réactive !”
                </blockquote>
                <div>
                    <p class="font-semibold text-white">Christelle B.</p>
                    <p class="text-sm text-gray-300">Directrice commerciale - STA Motors</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cta" class="bg-white py-20">
        <div
            class="max-w-5xl mx-auto rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 px-8 py-12 text-white text-center shadow-2xl">
            <h2 class="text-3xl font-bold">Prêt à moderniser vos contrats et vos paiements ?</h2>
            <p class="mt-4 text-lg text-indigo-100">
                Créez gratuitement votre compte, importez vos véhicules ou chambres et lancez vos premières locations.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-indigo-600 shadow-lg hover:translate-y-0.5 transition">
                    Créer mon compte gratuit
                </a>
                <a href="{{ route('login') }}"
                    class="rounded-full border border-white/40 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition">
                    J’ai déjà un compte
                </a>
            </div>
        </div>
    </section>
</body>

</html>
