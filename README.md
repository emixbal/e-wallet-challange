    # Aplikasi Payment

Ini adalah aplikasi web yang dibangun menggunakan framework Laravel.
Oleh Muhammad Iqbal

## Instalasi

1. Pastikan Anda telah menginstal PHP, Composer, dan MySQL di komputer Anda.
2. Clone repositori ini ke komputer Anda.
3. Buka terminal dan navigasikan ke direktori proyek.
4. Salin file `.env.example` dan ubah namanya menjadi `.env`.
5. Buka file `.env` dan konfigurasikan koneksi database sesuai dengan pengaturan MySQL Anda.
6. Jalankan perintah berikut untuk menginstal semua dependensi PHP:

    ```
    composer install
    ```

7. Generate key aplikasi dengan menjalankan perintah:

    ```
    php artisan key:generate
    ```

8. Migrasikan skema database dan jalankan seeder dengan perintah:

    ```
    php artisan migrate
    ```

    ```
    php artisan db:seed
    ```

9. Untuk menjalankan guanakan xampp server atau sejenisnya. Jalankan seperti app PHP biasa. kunjungi `http://localhost/[nama_folder]`
    atau dengan
    ```
    php artisan serve
    ``` 


## Kontribusi

Jika Anda ingin berkontribusi pada proyek ini, silakan kirimkan pull request atau buka isu untuk diskusi lebih lanjut.

