# Cấu trúc Cơ sở dữ liệu (Database Schema)

Hệ quản trị CSDL: MySQL 8.0

## Sơ đồ quan hệ (ERD Overview)

- **users**: Lưu thông tin người dùng và admin (phân biệt qua cột `role`).
- **categories**: Các loại không gian (Ballroom, Restaurant, Cafe, v.v.).
- **venues**: Thông tin chi tiết về địa điểm (tọa độ, sức chứa, giá cả, hình ảnh JSON).
- **quotes**: Các yêu cầu báo giá từ khách hàng gửi tới địa điểm.
- **favorites**: Bảng trung gian lưu danh sách địa điểm yêu thích của người dùng.

## Chi tiết các bảng

### Table: venues
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| category_id | bigint | Foreign Key (categories.id) |
| name | string | Tên địa điểm |
| address | string | Địa chỉ đầy đủ |
| latitude | decimal | Tọa độ Vĩ độ (cho maps) |
| longitude | decimal | Tọa độ Kinh độ (cho maps) |
| capacity | int | Sức chứa tối đa |
| rating | decimal | Điểm đánh giá (0-5) |
| price_level | int | Mức giá (1-5 tokens $) |
| images | json | Mảng các URL hình ảnh |
| is_featured | boolean | Nổi bật hay không |
| has_offer | boolean | Có ưu đãi không |

### Table: quotes
| Column | Type | Description |
|--------|------|-------------|
| id | bigint | Primary Key |
| venue_id | bigint | ID địa điểm |
| user_id | bigint | ID người dùng (nếu có) |
| status | enum | `pending`, `contacted`, `completed`, `cancelled` |
| event_date | date | Ngày dự kiến tổ chức |
| guest_count | int | Số lượng khách hàng |

---

## Migrations
Toàn bộ cấu trúc được định nghĩa trong thư mục `backend/database/migrations/`.
Chạy `php artisan migrate` để khởi tạo.
