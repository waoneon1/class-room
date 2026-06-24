# Planning: Sistem Classroom Berbasis Website
**SDN Ketintang II Surabaya**  
Stack: Laravel + Tailwind CSS + MySQL

---

## 1. Tech Stack

| Layer | Tech |
|-------|------|
| Backend | Laravel 11 (PHP) |
| Frontend | Blade + Tailwind CSS v3 |
| Database | MySQL |
| Auth & Role | Laravel Breeze + Spatie Laravel Permission |
| File Storage | Laravel Storage (local disk) |

---

## 2. Roles & Akses

| Role | Deskripsi |
|------|-----------|
| `admin` | Kelola pengguna, kelas, mata pelajaran |
| `guru` | Kelola materi, tugas, beri nilai, lihat foto jawaban siswa |
| `siswa` | Lihat materi, download soal, upload foto jawaban, lihat nilai |

---

## 3. Workflow Upload Tugas

```
Guru upload soal (pdf/doc/xlsx/jpg/png)
        ↓
Siswa download soal → kerjakan di kertas → foto → upload foto (jpg/png, bisa lebih dari 1)
        ↓
Guru buka halaman pengumpulan → lihat foto inline di browser → beri nilai + catatan
```

---

## 4. Database Schema

### Tabel: `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| nama_lengkap | string | |
| username | string unique | |
| email | string unique | |
| password | string | hashed |
| role | enum(admin, guru, siswa) | |
| kelas_id | bigint FK nullable | hanya untuk siswa |
| deleted_at | timestamp nullable | soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `kelas`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| nama_kelas | string | contoh: "Kelas 4A" |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `mata_pelajaran`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| nama_pelajaran | string | contoh: "Matematika" |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `materi`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| guru_id | bigint FK → users.id | |
| mata_pelajaran_id | bigint FK → mata_pelajaran.id | |
| judul | string | |
| deskripsi | text nullable | |
| file_materi | string nullable | path file (pdf/doc/xlsx/jpg/png) |
| deleted_at | timestamp nullable | soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `tugas`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| guru_id | bigint FK → users.id | |
| mata_pelajaran_id | bigint FK → mata_pelajaran.id | |
| judul | string | |
| deskripsi | text nullable | |
| file_tugas | string nullable | file soal dari guru (pdf/doc/xlsx/jpg/png) |
| semester | tinyint | nilai: 1 atau 2 |
| tahun_ajaran | string | contoh: "2024/2025" |
| deleted_at | timestamp nullable | soft delete |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `pengumpulan`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| tugas_id | bigint FK → tugas.id | |
| siswa_id | bigint FK → users.id | |
| status | enum(belum_kumpul, sudah_kumpul, sudah_dinilai) | default: sudah_kumpul saat upload |
| nilai | integer nullable | diisi guru setelah menilai |
| catatan | text nullable | feedback dari guru |
| created_at | timestamp | |
| updated_at | timestamp | |

### Tabel: `pengumpulan_files` ← BARU
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint PK | |
| pengumpulan_id | bigint FK → pengumpulan.id | |
| file_path | string | path foto jawaban (jpg/png) |
| urutan | tinyint | urutan halaman, default: 1 |
| created_at | timestamp | |
| updated_at | timestamp | |

> **Catatan Soft Delete:** `tugas` dan `materi` menggunakan SoftDeletes. Jika tugas dihapus,
> `pengumpulan` dan `pengumpulan_files` tetap ada di database (tidak ikut terhapus).
> Gunakan `withTrashed()` jika perlu menampilkan data historis.

---

## 5. Routes

### Auth (semua role)
```
GET  /login
POST /login
POST /logout
```

