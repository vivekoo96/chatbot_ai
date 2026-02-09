<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Widget</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-white dark:bg-gray-900 h-full m-0 p-0 overflow-hidden">
    <div class="h-full w-full">
        @livewire('widget.chat-box', ['chatbot' => $chatbot])
    </div>

    @livewireScripts
</body>

</html>