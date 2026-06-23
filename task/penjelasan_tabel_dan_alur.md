# Panduan Lengkap: Skema Tabel dan Cara Kerja Sistem

## 1. Penjelasan Masing-Masing Tabel (Database Schema)

1. **users**: Menyimpan seluruh data akun otentikasi. Dibedakan secara hak akses lewat kolom `role` (administrator, pegawai, customer).
2. **categories**: Daftar pengelompokkan jenis barang (contoh: Pakaian, Elektronik).
3. **products**: Etalase barang utama. Mencatat info identitas, harga, gambar, dan **jumlah _stock_** yang secara konsisten akan dikalkukasi oleh sistem.
4. **category_product (Pivot Table)**: Tabel penyambung karena model _Many-to-Many_. (e.g. 1 Kemeja masuk label kategori "Baju Pria" sekaligus "Diskon").
5. **suppliers**: Data _Vendor_ (Pabrikan partner/distributor). Pegawai memesan barang ke mereka saat stok toko akan habis.
6. **purchase_orders (PO)**: Formulir arsip rencana pemesanan barang dari Toko ke Supplier. _(Catatan: Pembuatan dokumen di tahap ini belum menambah stok di database)._
7. **purchase_order_details**: Anak dari tabel `purchase_orders`. Menyimpan keranjang rahasia "Barang apa dan berapa unit" yang dipesan ke Supplier.
8. **purchases (Penerimaan Barang/Inbound)**: Tabel pencatatan resmi/legal saat barang fisik dari kurir Supplier akhirnya tiba di gudang.
9. **purchase_details**: Rincian kuantitas aktual barang yang diterima gudang. _(Aksi penyimpanan baris di tabel ini akan otomatis memicu logika penambahan stok `products` lewat Backend)._
10. **sales (Penjualan/Outbound)**: Identitas/Nota transaksi milik Customer. Menyimpan info `tracking_receipt_number` (Nomor Resi) dan lonjakan `status` tracking.
11. **sale_details**: Keranjang belanja rincian barang apa saja yang di-checkout customer di 1 transaksi. Memuat rekaman `price` terkunci agar bebas dari fluktuasi harga jika tiba-tiba harga barang utama diskon keesokan harinya.

---

## 2. Cara Kerja (Workflow) Berdasarkan Sudut Pandang (Point of View)

### A. Point of View (POV) Penjual / Pegawai Toko

Sebagai _pegawai_ back-office, ada dua objektif harianmu: **"Pastikan stok di gudang tidak kosong"** dan **"Kirim cepat transaksi yang masuk"**.

- **Aksi Kulakan (Restocking):** Pegawai melihat sisa "Laptop" tinggal 2 buah di dashboard. Pegawai menekan menu **Surat PO** untuk pesan 50 Laptop ke Supplier _ASUS_. Dua minggu kemudian truk ASUS tiba. Pegawai langsung login sistem dan melakukan rekonsilisasi di menu **Penerimaan Barang (Purchases)**. Sistem aplikasi merespon dengan otomatis _Suntik/Menambah_ angka 50 ke kolom stok Laptop.
- **Memajang Etalase:** Pegawai memoles judul foto 50 Laptop yang baru datang tersebut pada fitur kelola **Master Data Barang**. Etalase halaman utama seketika diperbarui agar bisa dicari publik.
- **Aksi Pemrosesan (Fulfillment):** Masuk notifikasi merah berisi _"Pesanan Masuk ID: SLS-999"_. Pegawai me-review bahwa telah terjadi pembayaran sah. Ia pun langsung membungkus barang tersebut, menekan tombol **"Input Resi Pengiriman"**, dan menyerahkan barang fisik ke _Ekspedisi_ (JNE/J&T).

### B. Point of View (POV) Customer / Pembeli

Bagi entitas Customer (klien kasual internet), web yang ia lihat terasa standar layaknya _E-Commerce/Online Shop_ yang rapih.

- **Berbelanja:** Customer iseng _login_. Mencari-cari katalog, dan ia mendapati ada Laptop sisa stok 50 buah. Ia berkesimpulan ingin membelinya dan langsung memencet **"Checkout dan Konfirmasi"**.
- **Pertarungan Skrip _(Race Condition Handling)_:** Di bawah permukaan, begitu order Customer berhasil terekam `status = pending`, skrip back-end web akan instan memotong stok dari 50 ke 49 secara _Locking Method_. (Jika sesaat milidetik sebelum ia Checkout ada 50 pengguna iseng barengan mengeklik Checkout, sistem dapat menangkal selisih stok negatif!).
- **Memantau Status Resi / Melacak:** Customer bersabar di rumah sembari mengakses halaman "Riwayat Pesananku". Esoknya sistem notif dia berubah dari _"Pesanan Sedang Diproses Penjual"_ ke status _"Dalam Pengiriman Ekspedisi (Resi: JNE-XXX123)"_.
- **Serah Terima:** Tiga hari berselang kurir ekspedisi membawa kardus pesanan mendarat di terasnya. Customer membuka web, riang gembira me-klik tombol **"Pesanan Telah Kuterima"**. Transaksi terkunci sepenuhnya di status _Completed_ dan tercetak di buku Laporan PDF akhir bulan toko.