### Admin
```
GET|POST         /admin/pengguna
GET|POST         /admin/pengguna/create
GET|POST|DELETE  /admin/pengguna/{id}

GET|POST         /admin/kelas
GET|POST         /admin/kelas/create
GET|POST|DELETE  /admin/kelas/{id}

GET|POST         /admin/mata-pelajaran
GET|POST         /admin/mata-pelajaran/create
GET|POST|DELETE  /admin/mata-pelajaran/{id}

GET              /admin/rekap-nilai                    ← rekap nilai semua siswa
                                                         ?semester=1&tahun_ajaran=2024/2025
                                                         ?mata_pelajaran_id=1
```

### Guru
```
GET              /guru/dashboard
GET|POST         /guru/materi
GET|POST         /guru/materi/create
GET|POST|DELETE  /guru/materi/{id}

GET|POST         /guru/tugas
GET|POST         /guru/tugas/create
GET|POST|DELETE  /guru/tugas/{id}

GET              /guru/tugas/{id}/pengumpulan          ← lihat semua submission + counter sudah/belum kumpul
GET              /guru/pengumpulan/{id}                ← lihat foto jawaban siswa (image viewer)
POST             /guru/pengumpulan/{id}/nilai          ← beri nilai + catatan

GET              /guru/rekap-nilai                     ← rekap nilai semua siswa
                                                         ?semester=1&tahun_ajaran=2024/2025
                                                         ?mata_pelajaran_id=1
```

### Siswa
```
GET              /siswa/dashboard
GET              /siswa/materi                         ← lihat daftar materi
GET              /siswa/materi/{id}                    ← detail + download file

GET              /siswa/tugas                          ← lihat daftar tugas (+ badge status)
GET              /siswa/tugas/{id}                     ← detail + download soal
POST             /siswa/tugas/{id}/kumpul              ← upload foto jawaban (multiple)

GET              /siswa/nilai                          ← lihat semua nilai + catatan guru
```

---

## 6. Laravel File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── LoginController.php
│   │   ├── Admin/
│   │   │   ├── PenggunaController.php
│   │   │   ├── KelasController.php
│   │   │   ├── MataPelajaranController.php
│   │   │   └── RekapNilaiController.php
│   │   ├── Guru/
│   │   │   ├── MateriController.php
│   │   │   ├── TugasController.php
│   │   │   ├── PengumpulanController.php
│   │   │   └── RekapNilaiController.php
│   │   └── Siswa/
│   │       ├── MateriController.php
│   │       ├── TugasController.php
│   │       └── NilaiController.php
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/
│   ├── User.php
│   ├── Kelas.php
│   ├── MataPelajaran.php
│   ├── Materi.php
│   ├── Tugas.php
│   ├── Pengumpulan.php
│   └── PengumpulanFile.php

resources/views/
├── auth/
│   └── login.blade.php
├── admin/
│   ├── layout.blade.php
│   ├── pengguna/            (index, create, edit)
│   ├── kelas/               (index, create, edit)
│   ├── mata-pelajaran/      (index, create, edit)
│   └── rekap-nilai/         (index)
├── guru/
│   ├── layout.blade.php
│   ├── materi/              (index, create, edit)
│   ├── tugas/               (index, create, edit)
│   ├── pengumpulan/         (index ← list + counter, show ← image viewer, nilai)
│   └── rekap-nilai/         (index)
└── siswa/
    ├── layout.blade.php
    ├── dashboard.blade.php
    ├── materi/              (index, show)
    ├── tugas/               (index, show ← + form upload foto)
    └── nilai/               (index)
```

---

## 7. Model Relationships

```php
// User
hasMany(Materi::class, 'guru_id')
hasMany(Tugas::class, 'guru_id')
hasMany(Pengumpulan::class, 'siswa_id')
belongsTo(Kelas::class)

// Materi — SoftDeletes
belongsTo(User::class, 'guru_id')
belongsTo(MataPelajaran::class)

// Tugas — SoftDeletes
belongsTo(User::class, 'guru_id')
belongsTo(MataPelajaran::class)
hasMany(Pengumpulan::class)

