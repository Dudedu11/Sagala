# Article Service

## Pengaturan

1. Clone repository ini
2. Jalankan `composer install`
3. Salin `.env.example` menjadi `.env` dan sesuaikan konfigurasi database
4. Jalankan `php artisan migrate`
5. Jalankan `php artisan serve`

## Penggunaan

### [GET] /api/articles

### [POST] /api/articles

Untuk membuat artikel baru:

**Request:**

```json
{
    "author": "John Doe",
    "title": "Sample Title",
    "body": "Sample body text."
}
