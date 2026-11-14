# Thánh Ca Online — Ca Đoàn Séraphim

Trang Tập Hát Online phục vụ **Thánh Vịnh Đáp Ca** hàng tuần (Năm A, B, C) và Thánh Ca các mùa Phụng Vụ, thực hiện bởi **Ca Đoàn Séraphim** — Giáo xứ Các Thánh Tử Đạo Việt Nam, Richmond, VA.

---

## Cấu trúc Thư mục (Project Structure)

```text
chungnhan/
├── lib/                        # Shared PHP libraries (Page.php, Mobile_Detect)
├── taphat_media/               # External media storage (not tracked in Git)
│   ├── thanhvinh/              # Các bài Thánh Vịnh Đáp Ca (.mp3, .jpg, .pdf, tongphoi/)
│   └── baihatmoi/              # Các bài Thánh Ca / Sáng tác mới (.mp3, .jpg, .pdf)
└── thanhca/                    # Main Web Application (Git Repository)
    ├── index.php               # Trang chủ Thánh Ca Online
    ├── main/
    │   ├── tvdc.php            # Giao diện các Tab Phụng Vụ (Năm A, B, C, Lịch, Thánh Ca)
    │   ├── pvUtils.php         # AJAX endpoint tải danh sách bài hát theo từng Tab
    │   └── songMedia.php       # Centralized loader đọc file .mp3/.jpg/.pdf từ taphat_media/
    ├── inc/
    │   ├── pv.class.php        # Các class xử lý dữ liệu Phụng Vụ & Thánh Ca
    │   ├── template_jtab.php   # Layout chính + Sticky Choir Voice Switcher Player
    │   └── json/               # Dữ liệu JSON (ABC.json, A.json, B.json, C.json, tc.json, ...)
    └── lich/                   # Lịch Phụng Vụ hàng năm (2026, 2027, ...)
```

## Thêm Bài Hát Mới (Adding New Songs)

Nhờ bộ tải tập trung `main/songMedia.php`, khi thêm bài hát mới vào `taphat_media/thanhvinh/<folder>` hoặc `taphat_media/baihatmoi/<folder>`:
1. **Không cần** chép file `index.php` hay `media.php` vào từng thư mục bài hát nữa.
2. Chỉ cần chép các file nhạc (`.mp3`), bản nhạc (`.jpg`), và `.pdf` (hoặc thư mục con `tongphoi/*.jpg` nếu có bản Hòa Âm) vào thư mục bài hát.
3. Khai báo thư mục bài hát trong file JSON tương ứng tại `inc/json/` (`A.json`, `B.json`, `C.json`, `tc.json`, `gs.json`, `tt.json`, `ps.json`, `st.json`).
