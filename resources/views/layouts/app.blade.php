<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- 💡 ⭕ 修正ポイント：フォントの接続先URLを正しい「fonts.bunny.net」に修正しました -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- 各画面専用の独自CSS（reservations.cssなど）を安全に合体させるための受け皿 -->
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen bg-gray-100">
            <!-- 👥 共通ナビゲーションメニューバー -->
            @include('layouts.navigation')

            <!-- 👤 共通ヘッダータイトルエリア（グレーのラインが引かれる白い背景枠） -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- 🗓️ 各画面のメインコンテンツ -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- 各画面専用の独自JavaScriptを安全に起動するための受け皿 -->
        @stack('scripts')
    </body>
</html>
