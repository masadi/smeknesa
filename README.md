<p align="center"><img src="http://app.erapor-smk.net/logo.png" width="600"></p>

## Server Requirements
[PHP >= 8.0.2](https://laravel.com/docs/9.x/upgrade#updating-dependencies)

Ctype PHP Extension

cURL PHP Extension

DOM PHP Extension

Fileinfo PHP Extension

Filter PHP Extension

Hash PHP Extension

Mbstring PHP Extension

OpenSSL PHP Extension

PCRE PHP Extension

PDO PHP Extension

Session PHP Extension

Tokenizer PHP Extension

XML PHP Extension


## Cara Install (Untuk Pengguna Baru)

- Clone Repositori ini
```bash
git clone https://github.com/eraporsmk/erapor7.git dataweb
cd dataweb
```

## Membuat file .env
```bash
cp .env.example .env
nano .env
```


- Koneksi Database
```bash
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_name
DB_USERNAME=db_user
DB_PASSWORD=db_pass
```

- Install Dependencies
```bash
composer install
```


## Generate App Key
```bash
php artisan key:generate
```

## Migration
- Membuat struktur table
```bash
php artisan migrate
```

- Jalankan seeder
```bash
php artisan db:seed
```
## Untuk pengguna windows:
- Panduan aplikasi & installer. silahkan download [disini](https://drive.google.com/file/d/1DgxFrw9ANGAE8jVvc30bsuY9j9Wl_McF/view?usp=sharing)

## Cara Install (Untuk Pengguna Lama)

- Clone Repositori ini
```bash
git clone https://github.com/eraporsmk/erapor-php8.git dataweb
cd dataweb
```

## Copy file .env
Copy file .env dari root folder aplikasi versi 5xx ke root folder aplikasi versi 6xx

- Install Dependencies
```bash
composer install
```

## Update Versi Aplikasi
```bash
php artisan erapor:update
```

## Edit file .env untuk menampilkan foto profile
```APP_URL=http://localhost:8154```

Sesuaikan dengan alamat/domain yang dipakai

Kemudian tambah kode dibawah ini agar laman register tidak tersedia

```REGISTRATION=false```

## Catatan khusus pengguna windows:
- Konfigurasi koneksi database seperti dibawah ini
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1

DB_PORT=58154
DB_DATABASE=windows
DB_USERNAME=windows
DB_PASSWORD=windows
```

## Catatan khusus untuk pengguna lama (ALL OS):
Untuk mengambil gambar/foto/logo yang telah di upload di aplikasi versi sebelumnya, silahkan copy dari aplikasi lama di folder storage/public, kemudian paste di aplikasi baru di folder storage/public