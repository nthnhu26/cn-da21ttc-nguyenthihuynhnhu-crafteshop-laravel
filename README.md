## XÂY DỰNG WEBSITE BÁN ĐỒ THỦ CÔNG MỸ NGHỆ ##
### Giảng viên hướng dẫn ###
- Lê Minh Tự
  - Email: lmtu@tvu.edu.vn
  - Điện thoại: 0918 677 326
### Sinh viên thực hiện ###
- Nguyễn Thị Huỳnh Như
  - Email: nguyennhu3570@gmail.com
  - Số điện thoại: 0357104009
## CẤU TRÚC CÂY THƯ MỤC TRÊN GITHUB REPOSITORE ##
- progress-report: gồm các file word báo cáo tiến độ theo tuần.
- src: chứa mã nguồn của chương trình

## BÁO CÁO TIẾN ĐỘ ##
- Cài đặt các ứng dụng hỗ trợ làm đồ án:
  - Visual Studio Code
  - Cài đặt Xampp
- Thực hiện công việc của tuần 1:
  - Phân tích yêu cầu hệ thống
  - Thiết kế cơ sở dữ liệu
  - Cài đặt và cấu hình XAMPP: https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/8.2.12/xampp-windows-x64-8.2.12-0-VS16-installer.exe
  - Cài đặt composer: https://getcomposer.org/Composer-Setup.exe
  - Cài đặt dự án Laravel: composer create-project laravel/laravel <tên dự án>


## HƯỚNG DẪN CÀI ĐẶT DỰ ÁN
### Yêu Cầu Hệ Thống
- PHP 8.2
- Composer
- MySQL
- Git
### Các Bước Cài Đặt
1. **Clone Project**
    ```bash
    git clone https://github.com/username/repo-name.git
    cd repo-name
    ```
2. **Cài Đặt Dependencies**
    ```bash
    composer install
    ```
3. **Thiết Lập Môi Trường**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. **Import Database**
    - Tạo database mới trong MySQL
    - Import file `mysql.sql` vào database vừa tạo
    - Cập nhật thông tin database trong file `.env`:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=ten_database
        DB_USERNAME=root
        DB_PASSWORD=
        ```
5. **Cấu Hình Google Login**
    - Truy cập [Google Cloud Console](https://console.cloud.google.com)
    - Tạo project mới
    - Vào Credentials -> Create Credentials -> OAuth Client ID
    - Thiết lập OAuth consent screen
    - Tạo OAuth Client ID cho Web application
    - Thêm redirect URI: `http://localhost:8000/auth/google/callback`
    - Copy Client ID và Client Secret vào file `.env`:
        ```env
        GOOGLE_CLIENT_ID=your-client-id
        GOOGLE_CLIENT_SECRET=your-client-secret
        GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
        ```
6. **Khởi Động Server**
    ```bash
    php artisan serve
    ```
    Truy cập: [http://localhost:8000](http://localhost:8000)
### Xử Lý Lỗi
Nếu gặp lỗi permissions:
```bash
chmod -R 777 storage
chmod -R 777 bootstrap/cache
