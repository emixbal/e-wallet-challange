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
    kunjungi `http://localhost:8000` for user page
    kunjungi `http://localhost:8000/transaction`  for admin page

10. Jalankan Job queue
    ```
    php artisan queue:work 
    ```

11. Untuk login gunakan user di UserSeeder
    email: user@email.com
    password: aaaaaaaa




**Route Documentation:**

This route group is protected by the 'if_auth' middleware, ensuring that only authenticated users can access its endpoints.

- **POST /deposit**
  - Endpoint: `/deposit`
  - Controller Method: `deposit()` in `WalletController`
  - Description: Allows authenticated users to deposit funds into their wallet.
  - Parameters:
    - `amount`: Required, numeric, minimum value of 1000.
  - Response:
    - Success:
      ```json
      {
          "error": null,
          "status": "ok",
          "message": "ok"
      }
      ```
    - Error:
      ```json
      {
          "status": "nok",
          "message": "something went wrong",
          "error": { "validation_errors" }
      }
      ```

- **POST /withdraw**
  - Endpoint: `/withdraw`
  - Controller Method: `withdraw()` in `WalletController`
  - Description: Allows authenticated users to withdraw funds from their wallet.
  - Parameters:
    - `amount`: Required, numeric, minimum value of 10000.
    - `account`: Required, numeric, minimum value of 0.
    - `bank`: Required, string, must be one of: ABC, DEF, FGH.
  - Response:
    - Success:
      ```json
      {
          "error": null,
          "status": "ok",
          "message": "success"
      }
      ```
    - Error:
      ```json
      {
          "status": "nok",
          "message": "Something went wrong",
          "error": { "validation_errors" }
      }
      ```

- **POST /payment**
  - Endpoint: `/payment`
  - Controller Method: `payment()` in `WalletController`
  - Description: Allows authenticated users to make payments from their wallet.
  - Parameters:
    - `amount`: Required, numeric, minimum value of 1000.
  - Response:
    - Success:
      ```json
      {
          "error": null,
          "status": "ok",
          "message": "success"
      }
      ```
    - Error:
      ```json
      {
          "status": "nok",
          "message": "Something went wrong",
          "error": { "validation_errors" }
      }
      ```

- **GET /wallet-detail**
  - Endpoint: `/wallet-detail`
  - Controller Method: `walletDetailByUser()` in `WalletController`
  - Description: Retrieves the wallet details of the authenticated user.
  - Response:
    - Success:
      ```json
      {
          "status": "ok",
          "message": "success",
          "data": {
              "user_id": "user_id",
              "wallet": { "wallet_details" }
          }
      }
      ```

- **GET /transactions**
  - Endpoint: `/transactions`
  - Controller Method: `listTransactions()` in `WalletController`
  - Description: Retrieves the transaction history of the authenticated user.
  - Response:
    - Success:
      ```json
      {
          "status": "ok",
          "message": "success",
          "data": [ { "transaction_objects" } ]
      }
      ```


##screenshot
![all pods](https://github.com/emixbal/e-wallet-challenge/blob/main/ss/1.png)
![all pods](https://github.com/emixbal/e-wallet-challenge/blob/main/ss/2.png)