// Pengumpulan
belongsTo(Tugas::class)
belongsTo(User::class, 'siswa_id')
hasMany(PengumpulanFile::class)

// PengumpulanFile
belongsTo(Pengumpulan::class)

// Kelas
hasMany(User::class)

// MataPelajaran
hasMany(Materi::class)
hasMany(Tugas::class)
```

---

## 8. Implementation Phases

### Phase 1 — Setup & Auth
- [ ] Install Laravel 11
- [ ] Install Tailwind CSS
- [ ] Install Spatie Permission (`composer require spatie/laravel-permission`)
- [ ] Buat semua migrations & run
- [ ] Buat seeder: 1 admin, 1 guru, 2 siswa, 1 kelas, 1 mata pelajaran (untuk testing)
- [ ] Setup login + redirect berdasarkan role
- [ ] Buat RoleMiddleware & protect routes

### Phase 2 — Admin Panel
- [ ] Layout admin (sidebar + navbar)
- [ ] CRUD Pengguna (admin, guru, siswa) + soft delete
- [ ] CRUD Kelas
- [ ] CRUD Mata Pelajaran
- [ ] Halaman rekap nilai (filter semester, tahun ajaran, mata pelajaran)

### Phase 3 — Guru Panel
- [ ] Layout guru
- [ ] CRUD Materi + upload file (pdf/doc/xlsx/jpg/png) + soft delete
- [ ] CRUD Tugas + upload file soal + soft delete
- [ ] Halaman pengumpulan per tugas: list siswa + counter "X dari Y sudah mengumpulkan"
- [ ] Image viewer: lihat foto jawaban siswa inline di browser
- [ ] Form beri nilai + catatan → update status jadi `sudah_dinilai`
- [ ] Halaman rekap nilai

### Phase 4 — Siswa Panel
- [ ] Layout siswa
- [ ] Halaman daftar & detail materi + download file
- [ ] Halaman daftar tugas + badge status (belum kumpul / sudah kumpul / sudah dinilai)
- [ ] Form upload multiple foto jawaban
- [ ] Halaman lihat nilai + catatan dari guru

### Phase 5 — Polish & Testing
- [ ] Validasi semua form (tipe file, ukuran file)
- [ ] Flash message (success/error)
- [ ] Responsive check (mobile — penting karena siswa kemungkinan pakai HP untuk foto)
- [ ] Black box testing

---

## 9. Catatan Penting untuk Claude Code

- Gunakan `php artisan make:model NamaModel -mcr` untuk generate model + migration + controller sekaligus
- SoftDeletes: tambahkan `use SoftDeletes` di model + kolom `deleted_at` di migration
- File upload disimpan di `storage/app/public/` dengan `php artisan storage:link`
- Upload foto multiple: gunakan `$request->file('foto')` sebagai array, loop dan simpan ke `pengumpulan_files`
- Validasi tipe file: soal guru → `mimes:pdf,doc,docx,xlsx,jpg,png` | foto siswa → `mimes:jpg,jpeg,png`
- Image viewer: render `<img src="{{ Storage::url($file->file_path) }}">` langsung di blade, bukan download link
- Counter pengumpulan: `$tugas->pengumpulan()->count()` vs total siswa di kelas
- Middleware role: cek `auth()->user()->role` atau gunakan Spatie `role()`
- Nama file upload: gunakan `$request->file()->hashName()` agar unik
- Tailwind: gunakan CDN play untuk development, build untuk production

---

## 10. Hal yang Perlu Diklarifikasi (dari Class Diagram)

> ⚠️ Class diagram di proposal masih ada masalah:
> - `Mata Pelajaran` tidak terhubung ke class lain (floating)
> - `Pengumpulan` punya method `viewMateri()` yang tidak relevan
> - `Pengumpulan` tidak memiliki PK sendiri (`id_pengumpulan`)
> - Tidak ada relasi yang jelas antar class
>
> Planning ini sudah mengkoreksi hal-hal tersebut.
