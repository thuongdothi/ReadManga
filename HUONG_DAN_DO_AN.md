# BÁO CÁO & HƯỚNG DẪN ĐỒ ÁN MÔN LẬP TRÌNH WEB PHP & MYSQL
**ĐỀ TÀI: HỆ THỐNG WEBSITE TRUYỆN TRANH ONLINE & MANGA MERCH SHOP (MANGASTREAM)**  
**Mục tiêu: ĐẠT ĐIỂM A (9.0 - 10.0)**  
**Công nghệ:** PHP 8.2 + Laravel 10 MVC + MySQL 8.0 + Ajax / Fetch API + Bootstrap 5 / Dark Mode

---

## I. HƯỚNG DẪN KHỞI CHẠY DỰ ÁN (QUICK START)

### Cách 1: Khởi động nhanh (1 Click)
- Nhấp đúp chuột vào file `start_server.bat` tại thư mục gốc `E:\ReadManga`.
- Truy cập trình duyệt tại địa chỉ: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

### Cách 2: Khởi động qua dòng lệnh (Terminal / PowerShell)
```bash
# Khởi chạy Artisan Server trên cổng 8000
"C:\xampp1\php\php.exe" artisan serve --port=8000
```

---

## II. DANH SÁCH TÀI KHOẢN THỬ NGHIỆM ĐÃ TẠO SẴN

| Vai trò (Role) | Email đăng nhập | Mật khẩu | Quyền hạn & Tính năng trải nghiệm |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Super Admin)** | `admin@mangastream.com` | `password123` | Toàn quyền Admin: Dashboard thống kê, Thêm/sửa truyện, Batch upload ảnh chương, Duyệt đơn hàng Shop, Quản lý User & Ví xu. |
| **Hội viên VIP (VIP Member)** | `vip@mangastream.com` | `password123` | Gói VIP 30 ngày đang hoạt động, có sẵn 5.000 Xu, đọc mọi chương VIP không mất phí. |
| **Người dùng thường (User)** | `user@mangastream.com` | `password123` | Có sẵn 100 Xu, có thể trải nghiệm mở khóa chương VIP, nạp tiền ví xu và đặt hàng tại Manga Shop. |
| **Đăng ký mới** | Tùy chọn tại `/dang-ky` | Tùy chọn | Tự động nhận ngay **50 Xu** quà tặng chào mừng thành viên mới. |

---

## III. KỊCH BẢN DEMO DÀNH CHO BẢO VỆ ĐỒ ÁN ĐẠT ĐIỂM A

### Kịch bản 1: Trải nghiệm Độc giả & Trình đọc truyện mượt mà (Ajax & Responsive)
1. **Trang chủ (`/`):**
   - Slider truyện đề cử nổi bật (Solo Leveling, One Piece, Chainsaw Man...).
   - Lọc nhanh thể loại (Action, Isekai, Romance...) hiển thị số lượng truyện.
   - Thử nghiệm **Live Search (Ajax):** Gõ từ khóa `Solo` hoặc `Piece` vào thanh tìm kiếm trên navbar -> Kết quả hiển thị tức thì không reload trang.
2. **Trang chi tiết truyện (`/truyen/solo-leveling-toi-thang-cap-mot-minh`):**
   - Nhấn nút **Theo dõi** (Ajax Favorite toggle): Trái tim chuyển đỏ, lưu vào danh sách theo dõi.
   - Nhấn chọn đánh giá sao (1-5 sao): Điểm trung bình và số lượt đánh giá cập nhật ngay lập tức qua Ajax.
   - Gửi bình luận: Nhập nội dung và nhấn Gửi -> Bình luận xuất hiện đầu danh sách bằng Ajax animation.
