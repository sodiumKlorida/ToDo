<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Manual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="mx-auto max-w-5xl px-4 py-10">
        <div class="mb-8 rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.2em] text-slate-500">Issue Board</p>
                    <h1 class="mt-2 text-3xl font-black text-slate-900">User Manual</h1>
                </div>
                <a href="{{ route('app.password') }}" class="rounded-xl border-4 border-slate-900 bg-yellow-300 px-4 py-2 text-sm font-black shadow-[3px_3px_0_#111827]">
                    Kembali
                </a>
            </div>
        </div>

        <div class="space-y-6">
            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">1. Masuk ke aplikasi</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Saat membuka aplikasi, Anda akan diminta memasukkan password awal. Password default yang berlaku adalah
                    <span class="font-black text-slate-900">issueboard123</span>.
                    Setelah password benar, sistem akan menyimpan status login pada sesi browser Anda.
                </p>
            </section>

            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">2. Melihat daftar issue</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Setelah masuk, halaman utama menampilkan daftar issue yang sudah dibuat. Anda bisa mencari berdasarkan judul,
                    memilih status, dan filter berdasarkan departemen tertentu.
                </p>
            </section>

            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">3. Membuat issue baru</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Klik tombol <span class="font-black text-slate-900">Tambah Issue</span> untuk membuka formulir. Isi judul,
                    departemen, tanggal, status, dan bila perlu lampirkan referensi URL atau gambar.
                </p>
            </section>

            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">4. Mengedit issue</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Pada halaman edit, Anda bisa memperbarui isi issue, mengganti status, menambah atau menghapus gambar, serta
                    memperbarui referensi URL jika dibutuhkan.
                </p>
            </section>

            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">5. Menghapus issue</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Pada form edit, tersedia tombol <span class="font-black text-slate-900">Hapus Issue</span>. Setelah dikonfirmasi,
                    data issue akan dihapus dari sistem dan gambar terkait juga ikut dihapus bila tersedia.
                </p>
            </section>

            <section class="rounded-2xl border-4 border-slate-900 bg-white p-6 shadow-[6px_6px_0_#0f172a]">
                <h2 class="text-xl font-black text-slate-900">6. Menyimpan dan menjaga keamanan</h2>
                <p class="mt-3 leading-7 text-slate-700">
                    Sebaiknya ganti password default di file environment production setelah tahap deploy selesai. Hindari membagikan
                    password di dokumen publik atau repository yang bisa diakses umum.
                </p>
            </section>
        </div>
    </div>
</body>
</html>
