# Modul 3: Penjualan & Pelacakan (Outbound)

**Penanggung Jawab:** Kenny
**Target Pengguna:** Customer (depan) & Administrator/Pegawai (belakang) _(Akun Test Customer: `customer@uas.com` | Pass: `password`)_

## Ringkasan Tugas

Anda menavigasi ujung tombak etalase aplikasi web ini. Di modul inilah _Customer_ dapat mencetak interaksi jual-beli. Tanggung jawab Anda meliputi pelancaran fitur Keranjang/Checkout interaktif, hingga penyajian UI pelacakan alur perpindahan pesanannya.

## Rincian Cara Kerja Secara Detail

### 1. Katalog Penjualan & Checkout (Sisi Customer)

- **Tindakan:** Membuat area tampilan luar untuk Customer (Bisa menggunakan template/layout front-end web dinamis buatan Steve dari Modul 1). Customer (status=Terautentikasi Login) akan menjaring katalog dan melakukan _Checkout / Sales_.
- **Proses:** Saat Customer melakukan check-out, dokumen direkam otomatis di `sales` ditambah rincian harga+qty di `sale_details`.
- **Logika Kritis Back-End (Trigger):** Saat pembayaran/pembelian terkonfirmasi tersimpan, skrip Anda otomatis **Memotong/Mengurangi (➖)** indikator `stock` riil dari tabel etalase `products`!
- **Penengah Celah Konkurensi (Race Condition):** Secara bisnis sistem harus mencegah pembelian masuk jika di milidetik terakhir barang tersebut diborong oleh _customer_ lain. Tangkal skenario ini menggunakan Validasi Stok (DB Transaction dan _LockForUpdate_).

### 2. Fitur Pelacakan (Tracking) & Konfirmasi Berlapis

- **Tindakan:** Pembuatan antarmuka Histori Transaksi ("My Orders") untuk Customer dan "Panel Pesanan" untuk Pegawai.
- **Proses State-Management (Konfirmasi Status via Tombol):** Setiap perpindahan fase wajib digerakkan oleh penekanan **tombol** konfirmasi oleh pihak yang bertanggung jawab. Alurnya:
    1. **`pending`**: Status pasif, terjadi otomatis sesaat setelah Customer menekan _checkout_.
    2. **`processing`**: Tombol "Proses Pesanan" ditekan oleh **Pegawai (Penjual)** sebagai konfirmasi persetujuan/barang mulai dikemas.
    3. **`shipped`**: Tombol "Kirim Pesanan" ditekan oleh **Pegawai**, yang memunculkan jendela input (_prompt_) untuk mengisi Resi Pengiriman Asli (`tracking_receipt_number`).
    4. **`delivered / completed`**: Tombol "Pesanan Diterima" ditekan oleh **Customer** pada akun miliknya, menandakan barang telah ia pegang dengan baik (Transaksi Sukses).

---

## 🛡️ Target Keamanan Khusus (OWASP Top 10)

Sebagai pemegang Modul 3, kamu berfokus pada checklist target penjagaan keamanan berikut:

- **A. Insecure Direct Object Reference (IDOR) [A01:2021 - Broken Access Control]:** Terapkan skoping relasional "Privacy Boundary" mutlak pada Controller "Status Pelacakan" resi kurir. Filter kueri data pesanan HANYA untuk pemilik otentiknya (`where('user_id', auth()->id())`). Alasan: Serangan amatiran amat sering terjadi dimana _Hacker_ sengaja mengganti ujung ekstensi parameter `/order/tracking/12` menjadi `/order/tracking/13` guna membajak alamat struk pengiriman customer luar! Apabila parameter tersebut diutak-atik, web app wajib membalas teguran via laman respons HTTP `403 Forbidden Access`.
- **B. Cross-Site Scripting (XSS) [A03:2021 - Injection]:** Waspadai potensi Injeksi tersembunyi bilamana mencetak keluaran visual berupa nomor/keterangan Resi Ekspredisi Pengiriman di dasawarsa web Front-End Customer area _My-Orders_. Selalu utamakan perlindungan keluaran karakter HTML validasi dasar bawaan framework Blade Laravel `{{ $data }}`. Terlarang untuk mengepak data nomor resi ke eksekusi metode terbuka di luar _escape security_ (yaitu pengetikan via `{!! $data !!}`).
- **C. Data Concurrency (Race Conditions Vulnerability) [A04:2021 - Insecure Design]:** Titik ekploitasi Logika E-Commerce klasik. Kasusnya: Sisa tas branded di Gudang Database terbaca tersisa 1 buah. Namun dua kustomer yang berlomba dari ujung kota berbeda bertumbukan melalukan Aksi Transaksi Klik Checkout secara sangat persis di detik yang persis sama. Jika tanpa kunci perlindungan di level baris memori database, Engine MySQL dapat terjeblos menembus batas lalu menerbitkan tagihan berhasil `-1`. Tangkal kejadian over-selling (defisit stok ajaib) ini menggunakan _Locking DB Validation_ semacam fungsionalitas `->lockForUpdate()`.
