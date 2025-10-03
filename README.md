# USAGE GUIDE 
Tata cara penggunaan projek ini tanpa bingung (mengurangi migrain).
Configurasi .env sesuai dengan kebutuhan.

## Preparation
1.  composer install
2.  php artisan key:generate
3.  php artisan storage:link
4.  php artisan migrate
5.  php artisan serve

## Admin Account Creation 
Untuk masuk ke dashboard masuk menggunakan kredensial admin yang dihasilkan dari command:
```
    php artisan admin:create
```

## Web Route
-   **Dashboard Route**
    {{base_url}}/dashboard
-   **Form Route**
    {{base_url}}/form

## Notes
Untuk bisa pindah misalkan dari dashboard ke form route harus keluar dari akun untuk meresat session kemudian ke form

