<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    <link rel="stylesheet" href="{{ mix('css/app2.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.3.0/paper.css">
    <title>Document</title>
    <style>
        @page {
            size: A4
        }
    </style>
</head>

<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm" id="app">

        <!-- Write HTML just like a web page -->
        @yield('sheet1')


    </section>
    <section class="sheet padding-10mm" >

        <!-- Write HTML just like a web page -->
        @yield('sheet2')


    </section>

    <script src="/js/app.js"></script>
    @stack('script-print')
</body>
</html>
