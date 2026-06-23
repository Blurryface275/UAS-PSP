# Modul 1: Master Data, Layouting & Laporan PDF

**Penanggung Jawab:** Steve
**Target Pengguna:** Administrator _(Akun Test: `admin@uas.com` | Pass: `password`)_

## Ringkasan Tugas

Modul ini mengawali pondasi proyek. Anda dituntut untuk memulai penyiapan kerangka antarmuka pengguna (UI), mengamankan seluruh entitas dasar administratif menggunakan _CRUD_, hingga mengeksekusi konversi data Laporan menjadi PDF.

## Rincian Cara Kerja Secara Detail

### 1. Skeleton Tata Letak (Layouting) & Inisiasi Library

- **Tindakan:** Karena Anda mengawali proyek, Anda bertugas membuat _template layout_ visual dasar web ini (contoh: Master Blade untuk dasbor Admin, dan Master Blade untuk tampilan Customer di _frontend_). Desain Layout buatan Anda ini yang nanti akan diwariskan ke Orang 2 & 3.
- **Inisiasi Dependency (Library):** Instalasi _package_ pihak ketiga inti dilakukan oleh Anda via `composer` agar merata ke satu tim. Terutama ekstensi Ekspor PDF (`barryvdh/laravel-dompdf`) yang akan Anda pakai sebagai _engine_ di poin kelima.

### 2. Autentikasi & Manajemen Akun Utama (Administrator)

- **Tindakan:** Membangun antarmuka manajemen Role-Based Access Control (RBAC). Admin harus bisa mengelola data seluruh `users` (menambahkan, mengedit _role_ Pegawai dan memblokir Customer).
- **Keamanan (Broken Access Control):** Pastikan middleware mengecek role (`administrator` murni). Customer dan Pegawai dilarang keras dapat mengakses endpoint `/admin/users/`.
- **Upload Profil:** Implementasikan fitur unggah foto profil pengguna yang aman. Lakukan validasi MIME-Type (ekstensi `.png` / `.jpg` / `.jpeg`) dan pembatasan maksimal resolusi/ukuran (contoh 2MB) untuk melindungi integritas struktur (OWASP File Upload Security).

### 3. Manajemen Kategori

- **Tindakan:** Membuat halaman untuk Admin dalam mengelola daftar Kategori barang (`categories`).
- **Catatan Logika:** Karena relasi Kategori dan Barang menggunakan tipe _Many-to-Many_ (lewat tabel pivot `category_product`), sediakan antar-muka di fitur Produk nanti (_checkbox_ atau _multi-select_) agar Admin mudah memberikan banyak label Kategori pada satu Barang.

### 4. Manajemen Barang (Products)

- **Tindakan:** Membuat fitur katalog inventaris. Anda bertugas membuat form tambah barang berisi nama, deskripsi, harga, stok, dan gambar thumbnail.
- **Tampilan:** Tampilkan dalam tabel Admin Dashboard yang rapi, dengan fitur pagination (data banyak tidak membuat halaman macet).
- **Keamanan Ekstra:** Pembuatan, pengubahan, hingga penghapusan produk adalah hak khusus Administrator/Pegawai yang diberi wewenang. Modul ini diamankan dari injeksi _Payload_ XSS jika ada input _Cross-site script_ di kolom deskripsi barang.

### 5. Rekapitulasi & Laporan Ekspor PDF (Sisi Back-Office)

- **Tindakan:** Membangun UI backend di mana Admin/Pegawai dapat melihat keseluruhan agregat riwayat pesanan (`sales` tabel). Anda diizinkan untuk mem-fetching (mengkueri) _read-only_ dari tabel `sales` (bentukan Orang 3) khusus bagi data yang sudah berstatus terselesaikan atau **`delivered/completed`**.
- **Fungsi Ekstra (Export PDF):** Sediakan komponen penanggalan untuk mem-_filter_ rentang tanggal tertentu (Misal bulan Juni). Data tabel gabungan Laporan Pendapatan tersebut di-_compile_ seketika ke dalam bentuk cetak _Portable Document Format_ (`.PDF`) secara dipaksa ter-_download_ agar dapat disetorkan ke entitas luar (seperti tim Keuangan nyata), mempergunakan fungsi dari _library DomPDF_.

---

## 🛡️ Target Keamanan Khusus (OWASP Top 10)

Sebagai pemegang Modul 1, kamu berfokus pada checklist target penjagaan keamanan keamanan berikut:

- **A. Broken Access Control (BAC) [A01:2021]:** Memastikan rute grup di _backend_ dipagar rapat menggunakan `Middleware`. User biasa dilarang keras menembus halaman _dashboard management_ yang khusus diotorisasi hanya bagi parameter `role = administrator`.
- **B. Software and Data Integrity Failures (File Upload Security) [A08:2021]:** Saat me-manage penyimpanan unggahan Katalog & Profil, mutlak pasangkan deteksi otomatis _MIME-Type Image_. Blokir percobaan upload file _script-backdoor_ berekstensi samaran (`.php.png` dll) sebelum file penyerang menyelinap lolos menembus ke folder penyimpanan publik di sisi server web.
- **C. Identification and Authentication Failures [A07:2021]:** Menambahkan logika batas harian _Rate Limiting_ (`throttle_request`) milik framework Laravel pada skrip form Login untuk mem-banned sementara bot skrip berulang IP (_Brute-Force Protection_).
- **D. Injection (SQL Injection Protection SQL) [A03:2021]:** Pada fitur _Laporan PDF_, waspadai ancaman parameter pencarian injeksi (_Date Range Filter_). Wajib mutlak memastikan parameter _input tanggal awal & akhir_ tersebut ditalikan (binding) menggunakan fitur parameter PDO default Laravel (Eloquent), guna mematikan celah penyusup yang menyuntikkan karakter _Raw Query Injection_.
