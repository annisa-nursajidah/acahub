# 🎓 AcaHub — Platform Pendidikan Digital

AcaHub adalah sistem manajemen pendidikan berbasis **Native PHP** yang menghubungkan guru, siswa, dan orang tua dalam satu platform terintegrasi. Dibangun dengan PHP murni dan Tailwind CSS v3.
   
---

## 📋 Fitur Utama

- 🔐 **Autentikasi** — Login, Register, Logout dengan session & cookie (Remember Me)
- 📊 **Dashboard** — Statistik, grafik nilai, pengumuman terbaru
- 📝 **Manajemen Nilai** — Input & pantau nilai siswa (UH, UTS, UAS, Tugas)
- 📚 **Mata Pelajaran** — Kelola daftar mapel beserta guru pengampu
- 📢 **Pengumuman** — Buat dan kelola pengumuman sekolah
- 📄 **Rapor** — Laporan akademik per siswa per semester
- 👤 **Kelola Users** — CRUD pengguna (Admin, Guru, Siswa, Orang Tua)
- 🏫 **Admin Panel** — Panel khusus admin untuk manajemen sistem

---

## 🛠️ Persyaratan Sistem

| Komponen    | Versi Minimum |
|-------------|---------------|
| PHP         | 7.4+          |
| MySQL       | 5.7+          |
| Web Server  | Apache / Nginx (atau Laragon) |

> **Rekomendasi:** Gunakan [Laragon](https://laragon.org/) untuk setup instan di Windows.

---

## 🚀 Tutorial Setup di Laptop Lain

### Langkah 1: Install Laragon

1. Download Laragon di [https://laragon.org/download/](https://laragon.org/download/)
2. Install dan jalankan Laragon
3. Pastikan **Apache** dan **MySQL** sudah berjalan (klik `Start All`)

### Langkah 2: Clone / Copy Project

Copy seluruh folder `native` ke dalam folder `www` milik Laragon:

```
C:\laragon\www\native\
```

Struktur folder yang benar:

```
C:\laragon\www\native\
├── admin/
│   └── admin.php
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
├── layout/
│   ├── header.php
│   ├── footer.php
│   └── tailwind-config.php
├── pages/
│   ├── dashboard.php
│   ├── grades.php
│   ├── subjects.php
│   ├── announcements.php
│   ├── users.php
│   ├── reports.php
│   └── notifications.php
├── utils/
│   └── fix_hash.php
├── config.php
├── helpers.php
├── database.sql
├── index.php
└── README.md
```

### Langkah 3: Buat Database

1. Buka **phpMyAdmin** melalui Laragon:
   - Klik kanan icon Laragon di system tray → **MySQL** → **phpMyAdmin**
   - Atau buka browser: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

2. **Import database:**
   - Klik tab **Import** di phpMyAdmin
   - Pilih file `database.sql` dari folder project
   - Klik **Go / Kirim**

   **Atau** jalankan lewat terminal:
   ```bash
   mysql -u root < C:\laragon\www\native\database.sql
   ```

### Langkah 4: Konfigurasi Database

Buka file `config.php` dan sesuaikan pengaturan koneksi database:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'acahub_native');
define('DB_USER', 'root');
define('DB_PASS', '');
```

> ⚠️ **Default Laragon:** username `root` dengan password kosong (`''`). Jika MySQL kamu punya password, ubah `DB_PASS` sesuai password-mu.

### Langkah 5: Akses Aplikasi

Buka browser dan akses:

```
http://localhost/native/
```

---

## 🔑 Informasi Database

| Item              | Nilai              |
|-------------------|--------------------|
| **Nama Database** | `acahub_native`    |
| **Username DB**   | `root`             |
| **Password DB**   | *(kosong / empty)* |
| **Host**          | `localhost`         |
| **Charset**       | `utf8mb4`          |

---

## 🔐 Akun Login Default

Semua akun default menggunakan password yang sama: **`password`**

| Role    | Email              | Password   |
|---------|--------------------|------------|
| Admin   | `admin@acahub.id`  | `password` |
| Guru    | `guru@acahub.id`   | `password` |
| Siswa   | `siswa@acahub.id`  | `password` |

> Anda juga bisa mendaftar akun baru lewat halaman **Register**.

---

## 🔧 Troubleshooting

### ❌ "Access denied for user 'root'@'localhost'"
Password MySQL berbeda. Buka `config.php` dan ubah `DB_PASS` sesuai password MySQL kamu.

### ❌ "Unknown database 'acahub_native'"
Database belum dibuat. Import file `database.sql` lewat phpMyAdmin atau terminal.

### ❌ "Cannot modify header information - headers already sent"
Pastikan tidak ada spasi atau karakter sebelum tag `<?php` di file PHP.

### ❌ Halaman blank / error 500
Pastikan ekstensi PHP `pdo_mysql` sudah aktif. Cek di Laragon: Menu → PHP → Extensions → centang `pdo_mysql`.

---

## 🗃️ Tabel Database

| Tabel           | Deskripsi                        |
|-----------------|----------------------------------|
| `users`         | Data pengguna (admin, guru, siswa, orang tua) |
| `schools`       | Data sekolah terdaftar           |
| `subjects`      | Mata pelajaran                   |
| `classrooms`    | Kelas                            |
| `grades`        | Nilai siswa                      |
| `announcements` | Pengumuman                       |

---

## 👥 Role & Hak Akses

| Role      | Hak Akses                                                  |
|-----------|-------------------------------------------------------------|
| `admin`   | Full access — kelola users, sekolah, nilai, pengumuman     |
| `teacher` | Input nilai, buat pengumuman, lihat data siswa             |
| `student` | Lihat nilai sendiri, rapor, pengumuman                     |
| `parent`  | Lihat pengumuman                                           |

---

## 📦 Tech Stack

- **Backend:** PHP Native (tanpa framework)
- **Database:** MySQL
- **Frontend:** Tailwind CSS v3 (CDN)
- **Charts:** Chart.js v4
- **Font:** Inter (Google Fonts)

---

© 2026 AcaHub — Mendukung SDG 4: Pendidikan Berkualitas