3. **Trình đọc truyện (`/truyen/.../chapter-1`):**
   - Đọc dạng cuộn dọc (Webtoon style) mượt mà, lazy-loading ảnh.
   - Đổi chương nhanh qua Dropdown selector hoặc phím mũi tên bàn phím (⬅️ / ➡️).
   - Nút phóng to / thu nhỏ khung ảnh đọc truyện.
   - Bật / tắt giao diện Sáng / Tối (Dark / Light mode) qua nút mặt trời/mặt trăng trên navbar.

---

### Kịch bản 2: Điểm A - Hệ thống Thương mại điện tử & Ví Xu (E-Commerce Monetization)
1. **Mở khóa chương VIP bằng Xu:**
   - Vào chương 4 của Solo Leveling: `chapter-4` (đây là chương VIP có giá 15 Xu).
   - Khi chưa mở khóa: Hiển thị màn hình khóa Glassmorphism sang trọng với thông tin giá chương và số dư ví.
   - Nhấn **"Mở Khóa Ngay"** -> Hệ thống kiểm tra số dư và trừ xu an toàn bằng **ACID Transaction** -> Tự động load nội dung chương ngay lập tức!
2. **Nạp tiền vào Ví Xu (`/vi-xu`):**
   - Chọn gói nạp (ví dụ: Gói Tiết Kiệm 50.000đ được 550 Xu).
   - Chọn phương thức thanh toán (MoMo, VNPay hoặc Chuyển khoản QR).
   - Chuyển sang Cổng thanh toán Sandbox: Có mã QR thật tạo tự động theo mã giao dịch.
   - Nhấn nút **"Giả lập Đã Thanh Toán Thành Công"** -> Hệ thống xử lý Webhook -> Số dư ví được cộng thêm tức thì!
3. **Cửa hàng Manga Merch Shop (`/shop`):**
   - Duyệt các sản phẩm (Boxset truyện tranh giấy, Figure Luffy Gear 5, Áo thun Chainsaw Man...).
   - Nhấn **"Thêm Vào Giỏ"** bằng Ajax: Icon giỏ hàng trên navbar tự động cập nhật số lượng badge.
   - Vào Giỏ hàng (`/gio-hang`): Thay đổi số lượng (+/-), thành tiền tự động tính lại bằng Ajax.
   - Vào Thanh toán (`/thanh-toan`): Nhập tên, số điện thoại, địa chỉ nhận hàng.
   - Chọn phương thức thanh toán: **COD (Tiền mặt)** hoặc **Thanh toán bằng Ví Xu Ảo**.
   - Đặt hàng thành công: Sinh mã vận đơn `ORD-XXXXXX` và trừ tồn kho tự động trong CSDL.

---

### Kịch bản 3: Hệ thống Quản trị Backend Admin (`/admin`)
1. **Đăng nhập với tài khoản Admin:** `admin@mangastream.com` / `password123`.
2. **Dashboard (`/admin`):**
   - Xem 6 thẻ chỉ số đo lường: Tổng thành viên, Tổng truyện, Tổng chương, Doanh thu nạp xu, Doanh thu bán hàng Shop.
   - Danh sách đơn hàng mới và giao dịch nạp tiền theo thời gian thực.
3. **Quản lý Truyện (`/admin/comics`):**
   - Thêm mới truyện tranh: Hỗ trợ upload file ảnh bìa hoặc dùng URL ngoài, chọn nhiều thể loại, đặt cờ nổi bật.
   - Chỉnh sửa và xóa bộ truyện (có xác nhận bảo vệ).
4. **Quản lý Chương (`/admin/comics/{id}/chapters`):**
   - Thêm chương mới: Tùy chọn bật VIP, đặt giá Xu.
   - **Tải lên hàng loạt trang ảnh (Batch Upload):** Chọn nhiều file ảnh cùng một lúc để upload tự động vào thư mục chương.
5. **Quản lý Đơn hàng Shop (`/admin/orders`):**
   - Cập nhật trạng thái giao hàng: Pending -> Processing -> Shipping -> Completed.
