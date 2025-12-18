# Penjelasan Alur Aktivitas (Activity Diagram)

Berikut adalah penjelasan mengenai alur aktivitas pengguna dalam sistem, yang dijabarkan dalam bahasa yang mudah dipahami.

## 1. Login Sistem (activity-diagram-login.drawio)
Aktivitas login adalah pintu gerbang utama bagi seluruh pengguna untuk mengakses layanan sistem. Proses ini dirancang untuk memastikan keamanan data dengan memverifikasi identitas pengguna sebelum mereka diizinkan melihat atau mengubah informasi apa pun di dalam aplikasi.

Alurnya dimulai ketika pengguna membuka aplikasi dan memilih opsi untuk masuk. Sistem akan mengarahkan pengguna ke halaman login Google. Setelah pengguna memasukkan email dan password akun kampus mereka, Google memberikan "lampu hijau" kepada sistem kami. Jika sistem mengenali email tersebut sebagai pengguna terdaftar, maka pengguna akan langsung diarahkan ke halaman utama (Dashboard) sesuai dengan peran mereka masing-masing.

## 2. Kelola Pengguna (activity-diagram-kelola-pengguna.drawio)
Aktivitas ini merupakan tugas administratif untuk mengatur siapa saja yang boleh menggunakan aplikasi. Admin memiliki kendali penuh untuk menambahkan akun baru, mengubah data pengguna yang sudah ada, atau menghapus akun yang tidak lagi aktif.

Prosesnya dimulai dari Admin membuka menu data pengguna. Di sana, Admin bisa memilih untuk "Tambah Baru" dengan mengisi formulir pendaftaran, atau memilih nama tertentu untuk "Edit" atau "Hapus". Setiap kali Admin menyimpan perubahan, sistem akan memvalidasi data tersebut dan memperbarui database pusat, memastikan daftar pengguna selalu rapi dan akurat.

## 3. Kelola Periode Akademik (activity-diagram-kelola-periode-akademik.drawio)
Pengelolaan periode akademik mirip dengan mengatur kalender operasional sistem. Admin atau Koordinator menggunakan fitur ini untuk menentukan semester mana yang sedang berjalan, sehingga semua data nilai dan proyek mahasiswa tersimpan rapi sesuai tahun ajarannya masing-masing.

Dalam aktivitas ini, Admin dapat membuat periode baru (misalnya "Semester Genap 2024") dan menetapkan statusnya menjadi "Aktif". Saat status aktif dinyalakan, sistem secara otomatis akan mengunci periode sebelumnya dan mengarahkan semua aktivitas baru (seperti pembuatan kelas atau penilaian) ke periode yang baru tersebut.

## 4. Kelola Ruang Kelas (activity-diagram-kelola-kelas.drawio)
Aktivitas ini bertujuan untuk memetakan struktur perkuliahan di dalam sistem. Admin mengatur daftar kelas yang tersedia, menentukan mata kuliah apa yang diajarkan, serta menunjuk dosen mana yang bertanggung jawab mengampu kelas tersebut.

Alurnya sederhana: Admin mengakses menu kelas, lalu menekan tombol tambah untuk membuat kelas baru. Admin kemudian memasukkan nama kelas dan memilih nama dosen dari daftar yang tersedia. Setelah disimpan, kelas tersebut akan muncul di dashboard dosen yang bersangkutan, memberikan hak akses kepada dosen tersebut untuk mulai mengelola mahasiswa di dalamnya.

## 5. Kelola Kelompok Belajar (activity-diagram-kelola-kelompok.drawio)
Karena sistem ini berbasis proyek (Project Based Learning), pembentukan tim adalah langkah krusial. Aktivitas ini dilakukan oleh Koordinator untuk membagi mahasiswa ke dalam kelompok-kelompok kecil yang akan bekerja sama selama satu semester.

