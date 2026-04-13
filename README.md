# 🎓 AcaHub — Platform Pendidikan Digital

AcaHub adalah sistem manajemen pendidikan berbasis **Native PHP** yang menghubungkan guru, siswa, dan orang tua dalam satu platform terintegrasi. Dibangun dengan PHP murni dan Tailwind CSS v3.

---

## 👥 Informasi Kelompok

| No | NPM           | Nama Anggota               |
|----|---------------|-----------------------------|
| 1  | 24082010016   | Krisna Pratama Wijaya       |
| 2  | 24082010025   | Putri Anggun Lestari        |
| 3  | 24082010039   | Ahmad Zulfikar Ramdzi       |

---

## 📝 Pembagian Tugas

### Krisna Pratama Wijaya — `admin/` & `auth/`
Bertanggung jawab atas sistem autentikasi dan panel admin:
- `auth/login.php` — Halaman login pengguna
- `auth/register.php` — Halaman registrasi pengguna baru
- `auth/logout.php` — Proses logout & hapus session
- `admin/admin.php` — Panel admin (kelola users, pengumuman, mata pelajaran)

### Putri Anggun Lestari — `layout/` & `index.php`
Bertanggung jawab atas tampilan utama dan layout:
- `index.php` — Landing page AcaHub (hero, fitur, cara kerja, CTA)
- `layout/header.php` — Sidebar navigasi, topbar, dan flash message
- `layout/footer.php` — Footer halaman
- `layout/tailwind-config.php` — Konfigurasi Tailwind CSS & design tokens

### Ahmad Zulfikar Ramdzi — `pages/`, `utils/`, `config.php`, `helpers.php`, `database.sql`
Bertanggung jawab atas logika backend, halaman fitur, dan database:
- `config.php` — Koneksi database PDO & konfigurasi session
- `helpers.php` — Fungsi helper (auth, redirect, CSRF, flash message)
- `database.sql` — Schema database & sample data
- `pages/dashboard.php` — Dashboard utama dengan statistik & grafik
- `pages/grades.php` — Manajemen nilai siswa (input & tampil)
- `pages/subjects.php` — Daftar mata pelajaran
- `pages/announcements.php` — Kelola pengumuman sekolah
- `pages/users.php` — CRUD pengguna
- `pages/reports.php` — Rapor akademik siswa
- `pages/notifications.php` — Halaman notifikasi
- `utils/fix_hash.php` — Utility reset password untuk development

---

## 📖 Deskripsi Aplikasi

### Tentang AcaHub

AcaHub adalah **platform manajemen pendidikan** yang dirancang untuk memudahkan pengelolaan kegiatan akademik di lingkungan sekolah. Aplikasi ini menyediakan satu ekosistem terpadu bagi **admin**, **guru**, **siswa**, dan **orang tua** untuk berinteraksi dan memantau perkembangan pendidikan secara real-time.

Aplikasi ini dibangun menggunakan **PHP Native** (tanpa framework) untuk memenuhi kebutuhan pembelajaran pemrograman web dasar, dengan tetap menerapkan best practices seperti prepared statements, CSRF protection, password hashing, dan session management.

### Fitur Utama

| No | Fitur                  | Deskripsi                                                                 |
|----|------------------------|---------------------------------------------------------------------------|
| 1  | 🔐 Autentikasi         | Login, register, logout dengan session, cookie (Remember Me), dan CSRF   |
| 2  | 📊 Dashboard            | Statistik ringkasan, grafik distribusi nilai, pengumuman terbaru          |
| 3  | 📝 Manajemen Nilai      | Input & lihat nilai siswa (Ulangan Harian, UTS, UAS, Tugas)              |
| 4  | 📚 Mata Pelajaran       | Daftar mata pelajaran beserta guru pengampu                               |
| 5  | 📢 Pengumuman           | Buat, lihat, dan hapus pengumuman sekolah                                 |
| 6  | 📄 Rapor                | Laporan akademik per siswa per semester dengan grade (A-E)                |
| 7  | 👤 Kelola Users         | Tambah & hapus pengguna dengan role-based access                          |
| 8  | 🏫 Panel Admin          | Panel khusus admin untuk manajemen seluruh sistem                         |
| 9  | 🌙 Landing Page         | Halaman publik dengan informasi fitur dan ajakan mendaftar                |

### Role & Hak Akses