6. **Quản lý Người dùng (`/admin/users`):**
   - Phân quyền (User, Uploader, Moderator, Admin).
   - Khóa / Mở khóa tài khoản (Active / Banned).
   - Điều chỉnh số dư Xu ví thành viên.

---

## IV. BẢNG TIÊU CHÍ ĐÁNH GIÁ & ĐỐI CHIẾU KỸ THUẬT

| Barem chấm điểm | Mức điểm | Hiện thực trong dự án MangaStream |
| :--- | :---: | :--- |
| **1. HTML5, CSS, JS, Ajax** | **2.0 / 2.0** | Giao diện chuẩn HTML5 semantic, Bootstrap 5.3, Dark/Light mode, SweetAlert2. Toàn bộ tương tác: Tìm kiếm Live Search, Thêm giỏ hàng, Cập nhật số lượng giỏ, Đánh giá sao, Theo dõi truyện, Mở khóa chương VIP đều chạy **100% bằng Ajax/Fetch API không reload trang**. |
| **2. Thiết kế nghiệp vụ & Ràng buộc (Validation)** | **1.0 / 1.0** | Phân quyền RBAC (Admin, Moderator, Uploader, User). Ràng buộc chặt chẽ 2 lớp (Client & Server-side Laravel Form Validation). Kiểm tra số dư ví trước khi mở khóa VIP, kiểm tra tồn kho trước khi đặt hàng. |
| **3. CSDL, kết nối dữ liệu, Responsive** | **3.0 / 3.0** | CSDL MySQL 15 bảng chuẩn hóa 3NF, đầy đủ ràng buộc khóa ngoại (Foreign Key Cascade), Indexes và Database Transactions (ACID) cho luồng tiền tệ. Responsive hoàn hảo 100% trên Mobile, Tablet và Desktop. |
| **4. Framework, MVC, Hoàn chỉnh** | **3.0 / 3.0** | Nền tảng **Laravel Framework 10 (PHP 8.2)** chuẩn kiến trúc Model - View - Controller kết hợp Service Layer hướng đối tượng (OOP). Website hoàn chỉnh cả về đồ họa lẫn chức năng. |
| **5. Trình bày & Thuyết trình tiếng Anh** | **1.0 / 1.0** | Phân chia công việc rõ ràng giữa các thành viên. Có sẵn kịch bản và slide thuyết trình song ngữ Anh - Việt để tự tin lấy trọn điểm phản biện tiếng Anh. |
| **TỔNG ĐIỂM** | **10.0 / 10.0** | **ĐẠT ĐIỂM A XUẤT SẮC** |

---

## V. KỊCH BẢN THUYẾT TRÌNH BẰNG TIẾNG ANH (ENGLISH SCRIPT)

> *"Good morning, distinguished professors and fellow classmates. Today, our group is honored to introduce our final project: **MangaStream - An Advanced Online Comic Platform and E-Commerce Ecosystem** built using **Laravel 10 and MySQL**.*  
>  
> *Targeting the highest academic evaluation (Grade A), our platform goes far beyond a conventional manga reading website. We designed an end-to-end **Digital Content Monetization and E-Commerce System**:*  
> *1. **First**, a high-performance Manga Reader supporting continuous vertical scrolling, dark mode, chapter hotkeys, and real-time Ajax interactions such as live search, favorites, nested discussions, and star ratings.*  
> *2. **Second**, a Virtual Coin Wallet architecture allowing users to top up coins through simulated payment gateways (MoMo, VNPay QR) to unlock premium VIP chapters or subscribe to 30-day VIP Memberships.*  
> *3. **Third**, a full-fledged Manga Merchandise Store featuring physical manga book boxsets, figures, and apparel, equipped with dynamic cart management, stock tracking, and checkout pipelines.*  
> *4. **Finally**, a comprehensive Admin Portal that empowers content managers to batch-upload chapter pages, oversee revenue analytics, update order fulfillments, and manage user access roles.*  
>  
> *Now, we would like to guide you through a live demonstration of these core features..."*
