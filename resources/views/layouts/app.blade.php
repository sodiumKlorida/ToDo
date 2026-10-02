<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Issue Board Operasional')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .image-modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body class="min-h-screen bg-[#f4efe8] text-slate-900 antialiased">
    @yield('content')
    @stack('scripts')
</body>
</html>
