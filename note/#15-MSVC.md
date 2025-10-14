## MSVRC (Model–Service–Repository-View–Controller)
- ``Model`` Làm việc trực tiếp với DB (find, insert, update, delete).
- ``Repository`` Là tầng “trung gian” bao bọc Model. Chỉ chứa logic liên quan đến dữ liệu (như filter, join, query phức tạp).
- ``service`` Chứa logic nghiệp vụ — ví dụ: kiểm tra điều kiện trước khi lưu, gửi mail, ghi log, xử lý transaction, ...
- ``Controller`` Chỉ nhận request, gọi Service, và trả kết quả cho View hoặc JSON.

```pgsql
Controller  →  Service  →  Repository  →  Model  →  Database
             ↓
           View

User → Controller → Service → Repository → Model → Database

- Controller: nhận request, validate, gọi Service.
- Service: xử lý logic (ví dụ: kiểm tra trùng slug).
- Repository: chỉ lo truy vấn CSDL. trung gian giữa model va service
- Model: đại diện bảng dữ liệu.

```
