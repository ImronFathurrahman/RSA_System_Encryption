# RSA Crypto System — Bina Insani University 2026

Sistem implementasi Algoritma Rivest-Shamir-Adleman (RSA) untuk enkripsi parameter URL website.

## Cara Menjalankan

### Menggunakan PHP Built-in Server
```bash
cd rsa_system
php -S localhost:8080
```
Buka browser: http://localhost:8080

### Menggunakan XAMPP / Laragon / WAMP
Salin folder `rsa_system` ke dalam `htdocs` (XAMPP) atau `www` (Laragon), lalu buka:
http://localhost/rsa_system/

## Struktur File
```
rsa_system/
├── index.php               ← Entry point utama (routing + layout)
├── rsa.php                 ← Library algoritma RSA
├── README.md
└── pages/
    ├── dashboard.php       ← Halaman dashboard + statistik
    ├── customer_encrypted.php  ← Data customer (URL terenkripsi)
    ├── customer_plain.php  ← Data customer (URL plain/tidak aman)
    ├── encrypt.php         ← Proses enkripsi + langkah detail
    └── decrypt.php         ← Proses dekripsi + langkah detail
```

## Parameter RSA (Sesuai Artikel)
| Parameter | Nilai | Keterangan |
|-----------|-------|------------|
| p | 151 | Bilangan prima pertama |
| q | 173 | Bilangan prima kedua |
| n = p×q | 26.123 | Modulus |
| φ(n) = (p-1)(q-1) | 25.800 | Fungsi Totient Euler |
| e (kunci publik) | 16.397 | Digunakan untuk enkripsi |
| d (kunci privat) | 6.533 | Digunakan untuk dekripsi |

## Dua Mode Enkripsi
1. **Artikel Mode** (2-char hex/karakter): Sesuai persis metodologi artikel. "customer" → `5a9cb05811aa6e4c`
2. **Full Mode** (4-char hex/karakter): RSA penuh, fully reversible. Digunakan untuk sistem URL aktual.

## Tim Peneliti
- Aulia Naufal Nurhaqiqi (2023310062)
- Imron Fathurrahman (2023310116)  
- Agung Prasetyo (2023310086)

Program Studi Teknik Informatika, Universitas Bina Insani — Bekasi, 2026
