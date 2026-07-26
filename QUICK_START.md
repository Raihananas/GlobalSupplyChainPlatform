# 🚀 Quick Start Guide

## ⚡ Langkah Cepat

```bash
cd c:\Users\Victus\supply-chain-platform

# 1. Clear cache
php artisan optimize:clear

# 2. Seed risk scores (penting untuk dashboard cepat!)
php artisan db:seed --class=RiskScoresSeeder

# 3. Start server
php artisan serve
```

Buka browser: **http://localhost:8000**

---

## 🔐 Login

| Role  | Email                 | Password   |
|-------|-----------------------|------------|
| Admin | admin@supplychain.com | Admin@1234 |
| User  | user@supplychain.com  | User@1234  |

---

## ✅ Status Sistem

- ✅ Database: Connected (supply_chain_db)
- ✅ Tables: 18 tables created
- ✅ Users: 3 users seeded
- ✅ Countries: 25 countries seeded
- ✅ Ports: 56 ports seeded
- ✅ Risk Scores: 27 countries with risk data
- ✅ Routes: 60 routes registered
- ✅ All views: Available
- ✅ All controllers: No errors
- ✅ Dashboard: Fast load (2-5s)
- ✅ Comparison: Working (27 countries)

---

## 🔧 Jika Ada Error

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Reset Database
```bash
php artisan migrate:fresh --seed
```

### Check System
```bash
php artisan about
php artisan migrate:status
```

---

## 📚 Dokumentasi Lengkap

- `QUICK_START.md` - ⚡ Cara cepat start (Anda di sini!)
- `PERBAIKAN_ERROR.md` - Detail perbaikan error database
- `PERBAIKAN_TIMEOUT.md` - Detail perbaikan error timeout dashboard
- `PERBAIKAN_COMPARISON.md` - Detail perbaikan fitur comparison
- `AUDIT_REPORT.md` - Laporan audit lengkap sistem
- `CARA_INSTALL.md` - Panduan instalasi lengkap

---

## 📧 Support

Jika mengalami masalah, cek file `storage/logs/laravel.log` untuk error details.

**Happy Coding! 🎉**
