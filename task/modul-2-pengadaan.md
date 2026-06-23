# Modul 2: Pengadaan / Supply Chain (Inbound)

**Penanggung Jawab:** Shelyn
**Target Pengguna:** Administrator & Pegawai

## Ringkasan Tugas

Anda bertugas menjaga ritme pergerakan dan ketersediaan fisik stok barang. Modul ini diisolasi untuk sisi _Back-Office_ dan bertujuan meregistrasi masuknya suplai barang dari Pemasok Luar (Supplier).

## Rincian Cara Kerja Secara Detail

### 1. Pembuatan Purchase Order / PO (Pemesanan)

- **Tindakan:** Saat pegawai divisi pergudangan mengecek ketersediaan inventaris dan menemukan bahwa stok nyaris habis, mereka berkewajiban membuat rekap _Purchase Order_.
- **Proses:** Pegawai menyeleksi _Supplier_ yang dikorespondensikan dari master data, menunjuk rincian barang apa saja yang diorder dan merekam jumlahnya (`qty`) di tabel `purchase_order_details`.
- **Penting:** Status awal harus `pending`. Langkah ini sebatas pencatatan formal pemesanan dan **TIDAK BOLEH** mempengaruhi/menambah stok riil di tabel `products`.

### 2. Purchases / GR / Penerimaan Fisik Barang

- **Tindakan:** Ketika truk barang tiba di gudang beberapa hari kemudian, tugas pegawai adalah mencocokkan barang kedatangan dengan surat PO tersebut.
- **Proses:** Pegawai menekan tombol _Proses Penerimaan_, sistem lalu akan men-_generate_ rekaman historis baru di tabel `purchases` yang tertaut logikanya terhadap `purchase_order_id` lengkap dengan total biaya aslinya / _actual cost_.
- **Logika Kritis Back-End (Trigger):** Segera setelah tombol Simpan Penerimaan/Purchases dipencet, skrip Anda **wajib** melakukan penambahan (➕) jumlah stok ke dalam parameter `stock` pada _database table_ `products`.
- **Keamanan Transaksi (Insecure Design Prevention):** Membungkus eksekusi memotong database tersebut memakai `DB::transaction()`. Ingat, tidak boleh ada selisih asimetris masuk stok barang dengan rekaman nota faktur apabila sever mendadak kehilangan koneksi (_Deadlock Crash_).

---

## 🛡️ Target Keamanan Khusus (OWASP Top 10)

Sebagai pemegang Modul 2, kamu berfokus pada checklist target penjagaan keamanan berikut:

- **A. Insecure Design (Business Logic Flaws) [A04:2021]:** Melindungi _Financial Structural Business_. Jangan biarkan rekonsiliasi _stok_ dan _invoice_ asimetris. Terapkan Transaction Script bawaan Laravel pada momen penyimpanan Purchases agar jika satu sisi gagal bertambah (`stock`), database otomatis me-_Rollback_ seluruh query struk penerimaan di sekitarnya.
- **B. Injection (SQL Injection) [A03:2021]:** Secara khusus hindari masuknya input tidak wajar ketika meng-_insert_ _quantity_ pesanan ke _pivot table_ (`purchase_order_details`). Sangat krusial menempatkan validasi integer mutlak di sistem _Form Request_ untuk menghalau serangan modifikasi nilai formulir via HTTP Payload injeksi peretas.
