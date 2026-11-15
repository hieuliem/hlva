# Thánh Ca Online — Ca Đoàn Séraphim

Trang Tập Hát Online phục vụ **Thánh Vịnh Đáp Ca** hàng tuần (Năm A, B, C), **Lịch Phụng Vụ Công Giáo**, và **Thánh Ca các mùa Phụng Vụ**, thực hiện bởi **Ca Đoàn Séraphim** — Giáo xứ Các Thánh Tử Đạo Việt Nam, Richmond, VA.

- **Production Website (Cloudflare Workers):** https://thanhca.chungnhan.org
- **Lịch Phụng Vụ:**
  - https://thanhca.chungnhan.org/lich/2026
  - https://thanhca.chungnhan.org/lich/2027
  - https://thanhca.chungnhan.org/lich/2028
- **Cloudflare R2 Media CDN:** `https://pub-3de53d5d95bd4ddba9c86d03da43133d.r2.dev` (Bucket: `thanhca-media`)
- **GitHub Repository:** `git@github.com:hieuliem/hlva.git`

---

## 1. Kiến trúc Hệ thống (System Architecture)

Ứng dụng được thiết kế để chạy song song trên **2 môi trường** từ cùng một mã nguồn:

1. **Cloudflare Workers + Static Assets (Production 24/7 — `$0/month`):**
   - Toàn bộ giao diện, danh sách Thánh Vịnh Đáp Ca, Thánh Ca và Lịch Phụng Vụ được xử lý trực tiếp tại biên (Edge) qua `worker/index.js` và `wrangler.jsonc`.
   - Các tài nguyên tĩnh (`css/`, `js/`, `images/`, `taphat/css/`, `lich/style.css`) được phục vụ từ thư mục `public/` qua Cloudflare Workers Static Assets (`env.ASSETS`).
   - Toàn bộ **6,759 file media (~7.10 GiB)** gồm `.mp3`, `.pdf`, `.jpg` được lưu trữ và phát trực tiếp từ **Cloudflare R2** (`thanhca-media`).
   - Tự động build & deploy mỗi khi `git push` lên GitHub.

2. **Local XAMPP / Apache + PHP (Môi trường phát triển tại máy cá nhân):**
   - Chạy trực tiếp tại `http://localhost/chungnhan/thanhca/` bằng PHP (`index.php`, `main/tvdc.php`, `main/pvUtils.php`, `main/songMedia.php`, `inc/pv.class.php`).
   - Có thể đọc file media từ **Cloudflare R2** hoặc từ thư mục ngoài `C:\xampp\htdocs\chungnhan\taphat_media\` tùy cấu hình trong `inc/media_config.php`.

---

## 2. Cấu trúc Thư mục (Project Structure)

```text
chungnhan/
├── lib/                              # Thư viện PHP dùng chung cho XAMPP (Page.php, Mobile_Detect)
├── taphat_media/                     # Thư mục gốc chứa ~7.1 GB file .mp3/.jpg/.pdf trên máy local (ngoài Git)
│   ├── thanhvinh/                    # Các bài Thánh Vịnh Đáp Ca (.mp3, .jpg, .pdf, tongphoi/)
│   └── baihatmoi/                    # Các bài Thánh Ca / Sáng tác mới (.mp3, .jpg, .pdf)
└── thanhca/                          # Git Repository chính (hieuliem/hlva)
    ├── worker/
    │   └── index.js                  # Cloudflare Worker xử lý mọi route trên https://thanhca.chungnhan.org
    ├── public/                       # Static assets đóng gói cho Cloudflare Workers (css, js, images)
    ├── wrangler.jsonc                # Cấu hình Cloudflare Worker & Custom Domain (thanhca.chungnhan.org)
    ├── package.json                  # Cấu hình Node/Wrangler cho Cloudflare Builds
    ├── index.php                     # Trang chủ PHP (cho XAMPP local)
    ├── main/
    │   ├── tvdc.php                  # Giao diện các Tab Phụng Vụ & tự động chuyển năm Lịch Phụng Vụ
    │   ├── pvUtils.php               # AJAX endpoint tải danh sách bài hát theo từng Tab
    │   └── songMedia.php             # Bộ tải media tập trung (hỗ trợ R2 + media_manifest.json + local)
    ├── inc/
    │   ├── media_config.php          # Cấu hình URL Cloudflare R2 ($THANHCA_R2_PUBLIC_URL)
    │   ├── pv.class.php              # Các class PHP xử lý dữ liệu Phụng Vụ & Thánh Ca
    │   ├── template_jtab.php         # Giao diện chính + Sticky Choir Voice Switcher Player
    │   └── json/
    │       ├── ABC.json              # Khoảng ngày bắt đầu/kết thúc của Năm A, Năm B, Năm C
    │       ├── A.json, B.json, C.json# Danh sách Thánh Vịnh Đáp Ca theo ngày của từng năm Phụng Vụ
    │       ├── pl.json               # Các bài Ca Nhập Lễ (CNL) & Hiệp Lễ (HL/DL/KL) theo từng Chúa Nhật/Lễ
    │       ├── tc.json, gs.json, ... # Danh sách Thánh Ca, Giáng Sinh, Tuần Thánh, Phục Sinh, Sáng Tác Mới
    │       ├── media_manifest.json   # Chỉ mục toàn bộ 384 thư mục bài hát (.mp3, .pdf, .jpg, tongphoi)
    │       ├── lich_compiled.json    # Dữ liệu Lịch Phụng Vụ 2026, 2027, 2028 đã biên dịch cho Worker
    │       └── page_template.json    # Bản mẫu giao diện HTML/JS trích xuất từ template_jtab.php cho Worker
    └── lich/                         # Lịch Phụng Vụ Công Giáo (2026, 2027, 2028)
        ├── litCalendar.php           # Bộ xử lý Lịch Phụng Vụ & Tết Nguyên Đán (PHP)
        ├── data/                     # Dữ liệu JSON từng năm (2025.json, 2026.json, 2027.json, 2028.json, le.json)
        ├── 2026/index.php
        ├── 2027/index.php
        └── 2028/index.php
