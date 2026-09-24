# Justifikasi Hierarki Penggajian

Saya menggunakan `Pegawai` sebagai kelas induk karena empat jenis objek yang
dibuat memang sama-sama pegawai. Kalimat "Dosen adalah Pegawai", "Pegawai
Tetap adalah Pegawai", "Pegawai Kontrak adalah Pegawai", dan "Pegawai Harian
adalah Pegawai" masih masuk akal jika diucapkan sebagai hubungan sehari-hari.
Semua objek tersebut memiliki NIP, nama, gaji pokok, dan fungsi untuk
menghitung gaji. Data yang sama itu cukup ditulis sekali di kelas abstrak
`Pegawai`, sedangkan setiap turunan wajib mengisi jenis pegawainya sendiri.

`PegawaiTetap` mewarisi `Pegawai` karena perhitungannya dimulai dari gaji pokok
dan ditambah tunjangan masa kerja. Method `hitungGaji()` pada kelas ini
memanggil `super.hitungGaji()` atau `parent::hitungGaji()`. Pemanggilan tersebut
penting supaya rumus gaji dasar tidak disalin ke banyak kelas. Tunjangan masa
kerja dibatasi sampai 40 persen. `Dosen` kemudian diturunkan dari
`PegawaiTetap` karena dosen tetap mempunyai masa kerja dan tunjangan yang sama,
lalu mendapat tambahan tunjangan fungsional. Hubungan bertingkat ini masih
sesuai dengan makna "adalah".

`PegawaiKontrak` langsung mewarisi `Pegawai`. Lama kontraknya disimpan sebagai
data tambahan, tetapi tidak ada tunjangan masa kerja. Oleh sebab itu, kelas ini
tidak perlu menimpa `hitungGaji()` dan cukup memakai gaji pokok dari induknya.
Sementara itu, `PegawaiHarian` memakai gaji pokok sebagai tarif per hari. Gaji
akhirnya diperoleh dari tarif tersebut dikalikan jumlah hari kerja. Perbedaan
aturan inilah yang membuat overriding pada kelas harian diperlukan.

Komposisi digunakan pada hubungan `Pegawai` dengan `ProfilPembayaran`.
Profil pembayaran menyimpan nama bank dan nomor rekening. Profil ini bukan
jenis pegawai, sehingga tidak tepat jika dibuat sebagai kelas turunan.
Sebaliknya, setiap pegawai memiliki satu profil pembayaran yang menjadi bagian
dari data pegawai tersebut. Profil default dibuat ketika rekening belum diatur.
Dengan komposisi, detail pembayaran dapat dikembangkan tanpa membuat hierarki
pegawai menjadi semakin panjang. Hubungan ini juga lebih mudah diganti saat
program berjalan dibandingkan hubungan pewarisan yang sudah ditentukan sejak
compile time.

Validasi pada constructor juga membantu menjaga objek tetap masuk akal. Gaji,
masa kerja, jumlah hari, tunjangan, dan durasi kontrak tidak boleh bernilai
negatif. Aturan yang sama diterapkan di Java dan PHP agar perilaku kedua
implementasi tetap konsisten ketika diuji.