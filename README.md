# HappyCake - Website Bán Bánh Online

HappyCake là website thương mại điện tử chuyên bán bánh, xây dựng bằng **Laravel 12** (PHP 8.2+), **Tailwind CSS** và **SQLite**. Hỗ trợ đặt bánh theo size, đặt trước theo ngày giao và quản lý đơn hàng đầy đủ.

---

## Mục lục

1. [Công nghệ sử dụng](#1-công-nghệ-sử-dụng)
2. [Cấu trúc thư mục](#2-cấu-trúc-thư-mục)
3. [Cơ sở dữ liệu](#3-cơ-sở-dữ-liệu)
4. [Models và quan hệ](#4-models-và-quan-hệ)
5. [Phân quyền người dùng](#5-phân-quyền-người-dùng)
6. [Luồng hoạt động chính](#6-luồng-hoạt-động-chính)
7. [Chi tiết từng Controller](#7-chi-tiết-từng-controller)
8. [Hệ thống Route](#8-hệ-thống-route)
9. [Dữ liệu mẫu (Seeder)](#9-dữ-liệu-mẫu-seeder)
10. [Cách cài đặt và chạy](#10-cách-cài-đặt-và-chạy)

---

## 1. Công nghệ sử dụng

| Thành phần | Công nghệ | Mục đích |
|---|---|---|
| Backend | Laravel 12, PHP 8.2+ | Framework chính |
| Frontend | Blade Templates | Render HTML phía server |
| CSS | Tailwind CSS + Vite | Giao diện |
| Database | SQLite | Lưu trữ dữ liệu |
| Auth | Laravel Breeze | Đăng ký, đăng nhập |
| Session | Database | Lưu giỏ hàng |

---

## 2. Cấu trúc thư mục

```
happycake/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php          # Trang chủ
│   │   │   ├── ProductController.php       # Danh sách, tìm kiếm sản phẩm
│   │   │   ├── CartController.php          # Giỏ hàng
│   │   │   ├── CheckoutController.php      # Thanh toán
│   │   │   ├── OrderController.php         # Đơn hàng của user
│   │   │   ├── ProfileController.php       # Thông tin cá nhân
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php # Trang chủ admin
│   │   │   │   ├── ProductController.php   # Quản lý sản phẩm
│   │   │   │   ├── CategoryController.php  # Quản lý danh mục
│   │   │   │   ├── OrderController.php     # Quản lý đơn hàng
│   │   │   │   └── UserController.php      # Quản lý người dùng
│   │   │   └── Auth/                       # Đăng ký, đăng nhập (Breeze)
│   │   └── Middleware/
│   │       └── AdminMiddleware.php         # Kiểm tra quyền admin
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Product.php
│       ├── Order.php
│       └── OrderItem.php
├── database/
│   ├── migrations/                         # Định nghĩa cấu trúc bảng
│   └── seeders/DatabaseSeeder.php          # Dữ liệu mẫu
├── resources/views/
│   ├── home.blade.php                      # Trang chủ
│   ├── products/                           # Trang sản phẩm
│   ├── cart/                               # Giỏ hàng
│   ├── checkout/                           # Thanh toán
│   ├── orders/                             # Đơn hàng user
│   ├── admin/                              # Toàn bộ giao diện admin
│   ├── layouts/                            # Layout chung (app, guest)
│   ├── partials/                           # Component nhỏ (product-card)
│   └── auth/                               # Đăng ký, đăng nhập
└── routes/
    ├── web.php                             # Route chính
    └── auth.php                            # Route xác thực
```

---

## 3. Cơ sở dữ liệu

Dự án dùng **SQLite** (file `database/database.sqlite`), gồm 5 bảng chính:

### Bảng `users`
Lưu thông tin người dùng.

| Cột | Kiểu | Mô tả |
|---|---|---|
| id | integer | Khóa chính |
| name | string | Họ tên |
| email | string | Email (unique) |
| password | string | Mật khẩu (bcrypt) |
| is_admin | boolean | `true` = Admin, `false` = User thường |
| phone | string | Số điện thoại (nullable) |
| address | string | Địa chỉ (nullable) |

### Bảng `categories`
Phân loại bánh.

| Cột | Kiểu | Mô tả |
|---|---|---|
| id | integer | Khóa chính |
| name | string | Tên danh mục (VD: Bánh sinh nhật) |
| slug | string | Định danh URL (VD: banh-sinh-nhat) |
| description | text | Mô tả danh mục |
| image | string | Đường dẫn ảnh (nullable) |

### Bảng `products`
Sản phẩm bánh — bảng phức tạp nhất.

| Cột | Kiểu | Mô tả |
|---|---|---|
| id | integer | Khóa chính |
| category_id | FK | Thuộc danh mục nào |
| name | string | Tên bánh |
| slug | string | Định danh URL (unique) |
| price | decimal(12,0) | Giá gốc (VNĐ, không dùng thập phân) |
| sale_price | decimal(12,0) | Giá khuyến mãi (nullable) |
| image | string | Ảnh sản phẩm (nullable) |
| description | text | Mô tả |
| stock | integer | Số lượng tồn kho |
| featured | boolean | Sản phẩm nổi bật (hiển thị trang chủ) |
| size_options | JSON | Danh sách size + giá (nullable) |
| min_lead_days | tinyint | Số ngày phải đặt trước tối thiểu |

**Ví dụ `size_options`:**
```json
[
  {"key": "small",  "label": "Nhỏ (16cm - 4~6 người)",  "price": 250000},
  {"key": "medium", "label": "Vừa (20cm - 6~8 người)",  "price": 350000},
  {"key": "large",  "label": "Lớn (24cm - 10~12 người)", "price": 480000}
]
```

### Bảng `orders`
Đơn hàng của khách.

| Cột | Kiểu | Mô tả |
|---|---|---|
| id | integer | Khóa chính |
| user_id | FK | Người đặt |
| name | string | Tên người nhận |
| phone | string | Số điện thoại |
| address | string | Địa chỉ giao |
| note | text | Ghi chú thêm |
| delivery_date | date | Ngày nhận bánh (bắt buộc đặt trước) |
| cake_message | string(200) | Lời nhắn ghi trên bánh (VD: "Chúc mừng sinh nhật") |
| total_price | decimal(15,0) | Tổng tiền |
| status | enum | `pending / confirmed / baking / shipping / completed / cancelled` |

### Bảng `order_items`
Chi tiết từng sản phẩm trong đơn hàng.

| Cột | Kiểu | Mô tả |
|---|---|---|
| id | integer | Khóa chính |
| order_id | FK | Thuộc đơn hàng nào |
| product_id | FK | Sản phẩm nào |
| price | decimal | Giá tại thời điểm mua |
| quantity | integer | Số lượng |
| size_label | string | Size đã chọn (VD: "Vừa (20cm)") |

---

## 4. Models và quan hệ

```
User ──────────── hasMany ──────────── Order
                                          │
Category ── hasMany ── Product            └── hasMany ── OrderItem
                Product ◄─────────────────────── belongsTo
```

### User
```php
// User có nhiều đơn hàng
public function orders()
{
    return $this->hasMany(Order::class);
}
```
- Trường `is_admin` dùng để phân biệt admin và user thường.
- Mật khẩu tự động hash khi lưu nhờ `'password' => 'hashed'` trong `casts`.

### Category
```php
// Danh mục có nhiều sản phẩm
public function products()
{
    return $this->hasMany(Product::class);
}
```

### Product
```php
// Sản phẩm thuộc một danh mục
public function category()
{
    return $this->belongsTo(Category::class);
}

// Trả về giá hiển thị: ưu tiên giá sale, không có thì dùng giá gốc
public function getDisplayPriceAttribute()
{
    return $this->sale_price ?? $this->price;
}

// Kiểm tra sản phẩm có nhiều size không
public function hasSizeOptions(): bool
{
    return is_array($this->size_options) && count($this->size_options) > 0;
}
```
- `size_options` tự động parse từ JSON sang array PHP nhờ `'size_options' => 'array'` trong `casts`.

### Order
```php
public function user()  { return $this->belongsTo(User::class); }
public function items() { return $this->hasMany(OrderItem::class); }
```

### OrderItem
```php
public function order()   { return $this->belongsTo(Order::class); }
public function product() { return $this->belongsTo(Product::class); }
```

---

## 5. Phân quyền người dùng

Có 3 cấp độ truy cập:

| Vai trò | Điều kiện | Quyền |
|---|---|---|
| **Khách** | Chưa đăng nhập | Xem sản phẩm, thêm vào giỏ hàng |
| **User** | Đăng nhập, `is_admin = false` | + Thanh toán, xem đơn hàng, sửa profile |
| **Admin** | Đăng nhập, `is_admin = true` | + Toàn bộ trang quản trị `/admin` |

### Cách hoạt động của AdminMiddleware

```php
// app/Http/Middleware/AdminMiddleware.php
public function handle(Request $request, Closure $next): Response
{
    // Kiểm tra: đã đăng nhập VÀ có quyền admin
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Unauthorized'); // Trả về lỗi 403 nếu không đủ quyền
    }

    return $next($request); // Cho phép đi tiếp nếu đủ quyền
}
```

Middleware này được áp dụng cho toàn bộ nhóm route `/admin`:
```php
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(...)
// 'auth'  = phải đăng nhập (Laravel built-in)
// 'admin' = phải là admin (AdminMiddleware)
```

---

## 6. Luồng hoạt động chính

### Luồng mua hàng

```
[1] Khách vào trang chủ (/)
        │
        ▼
[2] Xem danh sách sản phẩm (/san-pham)
    - Tìm kiếm theo tên
    - Lọc theo danh mục, giá
    - Sắp xếp (giá tăng/giảm, mới nhất, tên)
        │
        ▼
[3] Xem chi tiết sản phẩm (/san-pham/{slug})
    - Nếu có size_options → bắt buộc chọn size
    - Chọn số lượng
        │
        ▼
[4] Thêm vào giỏ hàng (/gio-hang/them/{id})
    - Lưu vào SESSION (không cần đăng nhập)
    - Key giỏ: "productId" hoặc "productId_sizeKey"
        │
        ▼
[5] Xem giỏ hàng (/gio-hang)
    - Sửa số lượng
    - Xóa sản phẩm
        │
        ▼
[6] Thanh toán (/thanh-toan) ← YÊU CẦU ĐĂNG NHẬP
    - Điền thông tin giao hàng
    - Chọn ngày nhận (tự động tính ngày sớm nhất)
    - Ghi lời nhắn trên bánh (tùy chọn)
        │
        ▼
[7] Tạo đơn hàng
    - Lưu Order + OrderItems vào DB
    - Xóa giỏ hàng khỏi session
    - Chuyển đến trang xác nhận
```

### Vòng đời trạng thái đơn hàng (Admin xử lý)

```
pending → confirmed → baking → shipping → completed
                                    ↑
                  (hoặc cancelled ở bất kỳ bước nào)
```

---

## 7. Chi tiết từng Controller

### HomeController
**File:** `app/Http/Controllers/HomeController.php`

Xử lý trang chủ. Lấy dữ liệu để hiển thị các section:

```php
public function index()
{
    // Sản phẩm nổi bật (featured = true), lấy tối đa 8
    $featuredProducts = Product::where('featured', true)->take(8)->get();

    // Tất cả danh mục kèm số lượng sản phẩm
    $categories = Category::withCount('products')->get();

    // Sản phẩm mới nhất, lấy tối đa 8
    $latestProducts = Product::latest()->take(8)->get();

    // Riêng bánh sinh nhật (lọc theo slug danh mục)
    $birthdayCakes = Product::whereHas('category',
        fn($q) => $q->where('slug', 'banh-sinh-nhat')
    )->take(4)->get();
}
```

---

### ProductController
**File:** `app/Http/Controllers/ProductController.php`

Xử lý danh sách và tìm kiếm sản phẩm với nhiều bộ lọc kết hợp:

```php
public function index(Request $request)
{
    $query = Product::with('category'); // Eager load category, tránh N+1 query

    // Lọc theo tên
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Lọc theo danh mục (dùng slug thay vì id)
    if ($request->filled('category')) {
        $query->whereHas('category',
            fn($q) => $q->where('slug', $request->category)
        );
    }

    // Lọc giá: xét cả giá sale lẫn giá gốc
    if ($request->filled('price_from')) {
        $query->where(function ($q) use ($request) {
            $q->whereNotNull('sale_price')->where('sale_price', '>=', $request->price_from)
              ->orWhereNull('sale_price')->where('price', '>=', $request->price_from);
        });
    }

    // Sắp xếp dùng match expression (PHP 8+)
    match ($request->sort) {
        'price_asc'  => $query->orderByRaw('COALESCE(sale_price, price) ASC'),
        'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) DESC'),
        'newest'     => $query->latest(),
        'name'       => $query->orderBy('name'),
        default      => $query->latest(),
    };

    // Phân trang 12 sản phẩm/trang, giữ nguyên query string trên URL
    $products = $query->paginate(12)->withQueryString();
}
```

**Lưu ý:** `COALESCE(sale_price, price)` là hàm SQL trả về giá trị đầu tiên không NULL — nghĩa là ưu tiên `sale_price` nếu có, không thì dùng `price`. Đảm bảo sắp xếp theo giá thực tế khách trả.

---

### CartController
**File:** `app/Http/Controllers/CartController.php`

Quản lý giỏ hàng bằng **Laravel Session**. Không cần đăng nhập.

**Cấu trúc giỏ hàng trong session:**
```php
$cart = [
    '5'        => [...],   // Sản phẩm id=5, không có size
    '3_small'  => [...],   // Sản phẩm id=3, size "small"
    '3_large'  => [...],   // Sản phẩm id=3, size "large" (dòng riêng)
]
```

Mỗi sản phẩm trong giỏ chứa:
```php
[
    'product_id'    => 3,
    'name'          => 'Bánh kem dâu tươi',
    'price'         => 350000,     // Giá của size được chọn
    'quantity'      => 2,
    'size_key'      => 'medium',
    'size_label'    => 'Vừa (20cm - 6~8 người)',
    'min_lead_days' => 1,
]
```

**Cách thêm vào giỏ:**
```php
public function add(Request $request, Product $product)
{
    $sizeKey = $request->input('size');

    // Nếu sản phẩm có size_options mà không chọn size → báo lỗi
    if ($product->hasSizeOptions()) {
        if (!$sizeKey) {
            return redirect()->back()->with('error', 'Vui lòng chọn size bánh.');
        }
        // Tìm size được chọn trong danh sách size của sản phẩm
        $matched = collect($product->size_options)->firstWhere('key', $sizeKey);
        $price = $matched['price']; // Dùng giá của size đó, không phải giá mặc định
    }

    // Key phức hợp: "3" hoặc "3_small" hoặc "3_large"
    $cartKey = $sizeKey ? $product->id . '_' . $sizeKey : (string) $product->id;

    // Nếu đã có trong giỏ → cộng thêm số lượng
    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity'] += $qty;
    } else {
        $cart[$cartKey] = [...]; // Thêm mới
    }

    session()->put('cart', $cart); // Lưu lại session
}
```

---

### CheckoutController
**File:** `app/Http/Controllers/CheckoutController.php`

Phần quan trọng nhất — xử lý logic đặt trước theo ngày.

**Tính ngày giao sớm nhất:**
```php
private function maxLeadDays(array $cart): int
{
    $max = 1;
    foreach ($cart as $item) {
        $lead = (int) ($item['min_lead_days'] ?? 1);
        if ($lead > $max) $max = $lead;
    }
    return $max;
}
```

**Ví dụ thực tế:**
- Giỏ có: Bánh kem dâu (min_lead_days=1) + Bánh sinh nhật 2 tầng (min_lead_days=4)
- `maxLeadDays()` trả về 4
- Ngày giao sớm nhất = hôm nay + 4 ngày

**Validate khi đặt hàng:**
```php
$request->validate([
    'delivery_date' => 'required|date|after_or_equal:' . $earliestDate,
], [
    'delivery_date.after_or_equal' =>
        "Bánh cần đặt trước ít nhất {$minLeadDays} ngày. Ngày nhận sớm nhất: ..."
]);
```

**Tạo đơn hàng:**
```php
// Tạo bản ghi Order
$order = Order::create([
    'user_id'       => auth()->id(),
    'delivery_date' => $request->delivery_date,
    'cake_message'  => $request->cake_message, // Lời nhắn ghi trên bánh
    'total_price'   => $total,
    'status'        => 'pending',
    ...
]);

// Tạo từng OrderItem cho mỗi sản phẩm trong giỏ
foreach ($cart as $item) {
    OrderItem::create([
        'order_id'   => $order->id,
        'product_id' => $item['product_id'],
        'price'      => $item['price'],   // Lưu giá tại thời điểm mua
        'quantity'   => $item['quantity'],
        'size_label' => $item['size_label'] ?? null,
    ]);
}

session()->forget('cart'); // Xóa giỏ hàng sau khi đặt thành công
```

**Lưu ý quan trọng:** Giá được lưu trực tiếp vào `order_items.price` (không phải FK đến bảng giá). Điều này đảm bảo nếu admin thay đổi giá sau, đơn hàng cũ vẫn giữ nguyên giá gốc lúc khách mua.

---

### OrderController (User)
**File:** `app/Http/Controllers/OrderController.php`

```php
public function show(Order $order)
{
    // Bảo mật: user chỉ xem được đơn của mình
    // Ngoại lệ: admin có thể xem tất cả
    if ($order->user_id !== auth()->id() && !auth()->user()->is_admin) {
        abort(403);
    }

    $order->load('items.product'); // Load quan hệ lồng nhau (items + product của mỗi item)
    return view('orders.show', compact('order'));
}
```

---

### Admin\DashboardController
**File:** `app/Http/Controllers/Admin/DashboardController.php`

```php
public function index()
{
    $totalProducts = Product::count();
    $totalOrders   = Order::count();

    // Không đếm admin vào số lượng "người dùng"
    $totalUsers = User::where('is_admin', false)->count();

    // Doanh thu = tổng tiền của đơn đã hoàn thành
    $totalRevenue = Order::where('status', 'completed')->sum('total_price');

    // 5 đơn hàng mới nhất (kèm thông tin user bằng eager loading)
    $recentOrders = Order::with('user')->latest()->take(5)->get();

    // Thống kê số đơn theo từng trạng thái
    $ordersByStatus = Order::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');
    // Kết quả: ['pending' => 5, 'confirmed' => 3, 'completed' => 12, ...]
}
```

---

### Admin\ProductController
**File:** `app/Http/Controllers/Admin/ProductController.php`

Xử lý CRUD sản phẩm. Tính năng đặc biệt:

**Tự động tạo slug từ tên:**
```php
$data['slug'] = Str::slug($request->name);
// "Bánh kem dâu tươi" → "banh-kem-dau-tuoi"
```

**Xử lý size_options từ form:**
```php
$sizes = collect($request->input('sizes', []))
    ->filter(fn($s) => !empty($s['label']) && !empty($s['price'])) // Bỏ size để trống
    ->values()
    ->map(fn($s, $i) => [
        'key'   => 'size' . ($i + 1),  // Tự tạo key: size1, size2, size3...
        'label' => $s['label'],
        'price' => (int) $s['price'],
    ])
    ->toArray();

$data['size_options'] = count($sizes) > 0 ? $sizes : null;
```

**Upload ảnh:**
```php
if ($request->hasFile('image')) {
    // Lưu vào storage/app/public/products/
    $data['image'] = $request->file('image')->store('products', 'public');
}
```

---

### Admin\OrderController
**File:** `app/Http/Controllers/Admin/OrderController.php`

```php
// Cập nhật trạng thái đơn hàng
public function updateStatus(Request $request, Order $order)
{
    $request->validate([
        // Chỉ cho phép 6 trạng thái hợp lệ
        'status' => 'required|in:pending,confirmed,baking,shipping,completed,cancelled',
    ]);

    $order->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
}
```

---

## 8. Hệ thống Route

**File:** `routes/web.php`

```php
// === FRONTEND (không cần đăng nhập) ===
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/san-pham', [ProductController::class, 'index'])->name('products.index');

// {product:slug} = Route Model Binding: Laravel tự tìm Product theo cột slug
Route::get('/san-pham/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/danh-muc/{category:slug}', [ProductController::class, 'byCategory'])->name('categories.products');

// Giỏ hàng (không cần đăng nhập)
Route::get('/gio-hang', ...)->name('cart.index');
Route::post('/gio-hang/them/{product}', ...)->name('cart.add');
Route::put('/gio-hang/cap-nhat', ...)->name('cart.update');
Route::delete('/gio-hang/xoa/{key}', ...)->name('cart.remove')
    ->where('key', '[A-Za-z0-9_-]+'); // Giới hạn ký tự hợp lệ trong cart key

// === AUTH REQUIRED ===
Route::middleware('auth')->group(function () {
    Route::get('/thanh-toan', ...);
    Route::post('/thanh-toan', ...);
    Route::get('/don-hang', ...);
    Route::get('/don-hang/{order}', ...);
    Route::get('/profile', ...);
});

// === ADMIN (yêu cầu cả 'auth' lẫn 'admin' middleware) ===
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    // Route::resource tự tạo đủ 7 route: index, create, store, show, edit, update, destroy
    Route::resource('categories', AdminCategoryController::class)->except('show');
    Route::resource('products', AdminProductController::class)->except('show');

    Route::get('orders', ...)->name('orders.index');
    Route::get('orders/{order}', ...)->name('orders.show');
    Route::patch('orders/{order}/status', ...)->name('orders.updateStatus');
    Route::get('users', ...)->name('users.index');
});
```

---

## 9. Dữ liệu mẫu (Seeder)

**File:** `database/seeders/DatabaseSeeder.php`

Chạy `php artisan db:seed` để tạo dữ liệu mẫu gồm:

**Tài khoản mặc định:**

| Email | Mật khẩu | Vai trò |
|---|---|---|
| admin@happycake.com | password | Admin |
| user@happycake.com | password | User thường |

**6 danh mục:**

| Danh mục | Slug |
|---|---|
| Bánh sinh nhật | banh-sinh-nhat |
| Bánh kem | banh-kem |
| Cupcake | cupcake |
| Bánh mì | banh-mi |
| Bánh ngọt | banh-ngot |
| Bánh Trung thu | banh-trung-thu |

**20+ sản phẩm mẫu** với đầy đủ: size options, giá, mô tả, min_lead_days (1–4 ngày tùy loại).

---

## 10. Cách cài đặt và chạy

### Yêu cầu hệ thống
- PHP 8.2+
- Composer
- Node.js 18+

### Cài đặt

```bash
# 1. Cài PHP dependencies
composer install

# 2. Tạo file cấu hình môi trường
cp .env.example .env

# 3. Tạo app key bảo mật
php artisan key:generate

# 4. Tạo database và chạy migration
php artisan migrate

# 5. Nhập dữ liệu mẫu
php artisan db:seed

# 6. Cài Node dependencies
npm install

# 7. Build assets (CSS, JS)
npm run build

# 8. Tạo symlink để hiển thị ảnh upload
php artisan storage:link
```

### Chạy development server

```bash
# Chạy tất cả cùng lúc (PHP server + queue + log + Vite hot reload)
composer dev
```

Hoặc chạy riêng từng phần:
```bash
php artisan serve   # Backend tại http://localhost:8000
npm run dev         # Frontend với hot reload
```

### Truy cập sau khi chạy

| Trang | URL | Tài khoản |
|---|---|---|
| Trang chủ | http://localhost:8000 | Không cần đăng nhập |
| Đăng nhập | http://localhost:8000/login | — |
| Trang quản trị | http://localhost:8000/admin | admin@happycake.com / password |
| User thường | http://localhost:8000/login | user@happycake.com / password |
