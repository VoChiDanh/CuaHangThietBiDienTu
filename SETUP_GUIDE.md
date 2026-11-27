# HƯỚNG DẪN SETUP, VẬN HÀNH VÀ PHÁT TRIỂN DỰ ÁN VỚI DOCKER
**Dự án: Cửa Hàng Thiết Bị Điện Tử (Laravel 13 - PHP 8.5)**

---

## 📌 1. Yêu cầu môi trường
Trước khi chạy dự án, máy tính của bạn chỉ cần:
- **Docker Desktop** đã được cài đặt và đang bật (Running).
- Không cần cài đặt PHP, Composer hay Node.js trên máy tính vì toàn bộ đã được đóng gói và chạy bên trong container Docker.

---

## 🚀 2. Hướng dẫn Setup lần đầu (First Time Setup)

Mở terminal tại thư mục gốc của dự án:
- **Windows**: Dùng **PowerShell** hoặc **Git Bash**.
- **macOS**: Dùng **Terminal** hoặc **iTerm2**.

### Bước 1: Chuẩn bị file `.env`
Nếu chưa có file `.env`, hãy copy từ `.env.example`:
- **Windows (PowerShell):**
  ```powershell
  cp .env.example .env
  ```
- **macOS / Linux:**
  ```bash
  cp .env.example .env
  ```
> *Lưu ý: File `.env` đã được cấu hình chuẩn cho Docker:*
> - `APP_PORT=8000` (Web chính)
> - `DB_CONNECTION=mysql`, `DB_HOST=mysql`, `DB_PORT=3306`, `DB_DATABASE=laravel`
> - `DB_USERNAME=sail`, `DB_PASSWORD=password`
> - `FORWARD_DB_PORT=33066` (Cổng kết nối MySQL từ máy host)
> - `REDIS_HOST=redis`, `MAIL_HOST=mailpit`
>
> Phí vận chuyển demo có thể cấu hình qua `STORE_SHIPPING_STANDARD_FEE` (30.000₫),
> `STORE_SHIPPING_EXPRESS_FEE` (50.000₫) và `STORE_SHIPPING_SAME_DAY_FEE` (80.000₫).
> Ứng dụng tính phí ở phía máy chủ; không cần thông tin API hãng vận chuyển.

### Bước 2: Khởi động các Docker Containers
- **Windows:**
  ```powershell
  docker compose up -d
  # Hoặc dùng script tiện ích:
  .\sail.ps1 up -d
  ```
- **macOS / Linux:**
  ```bash
  docker compose up -d
  # Hoặc dùng lệnh Sail gốc của Laravel:
  ./vendor/bin/sail up -d
  ```
> Compose cũng khởi chạy worker `queue` để xử lý email nền. Dùng `docker compose logs -f queue` để theo dõi; email thử nghiệm xem trong Mailpit.

### Bước 3: Cài đặt thư viện PHP & Sinh Application Key
*(Chỉ cần chạy nếu thư mục `vendor/` chưa có hoặc khi mới clone project về máy mới)*
- **Windows:**
  ```powershell
  docker compose exec laravel.test composer install
  docker compose exec laravel.test php artisan key:generate
  ```
- **macOS / Linux:**
  ```bash
  docker compose exec laravel.test composer install
  docker compose exec laravel.test php artisan key:generate
  # Hoặc: ./vendor/bin/sail composer install && ./vendor/bin/sail artisan key:generate
  ```

### Bước 4: Chạy Migration khởi tạo Cơ sở dữ liệu
- **Windows:**
  ```powershell
  docker compose exec laravel.test php artisan migrate
  ```
- **macOS / Linux:**
  ```bash
  docker compose exec laravel.test php artisan migrate
  # Hoặc: ./vendor/bin/sail artisan migrate
  ```

