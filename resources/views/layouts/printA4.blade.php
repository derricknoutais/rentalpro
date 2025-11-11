<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ mix('css/app2.css') }}">
    <title>Document</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        :root {
            --c-text: #1f2937;
            --c-muted: #6b7280;
            --c-border: #e5e7eb;
            --c-accent: #f97316;
            --c-soft: #f8fafc;
        }

        body {
            margin: 0;
            background: #f3f4f6;
        }

        #printable-area {
            max-width: 210mm;
            margin: 0 auto;
            padding: 0;
        }

        .sheet {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 14mm 16mm;
            box-sizing: border-box;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.12);
        }

        .sheet + .sheet {
            margin-top: 12px;
        }

        .sheet--last {
            break-after: auto !important;
            page-break-after: auto !important;
        }

        #printable-area > .sheet:last-child {
            break-after: auto !important;
            page-break-after: auto !important;
        }

        .print-toolbar {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 0;
        }

        @media print {
            body {
                background: #fff;
            }

            .sheet {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
            }

            .print-toolbar {
                display: none !important;
            }
        }

        @media print {
            .sheet {
                page-break-after: always;
            }

            .sheet--last {
                page-break-after: auto !important;
            }

            #printable-area > .sheet:last-child {
                page-break-after: auto !important;
            }
        }

        .facture {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--c-text);
            background: #fff;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 1.75rem;
        }

        .facture__header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            border-bottom: 2px solid var(--c-border);
            padding-bottom: 1.5rem;
        }

        .facture__brand {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .facture__logo {
            width: 88px;
            height: 88px;
            border-radius: 16px;
            background: linear-gradient(135deg, #0f172a, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 30px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .facture__brand h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 600;
        }

        .facture__meta {
            border: 1px solid var(--c-border);
            border-radius: 12px;
            padding: 1.25rem;
        }

        .facture__meta-title {
            text-align: right;
            font-weight: 600;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--c-border);
            padding-bottom: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .facture__meta dl {
            margin: 0;
            display: grid;
            row-gap: 0.35rem;
        }

        .facture__meta dt {
            font-size: 0.75rem;
            text-transform: uppercase;
            color: var(--c-muted);
            letter-spacing: 0.08em;
        }

        .facture__meta dd {
            margin: 0;
            font-weight: 600;
        }

        .facture__info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .facture__info-card {
            border: 1px solid var(--c-border);
            border-radius: 12px;
            padding: 1.25rem;
            background: #fff;
        }

        .facture__info-card--highlight {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .facture__info-card h3 {
            margin: 0 0 0.25rem;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .facture__contact {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
            color: var(--c-muted);
            font-size: 0.92rem;
        }

        .facture__table-wrapper {
            border: 1px solid var(--c-border);
            border-radius: 12px;
            overflow: hidden;
        }

        .facture__table {
            width: 100%;
            border-collapse: collapse;
        }

        .facture__table thead th {
            background: var(--c-soft);
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--c-muted);
            text-align: left;
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--c-border);
        }

        .facture__table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--c-border);
            vertical-align: top;
            font-size: 0.95rem;
        }

        .facture__table tbody tr:last-child td {
            border-bottom: none;
        }

        .facture__desc {
            font-weight: 600;
            margin-bottom: 0.15rem;
        }

        .facture__desc small {
            display: block;
            color: var(--c-muted);
            font-weight: 400;
        }

        .facture__summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.25rem;
        }

        .facture__panel {
            border: 1px solid var(--c-border);
            border-radius: 12px;
            padding: 1.25rem;
            background: #fff;
        }

        .facture__panel h4,
        .facture__panel-title {
            margin: 0 0 0.5rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.08em;
            color: var(--c-muted);
        }

        .facture__panel dl {
            margin: 0;
            display: grid;
            row-gap: 0.35rem;
        }

        .facture__panel dt {
            font-size: 0.85rem;
            color: var(--c-muted);
        }

        .facture__panel dd {
            margin: 0;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .facture__totals {
            display: grid;
            row-gap: 0.35rem;
        }

        .facture__totals-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.95rem;
        }

        .facture__totals-row--em {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .facture__badge-soft {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.7rem;
            border-radius: 9999px;
            background: rgba(249, 115, 22, 0.1);
            color: #c2410c;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .invoice__list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: grid;
            row-gap: 0.5rem;
            font-size: 0.92rem;
        }

        .invoice__list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--c-border);
            padding-bottom: 0.4rem;
        }

        .invoice__list li:last-child {
            border-bottom: none;
        }

        .invoice__pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            border: 1px solid var(--c-border);
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--c-text);
            background: var(--c-soft);
        }

        .invoice__terms {
            list-style: decimal;
            padding-left: 1.25rem;
            color: var(--c-muted);
            display: grid;
            row-gap: 0.35rem;
            font-size: 0.92rem;
        }

        .invoice__signature-grid {
            display: grid;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .invoice__signature-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        .invoice__signature {
            border: 1px solid var(--c-border);
            border-radius: 12px;
            padding: 1rem;
            min-height: 150px;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .invoice__signature img {
            border: 1px solid var(--c-border);
            border-radius: 10px;
            width: 100%;
            height: auto;
        }

        .invoice__footnote {
            margin-top: 0.75rem;
            font-size: 0.85rem;
            color: var(--c-muted);
            text-align: center;
        }
    </style>
</head>

<body class="A4 bg-slate-100">
    <div id="app">
        @hasSection('print-toolbar')
            <div class="print-toolbar">
                @yield('print-toolbar')
            </div>
        @endif

        <div id="printable-area">
            @yield('print-content')
        </div>
    </div>

    <script src="/js/app.js"></script>
    @stack('script-print')
</body>

</html>
