# Issue Board - User Manual

Issue Board adalah aplikasi untuk mencatat dan mengelola issue yang terjadi di berbagai departemen. Aplikasi ini memudahkan pengguna untuk membuat, melihat, memperbarui, dan menghapus issue dengan status yang jelas.

## Login awal

Saat aplikasi dibuka untuk pertama kali, sistem akan menampilkan halaman password.

- Password default: `issueboard123`
- Setelah password benar, sesi browser akan aktif dan Anda bisa membuka halaman utama.
- Jika password salah, sistem akan kembali menampilkan pesan error dan meminta input ulang.

## Halaman utama

Halaman utama menampilkan daftar issue yang sudah dibuat. Di sana Anda dapat:

- melihat daftar issue
- mencari berdasarkan judul
- memfilter berdasarkan status
- menyaring berdasarkan departemen
- membuka detail issue apabila diperlukan

## Membuat issue baru

1. Klik tombol `Tambah Issue`.
2. Isi field berikut:
   - departemen
   - judul issue
   - tanggal issue
   - status (`todo`, `progress`, `done`)
   - URL referensi bila ada
   - gambar bila diperlukan
3. Klik `Simpan`.

## Mengedit issue

1. Buka issue yang ingin diubah.
2. Klik tombol `Edit`.
3. Ubah field yang diperlukan.
4. Simpan perubahan.

## Menghapus issue

1. Buka halaman edit issue.
2. Klik tombol `Hapus Issue`.
3. Konfirmasi penghapusan.
4. Data akan dihapus dari sistem.

## Manajemen gambar

Jika issue dilengkapi dengan gambar, sistem secara otomatis menyimpan file ke storage aplikasi. Saat issue dihapus atau diganti, file gambar akan dihapus sesuai kebutuhan.

## Catatan keamanan

- Jangan membagikan password default ke publik.
- Sebaiknya ganti password default setelah proses deploy atau penggunaan nyata.
- Semua konfigurasi sensitif disimpan di file environment yang tidak ikut dikirim ke repository.

## Jalankan aplikasi secara lokal

```bash
composer install
php artisan migrate
php artisan db:seed
php artisan serve
```

Lalu buka:

```text
http://127.0.0.1:8000
```

## Akses user manual

Dari halaman password awal, klik tombol:

`Baca User Manual`

untuk membuka panduan ini tanpa harus masuk ke halaman utama aplikasi.
