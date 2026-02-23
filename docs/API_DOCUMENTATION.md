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
  - `search`: Tìm theo tên/vị trí tổng quát
  - `category_id`: Lọc theo loại
  - `city`: Lọc theo thành phố
  - `min_capacity`: Lọc theo sức chứa tối thiểu
  - `price_level`: Lọc theo mức giá (1-5)
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

### Yêu cầu Báo giá của Tôi (User)
- **Endpoint:** `GET /my-quotes`
- **Headers:** `Authorization: Bearer <token>`
- **Query Params:** `venue_name` (Lọc theo tên địa điểm)
- **Response:** Mảng các Quote đã gửi của User hiện tại

---

## 5. Admin Endpoints (Yêu cầu JWT + role Admin)

### Quản lý Venues
- `POST /admin/venues` - Tạo mới (Hỗ trợ `multipart/form-data` cho tham số `images[]` upload file)
- `POST /admin/venues/{id}` - Cập nhật (Sử dụng `_method=PUT` đi kèm `multipart/form-data` để upload hình ảnh)
- `DELETE /admin/venues/{id}` - Xóa

### Quản lý Quotes
- `GET /admin/quotes` - Danh sách yêu cầu báo giá. Hỗ trợ query params: `venue_name`
- `PUT /admin/quotes/{id}` - Cập nhật trạng thái (`pending`, `contacted`, `completed`, `cancelled`)

---

## Error Codes
- `400`: Bad Request (Validation failed)
- `401`: Unauthorized (Invalid/Missing token)
- `403`: Forbidden (User is not an admin)
- `404`: Not Found
