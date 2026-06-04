<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Track Parcel — {{ config('app.name') }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">
    <header class="bg-gradient-to-r from-teal-600 to-slate-800 px-4 py-4 text-white shadow">
        <p class="text-center text-lg font-bold">{{ config('app.name', 'Shah Jee Courier') }}</p>
        <p class="text-center text-sm text-teal-100">Track your shipment</p>
    </header>

    <main class="min-h-[60vh]">
        @yield('content')
    </main>

</body>
</html>
