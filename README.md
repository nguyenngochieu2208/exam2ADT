<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Cách chạy code
1. Bước 1: Tải Xampp version: 8.2.12 / PHP 8.2.12 về máy với đường link: https://www.apachefriends.
org/download.html.

2. Bước 2: Tải Composer về máy với đường link: https://getcomposer.org/download/.

3. Bước 3: CD tới thư mục vừa clone code, chạy lệnh " composer install " để cài đặt các package của laravel.

4. Bước 4: Sau khi cài đặt xong, chạy các lệnh sau:
- cp .env.example .env
- php artisan key:generate

5. Bước 5 : Tạo 1 database với mysql và thay đổi các trường thông tin trong file env: 

- DB_CONNECTION , DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD.
- BITRIX_DOMAIN, BITRIX_CLIENT_ID, BITRIX_CLIENT_SECRET.

Sau đó chạy lệnh:

- php artisan migrate

6. Bước 6: Sau khi chạy xong các lệnh trên, chạy lệnh " php artisan serve ".

7. Bước 7: Dùng port hiển thị phía dưới để thực hiện chạy ngrok: 
- ngrok.exe http 127.0.0.1:8000

8. Bước 8: Thay đổi handler path và install path (/install) trong cấu hình Application của Bitrix24 và APP_URL trong file env của Laravel.

9. Bước 9: Truy cập vào url để xem trang web.

## Phương pháp làm bài
Trong bài test ở mức trung cấp lần này em đã sử dụng Laravel với mô hình MVC để thực hiện các yêu cầu mà Quý công ty đưa ra.
Các công việc cụ thể:

1. Bài 1:
- Tạo các route để nhận sự kiện install/reinstall từ Bitrix24 (routes/web.php).
- Viết class ApiHelper có phương thức giúp gọi API tới Bitrix24, xử lý lỗi, renew token, ...
- Tạo model và migration để định nghĩa bảng bitrix_tokens giúp lưu trữ dữ liệu.
- Tạo middleware (TokenMiddleware) để kiểm tra token đang lưu trong hệ thống còn hoạt động được hay không khi truy cập vào các route,
- Tạo controller (OAuthController) để xử lý gọi yêu cầu, lưu trữ token.

2. Bài 2:
- Tạo routes cho các phương thức CRUD Contact
- Tạo views cho các phương thức CRUD Contact.
- Tạo controller (ContactController) để thực hiện CRUD Contact, Requisite với Bitrix24. 
- Tạo các request để thực hiện validate dữ liệu trước khi gửi request add, update lên Bitrix.

Ngoài ra trong bài test lần này em còn sử dụng các thư viện để giúp làm giao diện, hiển thị lỗi,.. như: 
- Bootstrap 
- jQuery, ToastrJs, Sweetalert2.





