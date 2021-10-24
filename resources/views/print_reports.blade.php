<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        @media print {
            @page {
                margin: 0.5cm;
                size: A4 portrait;
            }
        }
    </style>
    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
<div class="grid grid-cols-4 gap-0">
    <div class="text-xs">
        <div class="w-full border text-center">
            <p class="font-semi-bold">ระดับชั้น</p>
        </div>
        <div class="flex">
            <div class="w-1/2">
                <div class="flex justify-between border">
                    <p>week</p>
                    <p>lesson</p>
                </div>
            </div>
            <div class="w-1/2">
                <div class="border">
                    <p>date</p>
                </div>

            </div>
        </div>
    </div>

</div>
</body>
</html>