Koordinator memilih kelas target, lalu mulai memasangkan mahasiswa-mahasiswa yang ada ke dalam nama kelompok tertentu (misalnya "Tim Alpha", "Tim Beta"). Sistem akan menjaga agar satu mahasiswa tidak masuk ke dua kelompok sekaligus. Setelah pembagian selesai, mahasiswa akan otomatis melihat rekan satu tim mereka saat login ke aplikasi.

## 6. Kelola Target Mingguan (activity-diagram-kelola-target-mingguan.drawio)
Ini adalah aktivitas rutin yang dilakukan oleh mahasiswa (terutama ketua kelompok) untuk merencanakan pekerjaan mereka. Setiap awal minggu, tim harus menyepakati apa saja target yang ingin dicapai agar proyek mereka selesai tepat waktu.

Dalam sistem, ketua kelompok akan membuka menu "Target Mingguan" dan membuat daftar pekerjaan (To-Do List). Mereka menuliskan judul tugas dan batas waktu pengerjaannya. Daftar ini kemudian tersimpan di sistem sebagai janji kerja yang bisa dilihat oleh Dosen pembimbing sebagai bahan monitoring.

## 7. Pengumpulan Target (activity-diagram-pengumpulan-target.drawio)
Setelah bekerja keras menyelesaikan tugas, mahasiswa melakukan aktivitas ini untuk melaporkan hasilnya. Ini adalah bukti pertanggungjawaban mereka bahwa target yang direncanakan telah benar-benar dikerjakan.

Mahasiswa memilih target yang statusnya masih "Berjalan", kemudian mengunggah bukti hasil kerja. Bukti ini bisa berupa file dokumen, foto, atau tautan ke hasil proyek. Setelah tombol "Kirim" atau "Submit" ditekan, sistem menandai tugas tersebut sebagai "Selesai Dikerjakan" dan mengirimkan notifikasi kepada Dosen bahwa tugas siap untuk diperiksa.

## 8. Review Target (activity-diagram-review-target.drawio)
Aktivitas ini adalah respons Dosen terhadap laporan yang dikirim mahasiswa. Dosen berperan sebagai supervisor yang memeriksa kualitas pekerjaan tim dan memberikan bimbingan.

Dosen membuka daftar target yang sudah dikumpulkan mahasiswa, lalu memeriksa lampiran bukti kerjanya. Jika hasilnya sudah bagus, Dosen memberikan status "Disetujui". Namun jika masih kurang, Dosen memberikan status "Revisi" beserta catatan komentar perbaikannya. Aktivitas ini menciptakan siklus umpan balik yang membantu mahasiswa belajar lebih baik.

## 9. Konfigurasi AHP (activity-diagram-konfigurasi-ahp.drawio)
Aktivitas ini cukup teknis dan dilakukan oleh Koordinator untuk menjaga standar penilaian. Tujuannya adalah menghitung bobot "kekuatan" dari setiap kriteria penilaian agar adil dan objektif.

Koordinator memasukkan data perbandingan (misalnya: "Kerapian vs Isi Laporan"). Sistem kemudian menggunakan rumus matematika AHP di latar belakang untuk menghitung angka persentase bobot masing-masing kriteria. Hasil hitungan ini disimpan otomatis dan akan menjadi rumus baku saat Dosen menilai mahasiswa nantinya.

## 10. Input Nilai Kelompok (activity-diagram-input-nilai-kelompok.drawio)
Di akhir proyek, Dosen melakukan aktivitas ini untuk memberikan rapor kinerja kepada tim. Penilaian ini mencakup berbagai aspek seperti hasil produk, dokumen laporan, dan presentasi.

Dosen memilih menu penilaian, lalu memilih kelompok yang akan dinilai. Sistem menyuguhkan formulir berisi kriteria-kriteria yang sudah disiapkan sebelumnya. Dosen tinggal mengisi skor pada setiap kriteria, dan sistem akan langsung menghitung total nilai akhir kelompok tersebut berdasarkan bobot yang berlaku.
