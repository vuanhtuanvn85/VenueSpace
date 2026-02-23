# Cấu trúc Cơ sở dữ liệu (Database Schema)

Hệ quản trị CSDL: MySQL 8.0

## Sơ đồ quan hệ (ERD Overview)

- **users**: Lưu thông tin người dùng và admin (phân biệt qua cột `role`).
- **categories**: Các loại không gian (Ballroom, Restaurant, Cafe, v.v.).
- **venues**: Thông tin chi tiết về địa điểm (tọa độ, sức chứa, giá cả, hình ảnh JSON).
- **quotes**: Các yêu cầu báo giá từ khách hàng gửi tới địa điểm.
- **favorites**: Bảng trung gian lưu danh sách địa điểm yêu thích của người dùng.

## Chi tiết các bảng

### Table: users
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | Tên người dùng |
| email | string | Địa chỉ email (Unique) |
| email_verified_at | timestamp | Thời gian xác thực email (nullable) |
| password | string | Mật khẩu mã hoá Hash |
| role | string | Quyền hệ thống (`user` hoặc `admin`), mặc định là `user` |
| remember_token | string | Token duy trì đăng nhập |
| created_at, updated_at| timestamp | Thời gian tạo và cập nhật |

### Table: categories
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| name | string | Tên loại địa điểm |
| slug | string | URL nhận diện (Unique) |
| description | text | Mô tả chi tiết (nullable) |
| icon | string | Icon đại diện (nullable) |
| created_at, updated_at| timestamp | Thời gian tạo và cập nhật |

### Table: venues
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| category_id | bigint | Foreign Key tham chiếu `categories.id` (Xoá cascade) |
| name | string | Tên địa điểm |
| description | text | Mô tả chi tiết không gian (nullable) |
| address | string | Địa chỉ nhà bóc tách cơ bản |
| suburb | string | Quận/Huyện (nullable) |
| city | string | Tên thành phố |
| state | string | Bang/Tỉnh thành (nullable) |
| latitude | decimal | Vĩ độ (`10, 8`) cho hiển thị bản đồ |
| longitude | decimal | Kinh độ (`11, 8`) cho hiển thị bản đồ |
| capacity | int | Tổng sức chứa tối đa |
| rating | decimal | Tổng điểm đánh giá mặc định 0 |
| reviews_count | int | Số lượng bài Review |
| price_level | int | Khung giá rẻ đến đắt: 1-5 |
| images | json | Mảng các đường link ảnh |
| amenities | json | Tiện ích liên quan dưới dạng text/json (nullable) |
| is_featured | boolean | Có phải không gian nổi bật hay không |
| has_offer | boolean | Không gian có chương trình đặc biệt không |
| offer_text | string | Đoạn text giới thiệu chương trình (nullable)|
| is_active | boolean | Trạng thái công khai (Mặc định `true`) |
| created_at, updated_at| timestamp | Thời gian tạo và cập nhật |

### Table: quotes
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| venue_id | bigint | ID địa điểm mục tiêu (Xoá Cascade) |
| user_id | bigint | ID người gửi yêu cầu nếu đã Login (Nullable) |
| name | string | Tên người gửi yêu cầu |
| email | string | Email liên lạc |
| phone | string | Điện thoại (nullable) |
| event_date | date | Ngày mong muốn tổ chức sự kiện |
| guest_count | int | Thiết lập tổng số khách mong muốn |
| message | text | Lời nhắn chi tiết gửi cho quản lý |
| status | enum | Trạng thái quản trị: `pending`, `contacted`, `completed`, `cancelled` |
| created_at, updated_at| timestamp | Lịch sử |

### Table: favorites
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| user_id | bigint | Foreign Key tham chiếu User Id |
| venue_id | bigint | Foreign Key tham chiếu Venue Id |
| created_at, updated_at| timestamp | Lịch sử tạo và cập nhật |
*(Ghi chú: Cột `user_id` và `venue_id` được đặt Unique Key chống trùng lặp dữ liệu Favorites).*

---

## Migrations
Toàn bộ cấu trúc được định nghĩa trong thư mục `backend/database/migrations/`.
Chạy `php artisan migrate` để khởi tạo.