| Role      | Hak Akses                                                   |
|-----------|--------------------------------------------------------------|
| `admin`   | Full access — kelola users, sekolah, nilai, pengumuman       |
| `teacher` | Input nilai, buat pengumuman, lihat data siswa               |
| `student` | Lihat nilai sendiri, rapor, pengumuman                       |
| `parent`  | Lihat pengumuman                                             |

---

## 💻 Tech Stack

| Komponen       | Teknologi                          |
|----------------|------------------------------------|
| **Backend**    | PHP Native (tanpa framework)       |
| **Database**   | MySQL 5.7+                         |
| **Frontend**   | HTML5, CSS3, JavaScript            |
| **CSS**        | Tailwind CSS v3 (via CDN)          |
| **Charts**     | Chart.js v4                        |
| **Font**       | Inter (Google Fonts)               |
| **Web Server** | Apache (Laragon)                   |

---

## 🗂️ Struktur Project

```
native/
├── admin/
│   └── admin.php              # Panel admin
├── auth/
│   ├── login.php              # Halaman login
│   ├── logout.php             # Proses logout
│   └── register.php           # Halaman register
├── layout/
│   ├── header.php             # Sidebar & topbar
│   ├── footer.php             # Footer
│   └── tailwind-config.php    # Konfigurasi Tailwind
├── pages/
│   ├── dashboard.php          # Dashboard utama
│   ├── grades.php             # Manajemen nilai
│   ├── subjects.php           # Mata pelajaran
│   ├── announcements.php      # Pengumuman
│   ├── users.php              # Kelola users
│   ├── reports.php            # Rapor
│   └── notifications.php      # Notifikasi
├── utils/
│   └── fix_hash.php           # Utility reset password
├── config.php                 # Koneksi database & session
├── helpers.php                # Fungsi helper
├── database.sql               # Schema & sample data
├── index.php                  # Landing page
└── README.md                  # Dokumentasi
```

---

## 🚀 Tutorial Setup (di Laptop Lain)

### Persyaratan

| Komponen    | Versi Minimum |
|-------------|---------------|
| PHP         | 7.4+          |
| MySQL       | 5.7+          |
| Web Server  | Apache (Laragon) |

### Langkah 1: Install Laragon

1. Download Laragon di [https://laragon.org/download/](https://laragon.org/download/)
2. Install dan jalankan Laragon
3. Klik **Start All** untuk menjalankan Apache & MySQL

### Langkah 2: Copy Project

Copy seluruh folder `native` ke dalam folder `www` milik Laragon:

```
C:\laragon\www\native\
```

### Langkah 3: Buat Database

1. Buka **phpMyAdmin**:
   - Klik kanan icon Laragon → **MySQL** → **phpMyAdmin**
   - Atau buka: [http://localhost/phpmyadmin](http://localhost/phpmyadmin)

2. Klik tab **Import** → pilih file `database.sql` → klik **Go**

   Atau jalankan lewat terminal:
   ```bash
   mysql -u root < C:\laragon\www\native\database.sql
   ```

### Langkah 4: Konfigurasi Database

Buka `config.php`, pastikan pengaturan sesuai:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'acahub_native');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### Langkah 5: Akses Aplikasi

Buka browser:
```
http://localhost/native/
```

---

## 🔑 Informasi Database & Login

### Database

| Item              | Nilai              |
|-------------------|--------------------|
| **Nama Database** | `acahub_native`    |
| **Username DB**   | `root`             |
| **Password DB**   | *(kosong / empty)* |
| **Host**          | `localhost`         |

### Akun Login Default

Semua akun menggunakan password: **`password`**

| Role    | Email              | Password   |
|---------|--------------------|------------|
| Admin   | `admin@acahub.id`  | `password` |
| Guru    | `guru@acahub.id`   | `password` |
| Siswa   | `siswa@acahub.id`  | `password` |

---

## 🔧 Troubleshooting

| Error | Solusi |
|-------|--------|
| Access denied for user 'root' | Ubah `DB_PASS` di `config.php` sesuai password MySQL kamu |
| Unknown database 'acahub_native' | Import `database.sql` lewat phpMyAdmin |
| Halaman blank / error 500 | Aktifkan ekstensi `pdo_mysql` di PHP |

---

© 2026 AcaHub — Mendukung SDG 4: Pendidikan Berkualitas
