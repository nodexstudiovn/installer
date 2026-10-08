# ⚡ NodeX Studio Project Installer (`nodex/installer`)

Công cụ CLI Global chính thức dành cho **NodeX Studio Framework v1.1.0**, cho phép khởi tạo nhanh dự án mới chỉ bằng 1 câu lệnh từ bất kỳ đâu trên hệ thống.

---

## 📌 Hướng dẫn Cài đặt Global (Khách hàng / Developer)

Sau khi gói `nodex/installer` được xuất bản lên **Packagist**, bất kỳ ai cũng có thể cài đặt công cụ này lên hệ thống thông qua Composer Global:

```bash
composer global require nodex/installer
```

> **Lưu ý:** Đảm bảo đường dẫn `~/.composer/vendor/bin` (trên Linux/macOS) hoặc `%USERPROFILE%\AppData\Roaming\Composer\vendor\bin` (trên Windows) đã được thêm vào biến môi trường **PATH** của máy tính.

---

## 🚀 Hướng dẫn Sử dụng Tạo Dự Án Mới

Mở bất kỳ cửa sổ Terminal / Command Prompt nào ở bất kỳ thư mục nào và chạy:

```bash
nodex new my-portfolio-app
```

### Tiến trình tự động của `nodex new`:
1. 📥 Tự động tải/clone bộ khung mã nguồn mới nhất của **NodeX Studio Framework**.
2. 🧹 Dọn dẹp cấu hình Git cũ và tự động tạo file `.env` từ `.env.example`.
3. 📦 Tự động chạy `composer install` cài đặt đầy đủ phụ thuộc.
4. ✨ Hiển thị hướng dẫn khởi chạy server local (`php nodex serve` & `npm run dev`).

---

## 🛠️ Hướng dẫn Phát triển & Thử nghiệm Cục bộ (Local Development)

Để thử nghiệm cục bộ gói installer này trước khi đưa lên GitHub/Packagist:

```bash
# 1. Di chuyển vào thư mục installer
cd installer

# 2. Cài đặt phụ thuộc
composer install

# 3. Test trực tiếp lệnh tạo dự án mẫu thử nghiệm
php bin/nodex test-project
```

---
*Phát triển & Sở hữu bởi NodeX Studio — Telegram: @nodexstudio*
