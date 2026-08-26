# 💻 Web System Source Code

本資料夾存放「台灣生態保育資訊系統」的主要網站原始碼，包含前端頁面、PHP 後端程式、MySQL 資料庫連線及網站圖片素材。

## 主要內容

- `index.html`：系統首頁
- `member.php` / `login.php` / `regist.php`：會員相關功能
- `board.php` / `boardAdd.php`：生態留言板功能
- `search.php`：動物關鍵字搜尋
- `multidata.php`：生態動物條件篩選
- `connect.php`：MySQL 資料庫連線
- `navbar.html`：網站導覽列
- `img/`：網站使用的圖片素材
- `ajax/`：負責部分非同步資料操作，主要處理留言板排序功能，透過 PHP 與資料庫查詢依留言時間進行升冪或降冪排列，並將結果回傳至前端顯示。

## 開發技術

HTML、JavaScript、PHP、MySQL、Apache
