# Tài liệu API Venue Listing Platform

Base URL: `http://localhost:8005/api`
Authentication: Bearer Token (JWT)

## 1. Authentication

### Đăng ký (Register)
- **Endpoint:** `POST /auth/register`
- **Body:** `{ "name", "email", "password", "password_confirmation" }`

### Đăng nhập (Login)
- **Endpoint:** `POST /auth/login`
- **Body:** `{ "email", "password" }`
- **Response:** `{ "access_token", "token_type", "expires_in", "user" }`

### Thông tin cá nhân (Me)
- **Endpoint:** `GET /auth/me`
- **Headers:** `Authorization: Bearer <token>`

---

## 2. Venues (Công khai)

### Danh sách Venues
- **Endpoint:** `GET /venues`
- **Query Params:** 
  - `search`: Tìm theo tên/vị trí
  - `category_id`: Lọc theo loại
  - `city`: Lọc theo thành phố
  - `min_capacity`: Lọc theo sức chứa
  - `price_level`: Lọc theo giá (1-5)
  - `page`: Trang hiện tại
- **Response:** Laravel Pagination Object

### Chi tiết Venue
- **Endpoint:** `GET /venues/{id}`

---

## 3. Categories (Công khai)

### Danh sách Categories
- **Endpoint:** `GET /categories`

---

## 4. Quote Request (Công khai)

### Gửi yêu cầu báo giá
- **Endpoint:** `POST /quotes`
- **Body:** `{ "venue_id", "name", "email", "phone", "event_date", "guest_count", "message" }`

---

## 5. Admin Endpoints (Yêu cầu JWT + role Admin)

### Quản lý Venues
- `POST /admin/venues` - Tạo mới
- `PUT /admin/venues/{id}` - Cập nhật
- `DELETE /admin/venues/{id}` - Xóa

### Quản lý Quotes
- `GET /admin/quotes` - Danh sách yêu cầu
- `PUT /admin/quotes/{id}` - Cập nhật trạng thái (`pending`, `contacted`, `completed`, `cancelled`)

---

## Error Codes
- `400`: Bad Request (Validation failed)
- `401`: Unauthorized (Invalid/Missing token)
- `403`: Forbidden (User is not an admin)
- `404`: Not Found
