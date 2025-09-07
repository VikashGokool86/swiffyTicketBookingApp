<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            width: 100vw;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            /* Cool gradient background */
            background: linear-gradient(135deg, #e0e7ff 0%, #fdf6fd 50%, #c7d2fe 100%);
        }
        .header-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            background: transparent;
            padding: 1.5rem 2rem 0 0;
        }
        .main-bg-img {
            position: absolute;
            top: 64px; /* height of header, adjust as needed */
            left: 50%;
            transform: translateX(-50%);
            width: auto;
            max-width: 600px; 
            height: calc(100vh - 64px);
            max-height: 500px; 
            object-fit: contain;
            z-index: 1;
        }
        @media (max-width: 768px) {
            .header-bar { padding-right: 1rem; }
            .main-bg-img { top: 56px; height: calc(100vh - 56px); max-height: 300px; max-width: 350px;}
        }
    </style>
</head>
<body>
    <header class="header-bar">
        @if (Route::has('login'))
            <nav class="flex items-center justify-end gap-4">
                @auth
                    <a
                        href="{{ url('/dashboard') }}"
                        class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal"
                    >
                        Dashboard
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="inline-block px-5 py-1.5 text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm text-sm leading-normal"
                    >
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a
                            href="{{ route('register') }}"
                            class="inline-block px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] rounded-sm text-sm leading-normal">
                            Register
                        </a>
                    @endif
                @endauth
            </nav>
        @endif
    </header>
    <img 
        src="{{ asset('images/swiffyWelcome.png') }}" 
        alt="Logo" 
        class="main-bg-img"
    >
</body>
</html>
