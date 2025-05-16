<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Instalasi Proyek

1. Download repository atau clone menggunakan perintah berikut di terminal
    ```
    git clone https://github.com/fiqihr/sistem-penggajian.git
    ```
2. Buka proyek menggunakan vscode, lalu ketikkan perintah berikut di terminal vscode

    ```
    composer install
    ```

    ```
    php artisan key:generate
    ```

3. Ubah file `env.example` menjadi `.env` dan sesuaikan konfigurasi database nya. <br>
   biasanya seperti ini jika menggunakan mysql di xampp.

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sistem_penggajian
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4. Ketik perintah berikut di terminal vscode

    ```
    php artisan migrate
    ```

5. Jalankan proyek

    ```
    php artisan serve
    ```
