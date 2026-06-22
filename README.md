# 🍰 HAPPY CAKE — Website Tiệm Bánh Đặt Trước

Website thương mại điện tử cho tiệm bánh ngọt: bán **bánh kem sinh nhật**, **bánh kem**, **cupcake**, **bánh mì**, **bánh ngọt** và **bánh Trung thu**, hỗ trợ **đặt trước theo ngày** và **ghi chữ trên bánh**.

Xây dựng bằng **Laravel 11 + Bootstrap 5 + MySQL**.

---

## 📋 MỤC LỤC

1. [Tổng quan dự án](#-tổng-quan-dự-án)
2. [Tính năng đặc trưng so với e-commerce thường](#-tính-năng-đặc-trưng-so-với-e-commerce-thường)
3. [Công nghệ sử dụng](#-công-nghệ-sử-dụng)
4. [Cấu trúc thư mục](#-cấu-trúc-thư-mục)
5. [Cài đặt & chạy dự án](#️-cài-đặt--chạy-dự-án)
6. [Tài khoản mẫu](#-tài-khoản-mẫu)
7. [Cấu trúc Database](#️-cấu-trúc-database)
8. [Hệ thống Routes](#️-hệ-thống-routes)
9. [Tính năng chính](#-tính-năng-chính)
10. [Cơ chế đặt bánh trước (lead time)](#-cơ-chế-đặt-bánh-trước-lead-time)
11. [Cơ chế size variants](#-cơ-chế-size-variants)
12. [Khắc phục sự cố](#️-khắc-phục-sự-cố)

---

## 🎯 TỔNG QUAN DỰ ÁN

### Mục tiêu
Xây dựng website tiệm bánh hoàn chỉnh, gồm:
- 🎂 Bán **bánh kem sinh nhật** custom (chọn size, ghi chữ trên bánh, đặt trước 3 ngày)
- 🍰 Bán **bánh kem, cupcake, bánh mì, bánh ngọt, bánh Trung thu**
- 📅 **Đặt trước theo ngày nhận** (delivery_date)
- 💌 **Ghi lời nhắn trên bánh** (cake_message) — vd "Chúc mừng sinh nhật Mai"
- 📏 **Chọn size bánh** (Nhỏ / Vừa / Lớn) — mỗi size 1 giá riêng
- 🛒 **Giỏ hàng** lưu bằng session
- 🔐 **Đăng ký / Đăng nhập** dùng Laravel Breeze
- 👨‍💼 **Trang quản trị (Admin)** với phân quyền

### Đối tượng người dùng
| Vai trò | Quyền hạn |
|---------|-----------|
| **Khách (Guest)** | Xem bánh, thêm vào giỏ |
| **Khách hàng (User)** | Đặt bánh, xem lịch sử đơn, sửa profile |
| **Quản trị (Admin)** | CRUD bánh/danh mục, quản lý đơn (6 trạng thái), quản lý khách hàng |

---

## ✨ TÍNH NĂNG ĐẶC TRƯNG SO VỚI E-COMMERCE THƯỜNG

Đây là các tính năng **chỉ có ở website tiệm bánh**, không phải shop thông thường:

| Tính năng | Mô tả | Cột DB |
|-----------|-------|--------|
| 📅 **Ngày nhận bánh** | Khách phải chọn ngày nhận khi đặt | `orders.delivery_date` |
| ⏰ **Lead time tối thiểu** | Mỗi loại bánh có số ngày chuẩn bị riêng (vd bánh sinh nhật = 3 ngày) | `products.min_lead_days` |
| 💌 **Lời nhắn trên bánh** | Khách nhập câu chúc, in trên bánh | `orders.cake_message` |
| 📏 **Size variants** | 1 sản phẩm có thể có nhiều size, mỗi size 1 giá | `products.size_options` (JSON) |
| 🎂 **Trạng thái "Đang làm bánh"** | Thêm trạng thái `baking` giữa `confirmed` và `shipping` | `orders.status` enum |
| 🏷 **Composite cart key** | Cùng 1 bánh nhưng khác size = 2 dòng giỏ riêng | session cart key `id_sizeKey` |

---

## 🛠 CÔNG NGHỆ SỬ DỤNG

| Lớp | Công nghệ |
|-----|-----------|
| Backend Framework | **Laravel 11** |
| Database | **MySQL** |
| Authentication | **Laravel Breeze** |
| Frontend Admin | **Bootstrap 5** + Bootstrap Icons (CDN) |
| Frontend User | **Bootstrap 5** + Custom CSS (theme cam ấm) |
| Font | Google Fonts (Quicksand + Pacifico cho logo) |
| Cart | **Session-based** (composite key cho size variants) |
| Server local | XAMPP (Apache + MySQL) |
| Build tool | Vite (cho Breeze auth pages) |

---

## 📁 CẤU TRÚC THƯ MỤC

```
happycake/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── ProductController.php   # CRUD bánh + size variants
│   │   │   │   ├── OrderController.php     # 6 trạng thái
│   │   │   │   └── UserController.php
│   │   │   ├── HomeController.php          # Trang chủ + section bánh SN
│   │   │   ├── ProductController.php       # Thực đơn bánh
│   │   │   ├── CartController.php          # Giỏ hàng + size variants
│   │   │   ├── CheckoutController.php      # Validate delivery_date + lead time
│   │   │   ├── OrderController.php
│   │   │   └── ProfileController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php                     # + size_options (JSON), min_lead_days
│       ├── Order.php                       # + delivery_date, cake_message
│       └── OrderItem.php                   # + size_label
│
├── database/
│   ├── migrations/                         # 8 migration files
│   └── seeders/
│       └── DatabaseSeeder.php              # Seed admin, 6 danh mục, 19 loại bánh
│
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php               # Theme cam pastel #f4a261
│       │   └── guest.blade.php
│       ├── admin/
│       │   ├── layouts/app.blade.php       # Sidebar nâu sô-cô-la
│       │   ├── dashboard.blade.php
│       │   ├── categories/
│       │   ├── products/                   # Form có size + min_lead_days
│       │   ├── orders/                     # Hiển thị delivery_date + cake_message
│       │   └── users/
│       ├── partials/
│       │   └── product-card.blade.php      # Có badge "Đặt trước N ngày"
│       ├── home.blade.php                  # Hero + section bánh sinh nhật
│       ├── products/
│       │   ├── index.blade.php
│       │   └── show.blade.php              # Size selector
│       ├── cart/index.blade.php            # Hiển thị size + cảnh báo lead time
│       ├── checkout/index.blade.php        # Input delivery_date + cake_message
│       └── orders/                         # Hiển thị ngày nhận + lời nhắn
│
├── routes/
│   ├── web.php
│   └── auth.php
└── .env                                    # APP_NAME="Happy Cake"
```

---

## ⚙️ CÀI ĐẶT & CHẠY DỰ ÁN

### Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- MySQL (qua XAMPP)
- Node.js & npm (cho Breeze auth)

### Bước 1: Khởi động XAMPP
- Bật **Apache** và **MySQL**
- Truy cập phpMyAdmin → tạo database tên: `happycake`

### Bước 2: Cấu hình `.env`
```env
APP_NAME="Happy Cake"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=happycake
DB_USERNAME=root
DB_PASSWORD=
```

### Bước 3: Cài đặt dependencies
```bash
composer install
npm install
```

### Bước 4: Chạy migration + seed
```bash
php artisan migrate:fresh --seed
```
Lệnh này sẽ:
- Tạo tất cả các bảng
- Tạo 2 user mẫu (1 admin + 1 user)
- Tạo 6 danh mục bánh
- Tạo 19 loại bánh mẫu (có size variants + lead time khác nhau)

### Bước 5: Tạo storage symlink (để hiển thị ảnh upload)
```bash
php artisan storage:link
```

### Bước 6: Chạy server
```bash
php artisan serve
```
→ Truy cập: **http://127.0.0.1:8000**

---

## 👤 TÀI KHOẢN MẪU

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| **Admin** | `admin@happycake.com` | `password` |
| **Khách hàng** | `user@happycake.com` | `password` |

> 🔑 Đăng nhập admin → menu "**Quản trị**" → vào `/admin`

---

## 🗄️ CẤU TRÚC DATABASE

### Bảng `users`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id, name, email, password | | (chuẩn Laravel) |
| **is_admin** | boolean | true = admin |
| phone, address | string | nullable |

### Bảng `categories`
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id, name, slug, description, image | | |

### Bảng `products` (có thêm cột bánh-specific)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id, category_id, name, slug, price, sale_price, image, description, stock, featured | | (chuẩn) |
| **size_options** | JSON nullable | Mảng `[{key, label, price}]` cho size variants |
| **min_lead_days** | tinyint default 1 | Số ngày tối thiểu phải đặt trước |

### Bảng `orders` (có thêm cột bánh-specific)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id, user_id, name, phone, address, note, total_price | | (chuẩn) |
| **delivery_date** | date | Ngày khách muốn nhận bánh |
| **cake_message** | string(200) nullable | Lời chúc ghi trên bánh |
| status | enum | `pending` / `confirmed` / **`baking`** / `shipping` / `completed` / `cancelled` |

### Bảng `order_items` (có thêm size_label)
| Cột | Kiểu | Ghi chú |
|-----|------|---------|
| id, order_id, product_id, price, quantity | | |
| **size_label** | string(100) nullable | Size đã chọn tại thời điểm mua |

### Eloquent Relationships
```php
User      hasMany    Order
Category  hasMany    Product
Product   belongsTo  Category
Order     belongsTo  User
Order     hasMany    OrderItem
OrderItem belongsTo  Order, Product
```

---

## 🛣️ HỆ THỐNG ROUTES

### 🌐 Frontend (User)
| Method | URL | Tên route | Chức năng |
|--------|-----|-----------|-----------|
| GET | `/` | `home` | Trang chủ + section bánh sinh nhật |
| GET | `/san-pham` | `products.index` | Thực đơn bánh |
| GET | `/san-pham/{slug}` | `products.show` | Chi tiết bánh + size selector |
| GET | `/danh-muc/{slug}` | `categories.products` | Bánh theo danh mục |
| GET | `/gio-hang` | `cart.index` | Giỏ hàng |
| POST | `/gio-hang/them/{product}` | `cart.add` | Thêm vào giỏ (kèm size) |
| PUT | `/gio-hang/cap-nhat` | `cart.update` | |
| DELETE | `/gio-hang/xoa/{key}` | `cart.remove` | Key có thể là `5` hoặc `5_small` |

### 🔐 Auth required
| Method | URL | Tên route |
|--------|-----|-----------|
| GET | `/thanh-toan` | `checkout.index` |
| POST | `/thanh-toan` | `checkout.store` (validate delivery_date) |
| GET | `/don-hang` | `orders.index` |
| GET | `/don-hang/{order}` | `orders.show` |

### 👨‍💼 Admin
| Method | URL | Tên route |
|--------|-----|-----------|
| GET | `/admin` | `admin.dashboard` |
| Resource | `/admin/categories` | `admin.categories.*` |
| Resource | `/admin/products` | `admin.products.*` |
| GET | `/admin/orders` | `admin.orders.index` |
| GET | `/admin/orders/{order}` | `admin.orders.show` |
| PATCH | `/admin/orders/{order}/status` | `admin.orders.updateStatus` |
| GET | `/admin/users` | `admin.users.index` |

---

## ✨ TÍNH NĂNG CHÍNH

### 🌟 Phía người dùng
- ✅ **Trang chủ** với hero pastel + section bánh sinh nhật riêng
- ✅ **Thực đơn 6 loại bánh** + tìm kiếm + lọc giá + sắp xếp
- ✅ **Chi tiết bánh** với:
  - Size selector (radio button) — chọn size sẽ đổi giá
  - Cảnh báo "Đặt trước N ngày" nếu là bánh custom
  - Quantity input
- ✅ **Giỏ hàng**:
  - Hiển thị size đã chọn ở mỗi dòng
  - Cảnh báo ngày nhận sớm nhất dựa trên `min_lead_days` lớn nhất trong giỏ
- ✅ **Checkout**:
  - Input `delivery_date` (HTML date picker, min = today + lead days)
  - Input `cake_message` (lời nhắn trên bánh, max 200 ký tự)
  - Validate: ngày nhận phải >= ngày sớm nhất theo `min_lead_days` lớn nhất
- ✅ **Lịch sử đơn**: hiển thị ngày nhận + lời nhắn + 6 trạng thái màu

### 🛠️ Phía quản trị (Admin)
- ✅ **Dashboard**:
  - 4 stat cards: bánh / đơn / khách / doanh thu
  - Bảng đếm 6 trạng thái đơn
  - Danh sách đơn gần đây (có cột "Ngày nhận")
- ✅ **CRUD bánh** với form đặc biệt:
  - 3 input size (label + price), gom lại thành JSON `size_options`
  - Input `min_lead_days` (1-14)
  - Upload ảnh, đánh dấu nổi bật
- ✅ **CRUD danh mục**: tạo, sửa, xóa, upload ảnh
- ✅ **Quản lý đơn**: lọc theo 6 trạng thái, xem chi tiết (có ngày nhận + lời nhắn + size từng item)
- ✅ **Quản lý khách hàng**: số đơn bánh, ngày tham gia

### 🎨 Giao diện
- ✅ Theme **cam ấm** `#f4a261` + chữ nâu caramel `#5b3a1e`
- ✅ Font **Quicksand** body + **Pacifico** logo "Happy Cake"
- ✅ Card sản phẩm bo tròn 16px, hover nâng nhẹ
- ✅ Badge "Đặt trước N ngày" trên card bánh sinh nhật
- ✅ Section "Đặt bánh sinh nhật custom" highlight ở trang chủ
- ✅ Flash messages tự động ẩn sau 4s

---

## 🕐 CƠ CHẾ ĐẶT BÁNH TRƯỚC (LEAD TIME)

Mỗi loại bánh có cột `min_lead_days` (mặc định 1):
- Bánh mì, bánh ngọt thường: `1` ngày
- Hộp quà Trung thu: `2` ngày
- Bánh sinh nhật custom: `3` ngày
- Bánh sinh nhật 2 tầng: `4` ngày

Khi user vào checkout, controller tính `maxLeadDays = max(min_lead_days của tất cả item trong giỏ)`:

```php
private function maxLeadDays(array $cart): int {
    $max = 1;
    foreach ($cart as $item) {
        $lead = (int) ($item['min_lead_days'] ?? 1);
        if ($lead > $max) $max = $lead;
    }
    return $max;
}
```

Sau đó:
- HTML date picker có `min = today + maxLeadDays`
- Validate Laravel: `'delivery_date' => 'required|date|after_or_equal:' . $earliestDate`

→ Khách KHÔNG THỂ chọn ngày nhận sớm hơn lead time cho phép.

---

## 📏 CƠ CHẾ SIZE VARIANTS

### Lưu trữ
Cột `products.size_options` là JSON nullable. Cấu trúc:
```json
[
  {"key": "small",  "label": "Nhỏ (16cm - 4~6 người)",   "price": 250000},
  {"key": "medium", "label": "Vừa (20cm - 6~8 người)",   "price": 350000},
  {"key": "large",  "label": "Lớn (24cm - 10~12 người)", "price": 480000}
]
```
- Nếu `null` → bánh chỉ có 1 giá (dùng `price` / `sale_price`)
- Helper: `$product->hasSizeOptions()` trong Model

### Form admin
Form create/edit có 3 dòng input (label + price). Controller gom lại và bỏ dòng trống:
```php
$sizes = collect($request->input('sizes', []))
    ->filter(fn($s) => !empty($s['label']) && !empty($s['price']))
    ->values()
    ->map(fn($s, $i) => ['key' => 'size'.($i+1), 'label' => $s['label'], 'price' => (int)$s['price']])
    ->toArray();
$data['size_options'] = count($sizes) > 0 ? $sizes : null;
```

### Cart với size variants
**Composite key** trong session cart:
- Bánh không có size: key = `5` (chỉ ID)
- Bánh có size: key = `5_small`, `5_medium`, `5_large`

Vậy cùng 1 bánh nhưng 2 size khác nhau = 2 dòng giỏ riêng. Route remove:
```php
Route::delete('/gio-hang/xoa/{key}', [...])->where('key', '[A-Za-z0-9_-]+');
```

### Khi đặt hàng
`OrderItem.size_label` lưu nhãn size tại thời điểm mua (vd "Nhỏ (16cm)"). Kể cả admin có đổi giá hay xóa size sau này, đơn cũ vẫn hiển thị đúng.

---

## 📦 6 trạng thái đơn

| Trạng thái | Màu | Ý nghĩa |
|-----------|-----|---------|
| `pending` | 🟡 Vàng | Chờ xác nhận |
| `confirmed` | 🔵 Xanh nhạt | Đã xác nhận |
| `baking` | 🟦 Xanh | **Đang làm bánh** (mới so với e-commerce thường) |
| `shipping` | 🟦 Xanh | Đang giao |
| `completed` | 🟢 Xanh lá | Hoàn thành |
| `cancelled` | 🔴 Đỏ | Đã hủy |

---

## 🛠️ KHẮC PHỤC SỰ CỐ

### ❌ Lỗi "delivery_date is required"
→ Form checkout chưa được điền ngày nhận. Kiểm tra `<input type="date" name="delivery_date">` có gửi đúng định dạng `YYYY-MM-DD`.

### ❌ "Bánh cần đặt trước ít nhất X ngày"
→ Đây là validation đúng. Khách chọn ngày sớm hơn lead time cho phép.

### ❌ Size selector không hiển thị
→ Sản phẩm chưa được set `size_options`. Vào admin → sửa bánh → điền 3 size.

### ❌ Cart bị trùng dòng khi thêm cùng 1 bánh khác size
→ Đây là **đúng**. Mỗi size là 1 dòng riêng (composite key `id_sizeKey`).

### ❌ Ảnh không hiển thị
→ Chạy: `php artisan storage:link`

### ❌ Lỗi 404 khi click vào bánh
→ Slug chưa được tạo. Chạy lại: `php artisan migrate:fresh --seed`

### ❌ Giỏ hàng bị mất sau reload
→ Kiểm tra `SESSION_DRIVER=database` trong `.env` và bảng `sessions` đã tồn tại (`php artisan session:table` rồi migrate).

### ❌ Không vào được trang admin
→ Đảm bảo user có `is_admin = 1`. Hoặc đăng nhập `admin@happycake.com`.

### ❌ Lỗi APP_KEY
→ Chạy: `php artisan key:generate`

---

## 📞 LỆNH NHANH

```bash
# Reset toàn bộ DB + dữ liệu mẫu
php artisan migrate:fresh --seed

# Chạy server
php artisan serve

# Xem routes
php artisan route:list

# Clear cache
php artisan optimize:clear

# Symlink storage
php artisan storage:link
```

---

## 📝 CHECKLIST KIẾN THỨC ĐÃ DÙNG

✅ MVC Pattern  
✅ Eloquent ORM (relationships, eager loading, withCount)  
✅ Migration + Seeder + **JSON column** cho size_options  
✅ Validation (custom messages cho lead time)  
✅ **Cast** Eloquent (`'size_options' => 'array'`, `'delivery_date' => 'date'`)  
✅ Authentication (Laravel Breeze)  
✅ Custom Middleware (AdminMiddleware)  
✅ Route grouping & prefix & name prefix  
✅ Route Model Binding (`{product:slug}`)  
✅ **Route constraint regex** (`where('key', '[A-Za-z0-9_-]+')`)  
✅ Resource Controllers  
✅ Pagination với `withQueryString()`  
✅ Session (cart với **composite key** cho size variants)  
✅ File Upload (Storage)  
✅ Blade templating + Components  
✅ Flash messages  
✅ CSRF / Method Spoofing  
✅ Form Validation với `@error` / `is-invalid`  
✅ **Carbon** để xử lý ngày (`->addDays()`, `->format()`)  
✅ Collection helpers (`->filter()`, `->map()`, `->firstWhere()`)  

---

## 👨‍💻 TÁC GIẢ

**Built with 🧁 — Happy Cake Project**

🎉 **Chúc bạn nướng bánh & được điểm cao!**