```

---

## 3. Trình Phát Nhạc Tập Hát (Audio Player Options)

Trong `inc/template_jtab.php`, hệ thống tích hợp sẵn **3 giao diện trình phát nhạc** (có thể chuyển đổi qua thanh `.demo-switcher-bar`):
- **Option 1:** Plyr.io Cards (hỗ trợ chỉnh tốc độ & lặp lại).
- **Option 2 (Mặc định toàn trang):** **Sticky Choir Voice Switcher** — Thanh điều khiển bám dính (sticky) ở đầu bản nhạc khi cuộn trang, cho phép chuyển nhanh giữa các bè (*Thánh Vịnh, Giọng Nữ, Giọng Nam, Soprano, Alto, Tenor, Bass, Piano, Keyboard, Tổng Phối*), chỉnh tốc độ tập chậm (`0.75x`, `0.9x`, `1.0x`, `1.25x`), và bật/tắt lặp lại (`🔁 Lặp lại`).
- **Option 3:** Native HTML5 Audio Grid (tự động dừng bài khác khi bấm phát).

> **Mẹo:** Thanh chuyển đổi 3 chế độ `.demo-switcher-bar` được giữ nguyên trong `inc/template_jtab.php` (đang ẩn bằng `style="display:none;"`). Khi muốn bật lại để thử nghiệm, chỉ cần đổi thành `display:flex;`.

---

## 4. Tự Động Chuyển Năm Phụng Vụ & Lịch Phụng Vụ (Auto-Rollover)

Hệ thống tự động xác định **Năm Phụng Vụ hiện hành** và **2 tab Lịch Phụng Vụ** dựa vào ngày hiện tại so với `inc/json/ABC.json`:

```json
{
	"A":{"start":"11/30/2025", "end":"11/22/2026"},
	"B":{"start":"11/23/2026", "end":"11/21/2027"},
	"C":{"start":"11/22/2027", "end":"11/26/2028"}
}
```

- **Từ nay đến hết Chúa Nhật Lễ Chúa Kitô Vua Năm A (`11/22/2026`):**
  - Tab mặc định: **Phụng Vụ Đáp Ca Năm A (2025-2026)**
  - Hai tab Lịch Phụng Vụ: **`Lịch Phụng Vụ 2026`** và **`2027`**
- **Tự động từ Thứ Hai `11/23/2026` (ngay sau Lễ Chúa Kitô Vua, chuẩn bị Chúa Nhật 1 Mùa Vọng Năm B `11/29/2026`):**
  - Tab mặc định tự động chuyển sang: **Phụng Vụ Đáp Ca Năm B (2026-2027)**
  - Hai tab Lịch Phụng Vụ tự động chuyển sang: **`Lịch Phụng Vụ 2027`** và **`2028`** (ẩn `2026`).
- **Tự động từ Thứ Hai `11/22/2027` (sau Lễ Chúa Kitô Vua Năm B):**
  - Tab mặc định tự động chuyển sang: **Phụng Vụ Đáp Ca Năm C (2027-2028)**.

---

## 5. Hướng Dẫn Thêm Bài Hát Mới & Đồng Bộ Cloudflare R2

Khi có bài Thánh Vịnh hoặc Thánh Ca mới:

### Bước 1: Thêm file vào thư mục `taphat_media`
Đặt các file `.mp3`, `.pdf`, `.jpg` (và thư mục con `tongphoi/*.jpg` nếu có) vào:
- `C:\xampp\htdocs\chungnhan\taphat_media\thanhvinh\<ten_thu_muc>` (cho Thánh Vịnh Đáp Ca), hoặc
- `C:\xampp\htdocs\chungnhan\taphat_media\baihatmoi\<ten_thu_muc>` (cho Thánh Ca).
*(Không cần tạo file `index.php` hay `media.php` trong thư mục bài hát).*

### Bước 2: Tải file mới lên Cloudflare R2 (`rclone`)
Chạy lệnh sau trong PowerShell để đẩy các file mới lên bucket `r2:thanhca-media`:

```powershell
& "C:\Users\HLiem\AppData\Local\Programs\rclone\rclone.exe" copy "C:\xampp\htdocs\chungnhan\taphat_media" "r2:thanhca-media" --include "*.mp3" --include "*.jpg" --include "*.pdf" --transfers 16 --checkers 32 --stats-one-line -v
```

### Bước 3: Cập nhật chỉ mục `media_manifest.json`
Chạy đoạn lệnh PHP sau để quét lại danh sách file trong `taphat_media` vào `inc/json/media_manifest.json`:

```powershell
& "C:\xampp\php\php.exe" -r '
$root = realpath("C:/xampp/htdocs/chungnhan/taphat_media");
$manifest = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
foreach ($it as $item) {
    if ($item->isDir()) {
        $rel = str_replace("\\", "/", substr($item->getPathname(), strlen($root) + 1));
        if (basename($rel) === "tongphoi") continue;
        $old = getcwd(); chdir($item->getPathname());
        $mp3s = glob("*.mp3") ?: []; $pdfs = glob("*.pdf") ?: []; $jpgs = glob("*.jpg") ?: [];
        $tp = is_dir("tongphoi") ? (glob("tongphoi/*.jpg") ?: []) : [];
        chdir($old);
        if ($mp3s || $pdfs || $jpgs || $tp) {
            $manifest[$rel] = ["mp3"=>array_values($mp3s),"pdf"=>array_values($pdfs),"jpg"=>array_values($jpgs),"tongphoi"=>array_values($tp)];
        }
    }
}
ksort($manifest);
file_put_contents("C:/xampp/htdocs/chungnhan/thanhca/inc/json/media_manifest.json", json_encode($manifest, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo "Indexed " . count($manifest) . " folders.\n";
'
```

### Bước 4: Đẩy cập nhật lên GitHub (Tự động Deploy lên Cloudflare)
```powershell
cd "C:\xampp\htdocs\chungnhan\thanhca"
& "C:\Users\HLiem\AppData\Local\Programs\Git\cmd\git.exe" add -A
& "C:\Users\HLiem\AppData\Local\Programs\Git\cmd\git.exe" commit -m "Update songs and media manifest"
& "C:\Users\HLiem\AppData\Local\Programs\Git\cmd\git.exe" push origin main
& "C:\Users\HLiem\AppData\Local\Programs\Git\cmd\git.exe" push origin main:master
```
