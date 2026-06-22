# HappyCake (trimmed)

This repository is a trimmed-down version of the HappyCake project. Only the "product-catalog" feature (product listing, categories, product details) is kept. Admin, cart/checkout, order/account and order-related models were removed.

Quick setup

1. Copy `.env.example` → `.env` and set DB and other environment variables.
2. Install PHP dependencies: `composer install`
3. Generate app key: `php artisan key:generate`
4. Run migrations: `php artisan migrate`
5. Serve: `php artisan serve`

If you need any removed feature restored (admin, cart/checkout, orders), tell me which one and I will restore it incrementally.

Files of interest
- `app/Models/Product.php`, `app/Models/Category.php`
- `resources/views/` (product catalog views)
- `routes/web.php` (product routes)