### Bước 5: Tạo liên kết đến ảnh sản phẩm và tệp tải lên
Ảnh nằm trong `storage/app/public/products`. Sau khi clone hoặc pull cập nhật mới, chạy lệnh sau để website phục vụ ảnh qua đường dẫn `/storage`:
- **Windows:**
  ```powershell
  docker compose exec laravel.test php artisan storage:link
  ```
- **macOS / Linux:**
  ```bash
  docker compose exec laravel.test php artisan storage:link
  # Hoặc: ./vendor/bin/sail artisan storage:link
  ```

### Bước 6: Cài đặt thư viện Frontend & Biên dịch Assets
- **Windows:**
  ```powershell
  docker compose exec laravel.test npm install
  docker compose exec laravel.test npm run build
  ```
- **macOS / Linux:**
  ```bash
  docker compose exec laravel.test npm install
  docker compose exec laravel.test npm run build
  # Hoặc: ./vendor/bin/sail npm install && ./vendor/bin/sail npm run build
  ```

---

## 🌐 3. Danh sách các dịch vụ & Đường dẫn truy cập

| Dịch vụ | Địa chỉ URL | Thông tin đăng nhập / Ghi chú |
| :--- | :--- | :--- |
| **Ứng dụng Web (Laravel)** | [http://localhost:8000](http://localhost:8000) | Giao diện trang web chính |
| **phpMyAdmin** | [http://localhost:8080](http://localhost:8080) | **Server:** `mysql`<br>**User:** `sail` \| **Password:** `password` |
| **Mailpit (Web UI)** | [http://localhost:8025](http://localhost:8025) | Hộp thư xem trước các email gửi đi từ ứng dụng |
| **MySQL (Từ máy host)** | `localhost:33066` | Dùng cho DBeaver, Navicat, TablePlus (macOS), HeidiSQL |
| **Redis** | `localhost:6379` | Cache & Queues |

---

## 🍎 4. Mẹo riêng cực kỳ tiện lợi cho người dùng macOS

Trên macOS, bạn có thể tạo một alias ngắn gọn trong terminal để gõ `sail` thay vì phải gõ dài dòng:

1. Mở file cấu hình shell (Zsh mặc định trên macOS):
   ```bash
   nano ~/.zshrc
   ```
2. Thêm dòng này vào cuối file:
   ```bash
   alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
   ```
3. Lưu lại (`Ctrl + O`, `Enter`, `Ctrl + X`) rồi tải lại shell:
   ```bash
   source ~/.zshrc
   ```
4. Bây giờ ở bất kỳ thư mục dự án Laravel nào trên macOS, bạn chỉ cần gõ:
   - `sail up -d` (Bật Docker)
   - `sail down` (Tắt Docker)
   - `sail artisan migrate` (Chạy migrate)
   - `sail npm run dev` (Bật Vite hot-reload)
   - `sail bash` (Vào terminal trong container)

---

## 🛠 5. HƯỚNG DẪN PHÁT TRIỂN: Chỉnh sửa Backend, Frontend, API ở đâu & Chạy lại ra sao?

> 💡 **NGUYÊN LÝ HOẠT ĐỘNG (Volume Mount):**
> Toàn bộ thư mục dự án trên máy tính của bạn được đồng bộ trực tiếp hai chiều vào thư mục `/var/www/html` bên trong Docker container.
> 👉 **Bất kỳ file nào bạn sửa và lưu (Ctrl + S / Cmd + S) trong VS Code/PhpStorm sẽ CÓ HIỆU LỰC NGAY LẬP TỨC bên trong Docker mà KHÔNG CẦN build lại image hay restart container!**

---

### A. Chỉnh sửa BACKEND (PHP, Database, Logic nghiệp vụ)

#### 1. Chỉnh sửa ở đâu?
- **Routes (Điều hướng URL)**: `routes/web.php`
  - Nơi khai báo các URL như trang chủ `/`, xem chi tiết sản phẩm `/products/{slug}`, giỏ hàng `/cart`, thanh toán `/checkout`.
- **Controllers (Xử lý logic)**: `app/Http/Controllers/`
  - Ví dụ: `ProductController.php`, `CartController.php`, `OrderController.php`.
- **Models (Tương tác CSDL - Eloquent)**: `app/Models/`
  - Ví dụ: `Product.php`, `Category.php`, `Order.php`.
- **Migrations (Tạo/Sửa bảng CSDL)**: `database/migrations/`
  - Nơi định nghĩa các cột, khóa ngoại của bảng.
- **Dữ liệu mẫu (Seeders/Factories)**: `database/seeders/` và `database/factories/`
  - Nơi tạo dữ liệu mẫu cho sản phẩm, danh mục để test.
- **Cấu hình hệ thống**: `config/` và file `.env`.

#### 2. Chạy lại như thế nào khi sửa Backend?
- **Khi sửa logic trong Controller, Model, Service, Route:**
  - 👉 **Chỉ cần lưu file và F5 lại trình duyệt**. Không cần gõ thêm lệnh nào!
- **Khi tạo Model, Controller mới:**
  ```powershell
  # Windows
  .\sail.ps1 artisan make:model Product -mcr
  # macOS
  sail artisan make:model Product -mcr
  ```
  *(Tham số `-mcr` sẽ tự động tạo luôn Migration, Controller dạng Resource, và Model)*.
- **Khi tạo mới hoặc sửa file Migration (CSDL):**
  ```powershell
  # Windows
  .\sail.ps1 artisan migrate
  # macOS
  sail artisan migrate
  ```
- **Khi sửa file `.env` hoặc file cấu hình trong `config/`:**
  ```powershell
  # Windows
  .\sail.ps1 artisan optimize:clear
  # macOS
  sail artisan optimize:clear
  ```

---

### B. Chỉnh sửa FRONTEND (Giao diện, HTML/Blade, CSS, JavaScript)

#### 1. Chỉnh sửa ở đâu?
- **Giao diện HTML / Template Blade**: `resources/views/`
  - Trang chủ, danh sách sản phẩm, chi tiết sản phẩm, header, footer... (các file đuôi `.blade.php`).
- **CSS / Giao diện Tailwind CSS**: `resources/css/app.css`
  - Dự án đang sử dụng **Tailwind CSS v4**. Bạn có thể viết utility class trực tiếp trong file Blade hoặc khai báo CSS tùy chỉnh tại đây.
- **JavaScript**: `resources/js/app.js`
  - Code xử lý tương tác giao diện, gọi API AJAX/Fetch, giỏ hàng, popup...

#### 2. Chạy lại như thế nào khi sửa Frontend?
Có 2 chế độ chạy:

- **Chế độ 1: Khi ĐANG LẬP TRÌNH (Khuyên dùng - Bật Vite Hot-Reload):**
  Mở 1 tab terminal riêng và chạy:
  ```powershell
  # Windows
  .\sail.ps1 npm run dev
  # macOS
  sail npm run dev
  # Hoặc lệnh Docker tiêu chuẩn (chạy được trên mọi hệ điều hành):
  docker compose exec laravel.test npm run dev
  ```
  > Khi lệnh này đang chạy, bất cứ khi nào bạn sửa file Blade, CSS hay JS và bấm Save, trình duyệt sẽ **tự động cập nhật giao diện ngay lập tức** mà bạn không cần phải bấm F5 thủ công!

- **Chế độ 2: ĐÓNG GÓI SẢN PHẨM (Build tĩnh khi xong việc hoặc mang đi demo/deploy):**
  ```powershell
  # Windows
  .\sail.ps1 npm run build
  # macOS
  sail npm run build
  ```

---

### C. Chỉnh sửa API (RESTful API cho Mobile App hoặc Frontend tách rời)

#### 1. Chỉnh sửa ở đâu?
- **Cài đặt file routes API** (Laravel mặc định rút gọn file api, nếu chưa có `routes/api.php`, bạn chỉ cần chạy 1 lần lệnh sau):
  ```powershell
  # Windows: .\sail.ps1 artisan install:api
  # macOS:   sail artisan install:api
  ```
- **Khai báo Endpoints**: `routes/api.php`
  - Ví dụ:
    ```php
    Route::get('/products', [ApiProductController::class, 'index']);
    Route::post('/cart/add', [ApiCartController::class, 'store']);
    ```
- **Controllers API**: `app/Http/Controllers/Api/`
  - Nhận request từ client và trả về dữ liệu chuẩn JSON:
    ```php
    return response()->json([
        'status' => 'success',
        'data' => $products
    ]);
    ```
- **Chuẩn hóa dữ liệu JSON (API Resources)**: `app/Http/Resources/`
  - Tạo Resource định dạng dữ liệu:
    ```powershell
    # Windows: .\sail.ps1 artisan make:resource ProductResource
    # macOS:   sail artisan make:resource ProductResource
    ```

#### 2. Chạy lại & Kiểm thử API như thế nào?
- Khi lưu code API, dữ liệu cập nhật ngay tức thì.
- **Địa chỉ gọi API**: `http://localhost:8000/api/<endpoint-cua-ban>`
  - Ví dụ: `http://localhost:8000/api/products`
- **Công cụ test API tiện lợi**:
  - Dùng **Postman** hoặc cài extension **Thunder Client** ngay bên trong VS Code.
  - Test nhanh bằng trình duyệt (với phương thức GET) hoặc bằng lệnh `curl`.

---

## 💻 6. Bảng tổng hợp các lệnh thường dùng hàng ngày

| Thao tác mong muốn | Windows (PowerShell) | macOS / Linux (Terminal) |
| :--- | :--- | :--- |
| **Bật toàn bộ dự án** | `.\sail.ps1 up -d` | `sail up -d` *(hoặc `docker compose up -d`)* |
| **Tắt dự án** | `.\sail.ps1 down` | `sail down` *(hoặc `docker compose down`)* |
| **Xem trạng thái containers** | `.\sail.ps1 ps` | `sail ps` *(hoặc `docker compose ps`)* |
| **Xem logs thời gian thực** | `.\sail.ps1 logs` | `sail logs -f` |
| **Chạy migrate CSDL** | `.\sail.ps1 artisan migrate` | `sail artisan migrate` |
| **Bật Vite hot-reload** | `.\sail.ps1 npm run dev` | `sail npm run dev` |
| **Build assets giao diện** | `.\sail.ps1 npm run build` | `sail npm run build` |
| **Cài package PHP** | `.\sail.ps1 composer require <tên>` | `sail composer require <tên>` |
| **Cài package JS** | `.\sail.ps1 npm i <tên>` | `sail npm i <tên>` |
| **Chạy Tinker CLI** | `.\sail.ps1 tinker` | `sail tinker` |
| **Vào Terminal Linux container**| `.\sail.ps1 bash` | `sail bash` |

---

## ⚠️ 7. Xử lý các lỗi thường gặp (Troubleshooting)

### 1. Lỗi cổng bị chiếm (Port already in use)
- **MySQL (Port 3306)**: Nếu máy tính đã có MySQL chạy ngoài máy thật, file `.env` của dự án đã được đặt cổng ngoài là `FORWARD_DB_PORT=33066` để không bao giờ bị đụng độ.
- **Port 8000 (Web)**: Nếu bị ứng dụng khác chiếm, mở `.env` sửa thành `APP_PORT=8001`.

### 2. Giao diện bị vỡ layout / Thiếu file CSS
Chỉ cần chạy lệnh build lại assets:
- Windows: `.\sail.ps1 npm run build`
- macOS: `sail npm run build`

### 3. Docker báo lỗi kết nối daemon
Đảm bảo phần mềm **Docker Desktop** đã được mở và chuyển sang trạng thái xanh (Engine running).
