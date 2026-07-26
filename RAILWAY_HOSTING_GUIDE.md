# 🚂 Panduan Deployment / Hosting ke Railway

Dokumen ini berisi panduan lengkap langkah demi langkah untuk meng-host aplikasi **Global Supply Chain Risk Intelligence Platform** (Laravel) di **Railway.app**.

---

## 📋 Prasyarat
1. Akun **GitHub** ([github.com](https://github.com))
2. Akun **Railway** ([railway.app](https://railway.app))

---

## 🛠️ Persiapan yang Telah Dibuat di Project

Project ini sudah dilengkapi dengan berkas konfigurasi siap pakai untuk Railway:
- `Dockerfile` & `docker-entrypoint.sh` — Menangani instalasi dependency, migrasi database (`migrate`), seeding data otomatis (`db:seed`), dan menjalankan web server di port dinamis `$PORT` yang diberikan Railway.
- `railway.json` — Konfigurasi build dan deploy otomatis di Railway.
- `nixpacks.toml` — Alternatif build runner Railway.
- Support variabel environment otomatis (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`, `DATABASE_URL`).

---

## 🚀 Langkah-Langkah Hosting di Railway

### Langkah 1: Push Project ke GitHub (Jika belum)
1. Buat repository baru di GitHub (misal: `supply-chain-platform`).
2. Jalankan perintah berikut di terminal local Anda:
   ```bash
   git add .
   git commit -m "Configure Railway deployment files"
   git branch -M main
   git remote add origin https://github.com/USERNAME/supply-chain-platform.git
   git push -u origin main
   ```

---

### Langkah 2: Deploy ke Railway via Dashboard

1. **Login ke Railway**:
   - Buka [railway.app](https://railway.app) dan login menggunakan akun GitHub Anda.

2. **Buat New Project**:
   - Klik tombol **+ New Project**.
   - Pilih **Deploy from GitHub repo**.
   - Pilih repository `supply-chain-platform` yang sudah dipush.

3. **Tambah Database MySQL di Railway**:
   - Di dalam Canvas project Railway Anda, klik **+ New** (atau tombol `Add Service`).
   - Pilih **Database** -> **MySQL**.
   - Railway akan secara otomatis membuatkan instance database MySQL untuk Anda.

4. **Koneksikan Web Service ke MySQL**:
   - Klik pada service Web App Anda (service Laravel).
   - Masuk ke tab **Variables**.
   - Tambahkan variabel-variabel berikut:

     | Key | Value | Catatan |
     |---|---|---|
     | `APP_NAME` | `Supply Chain Risk Platform` | |
     | `APP_ENV` | `production` | |
     | `APP_DEBUG` | `false` | |
     | `APP_KEY` | `base64:PASTE_APP_KEY_ANDA_DI_SINI` | Ambil dari file `.env` lokal Anda |
     | `APP_URL` | `https://${{RAILWAY_PUBLIC_DOMAIN}}` | Otomatis menyesuaikan domain Railway |
     | `DB_CONNECTION` | `mysql` | |
     | `DB_HOST` | `${{MySQL.MYSQLHOST}}` | Mengambil host dari MySQL Railway |
     | `DB_PORT` | `${{MySQL.MYSQLPORT}}` | Mengambil port dari MySQL Railway |
     | `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` | Mengambil nama database dari Railway |
     | `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` | Mengambil username dari Railway |
     | `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` | Mengambil password dari Railway |
     | `EXCHANGERATE_API_KEY` | `(optional key Anda)` | Jika ada |
     | `GNEWS_API_KEY` | `(optional key Anda)` | Jika ada |

     *Tips Railway*: Anda bisa menggunakan fitur **New Variable** -> **Reference Variable** untuk memilih variabel MySQL secara langsung!

5. **Generate Public Domain**:
   - Masuk ke tab **Settings** pada service Laravel.
   - Pada bagian **Networking**, klik **Generate Domain**.
   - Railway akan memberikan URL publik, misalnya: `supply-chain-platform-production.up.railway.app`.

---

## ⚡ Langkah Alternatif: Deploy via Railway CLI

Jika Anda lebih suka menggunakan Command Line Interface (CLI):

1. **Install Railway CLI** (di Windows via Powershell / CMD / npm):
   ```bash
   npm i -g @railway/cli
   ```
2. **Login**:
   ```bash
   railway login
   ```
3. **Inisialisasi Project**:
   ```bash
   railway init
   ```
4. **Tambah MySQL Plugin**:
   ```bash
   railway add --plugin mysql
   ```
5. **Set Environment Variables**:
   ```bash
   railway variables --set APP_ENV=production APP_DEBUG=false APP_KEY="base64:..." DB_CONNECTION=mysql
   ```
6. **Deploy**:
   ```bash
   railway up
   ```

---

## 🔍 Verifikasi & Uji Coba

Setelah proses build & deployment di Railway selesai:
1. Buka URL domain Railway yang dihasilkan (contoh: `https://supply-chain-platform-production.up.railway.app`).
2. Login menggunakan kredensial default yang telah di-seed secara otomatis:
   - **Admin**: `admin@supplychain.com` / `Admin@1234`
   - **User**: `user@supplychain.com` / `User@1234`
3. Cek log jika terjadi kendala pada tab **Deployments** -> **View Logs** di Railway.

---
**Hosting Anda di Railway Siap Digunakan! 🚀**
