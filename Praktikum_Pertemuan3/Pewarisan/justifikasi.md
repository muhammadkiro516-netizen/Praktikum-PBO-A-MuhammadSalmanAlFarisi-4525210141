# Justifikasi Hierarki Penggajian

Hierarki ini berangkat dari kalimat "Dosen adalah Pegawai", "Pegawai Tetap
adalah Pegawai", "Pegawai Kontrak adalah Pegawai", dan "Pegawai Harian adalah
Pegawai". Semua kalimat tersebut masuk akal karena setiap objek turunan memang
memiliki identitas pegawai, nama, gaji dasar, serta perilaku untuk menghitung
gaji. Karena itu, atribut yang sama ditempatkan di kelas abstrak `Pegawai`.
Kelas ini juga menyediakan `hitungGaji()` sebagai gaji dasar dan mewajibkan
setiap turunan menyatakan `jenis()` masing-masing.

`PegawaiTetap` mewarisi `Pegawai` karena gajinya tetap berawal dari gaji pokok
dan memperoleh tunjangan masa kerja. Method `hitungGaji()` di-override dan
memanggil `super.hitungGaji()` atau `parent::hitungGaji()` agar rumus dasar
tidak disalin. Tunjangan dibatasi maksimum 40 persen. `Dosen` adalah bentuk
khusus pegawai tetap karena memiliki masa kerja dan tunjangan tetap yang sama,
kemudian menambahkan tunjangan fungsional. Jadi, pewarisan bertingkat tersebut
masih menggambarkan hubungan "adalah" yang benar.

`PegawaiKontrak` juga langsung mewarisi `Pegawai`. Pegawai jenis ini memiliki
lama kontrak, tetapi tidak mendapat tunjangan masa kerja sehingga cukup
menggunakan implementasi gaji dasar. `PegawaiHarian` mewarisi `Pegawai` dan
mengartikan gaji pokoknya sebagai tarif per hari. Gaji akhirnya merupakan
tarif per hari dikalikan jumlah hari kerja. Perbedaan rumus ini merupakan
alasan yang tepat untuk melakukan overriding.

Relasi komposisi sengaja digunakan antara `Pegawai` dan `ProfilPembayaran`.
Profil tersebut adalah bagian dari data pegawai untuk menyimpan bank dan nomor
rekening, bukan jenis pegawai baru. Setiap objek pegawai membuat atau memiliki
satu profil pembayaran, dan profil default dibuat ketika data rekening belum
diatur. Siklus hidup profil mengikuti pegawai sehingga komposisi lebih tepat
daripada pewarisan. Pewarisan akan keliru karena profil pembayaran bukan
pegawai dan tidak dapat menggantikan objek `Pegawai`. Komposisi juga menjaga
agar detail pembayaran dapat berubah tanpa menambah tingkat hierarki.