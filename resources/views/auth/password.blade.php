<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
        <h1 class="text-2xl font-black text-slate-900">Password Required</h1>
        <p class="mt-2 text-sm text-slate-600">Masukkan password untuk membuka board.</p>

        @if (session('error'))
            <div class="mt-4 rounded-xl border-2 border-red-500 bg-red-50 px-3 py-2 text-sm font-semibold text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('app.password.submit') }}" method="POST" class="mt-6 space-y-4">
            @csrf
            <div>
                <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full rounded-xl border-2 border-slate-300 bg-slate-50 px-3 py-2 text-slate-900 outline-none ring-0 focus:border-slate-900"
                    placeholder="Masukkan password"
                >
            </div>

            <button type="submit" class="w-full rounded-xl border-4 border-slate-900 bg-yellow-300 px-4 py-3 text-sm font-black shadow-[3px_3px_0_#111827] transition hover:-translate-y-0.5">
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
