# Hướng dẫn Setup Dự án Venue Listing Platform

Dự án này sử dụng Laravel 12 (Backend), Next.js (Frontend) và MySQL 8. Tất cả được đóng gói trong Docker để dễ dàng triển khai.

## 1. Yêu cầu hệ thống (macOS Fresh)

Trước khi bắt đầu, hãy đảm bảo máy của bạn đã cài đặt các công cụ sau:

### Cài đặt Docker Desktop
1. Truy cập [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop/).
2. Chọn phiên bản phù hợp (Apple Chip hoặc Intel Chip).
3. Tải về file `.dmg`, mở lên và kéo Docker vào thư mục Applications.
4. Mở Docker từ Applications và đợi cho đến khi icon Docker trên menu bar báo "running".

### Cài đặt Git (Nếu chưa có)
Mở Terminal và chạy:
```bash
git --version
```
Nếu chưa có, macOS sẽ yêu cầu cài đặt Command Line Tools, hãy chọn Install.

---

## 2. Triển khai Project Locally

### Bước 1: Clone dự án
```bash
git clone <repository_url> tuan
cd tuan
```

### Bước 2: Cấu hình Environment
Copy file mẫu và sửa nếu cần (mặc định đã cấu hình sẵn cho Docker):
```bash
cp .env.example .env
cp backend/.env.example backend/.env
```
*Lưu ý: File `.env` trong thư mục `backend` cần cấu hình đúng DB_HOST là `mysql`.*

### Bước 3: Build và khởi chạy Docker Containers
```bash
docker-compose up -d --build
```
Lệnh này sẽ tải các image cần thiết (PHP 8.4, Node 20, MySQL 8, Nginx) và khởi tạo các service:
- **venue-app**: PHP 8.4 (Laravel)
- **venue-frontend**: Next.js (React 19)
- **venue-mysql**: MySQL 8
- **venue-nginx**: Nginx (Web server cho Laravel)

### Bước 4: Cài đặt Backend & Khởi tạo Database
Khi các container đã chạy, thực hiện các lệnh sau:

1. **Cài đặt PHP dependencies:**
```bash
docker exec venue-app composer install
```

2. **Generate App Key:**
```bash
docker exec venue-app php artisan key:generate
```

3. **Cấu hình liên kết Storage (cho việc upload ảnh):**
```bash
docker exec venue-app php artisan storage:link
```

4. **Thiết lập JWT:**
```bash
docker exec venue-app php artisan jwt:secret
```

5. **Migration & Seeding (Tạo bảng và dữ liệu mẫu):**
```bash
docker exec venue-app php artisan migrate --seed
```

---

## 3. Truy cập Ứng dụng

Sau khi hoàn tất, bạn có thể truy cập:

- **Frontend (Người dùng):** [http://localhost:3005](http://localhost:3005)
- **Admin Portal:** [http://localhost:3005/admin/login](http://localhost:3005/admin/login)
  - **Email:** `admin@example.com`
  - **Password:** `password`
- **Backend API Docs (JSON):** [http://localhost:8005/api](http://localhost:8005/api)
- **MySQL Direct Access:** `localhost:3011` (User: `sail`, Pass: `root`)

---

## 4. Các lệnh hữu ích

- **Xem logs:** `docker-compose logs -f`
- **Dừng containers:** `docker-compose stop`
- **Xóa toàn bộ (bao gồm dữ liệu DB):** `docker-compose down -v`
- **Chạy Tests:**
  - Backend: `docker exec venue-app php artisan test`
  - Frontend: `docker exec venue-frontend npm test`
