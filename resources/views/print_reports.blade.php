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
                size: A4 landscape;
            }
        }
    </style>
    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
<div>
    <div class="flex justify-center">
        <div class="w-1/4">
            <p>Lesson Plan</p>
        </div>
        <div class="w-1/4">
            <p>school___________________</p>
        </div>
        <div class="w-1/4">
            <p>Teacher___________________</p>
        </div>
        <div class="w-1/4">
            <p>Subject___________________</p>
        </div>
    </div>
</div>
<div class="grid grid-cols-2 gap-0 border-t-2 border-blue-500 mt-2">
    <div class="border-r-2 border-blue-500 mt-1 px-2">
        <div class="text-center">
            <p>Grade xxxx</p>
        </div>
        <div class="grid grid-cols-3 gap-2 text-sm">
            <p>Week</p>
            <p>Lesson</p>
            <p>Date</p>
        </div>
        <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
                <p class="text-sm py-0.5 px-2">topic-1</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
            <div class="border">
                <p class="text-sm py-0.5 px-2">Vocabularies</p>
                <div class="border-t text-2xs flex flex-wrap px-2 py-1">
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
                <p class="text-sm py-0.5 px-2">topic-2</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
            <div class="border">
                <p class="text-sm py-0.5 px-2">Vocabularies</p>
                <div class="border-t text-2xs flex flex-wrap px-2 py-1">
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                    <p class="border rounded-md px-0.5 py-0.5 mx-0.5 my-0.5">sdfsdfdsf</p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="border-t border-b border-l">
                <p class="text-sm py-0.5 px-2">Teaching Materials</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
            <div class="border">
                <p class="text-sm py-0.5 px-2">Activities</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-0 mt-1">
            <div class="col-span-2 border-t border-l border-r">
                <p class="text-sm py-0.5 px-2 text-center">Outcome</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
            <div class="border-t border-b border-l">
                <p class="text-sm py-0.5 px-2">Hight distinction students</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
            <div class="border">
                <p class="text-sm py-0.5 px-2">Need improvment students</p>
                <div class="border-t text-2xs px-2 py-1">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi error quo
                    tempora
                    voluptate. Architecto aut dolorem dolores error inventore necessitatibus officiis porro quaerat qui
                    quo quos, repellendus, unde vitae!
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
